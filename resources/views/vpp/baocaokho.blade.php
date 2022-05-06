@extends('admin.index')
@section('title')
    Báo cáo kho
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
                        <h1 class="m-0"><strong>Báo cáo kho</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Báo cáo kho</li>
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
                                <strong>Báo cáo kho</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="row container">
                            <div class="col-md-4">
                                <label>Chọn loại báo cáo</label>
                                <select name="chonBaoCao" class="form-control">
                                    <option value="1">Tồn kho (thực tế)</option>
                                    <option value="2">Biến động kho (nhu cầu sử dụng)</option>
                                    <option value="3">Yêu cầu đã duyệt</option>
                                    <option value="4">Yêu cầu đợi duyệt (thiếu CCDC)</option>
                                    <option value="5">Nhập kho chi tiết</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Từ ngày</label>
                                <input type="date" name="tuNgay" value="<?php echo Date('Y-m-d');?>" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Đến ngày</label>
                                <input type="date" name="denNgay" value="<?php echo Date('Y-m-d');?>" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="button" class="form-control btn btn-success" id="xem" value="Xem">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12" id="tonKho" style="display:none;">
                                <h3>TỒN KHO THỰC TẾ</h3>
                                <div id="tonKhoShow">

                                </div>                                
                            </div>
                            <div class="col-md-12" id="bienDongKho" style="display:none;">
                                <h3>BIẾN ĐỘNG KHO (NHU CẦU SỬ DỤNG)</h3>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>TT</th>
                                            <th>Tên CCDC</th>
                                            <th>Mô tả</th>                                            
                                            <th>Số lượng nhập mới</th>
                                            <th>Số lượng xuất</th>
                                            <th>Mã yêu cầu (PX)</th>
                                            <th>Nhân viên yêu cầu</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bienDongKhoShow">                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12" id="yeuCauDaDuyet" style="display:none;">
                                <h3>YÊU CẦU CÔNG CỤ/DỤNG CỤ ĐÃ DUYỆT</h3>                               
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>TT</th>
                                            <th>Ngày yêu cầu (Ngày duyệt)</th>
                                            <th>Nhân viên</th>
                                            <th>Mục đích sử dụng</th> 
                                            <th>Mã yêu cầu</th>                                            
                                            <th>Danh mục</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody id="yeuCauDaDuyetShow">                                                                   
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12" id="yeuCauDoiDuyet" style="display:none;">
                                <h3>YÊU CẦU ĐỢI DUYỆT (THIẾU CCDC)</h3>                                
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>TT</th>
                                            <th>Ngày yêu cầu</th>
                                            <th>Nhân viên</th>
                                            <th>Mục đích sử dụng</th> 
                                            <th>Mã yêu cầu</th>                                            
                                            <th>Yêu cầu</th>
                                            <th>Đáp ứng (thực tế)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="yeuCauDoiDuyetShow">
                                                                  
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12" id="nhapKhoChiTiet" style="display:none;">
                                <h3>NHẬP KHO CHI TIẾT</h3>                                
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>TT</th>
                                            <th>Ngày nhập</th>
                                            <th>Người nhập</th>
                                            <th>Mã phiếu</th>
                                            <th>Nội dung</th> 
                                            <th>Danh mục</th> 
                                            <th>Số lượng</th> 
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>         
                                        </tr>
                                    </thead>
                                    <tbody id="nhapKhoChiTietShow">
                                                                       
                                    </tbody>
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
           $("#tonKho").hide();
           $("#bienDongKho").hide();
           $("#yeuCauDaDuyet").hide();
           $("#yeuCauDoiDuyet").hide();
           $("#nhapKhoChiTiet").hide();
           $("#xem").click(function(){
               let tuNgay = $("input[name=tuNgay]").val();
               let denNgay = $("input[name=denNgay]").val();
               let chose = $("select[name=chonBaoCao]").val();
               let urlPoint = "";
               switch(parseInt(chose)) {
                    case 1: {
                        urlPoint = "{{url('management/vpp/baocaokho/tonkhothucte/')}}";
                        $("#bienDongKho").hide();
                        $("#yeuCauDaDuyet").hide();
                        $("#yeuCauDoiDuyet").hide();
                        $("#nhapKhoChiTiet").hide();
                    } break;
                    case 2: {
                        urlPoint = "{{url('management/vpp/baocaokho/biendongkho/')}}";
                        $("#tonKho").hide();
                        $("#yeuCauDaDuyet").hide();
                        $("#yeuCauDoiDuyet").hide();
                        $("#nhapKhoChiTiet").hide();
                    } break;
                    case 3: {
                        urlPoint = "{{url('management/vpp/baocaokho/yeucaudaduyet/')}}";
                        $("#tonKho").hide();
                        $("#bienDongKho").hide();
                        $("#yeuCauDoiDuyet").hide();
                        $("#nhapKhoChiTiet").hide();
                    } break;
                    case 4: {
                        urlPoint = "{{url('management/vpp/baocaokho/yeucaudoiduyet/')}}";
                        $("#tonKho").hide();
                        $("#bienDongKho").hide();
                        $("#yeuCauDaDuyet").hide();
                        $("#nhapKhoChiTiet").hide();
                    } break;
                    case 5: {
                        urlPoint = "{{url('management/vpp/baocaokho/nhapkhochitiet/')}}";
                        $("#tonKho").hide();
                        $("#bienDongKho").hide();
                        $("#yeuCauDaDuyet").hide();
                        $("#yeuCauDoiDuyet").hide();
                    } break;
                    default: alert('Không tồn tại lựa chọn này');
               }
               $.ajax({
                    type:'POST',
                    url: urlPoint,
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "chose": chose,
                        "tuNgay": tuNgay,
                        "denNgay": denNgay
                    },                  
                    success: (response) => {  
                        switch(parseInt(chose)) {
                            case 1: {
                                $("#bienDongKho").hide();
                                $("#yeuCauDaDuyet").hide();
                                $("#yeuCauDoiDuyet").hide();
                                $("#nhapKhoChiTiet").hide();
                                $("#tonKho").show();
                                $("#tonKhoShow").html(response);
                            } break;
                            case 2: {
                                $("#tonkho").hide();
                                $("#yeuCauDaDuyet").hide();
                                $("#yeuCauDoiDuyet").hide();
                                $("#nhapKhoChiTiet").hide();
                                $("#bienDongKho").show();
                                $("#bienDongKhoShow").html(response);
                            } break;
                            case 3: {
                                $("#tonkho").hide();                                
                                $("#yeuCauDoiDuyet").hide();
                                $("#nhapKhoChiTiet").hide();
                                $("#bienDongKho").hide();
                                $("#yeuCauDaDuyet").show();
                                $("#yeuCauDaDuyetShow").html(response);
                            } break;
                            case 4: {
                                $("#tonkho").hide();                                
                                $("#yeuCauDuyet").hide();
                                $("#nhapKhoChiTiet").hide();
                                $("#bienDongKho").hide();
                                $("#yeuCauDoiDuyet").show();
                                $("#yeuCauDoiDuyetShow").html(response);
                            } break;
                            case 5: {
                                $("#tonkho").hide();                                
                                $("#yeuCauDuyet").hide();
                                $("#yeuCauDoiDuyet").hide();
                                $("#bienDongKho").hide();
                                $("#nhapKhoChiTiet").show();
                                $("#nhapKhoChiTietShow").html(response);
                            } break;
                            default: alert('Không tồn tại lựa chọn này');
                        }                
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Không xem báo cáo lúc này!'
                        })
                    }
               });
           });
        });
    </script>
@endsection
