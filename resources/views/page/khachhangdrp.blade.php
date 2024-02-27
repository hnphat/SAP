@extends('admin.index')
@section('title')
    Khách hàng DRP
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
                        <h1 class="m-0"><strong>Khách hàng DRP</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng DRP</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Khách hàng DRP</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <button class="btn btn-info" data-toggle="modal" data-target="#addModal">Tiếp nhận</button>
                                &nbsp;&nbsp;
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                    <a href="{{route('khachhang.question.drp')}}" class="btn btn-primary">Bảng câu hỏi</a>
                                @endif
                              <form>
                                <div class="card-body row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Chọn nhân viên</label>
                                                <select name="nhanVien" class="form-control"> 
                                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('cskh') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))
                                                    <option value="0">Tất cả</option>                                                               
                                                    @foreach($groupsale as $row)    
                                                        @if (!\Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))                                                    
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @elseif ($row['group'] == $groupid)
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @else
                                                        
                                                        @endif 
                                                    @endforeach   
                                                @else   
                                                    <option value="{{$iduser}}">{{$nameuser}}</option>
                                                @endif
                                                </select> <br/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Từ</label>
                                                <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Đến</label>
                                                <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <br/>
                                                <button id="xemReport" type="button" class="btn btn-info" class="form-control">Xem</button>
                                            </div>
                                        </div>
                                </div>
                              </form>
                              <hr/>
                              <div id="all">
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                        <tr class="bg-gradient-lightblue">
                                            <th>TT</th>
                                            <th>Ngày</th>
                                            <th>Người tạo</th>
                                            <th>Khách hàng</th>
                                            <th>Điện thoại</th>
                                            <th>Địa chỉ</th>
                                            <th>Xe quan tâm</th>
                                            <th>Trạng thái</th>
                                            <th>File đính kèm</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                    </thead>
                                  </table>
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!--  MEDAL ADD-->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm mới</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            <div class="form-group">
                               <label>Khách hàng</label> 
                               <input placeholder="Họ và tên" type="text" name="khachHang" class="form-control" required >
                            </div>    
                            <div class="form-group">
                               <label>Số điện thoại</label> 
                               <input placeholder="Số điện thoại" type="text" name="dienThoai" class="form-control" required>
                            </div>    
                            <div class="form-group">
                               <label>Địa chỉ</label> 
                               <input placeholder="Địa chỉ" type="text" name="diaChi" class="form-control" required>
                            </div>  
                            <div class="form-group">
                               <label>Xe quan tâm</label> 
                               <select name="xeQuanTam" class="form-control" required>
                                    <option value="" selected disabled>Vui lòng chọn</option>
                                    @foreach($typecar as $row)
                                    <option value="{{$row->name}}">{{$row->name}}</option>
                                    @endforeach
                               </select>
                            </div>                           
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btnAdd" class="btn btn-primary" form="addForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->

    <!--  MEDAL EDIT-->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cập nhật</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="editForm" autocomplete="off">
                            <input type="hidden" name="idUpdate">
                            <div class="form-group">
                               <label>Khách hàng</label> 
                               <input placeholder="Họ và tên" type="text" name="ekhachHang" class="form-control" required >
                            </div>    
                            <div class="form-group">
                               <label>Số điện thoại</label> 
                               <input placeholder="Số điện thoại" type="text" name="edienThoai" class="form-control" required>
                            </div>    
                            <div class="form-group">
                               <label>Địa chỉ</label> 
                               <input placeholder="Địa chỉ" type="text" name="ediaChi" class="form-control" required>
                            </div>  
                            <div class="form-group">
                               <label>Xe quan tâm</label> 
                               <select name="exeQuanTam" class="form-control" required>
                                    <option value="" selected disabled>Vui lòng chọn</option>
                                    @foreach($typecar as $row)
                                    <option value="{{$row->name}}">{{$row->name}}</option>
                                    @endforeach
                               </select>
                            </div>    
                            <div class="form-group">
                               <label>Đánh giá</label>
                               <select name="edanhGia" class="form-control" required>
                                    <option value="1">ĐÃ ĐÁNH GIÁ</option>
                                    <option value="0">CHƯA ĐÁNH GIÁ</option>
                               </select> 
                            </div>                       
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btnUpdate" class="btn btn-primary" form="editForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->

    <!--  MEDAL UPLOAD-->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="uploadModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">UPLOAD</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="uploadForm" autocomplete="off">
                            <input type="hidden" name="idUpload">
                            <div class="form-group">
                               <label>Chọn file</label> 
                               <input type="file" name="uploadFile" class="form-control" required >
                            </div>          
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btnUpload" class="btn btn-info" form="uploadForm">Upload</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();
       
        $(document).ready(function(){
            let from = $("input[name=chonNgayOne]").val();
            let to = $("input[name=chonNgayTwo]").val();
            let nhanVien = $("select[name=nhanVien]").val();
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/guest/loadkhachhangdrp/') }}" + "/" + from + "/to/" + to + "/mode/" + nhanVien,
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "ngay" },
                    { "data": "id_user" },         
                    { "data": "khachHang" },
                    { "data": "dienThoai" },
                    { "data": "diaChi" },
                    { "data": "xeQuanTam" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                           if (row.danhGia) {
                            return "<strong class='text-success'>Đã đánh giá</strong>";
                           } else {
                            return "<strong class='text-danger'>Chưa đánh giá</strong>";
                           }
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                           if (row.dinhKem) {
                            if(row.mode == "active")
                                return "<strong class='text-info'><a target='_blank' href='./upload/drp/"+row.dinhKem+"'>Download</a></strong>&nbsp;&nbsp;<button id='xoaFile' data-id='"+row.id+"' data-link='"+row.dinhKem+"' class='btn btn-danger btn-sm'>x</button>";
                            else
                                return "<strong class='text-info'><a target='_blank' href='./upload/drp/"+row.dinhKem+"'>Download</a></strong>";
                           } else {
                            return "<button id='uploadFile' data-id='"+row.id+"' data-toggle='modal' data-target='#uploadModal' class='btn btn-secondary btn-sm'>Upload</button>";
                           }
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.mode == "active")
                                return "<button id='btnDanhGia' data-id='"+row.id+"' class='btn btn-primary btn-sm'><span class='fas fa-binoculars'></span></button>&nbsp;&nbsp;" 
                                + "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;&nbsp;" 
                                + "<button id='editData' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='fas fa-edit'></span></button>";
                            else return "<button id='btnDanhGia' data-id='"+row.id+"' class='btn btn-primary btn-sm'><span class='fas fa-binoculars'></span></button>&nbsp;&nbsp;"; 
                        }
                    }
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Add data
            $("#btnAdd").click(function(){ 
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#addForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ route('danhgia.drp.post') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            if (response.code !== 500)
                                this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            if (response.code !== 500)
                                $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: 'Trùng dữ liệu hoặc lỗi'
                            })
                            $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{ route('danhgia.drp.delete') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            $("#xemReport").click(function(){
                let from = $("input[name=chonNgayOne]").val();
                let to = $("input[name=chonNgayTwo]").val();
                let nhanVien = $("select[name=nhanVien]").val();
                let urlpathcurrent = "{{ url('management/guest/loadkhachhangdrp/') }}";
                table.ajax.url( urlpathcurrent + "/" + from + "/to/" + to + "/mode/" + nhanVien).load();
            });


            $(document).on('click','#btnDanhGia', function(){
                let drpcheck = $(this).data('id');
                open("{{url('management/guest/danhgiadrp/')}}" + "/" + drpcheck,"_self");
            });    
            
            $(document).on('click','#editData', function(){
                $.ajax({
                    url: "{{ route('drp.get.guest') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        if (response.code == 200) {
                           $("input[name=ekhachHang]").val(response.data.khachHang);
                           $("input[name=edienThoai]").val(response.data.dienThoai);
                           $("input[name=ediaChi]").val(response.data.diaChi);
                           $("select[name=exeQuanTam]").val(response.data.xeQuanTam);
                           $("input[name=idUpdate]").val(response.data.id);
                           $("select[name=edanhGia]").val(response.data.danhGia);
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể lấy dữ liệu lúc này!"
                        })
                    }
                });
            });   

            // Add data
            $("#btnUpdate").click(function(){ 
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#editForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ route('drp.update.guest') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnUpdate").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            if (response.code !== 500)
                                $("#editModal").modal('hide');
                            $("#btnUpdate").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: 'Trùng dữ liệu hoặc lỗi'
                            })
                            $("#editModal").modal('hide');
                            $("#btnUpdate").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });
            
            $(document).on('click','#uploadFile', function(){
                let idUpload = $(this).data('id');
                $("input[name=idUpload]").val(idUpload);
            });  

            // Upload file
            $("#btnUpload").click(function(){                 
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#uploadForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ route('drp.uploadfile') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnUpload").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            if (response.code !== 500)
                                this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            if (response.code !== 500)
                                $("#uploadModal").modal('hide');
                            $("#btnUpload").attr('disabled', false).html("Upload");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: 'Không thể upload file'
                            })
                            $("#uploadModal").modal('hide');
                            $("#btnUpload").attr('disabled', false).html("Upload");
                            console.log(response);
                        }
                    });
                });
            });


            $(document).on('click','#xoaFile', function(){
                let linkXoa = $(this).data('link');
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{ route('drp.xoafile') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "link": linkXoa
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });  
       });
    </script>
@endsection
