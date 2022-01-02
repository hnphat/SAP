@extends('admin.index')
@section('title')
    Hồ sơ nhân viên
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
                <div class="row">
                    <div class="col-sm-4">
                        <input name="iInput" type="text" class="form-control" name="findMember" placeholder="Nhập tên cần tìm (có dấu)"> 
                    </div>        
                </div>            
                <hr>   
                <!-- <table id="showData" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Địa chỉ</th>
                    </tr>
                    </thead>
                </table> -->
                 <table class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Họ và tên</th>
                        <th>Điện thoại</th>
                    </tr>
                    </thead>
                    <tbody id="showData">

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>    
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
            //Display data
            // var table = $('#showData').DataTable( {
            //     responsive: true,
            //     ajax: "{{url('management/hoso/get/')}}",
            //     columns: [
            //         { "data": "id" },
            //         { "data": "surname" },
            //         { "data": "phone" },
            //         { "data": "birthday" },
            //         { "data": "address" }
            //     ]
            // });

                $("input[name=iInput]").change(function(){
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: "{{url('management/hanhchinh/gethoso/')}}",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "name": $("input[name=iInput]").val()
                        },
                        success: function(response){
                            if (response.code == 200) {
                                Toast.fire({
                                    icon: 'success',
                                    title: "Đã tìm được"
                                })
                                $('#showData').html(response.result);
                            } else {
                                Toast.fire({
                                    icon: 'warning',
                                    title: "Không tìm thấy!"
                                })
                            }                                
                        },
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Error 500!"
                            })
                        }
                    });
                });
        });
    </script>
@endsection
