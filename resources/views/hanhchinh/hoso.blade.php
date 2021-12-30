@extends('admin.index')
@section('title')
    Hồ sơ nhân viên
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>HỒ SƠ NHÂN VIÊN</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Hồ sơ nhân viên</li>
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
                        <th>Họ và tên</th>
                        <th>Điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Địa chỉ</th>
                    </tr>
                    </thead>
                </table>
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
                    { "data": "surname" },
                    { "data": "phone" },
                    { "data": "birthday" },
                    { "data": "address" }
                ]
            });
        });
    </script>
@endsection
