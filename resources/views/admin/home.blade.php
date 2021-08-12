@extends('admin.index')
@section('title')
    Quản lý hồ sơ
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>DEFAULT DESIGN</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Dòng xe</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <button class="btn btn-success" data-toggle="modal" data-target="#add"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <table id="showData" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tài khoản</th>
                        <th>Họ và tên</th>
                        <th>Điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Địa chỉ</th>
                        <th>Tác vụ</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Tài khoản</th>
                        <th>Họ và tên</th>
                        <th>Điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Địa chỉ</th>
                        <th>Tác vụ</th>
                    </tr>
                    </tfoot>
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
            </div>
        </div>
        <!-- /.content -->
    </div>
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
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endsection
