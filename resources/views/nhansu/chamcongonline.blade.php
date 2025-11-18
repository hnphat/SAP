@extends('admin.index')
@section('title')
   Chấm công online
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
                        <h1 class="m-0"><strong>Chấm công Online</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Chấm công Online</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="container">       
                    <p>Trạng thái: <strong class="text-danger">Bạn chưa đăng ký thiết bị <button id="regDevice" class="btn btn-success btn-sm">Đăng ký ngay</button></strong></p>     
                    <p>Trạng thái: <strong class="text-success">Thiết bị đã đăng ký</strong></p>
                    <p>Trạng thái: <strong class="text-danger">Thiết bị lạ khác với thiết bị đã đăng ký trước đó</strong></p>
                    <input type="hidden" name="getStt" id="getStt">
                    <input type="hidden" name="getNowTimer" id="getNowTimer">
                    <p>Thời gian hiện tại: <strong style="font-size:25pt;">08:00</strong></p>
                    <p>Trạng thái vị trí: <strong class="text-danger">Đang không ở Công ty</strong></p>
                    <p>Trạng thái vị trí: <strong class="text-success">Đang ở Công ty</strong></p>
                    <p>Điều kiện chấm công: <strong class="text-success">Có thể chấm công</strong></p>
                    <p>Điều kiện chấm công: <strong class="text-danger">Không thể chấm công</strong></p>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>CHỌN BUỔI</strong>
                            <select class="form-control" name="buoi">
                                <option value="1">Sáng</option>
                                <option value="2">Chiều</option>
                                <option value="3">Tối</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <strong>CHỌN LOẠI CHẤM CÔNG</strong>
                            <select class="form-control" name="buoi">
                                <option value="1">Chấm công vào</option>
                                <option value="2">Chấm công ra</option>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <p class="text-center">
                        <button class="btn btn-primary">CHẤM CÔNG</button>
                    </p>
                    <h6 class="text-info">Ghi nhận chấm công: <strong>12:00 ngày 18/11/2025</strong></h6>
                    <p>
                        <strong>ĐÃ CHẤM CÔNG</strong><br>
                        - Buổi sáng: <br>
                        + Vào: Chưa có <br>
                        + Ra: 16:00 <br>
                        - Buổi chiều: <br>
                        + Vào: 12:00 <br>
                        + Ra: Chưa có <br>
                        - Buổi tối: <br>
                        + Vào: Chưa có <br>
                        + Ra: Chưa có <br>
                    </p>
                </div>               
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
           
           function loadWithRoom() {
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/getnhanvienroom')}}",
                    type: "get",
                    dataType: "text",
                    success: function(response){                        
                        $("select[name=nhanVien]").append(response);                                  
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không tải bổ sung"
                        })
                    }
                });
           } 
           loadWithRoom();
           function reload() {
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id": $("select[name=nhanVien]").val() ? $("select[name=nhanVien]").val() : $("input[name=nhanVien]").val(),
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
           }            

           $("#chon").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id":  $("select[name=nhanVien]").val() ? $("select[name=nhanVien]").val() : $("input[name=nhanVien]").val(),
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
                
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/getnhanvieninfo')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id":  $("select[name=nhanVien]").val() ? $("select[name=nhanVien]").val() : $("input[name=nhanVien]").val(),
                    },
                    success: function(response){       
                        console.log(response);                
                        $("#nameshow").val(response.ten);
                    },
                    error: function(){
                        console.log("Lỗi: Không thể load thông tin nhân viên!");
                    }
                });    
           });

           $(document).on('click','#xinPhep', function(){
                $("input[name=ngayXin]").val($(this).data('ngay'));
                $("input[name=thangXin]").val($(this).data('thang'));
                $("input[name=namXin]").val($(this).data('nam'));
                $("input[name=idUserXin]").val($("select[name=nhanVien]").val() ? $("select[name=nhanVien]").val() : $("input[name=nhanVien]").val());
           });

           $(document).on('click','#tangCa', function(){
                $("input[name=ngayXinTangCa]").val($(this).data('ngay'));
                $("input[name=thangXinTangCa]").val($(this).data('thang'));
                $("input[name=namXinTangCa]").val($(this).data('nam'));
                $("input[name=idUserXinTangCa]").val($("select[name=nhanVien]").val() ? $("select[name=nhanVien]").val() : $("input[name=nhanVien]").val());
           });


           $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/themphep')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    beforeSend: function () {
                       $("#btnAdd").attr('disabled', true).html("Đang xử lý vui lòng đợi....");
                    },
                    success: function(response){
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#btnAdd").attr('disabled', false).html("Lưu");
                        $("#addModal").modal('hide');
                        reload();
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể tạo phép"
                        })
                    }
                });
           });

           $("#btnAddTangCa").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/nhansu/chitiet/ajax/themtangca')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addFormTangCa").serialize(),
                    success: function(response){
                        $("#addFormTangCa")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addModalTangCa").modal('hide');
                        reload();
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể xin tăng ca"
                        })
                    }
                });
           });


           $(document).on('click','#xacNhan', function(){
                if (confirm("Xác nhận giờ công?\nLưu ý: Sau khi xác nhận sẽ không được chỉnh sửa và thêm phép")){
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/chot')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "id": $("select[name=nhanVien]").val() ? $("select[name=nhanVien]").val() : $("input[name=nhanVien]").val(),
                            "_token": "{{csrf_token()}}",
                            "thang": $(this).data('thang'),
                            "nam": $(this).data('nam'),
                            "ngayCong": $(this).data('ngaycong'),
                            "tangCa": $(this).data('tangca'),
                            "tongTre": $(this).data('tongtre'),
                            "khongPhep": $(this).data('khongphep'),
                            "khongPhepCaNgay": $(this).data('khongphepcangay'),
                            "phepNam": $(this).data('phepnam'),
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
                                title: "Lỗi! Không thể xác nhận giờ công"
                            })
                        }
                    });
                }
           });



           $(document).on('click','#xemBienBan', function(){
                $.ajax({
                    url: "{{url('management/nhansu/xembienban')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "id": $(this).data('id'),
                        "_token": "{{csrf_token()}}",
                        "thang": $(this).data('thang'),
                        "nam": $(this).data('nam')                        
                    },
                    success: function(response){
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#showChiTietBB").empty();
                        let bbHtml = "";
                        for(let i = 0; i < response.data.length; i++) {
                            bbHtml += `<tr>
                                <td>${(i+1)}</td>
                                <td>${response.data[i].noiDung}</td>
                                <td>${response.data[i].hinhThuc}</td>
                                <td><a class="btn btn-primary btn-sm" href="{{asset('upload/bienbankhenthuong/${response.data[i].url}')}}" target="_blank">XEM</a></td>
                            </tr>`;
                        }
                        $("#showChiTietBB").html(bbHtml);
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể xem biên bản lúc này"
                        })
                    }
                });
           });
        });
    </script>
@endsection
