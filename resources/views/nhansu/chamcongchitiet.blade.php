@extends('admin.index')
@section('title')
   Chấm công chi tiết
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Chấm công chi tiết</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Chấm công chi tiết</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">                    
                    <div class="col-md-1">
                        <label>Tháng</label>
                        <select name="thang" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Năm</label>
                        <select name="nam" class="form-control">
                            @for($i = 2021; $i < 2100; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Nhân viên</label>
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <select name="nhanVien" class="form-control">
                            @foreach($user as $row)
                                @if($row->active == true)
                                    <option value="{{$row->id}}">{{$row->name}} - {{$row->userDetail->surname}}</option>
                                @endif
                            @endforeach
                        </select>
                        @else
                        <select name="nhanVien" class="form-control">
                            <option value="{{Auth::user()->id}}">{{Auth::user()->userDetail->surname}}</option>
                        </select>
                        @endif
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="chon" type="button "class="btn btn-xs btn-info">Chọn</button>
                    </div>
                </div>  
                <br/>
                <input type="hidden" name="idChiTiet">
                <div style="overflow: auto;">
                    <table class="table table-striped table-bordered">
                        <tr class="text-center">
                            <th>Ngày</th>
                            <th>Vào Sáng</th>
                            <th>Ra Sáng</th>
                            <th>Vào Chiều</th>
                            <th>Ra Chiều</th>
                            <th>Công sáng</th>
                            <th>Công chiều</th>
                            <th>Trể/Sớm Sáng</th>
                            <th>Trể/Sớm Chiều</th>
                            <th>Trạng thái</th>
                            <th>Phép (hành chính)</th>
                            <th>Tăng ca (ngoài giờ)</th>
                        </tr>
                        <tbody class="text-center" id="chiTietCong">
                        
                        </tbody>                    
                    </table>                  
                </div>                
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- Medal Add -->
    <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Xin phép (giờ hành chính)</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            {{csrf_field()}}         
                            <input type="hidden" name="idUserXin">    
                            <div class="form-group row">
                                <div class="col-md-2"><input type="text" name="ngayXin" readonly class="form-control"></div>
                                <div class="col-md-2"><input type="text" name="thangXin" readonly class="form-control"></div>
                                <div class="col-md-2"><input type="text" name="namXin" readonly class="form-control"></div>
                            </div>            
                            <div class="form-group">
                               <label>Chọn buổi</label> 
                               <select name="buoi" class="form-control">
                                   <option value="SANG">Sáng</option>
                                   <option value="CHIEU">Chiều</option>
                                   <option value="CANGAY">Cả ngày</option>
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Loại phép</label> 
                               <select name="loaiPhep" class="form-control">
                                  @foreach($phep as $row)
                                    <option value="{{$row->id}}">{{$row->tenPhep}}</option>
                                  @endforeach
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Lý do xin</label> 
                               <input type="text" name="lyDo" class="form-control" placeholder="Lý do xin">
                            </div>
                            <div class="form-group">
                               <label>Người duyệt</label> 
                               <select name="nguoiDuyet" class="form-control">
                                  @foreach($user as $row)
                                    @if($row->hasRole('lead'))
                                        <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                    @endif                                    
                                  @endforeach
                               </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnAdd" class="btn btn-primary" form="addForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Medal Add Tăng ca -->
    <div class="modal fade" id="addModalTangCa">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Xin phép tăng ca (ngoài giờ)</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addFormTangCa" autocomplete="off">
                            {{csrf_field()}}         
                            <input type="hidden" name="idUserXinTangCa">    
                            <div class="form-group row">
                                <div class="col-md-2"><input type="text" name="ngayXinTangCa" readonly class="form-control"></div>
                                <div class="col-md-2"><input type="text" name="thangXinTangCa" readonly class="form-control"></div>
                                <div class="col-md-2"><input type="text" name="namXinTangCa" readonly class="form-control"></div>
                            </div>   
                            <div class="form-group">
                               <label>Lý do xin</label> 
                               <input type="text" name="lyDoTangCa" class="form-control" placeholder="Lý do xin">
                            </div>
                            <div class="form-group">
                               <label>Người duyệt</label> 
                               <select name="nguoiDuyetTangCa" class="form-control">
                                  @foreach($user as $row)
                                    @if($row->hasRole('lead'))
                                        <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                    @endif                                    
                                  @endforeach
                               </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnAddTangCa" class="btn btn-primary" form="addFormTangCa">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Below is plugin for datatables -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function(){

           function reload() {
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){                        
                        $("#chiTietCong").html(response);                                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
           }            

           $("#chon").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){                        
                        $("#chiTietCong").html(response);                                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
           });

           $(document).on('click','#xinPhep', function(){
                $("input[name=ngayXin]").val($(this).data('ngay'));
                $("input[name=thangXin]").val($(this).data('thang'));
                $("input[name=namXin]").val($(this).data('nam'));
                $("input[name=idUserXin]").val($("select[name=nhanVien]").val());
           });

           $(document).on('click','#tangCa', function(){
                $("input[name=ngayXinTangCa]").val($(this).data('ngay'));
                $("input[name=thangXinTangCa]").val($(this).data('thang'));
                $("input[name=namXinTangCa]").val($(this).data('nam'));
                $("input[name=idUserXinTangCa]").val($("select[name=nhanVien]").val());
           });


           $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/themphep')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response){
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addModal").modal('hide');
                        reload();
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể tạo phép"
                        })
                    }
                });
           });

           $("#btnAddTangCa").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/themtangca')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addFormTangCa").serialize(),
                    success: function(response){
                        $("#addFormTangCa")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addModalTangCa").modal('hide');
                        reload();
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể xin tăng ca"
                        })
                    }
                });
           });


           $(document).on('click','#xacNhan', function(){
                if (confirm("Xác nhận giờ công?\nLưu ý: Sau khi xác nhận sẽ không được chỉnh sửa và thêm phép")){
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/chot')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "id": $("select[name=nhanVien]").val(),
                            "_token": "{{csrf_token()}}",
                            "thang": $(this).data('thang'),
                            "nam": $(this).data('nam'),
                            "ngayCong": $(this).data('ngaycong'),
                            "tangCa": $(this).data('tangca'),
                            "tongTre": $(this).data('tongtre'),
                            "khongPhep": $(this).data('khongphep'),
                        },
                        success: function(response){
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reload();
                        },
                        error: function(){
                            Toast.fire({
                                icon: "error",
                                title: "Lỗi! Không thể xác nhận giờ công"
                            })
                        }
                    });
                }
           });
        });
    </script>
@endsection
