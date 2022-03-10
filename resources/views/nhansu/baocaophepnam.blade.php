@extends('admin.index')
@section('title')
    Báo cáo sử dụng phép năm
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Báo cáo sử dụng phép năm</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý xin phép - Báo cáo phép năm</li>
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
                                <strong>Báo cáo sử dụng phép năm</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <h4>Từ <strong class="text-danger">01-01-<?php echo Date('Y');?></strong> đến ngày <strong class="text-danger"><?php echo Date('d-m-Y');?></strong></h4>
                        <p><i>Phép năm được tính bắt đầu từ 01-01-<?php echo Date('Y');?>. 
                            Mỗi nhân viên sẽ được 01 ngày phép năm (tối đa 12 ngày/năm) nếu thời gian công tác đủ 30 ngày kể từ ngày bắt đầu làm việc trên phần mềm. <span class="text-danger text-bold">Lưu ý: Phép năm không dùng hết trong năm sẽ bị hủy và không bảo lưu cho năm sau.</span></i></p>
                        <div style="overflow: auto;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Nhân viên</th>
                                        <th>Ngày bắt đầu (trên phần mềm)</th>
                                        <th>Phép năm thực tế</th>
                                        <th>Phép năm sử dụng</th>
                                        <th>Phép năm còn lại</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody id="showData">
                                    
                                </tbody>
                            </table>            
                        </div>                     
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
    <!-- /.content -->
    </div>   
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
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

        // Exe
        $(document).ready(function() {
            $.ajax({
                    type:'GET',
                    url: "{{url('management/nhansu/loadbaocaophepnam/')}}",
                    dataType: "text",                  
                    success: (response) => {                         
                        $("#showData").html(response);
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Không thể xem báo cáo!'
                        })
                    }
            });
        });
    </script>
@endsection
