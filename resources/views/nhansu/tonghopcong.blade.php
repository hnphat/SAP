@extends('admin.index')
@section('title')
   Tổng hợp công
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
                        <h1 class="m-0"><strong>Tổng hợp công</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Tổng hợp công</li>
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
                    <div class="col-md-2">
                        <label>Ngày</label>
                        <select name="ngay" class="form-control">
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>                  
                    <div class="col-md-2">
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
                        <button id="xemNgay" type="button "class="btn btn-xs btn-info">Xem ngày</button>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="xemThang" type="button "class="btn btn-xs btn-warning">Xem tháng</button>
                    </div>
                </div>  
                <br/>
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Vào sáng</th>
                        <th>Ra sáng</th>
                        <th>Vào chiều</th>
                        <th>Ra chiều</th>
                        <th>Công sáng</th>
                        <th>Công chiều</th>
                        <th>T/S Chiều</th>
                        <th>T/S  Sáng</th>
                        <th>Trạng thái</th>
                        <th>Tăng ca</th>
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

        $(document).ready(function(){
           $("#xemNgay").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/tonghop/ajax/getngay')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "ngay": $("select[name=ngay]").val(),
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

           $("#xemThang").click(function(){
                open("management/nhansu/xemthang" + "/ngay/" +  $("select[name=ngay]").val() + "/thang/" + $("select[name=thang]").val() + "/nam/" + $("select[name=nam]").val(),"_blank");
           });         
        });
    </script>
@endsection
