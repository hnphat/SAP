@extends('admin.index')
@section('title')
   Phê duyệt hợp đồng
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
                        <h1 class="m-0"><strong>Phê duyệt hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Phê duyệt hợp đồng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Đề nghị hợp đồng</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                                <form id="addPkForm">
                                    {{csrf_field()}}
                                    <input type="hidden" name="idHopDong" id="idHopDong">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Chọn hợp đồng</label>
                                                <select name="chonDeNghi" id="chonDeNghi" class="form-control">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5>THÔNG TIN KHÁCH HÀNG</h5>
                                        <p>Họ và tên: <strong id="sHoTen"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày sinh: <strong id="sNgaySinh"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Số điện thoại: <strong id="sDienThoai"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MST: <strong id="smst"></strong></p>
                                        <p>CMND/CCCD: <strong id="scmnd"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày Cấp: <strong id="sNgayCap"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi cấp: <strong id="sNoiCap"></strong></p>
                                        <p>Địa chỉ: <strong id="sDiaChi"></strong></p>
                                        <p>Đại diện: <strong id="sDaiDien"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chức vụ: <strong id="sChucVu"></strong></p>
                                    </div>
                                    <div>
                                        <h5>THÔNG TIN XE BÁN</h5>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>ĐÃ DUYỆT XE:</label>
                                                    <input name="xeGan" type="hidden" class="form-control">
                                                </div>
                                                <table class="table table-bordered table-striped">
                                                    <tr class="bg-cyan">
                                                        <th>Tên xe</th>
                                                        <th>VIN</th>
                                                        <th>Số khung/số máy</th>
                                                        <th>Thông tin khác</th>
                                                    </tr>
                                                    <tbody id="showXeGan"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Xe bán</label>
                                                    <input id="xeBan" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Tiền cọc:</label>
                                                    <input disabled name="tamUng" id="tamUng" value="0" placeholder="Nhập số tiền thu tạm ứng" type="number" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" id="showCost" class="form-control" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Màu sắc</label>
                                                    <input id="mauSac" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Giá xe:</label>
                                                    <input disabled name="giaBanXe" id="giaBanXe" value="0" placeholder="Nhập giá bán xe" type="number" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" id="showCostCar" class="form-control" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Hình thức thanh toán</label>
                                                    <select disabled name="hinhThucThanhToan" id="hinhThucThanhToan" class="form-control">
                                                        <option value="1">Tiền mặt</option>   
                                                        <option value="0">Ngân hàng</option>                                                      
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Giá niêm yết:</label>
                                                    <input disabled name="giaNiemYet" id="giaNiemYet" value="0" placeholder="Nhập giá niêm yết" type="number" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" id="showNiemYet" class="form-control" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Hoa hồng môi giới:</label>
                                                    <input disabled name="hoaHongMoiGioi" id="hoaHongMoiGioi" value="0" placeholder="Nhập hoa hồng môi giới" type="number" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" id="showHoaHongMoiGioi" class="form-control" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Họ và tên:</label>
                                                    <input disabled name="hoTen" id="hoTen" placeholder="Họ tên" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>CMND/CCCD:</label>
                                                    <input disabled name="cmnd" id="cmnd" placeholder="CMND/CCCD" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Điện thoại:</label>
                                                    <input disabled name="dienThoai" id="dienThoai" placeholder="Điện thoại" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- <button type="button" id="reload"  class="btn btn-info">Tải lại</button><br/><br/> -->
                                <h5>CÁC LOẠI PHÍ</h5>
                                        <table class="table table-bordered table-striped">
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Nội dung</th>
                                                <th>Giá</th>
                                                <th>Tặng</th>
                                            </tr>
                                            <tbody id="showPKCOST">
                                            </tbody>
                                        </table>
                                        <p>Tổng cộng: <strong id="xtongCost"></strong></p>
                                        <h5>PHỤ KIỆN BÁN</h5>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr class="bg-cyan">
                                                    <th>TT</th>
                                                    <th>Nội dung</th>
                                                    <th>Giá</th>
                                                </tr>
                                                <tbody id="showPKPAY">
                                                </tbody>
                                            </thead>
                                        </table>
                                        <p>Tổng cộng: <strong id="xtongPay"></strong></p>
                                        <h5>PHỤ KIỆN KHUYẾN MÃI, QUÀ TẶNG</h5>
                                        <table class="table table-bordered table-striped">
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Nội dung</th>
                                                <th>Giá</th>
                                                <th>Loại</th>
                                            </tr>
                                            <tbody id="showPKFREE">
                                            </tbody>
                                        </table>
                                        <h4 class="text-right">
                                            TỔNG: <strong id="xtotal"></strong>
                                        </h4>
                                        <h5>Yêu cầu sửa hợp đồng: <strong class="text-danger" id="requestSaleEdit"></strong></h5>
                                        <h5>Yêu cầu hủy: <strong class="text-danger" id="requestSaleCancel"></strong></h5>
                                        <h5>Hỗ trợ HTV: <strong class="text-success" id="htvSupport">0</strong></h5>
                                        <h5>Chi phí vận chuyển: <strong class="text-success" id="phiVanChuyen">0</strong></h5>
                                        <button id="duyetDeNghi" class="btn btn-info">DUYỆT ĐỀ NGHỊ</button>
                                        <button id="choPhepHuy" class="btn btn-warning">CHO PHÉP HỦY</button>
                                        <button id="choPhepSua" class="btn btn-warning">CHO PHÉP CHỈNH SỬA HỢP ĐỒNG</button>

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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();

        // show data
        $(document).ready(function() {
            // default set
            $("#duyetDeNghi").hide();
            $("#choPhepHuy").hide();
            $("#choPhepSua").hide();
            // load list hợp đồng
            function loadList() {
                $.ajax({
                    url: "management/hd/hd/danhsach/",
                    dataType: "text",
                    success: function(response) {
                        $('#chonDeNghi').html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không lấy được danh sách hợp đồng"
                        })
                    }
                });
            }
            loadList();
            // --- end load list hợp đồng
            //--- end default
            function showSoTien() {
                var cos = $('#tamUng').val();
                $('#showCost').val("(" + DOCSO.doc(cos) + ")");
                var cos = $('#giaBanXe').val();
                $('#showCostCar').val("(" + DOCSO.doc(cos) + ")");
                var cos = $('#giaNiemYet').val();
                $('#showNiemYet').val("(" + DOCSO.doc(cos) + ")");
                var cos = $('#hoaHongMoiGioi').val();
                $('#showHoaHongMoiGioi').val("(" + DOCSO.doc(cos) + ")");
            }

            $('#tamUng').keyup(function(){
                var cos = $('#tamUng').val();
                $('#showCost').val("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaBanXe').keyup(function(){
                var cos = $('#giaBanXe').val();
                $('#showCostCar').val("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaNiemYet').keyup(function(){
                var cos = $('#giaNiemYet').val();
                $('#showNiemYet').val("(" + DOCSO.doc(cos) + ")");
            });

            $('#hoaHongMoiGioi').keyup(function(){
                var cos = $('#hoaHongMoiGioi').val();
                $('#showHoaHongMoiGioi').val("(" + DOCSO.doc(cos) + ")");
            });

            function reloadSS(leadAllowCancel,requestCancel, adminCheck, leadCheck, requestEdit) {
                if (leadAllowCancel == true) {
                    $("#duyetDeNghi").hide();
                    $("#choPhepHuy").hide();
                    $("#choPhepSua").hide();
                } else if (adminCheck == true && leadCheck == false) {
                    $("#duyetDeNghi").show();
                } else if(adminCheck == true && leadCheck == true) {
                    if (requestCancel == true)
                        $("#choPhepHuy").show();
                    else $("#choPhepHuy").hide();
                    if (requestEdit == true)
                        $("#choPhepSua").show();
                    else $("#choPhepSua").hide();
                    $("#duyetDeNghi").hide();
                } else {
                    $("#duyetDeNghi").hide();
                    $("#choPhepHuy").hide();
                    $("#choPhepSua").hide();
                }
            }

            $("#chonDeNghi").change(function(){
                $.ajax({
                    url: "management/hd/hd/denghi/chondenghi/" + $("select[name=chonDeNghi]").val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code != 500) {
                            $("#sHoTen").text(response.data.guestname);
                            $("#sDienThoai").text(response.data.phone);
                            $("#smst").text(response.data.mst);
                            $("#scmnd").text(response.data.cmnd);
                            $("#sNgayCap").text(response.data.ngayCap);
                            $("#sNoiCap").text(response.data.noiCap);
                            $("#sNgaySinh").text(response.data.ngaySinh);
                            $("#sDiaChi").text(response.data.address);
                            $("#sDaiDien").text(response.data.daiDien);
                            $("#sChucVu").text(response.data.chucVu);
                            $("#mauSac").val(response.data.mau);
                            $("#xeBan").val(response.data.namecar);
                            $("#xeCheck").text(response.data.namecar);
                            $("#xeCheckMau").text(response.data.mau);
                            $("#tamUng").val(response.data.tienCoc);
                            $("#giaBanXe").val(response.data.giaXe);
                            $("#giaNiemYet").val(response.data.giaNiemYet);
                            $("#hinhThucThanhToan").val(response.data.isTienMat);
                            $("#hoaHongMoiGioi").val(response.data.hoaHongMoiGioi);
                            $("#hoTen").val(response.data.hoTen);
                            $("#cmnd").val(response.data.CMND2);
                            $("#dienThoai").val(response.data.dienThoai);
                            $("input[name=idHopDong]").val(response.data.id);
                            $("#showXeGan").html("");
                            $("input[name=xeGan]").val("");
                            $("#htvSupport").text(formatNumber(response.data.htvSupport));
                            $("#phiVanChuyen").text(formatNumber(response.data.phiVanChuyen));
                            if (response.data.lyDoEdit != null)
                                $("#requestSaleEdit").text(response.data.lyDoEdit);
                            else
                                $("#requestSaleEdit").text("Không");

                            if (response.data.lyDoCancel != null)
                                $("#requestSaleCancel").text(response.data.lyDoCancel);
                            else
                                $("#requestSaleCancel").text("Không");
                            // BUTTON
                            showSoTien();
                            Toast.fire({
                                icon: 'info',
                                title: "Đã load dữ liệu"
                            })
                            loadPKFree(response.data.id);
                            loadPKPay(response.data.id);
                            loadPKCost(response.data.id);
                            loadTotal(response.data.id);
                            reloadSS(response.data.lead_check_cancel,response.data.requestCancel, response.data.admin_check, response.data.lead_check, response.data.requestEditHD);

                            let svin = "";
                            let sframe = "";
                            let scolor = "";
                            let syear = "";
                            try {
                                svin = response.car.vin;
                                sframe = response.car.frame;
                                scolor = response.car.color;
                                syear = response.car.year;
                            } catch(error) {
                                svin = "<span class='text-danger'>Chưa gán</span>";
                                sframe = "<span class='text-danger'>Chưa gán</span>";
                                scolor = "<span class='text-danger'>Chưa gán</span>";
                                syear = "<span class='text-danger'>Chưa gán</span>";
                            }
                            // show xe gán
                            txt = "<tr>"+
                            "<td>"+ response.data.namecar +"</td>"+
                            "<td>"+ svin +"</td>"+
                            "<td>"+ sframe +"</td>"+
                            "<td>Màu: "+ response.data.mau +"; Năm SX: "+ syear +"; Hộp số: "+ response.waitcar.gear +"; Chỗ ngồi: "+ response.waitcar.seat +"; Động cơ: "+ response.waitcar.machine +"; Nhiên liệu: "+ response.waitcar.fuel +"</td>"+
                            "</tr>";
                            $("#showXeGan").html(txt);
                        } else {
                            Toast.fire({
                                icon: 'info',
                                title: "Chọn đề nghị để biết thông tin"
                            })
                            $("#sHoTen").text("");
                            $("#sDienThoai").text("");
                            $("#smst").text("");
                            $("#scmnd").text("");
                            $("#sNgayCap").text("");
                            $("#sNoiCap").text("");
                            $("#sNgaySinh").text("");
                            $("#sDiaChi").text("");
                            $("#sDaiDien").text("");
                            $("#sChucVu").text("");
                            $("#mauSac").val("");
                            $("#xeBan").val("");
                            $("#tamUng").val("");
                            $("#giaBanXe").val("");
                            $("#giaNiemYet").val("");
                            $("#hinhThucThanhToan").val("");
                            $("#hoaHongMoiGioi").val("");
                            $("#hoTen").val("");
                            $("#cmnd").val("");
                            $("#dienThoai").val("");
                            $("#xeCheck").text("");
                            $("#xeCheckMau").text("");
                            $("input[name=idHopDong]").val("");
                            $("#showXeGan").html("");
                            $("input[name=xeGan]").val("");
                            loadPKFree(null);
                            loadPKPay(null);
                            loadPKCost(null);
                            $("#deNghiHopDong").hide();
                            $("#deNghiHuy").hide();
                            $("#deNghiChinhSua").hide();
                            $("#xoaDeNghi").hide();
                            $("#deNghiHopDong").hide();
                            $("#pkCostAdd").hide();
                            $("#pkFreeAdd").hide();
                            $("#pkPayAdd").hide();
                            $("#tamUng").prop('disabled', true);
                            $("#giaBanXe").prop('disabled', true);
                            $("#giaNiemYet").prop('disabled', true);
                            $("#hinhThucThanhToan").prop('disabled', true);
                            $("#hoaHongMoiGioi").prop('disabled', true);
                            $("#hoTen").prop('disabled', true);
                            $("#cmnd").prop('disabled', true);
                            $("#dienThoai").prop('disabled', true);
                            $("#duyetDeNghi").hide();
                            $("#choPhepSua").hide();
                            $("#huyDeNghi").hide();
                            $("#htvSupport").text("0");
                            $("#phiVanChuyen").text("0");
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không lấy được dữ liệu"
                        })
                        $("#htvSupport").text("0");
                        $("#phiVanChuyen").text("0");
                        $("#showXeGan").html("");
                        $("#duyetDeNghi").hide();
                        $("#choPhepHuy").hide();
                    }
                });
            });

            //load quickly PK Free
            function loadPKFree(id) {
                $.ajax({
                    url: 'management/hd/get/pkfree/' + id,
                    dataType: 'json',
                    success: function(response){
                        // Show package pay
                        txt = "";
                        sum = 0;
                        for(let i = 0; i < response.pkfree.length; i++) {
                            let mode = "";
                            switch(response.pkfree[i].mode) {
                                case "KEMTHEOXE": mode = "<strong class='text-secondary'>Kèm theo xe</strong>"; break;
                                case "GIABAN": mode = "<strong class='text-pink'>Tặng trên giá bán</strong>"; break;
                                case "CTKM": mode = "<strong class='text-info'>Chương trình khuyến mãi</strong>"; break;
                                case "TANGTHEM": mode = "<strong class='text-success'>Tặng thêm</strong>"; break;
                                default: mode = "";
                            }
                            mode = (mode != "") ? mode : (response.pkfree[i].free_kem == true ? "<strong class='text-secondary'>Kèm theo xe</strong>" : "<strong class='text-success'>Tặng thêm</strong>");
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkfree[i].name + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkfree[i].cost)) + "</td>" +
                                "<td>" + mode + "</td>" +
                                "</tr>";
                        }
                        $("#showPKFREE").html(txt);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tải"
                        })
                    }
                });
            }

            //load quickly PK Pay
            function loadPKPay(id) {
                $.ajax({
                    url: 'management/hd/get/pkpay/' + id,
                    dataType: 'json',
                    success: function(response){
                        // Show package pay
                        txt = "";
                        sum = 0;
                        for(i = 0; i < response.pkban.length; i++) {
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkban[i].name + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkban[i].cost)) + "</td>" +
                                "</tr>";
                            sum += parseInt(response.pkban[i].cost);
                        }
                        $("#showPKPAY").html(txt);
                        $("#xtongPay").text(formatNumber(sum));
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tải"
                        })
                    }
                });
            }

             //load quickly PK Cost
             function loadPKCost(id) {
                $.ajax({
                    url: 'management/hd/get/pkcost/' + id,
                    dataType: 'json',
                    success: function(response){
                        // Show package pay
                        txt = "";
                        sum = 0;
                        tru = 0;
                        for(let i = 0; i < response.pkcost.length; i++) {
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkcost[i].name + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkcost[i].cost)) + "</td>" +
                                "<td>" + (response.pkcost[i].cost_tang == true ? "<strong class='text-success'>Có</strong>" : "Không") + "</td>" +
                                "</tr>";
                            sum += parseInt(response.pkcost[i].cost);
                            if (response.pkcost[i].cost_tang == true) 
                                tru += parseInt(response.pkcost[i].cost);
                        }
                        $("#showPKCOST").html(txt);
                        let totalTang = sum - tru;
                        $("#xtongCost").text(formatNumber(totalTang) + " (Đã trừ chi phí tặng " +formatNumber(tru)+")");
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tải"
                        })
                    }
                });
            }

            //Load total
            function loadTotal(id) {
                $.ajax({
                    url: 'management/hd/get/total/' + id,
                    dataType: 'text',
                    success: function(response){
                        $("#xtotal").text(formatNumber(response));
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tổng cộng chi phí"
                        })
                    }
                });
            }

            $("#duyetDeNghi").click(function(e){
                e.preventDefault();
                if(confirm('Xác nhận phê duyệt hợp đồng này!\nLưu ý: Phê duyệt sẽ không thể thu hồi được.')){
                    $.ajax({
                        url: "{{url('management/hd/hd/denghi/pheduyetlead/ok/')}}",
                        type: "post",
                        dataType: 'json',
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $("input[name=idHopDong]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })                            
                            reloadSS(response.data.lead_check_cancel,response.data.requestCancel,response.data.admin_check,response.data.lead_check, response.data.requestEditHD);
                            loadList();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "LỖi không thể phê duyệt!"
                            })
                        }
                    });
                }
            });

            $("#choPhepHuy").click(function(e){
                e.preventDefault();
                if(confirm('Xác nhận cho phép hủy hợp đồng này!\nLưu ý: Phê duyệt sẽ không được hoàn lại, xe sẽ được trả vào kho.')){
                    $.ajax({
                        url: "{{url('management/hd/hd/denghi/pheduyetleadhuy/ok/')}}",
                        type: "post",
                        dataType: 'json',
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $("input[name=idHopDong]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reloadSS(response.data.lead_check_cancel,response.data.requestCancel,response.data.admin_check,response.data.lead_check, response.data.requestEditHD);
                            loadList();
                            $("#choPhepHuy").hide();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "LỖi không thể phê duyệt!"
                            })
                        }
                    });
                }
            });

            $("#choPhepSua").click(function(e){
                e.preventDefault();
                if(confirm("Xác nhận cho phép chỉnh sửa hợp đồng này?")) {
                    $.ajax({
                        url: "{{url('management/hd/hd/denghi/yeucausualead')}}",
                        type: "post",
                        dataType: 'json',
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $("input[name=idHopDong]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reloadSS(response.data.requestCheck,response.data.admin_check,response.data.lead_check, response.data.requestEditHD);
                            $("#showXeGan").html("");
                            $("input[name=xeGan]").val("");
                            loadList();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể duyệt yêu cầu chỉnh sửa!"
                            })
                        }
                    });
                }
            });
        });
    </script>
@endsection
