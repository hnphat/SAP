@extends('admin.index')
@section('title')
    Quản lý tăng ca - ngày lễ
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
                        <h1 class="m-0"><strong>Quản lý tăng ca - ngày lễ</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý xin phép - Quản lý tăng ca/ngày lễ</li>
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
                        <label>Ngày</label>
                        <select name="ngay" class="form-control">
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}" <?php if(Date('d') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Tháng</label>
                        <select name="thang" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}" <?php if(Date('m') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Năm</label>
                        <select name="nam" class="form-control">
                            @for($i = 2021; $i < 2100; $i++)
                                <option value="{{$i}}" <?php if(Date('Y') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label><br/>
                        <input id="xem" type="submit" class="btn btn-xs btn-primary" value="XEM">
                    </div>
                </div>
                <hr/>
                <h4>Quản lý ngày lễ/nghĩ</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label>Loại phép</label>
                        <select name="loaiPhep" class="form-control">
                            <option value="1">Nghỉ lễ theo quy định</option>
                            <option value="0">Nghỉ lễ không hưởng lương</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label><br/>
                        <input id="themAll" type="submit" class="btn btn-xs btn-success" value="THÊM TẤT CẢ">
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <input id="xoaAll" type="submit" class="btn btn-xs btn-danger" value="HỦY TẤT CẢ">
                    </div>
                </div>  
                <hr/>
                <h4>Quản lý tăng ca</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label>Nhân viên</label>
                        <select name="nhanVien" class="form-control">
                            @foreach($user as $row)
                                @if($row->active == true)
                                    <option value="{{$row->id}}">{{$row->name}} - {{$row->userDetail->surname}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Hệ số lương</label> 
                            <select name="heSo" class="form-control">
                                <option value="1">Bình thường (x 1.0)</option>
                                <option value="1.5">Ngoài giờ (x 1.5)</option>
                                <option value="2">Chủ nhật (x 2.0)</option>
                                <option value="3">Lễ, Tết (x 3.0)</option>
                            </select>
                    </div>
                    <div class="col-md-2">
                       <label>Chọn loại giờ công</label>
                       <select name="loaiGioCong" class="form-control">
                           <option value="0" selected>Giờ công quy định</option>
                           <option value="1">Giờ công thực tế</option>
                       </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label><br/>
                        <input id="themTangCa" type="submit" class="btn btn-xs btn-info" value="THÊM">
                    </div>
                </div>
                <br/>
                <br/>
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <th>Ngày</th>
                        <th>Nhân viên</th>
                        <th>Hệ số lương</th>
                        <th>Vào sáng</th>
                        <th>Ra sáng</th>
                        <th>Vào chiều</th>
                        <th>Ra Chiều</th>
                        <th>Công sáng</th>
                        <th>Công chiều</th>
                        <th>Tổng công</th>
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

        $(document).ready(function(){

            function reloadData() {
                $.ajax({
                    url: "{{url('management/nhansu/quanlytangca/ajax/get')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val(),
                    },
                    success: function(response){
                        $("#chiTietCong").html(response);
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể xem"
                        })
                    }
                });                   
            }

            $("#xem").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/quanlytangca/ajax/get')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val(),
                    },
                    success: function(response){
                        $("#chiTietCong").html(response);
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể xem"
                        })
                    }
                });
            });

            $("#themTangCa").click(function(){
                if(confirm("Xác nhận thêm tăng ca đối với nhân viên này vào ngày " + $("select[name=ngay]").val() +"/"+ $("select[name=thang]").val() +"/"+ $("select[name=nam]").val() +" ?")) {
                    $.ajax({
                        url: "{{url('management/nhansu/quanlytangca/ajax/postnhanvien')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "ngay": $("select[name=ngay]").val(),
                            "thang": $("select[name=thang]").val(),
                            "nam": $("select[name=nam]").val(),
                            "nhanVien": $("select[name=nhanVien]").val(),
                            "heSo": $("select[name=heSo]").val(),
                            "loaiGioCong": $("select[name=loaiGioCong]").val(),
                        },
                        success: function(response){
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reloadData();
                        },
                        error: function(){
                            Toast.fire({
                                icon: "error",
                                title: "Lỗi! Không thể thêm"
                            })
                        }
                    });
                }
            });


            $(document).on("click","#del",function(){
                if(confirm("Bạn có chắc muốn xóa?")){
                    $.ajax({
                        url: "{{url('management/nhansu/quanlytangca/ajax/xoa')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('idquanly'),
                            "idPhep": $(this).data('idphep'),
                        },
                        success: function(response){
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reloadData();
                        },
                        error: function(){
                            Toast.fire({
                                icon: "error",
                                title: "Lỗi! Không thể xóa"
                            })
                        }
                    });
                }
            });

            $("#themAll").click(function(){
            if(confirm("Xác nhận thêm phép hàng loạt nhân viên vào ngày " + $("select[name=ngay]").val() +"/"+ $("select[name=thang]").val() +"/"+ $("select[name=nam]").val() +" với phép tương ứng")) {
                $.ajax({
                    url: "{{url('management/nhansu/quanlytangca/ajax/themall')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val(),
                        "loaiPhep": $("select[name=loaiPhep]").val(),                        
                    },
                    success: function(response){
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể thêm"
                        })
                    }
                });
            }
            });

            $("#xoaAll").click(function(){
            if(confirm("Xác nhận xóa tất cả phép của nhân viên vào ngày " + $("select[name=ngay]").val() +"/"+ $("select[name=thang]").val() +"/"+ $("select[name=nam]").val() +" \nLưu ý: Kiểm tra kỹ ngày cần xóa, hệ thống sẽ tự động xóa tất cả các loại phép trong ngày được chọn của tất cả nhân viên\nHệ thống không thể hoàn lại các phép đã xóa")) {
                $.ajax({
                    url: "{{url('management/nhansu/quanlytangca/ajax/huyall')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val(),                       
                    },
                    success: function(response){
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể thêm"
                        })
                    }
                });
            }
            });
        });
    </script>
@endsection
