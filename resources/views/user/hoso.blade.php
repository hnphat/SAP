@extends('admin.index')
@section('title')
    Quản lý hồ sơ
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
                        <h1 class="m-0"><strong>HỒ SƠ</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Hồ sơ</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div>
                <button class="btn btn-success" data-toggle="modal" data-target="#add"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <table id="showData" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tài khoản</th>
                        <th>Họ và tên</th>
                        <th>Điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Địa chỉ</th>
                        <th>Hồ sơ nén</th>
                        <th>Tác vụ</th>
                    </tr>
                    </thead>                    
                </table>

                <!-- Medal Add -->
                <div class="modal fade" id="add">
                    <div class="modal-dialog">
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
                                        <h3 class="card-title">THÊM MỚI</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="ajaxform" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="acc">Tài khoản chưa bổ sung thông tin</label>
                                                <select class="form-control" name="user_id" id="acc">
{{--                                                    @foreach($user_empty as $row)--}}
{{--                                                        <option value="{{$row->id}}">{{$row->name}}</option>--}}
{{--                                                    @endforeach--}}
                                                </select>
                                            </div>                                            
                                            <div class="form-group">
                                                <label for="hoTen">Họ và tên</label>
                                                <input type="text" class="form-control" id="hoTen" placeholder="Họ và tên" name="hoTen">
                                            </div>
                                            <div class="form-group">
                                                <label for="ngaySinh">Ngày sinh</label>
                                                <input type="text" class="form-control" id="ngaySinh" placeholder="Ngày sinh VD 01/10/2020" name="ngaySinh">
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="text" class="form-control" id="phone" placeholder="Số điện thoại" name="phone">
                                            </div>
                                            <div class="form-group">
                                                <label for="diaChi">Địa chỉ</label>
                                                <input type="text" class="form-control" id="diaChi" placeholder="Địa chỉ" name="diaChi">
                                            </div>                                           
                                            <div class="form-group">
                                                <button id="submit" class="btn btn-primary">Lưu</button>
                                            </div>
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

                <!-- Medal Edit -->
                <div class="modal fade" id="edit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- general form elements -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">CHỈNH SỬA</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="ajaxEditForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="edit_id" />
                                            <label for="acc_id">Tài khoản</label>
                                            <select class="form-control" name="acc_id" id="acc_id" disabled="disabled">
                                                @foreach($user as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-group">
                                                <label for="hoTen">Họ và tên</label>
                                                <input type="text" class="form-control" placeholder="Họ và tên" name="_hoTen">
                                            </div>
                                            <div class="form-group">
                                                <label for="ngaySinh">Ngày sinh</label>
                                                <input type="text" class="form-control" placeholder="Ngày sinh VD 01/10/2020" name="_ngaySinh">
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="text" class="form-control" placeholder="Số điện thoại" name="_phone">
                                            </div>
                                            <div class="form-group">
                                                <label for="diaChi">Địa chỉ</label>
                                                <input type="text" class="form-control" placeholder="Địa chỉ" name="_diaChi">
                                            </div>
                                            <div class="form-group">
                                                <button id="update" class="btn btn-success">Cập nhật</button>
                                            </div>
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


                <!-- Medal Upload file -->
                <div class="modal fade" id="upModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- general form elements -->
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">CẬP NHẬT HÌNH ẢNH VÀ HỒ SƠ</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="upForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="up_id" />                                            
                                            <div class="form-group">
                                                <label for="fileAnh">Hình ảnh</label>
                                                <input type="file" class="form-control" name="fileAnh" id="fileAnh">
                                                <span>Tối đa 10MB (jpg,png,JPG,PNG)</span>
                                            </div>
                                            <div class="form-group">
                                                <label for="fileHoso">Tệp hồ sơ</label>
                                                <input type="file" class="form-control" name="fileHoso" id="fileHoso">
                                                <span>Tối đa 20MB (pdf,zip,rar,doc,docx,xls,xlsx,ppt,pptx)</span>
                                            </div>
                                            <div class="form-group">
                                                <button id="upload" class="btn btn-success">Tải tệp lên</button>
                                            </div>
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
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function(){

            function reloadUserOption() {
                // reload when add new user
                $.get('{{url('management/hoso/users/')}}', function(data){
                    $("#acc").html(data);
                });
            }

            // reload when start page
            reloadUserOption();

            $("#submit").click(function(e){
                e.preventDefault();

                $.ajax({
                    url: "{{url('management/hoso/add/')}}",
                    type: "POST",
                    dataType: "json",
                    data: $('#ajaxform').serialize(),
                    success: function(response){
                        $('#ajaxform')[0].reset();
                        console.log(response);
                        Toast.fire({
                            icon: 'success',
                            title: "Đã thêm!"
                        })
                        table.ajax.reload();
                        $("#add").modal("hide");
                        reloadUserOption();
                    }
                });
            });

            //Display data
            var table = $('#showData').DataTable( {
                responsive: true,
                ajax: "{{url('management/hoso/get/')}}",
                columns: [
                    { "data": "id" },
                    {  "data": null,
                        render: function(data, type, row) {
                            if (row.anh == null)
                                return "<span class='badge badge-danger'>Chưa có</span>";
                            else
                                return "<img src='upload/hoso/"+row.anh+"' style='max-width: 100px; height: auto;'/>";
                        } 
                    },
                    { "data": "name" },
                    { "data": "surname" },
                    { "data": "phone" },
                    { "data": "birthday" },
                    { "data": "address" },
                    {   "data": null,
                        render: function(data, type, row) {
                            if (row.hoSo == null)
                                return "<span class='badge badge-danger'>Chưa có</span>";
                            else
                                return "<a href='upload/tephoso/"+row.hoSo+"' download>Tải về</a>";
                        }  },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button data-id='"+row.id+"' class='btn btn-success btn-sm' data-toggle='modal' data-target='#edit' id='editForm'><span class='far fa-edit'></span></button>&nbsp;" 
                            + "<button data-id='"+row.id+"' class='btn btn-danger btn-sm' id='delete'><span class='fas fa-times-circle'></span></button>&nbsp;"
                            + "<button data-id='"+row.id+"' class='btn btn-info btn-sm' data-toggle='modal' data-target='#upModal' id='up'><span class='fab fa-wpforms'></span></button>&nbsp;";
                        }
                    }
                ]
            });

            //edit code here
            $(document).on('click','#editForm',function(){
                $.ajax({
                    url: "{{url('management/hoso/edit/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'id': $(this).data('id')
                    },
                    success: function(response){
                        console.log(response.data);
                        $("input[name=_hoTen]").val(response.data.surname);
                        $("input[name=_ngaySinh]").val(response.data.birthday);
                        $("input[name=_phone]").val(response.data.phone);
                        $("input[name=_diaChi]").val(response.data.address);
                        $("select[name=acc_id]").val(response.data.id_user);
                        $("input[name=edit_id]").val(response.data.id);
                    }
                });
            });

            //update code here
            $(document).on('click','#update',function(e){
                e.preventDefault();
                if(confirm("Bạn có chắc muốn cập nhật")) {
                    $.ajax({
                        url: "{{url('management/hoso/update/')}}",
                        type: "POST",
                        dataType: "json",
                        data: $('#ajaxEditForm').serialize(),
                        success: function(response){
                            $('#ajaxEditForm')[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: "Đã cập nhật!"
                            })
                            table.ajax.reload();
                            $("#edit").modal("hide");
                        }
                    });
                }
            });

            //up form
            $(document).on('click','#up',function(){
                $("input[name=up_id]").val($(this).data('id'));
            });

            //upload
            $(document).one('click','#upload',function(e){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#upForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/hoso/ajax/posttep/')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#upload").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#upModal").modal('hide');
                            $("#upload").attr('disabled', false).html("Tải tệp lên");
                            console.log(response);
                            $("#upModal").modal("hide");
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Có lỗi Ảnh: ' + response.responseJSON.errors.fileAnh + " Hồ sơ: " + response.responseJSON.errors.fileHoso 
                            })
                            $("#upModal").modal('hide');
                            $("#upload").attr('disabled', false).html("Tải tệp lên");
                            console.log(response);
                        }
                    });
                });                
                table.ajax.reload();              
            });

            //delete code here
            $(document).on('click','#delete',function(){
                if (confirm("Bạn có chắc muốn xóa?")) {
                    $.ajax({
                        url: "{{url('management/hoso/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            '_token': "{{csrf_token()}}",
                            'id': $(this).data('id')
                        },
                        success: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: "Đã xóa!"
                            })
                            table.ajax.reload();
                            reloadUserOption();
                        }
                    });
                }
            });
        });
    </script>
@endsection
