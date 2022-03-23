
@extends('admin.index')
@section('title')
    Đề nghị cấp hoa
@endsection
@section('script_head')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Đề nghị cấp hoa</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Đề nghị cấp hoa</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            @if(session('succ'))
                <div class="alert alert-success">
                    {{session('succ')}}
                </div>
            @endif
            @if(session('err'))
                <div class="alert alert-warning">
                    {{session('err')}}
                </div>
            @endif
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">
                                Đề nghị cấp hoa
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                            <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                            <!-- Medal Add -->
                            <div class="modal fade" id="addModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- general form elements -->
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">ĐỀ NGHỊ CẤP HOA</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <!-- form start -->
                                                <form id="addForm" action="{{route('caphoa.post')}}" method="post" autocomplete="off">
                                                    {{csrf_field()}}
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-sm-4">
                                                                <label>Khách hàng: </label>                                                        
                                                                <input placeholder="Thông tin khách hàng" type="text" name="khachHang" class="form-control"/>
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <label>Dòng xe:</label>                                                        
                                                                <input placeholder="Accent, Santafe,..." type="text" name="dongXe" class="form-control" required="required"/>
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <label>Biển số/số khung: </label>                                                        
                                                                <input placeholder="Biển số/Số khung" type="text" name="num" class="form-control" required="required"/>
                                                            </div>                                                            
                                                        </div>       
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label>Giờ giao xe: </label>                                                        
                                                                <input type="time" name="gioGiaoXe" class="form-control" required="required"/>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Ngày giao xe:</label>                                                        
                                                                <input type="date" name="ngayGiaoXe" class="form-control" required="required"/>
                                                            </div>                                                                                                                  
                                                        </div>   
                                                        <div class="row">
                                                            <div class="form-group col-md">
                                                                <label>Ghi chú: </label>                                                        
                                                                <input placeholder="Ghi chú nếu có" type="text" name="ghiChu" class="form-control"/>
                                                            </div>                                                                                                                                                                           
                                                        </div>       
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <div class="card-footer">
                                                        <button id="btnAdd" type="button" class="btn btn-primary">GỬI</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Ngày tạo</th>
                                    <th>Người đề nghị</th>
                                    <th>Khách hàng</th>
                                    <th>Dòng xe</th>
                                    <th>Biển số/số khung</th>
                                    <th>Giờ giao xe</th>
                                    <th>Ngày giao xe</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày giao hoa</th>
                                    <th>Ghi chú</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hoa as $row)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{\HelpFunction::getDateRevertCreatedAt($row->created_at)}}</td>
                                        <td> @if($row->user !== null)
                                                    {{$row->user->userDetail->surname}}
                                                @else
                                                    Không
                                                @endif</td>
                                        <td>{{$row->khachHang}}</td>
                                        <td>{{$row->dongXe}}</td>
                                        <td>{{$row->num}}</td>
                                        <td>{{$row->gioGiaoXe}}</td>
                                        <td>{{\HelpFunction::revertDate($row->ngayGiaoXe)}}</td>
                                        <td>
                                            @if($row->duyet == false)
                                                <span class="text-danger"><strong>Chưa duyệt</strong></span>
                                            @else
                                                <span class="text-success"><strong>Đã nhận hoa</strong></span>
                                            @endif
                                        </td>
                                        <td>{{\HelpFunction::revertDate($row->ngayGiaoHoa)}}</td>
                                        <td>{{$row->ghiChu}}</td>
                                        <td>
                                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                                @if($row->duyet == false)
                                                    <button id="xoa" data-id="{{$row->id}}" class="btn btn-warning btn-xs">Xóa</button>
                                                    <button data-toggle="modal" data-target="#editModal" id="pheDuyet" data-id="{{$row->id}}" class="btn btn-success btn-xs">Duyệt</button>
                                                @else
                                                    <button id="xoa" data-id="{{$row->id}}" class="btn btn-warning btn-xs">Xóa</button>
                                                @endif
                                            @elseif (\Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))
                                                @if($row->duyet == false)
                                                    <button id="xoa" data-id="{{$row->id}}" class="btn btn-danger btn-xs">Không duyệt</button>
                                                    <button data-toggle="modal" data-target="#editModal" id="pheDuyet" data-id="{{$row->id}}" class="btn btn-success btn-xs">Duyệt</button>
                                                @endif
                                            @else
                                                @if($row->duyet == false)
                                                    <button id="xoa" data-id="{{$row->id}}" class="btn btn-danger btn-xs"> Xóa </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- Medal Update -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Cập nhật ngày giao hoa</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editForm" action="{{route('caphoa.post')}}" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" name="idCapHoa">
                                    <div class="form-group col-sm-6">
                                        <label>Ngày giao hoa:</label>                                                        
                                        <input type="date" name="engayGiaoHoa" class="form-control" required="required"/>
                                    </div>                                                                                                                                                                              
                                </div>   
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnEdit" type="button" class="btn btn-success">XÁC NHẬN PHÊ DUYỆT</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- Page specific script -->
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            $("#dataTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');


            $('#btnAdd').click(function(event) {               
                let form = $("#addForm");   
                $("#btnAdd").attr('disabled', true).html("Đang xử lý vui lòng đợi....");            
                event.preventDefault();
                form.submit();
            }); 
        });

        //Delete data
        $(document).on('click','#xoa', function(){
            if(confirm('Bạn có chắc muốn xóa?')) {
                $.ajax({
                    url: "{{url('management/caphoa/del/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('caphoa.panel')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể xóa lúc này!"
                        })
                        setTimeout(function(){
                            open('{{route('caphoa.panel')}}','_self');
                        }, 1000);
                    }
                });
            }          
        });

        //Phê duyệt
        $(document).on('click','#pheDuyet', function(){
           $("input[name=idCapHoa]").val($(this).data('id'));
        });

        $(document).on('click','#btnEdit', function(){
            if (!$("input[name=engayGiaoHoa]").val()) {
                alert("Bạn chưa nhập ngày giao hoa!");
            } else
            if(confirm('Xác nhận phê duyệt đề nghị cấp hoa này?')) {
                let form = $("#addForm");   
                    $("#btnEdit").attr('disabled', true).html("Đang xử lý vui lòng đợi....");     

                $.ajax({
                    url: "{{url('management/caphoa/duyet/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $("input[name=idCapHoa]").val(),
                        "ngayGiaoHoa": $("input[name=engayGiaoHoa]").val()
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('caphoa.panel')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể phê duyệt lúc này!"
                        })
                        setTimeout(function(){
                            open('{{route('caphoa.panel')}}','_self');
                        }, 1000);
                    }
                });
            }          
        });
    </script>
@endsection
