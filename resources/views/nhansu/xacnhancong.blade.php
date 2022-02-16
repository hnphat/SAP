@extends('admin.index')
@section('title')
    Quản lý chốt công
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
                        <h1 class="m-0"><strong>Quản lý chốt công</strong> 
                    </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý chốt công</li>
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
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="chon" type="button "class="btn btn-xs btn-info">Chọn</button>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label><br/>
                        <button id="xacNhanAll" type="button "class="btn btn-xs btn-success">Xác nhận tất cả</button>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="huyAll" type="button "class="btn btn-xs btn-warning">Hủy tất cả</button>
                    </div>
                </div>  
                <br/>
                <p id="loading" style="text-align:center;display:none;">
                    <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width: 100px; height:auto;"/>
                </p>
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <th>Nhân viên</th>
                        <th>Ngày công</th>
                        <th>Phép năm</th>
                        <th>Tăng ca</th>
                        <th>Tổng trể/sớm</th>
                        <th>Không phép (Trể/sớm/QCC/Nữa ngày)</th>
                        <th>Không phép (Cả ngày)</th>
                        <th>Trạng thái</th>
                        <th>Tác vụ</th>
                    </tr>
                    <tbody class="text-center" id="chiTietCong">
                       
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

        // Exe
        $(document).ready(function() {
            function reload() {
                $.ajax({
                    url: "{{url('management/nhansu/chotcong/ajax/get')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){                        
                        $("#chiTietCong").html(response);                                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể tải chi tiết chốt công"
                        })
                    }
                });
           } 

           $("#chon").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/chotcong/ajax/get')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){                        
                        $("#chiTietCong").html(response);                                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể tải chi tiết chốt công"
                        })
                    }
                });
           });

            //Delete data
            $(document).on('click','#huy', function(){
                if(confirm('Bạn có chắc muốn hủy xác nhận của nhân viên này?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/huy')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "thang": $(this).data('thang'),
                            "nam": $(this).data('nam')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể hủy lúc này!"
                            })
                        }
                    });
                }
            });

            //Xác nhận tất cả
            $("#xacNhanAll").click(function(){
                if(confirm('Xác nhận chốt công cho TẤT CẢ nhân viên?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/xacnhanall')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "thang": $("select[name=thang]").val(),
                            "nam": $("select[name=nam]").val()
                        },
                        beforeSend: function() {
                            $("#loading").show();
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
                                title: "Lỗi! Không thể tải chi tiết chốt công"
                            })
                        },
                        complete: function() {
                            $("#loading").hide();
                        }
                    });
                }
           });


           // Hủy tất cả
           $("#huyAll").click(function(){
                if(confirm('Xác nhận hủy chốt công cho TẤT CẢ nhân viên?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/huyall')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "thang": $("select[name=thang]").val(),
                            "nam": $("select[name=nam]").val()
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
                                title: "Lỗi! Không thể tải chi tiết chốt công"
                            })
                        }
                    });
                }
           });
        });
    </script>
@endsection
