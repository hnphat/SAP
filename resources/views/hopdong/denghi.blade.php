@extends('admin.index')
@section('title')
   Quản lý hợp đồng
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
                        <h1 class="m-0"><strong>Đề nghị thực hiện hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Đề nghị thực hiện hợp đồng</li>
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
                                    <input type="hidden" name="idGuest" id="idGuest">
                                    <input type="hidden" name="idCarSale" id="idCarSale">
                                    <input type="hidden" name="idHopDong" id="idHopDong">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Loại khách hàng</label>
                                                <select name="loaiKhachHang" id="loaiKhachHang" class="form-control">
                                                    <option value="0">Chọn</option>
                                                    <option value="personal">Cá nhân</option>
                                                    <option value="company">Công ty</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Chọn khách hàng</label>
                                                <select name="khachHang" id="khachHang" class="form-control">
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
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Chọn xe</label>
                                                    <select name="chonXe" id="chonXe" class="form-control">
                                                        <!-- <option value="0">Chọn</option> -->
                                                        @foreach($xeList as $row)
                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Tiền cọc:</label>
                                                    <input name="tamUng" id="tamUng" value="0" placeholder="Nhập số tiền thu tạm ứng" type="number" class="form-control"/>
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
                                                    <select name="chonMauXe" id="chonMauXe" class="form-control">
                                                        <option value="0">Chọn</option>
                                                        <option value="Đỏ">Đỏ</option>
                                                        <option value="Xanh">Xanh</option>
                                                        <option value="Trắng">Trắng</option>
                                                        <option value="Vàng">Vàng</option>
                                                        <option value="Ghi">Ghi</option>
                                                        <option value="Nâu">Nâu</option>
                                                        <option value="Bạc">Bạc</option>
                                                        <option value="Xám">Xám</option>
                                                        <option value="Xám_kim_loại">Xám_kim_loại</option>
                                                        <option value="Đen">Đen</option>
                                                        <option value="Vàng_cát">Vàng_cát</option>
                                                        <option value="Xanh_lục_bảo">Xanh_lục_bảo</option>
                                                        <option value="Xanh_bóng_đêm">Xanh_bóng_đêm</option>
                                                        <option value="Trắng_mờ">Trắng_mờ</option>
                                                        <option value="Vàng_mờ">Vàng_mờ</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Giá xe:</label>
                                                    <input name="giaBanXe" id="giaBanXe" value="0" placeholder="Nhập giá bán xe" type="number" class="form-control"/>
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
                                                    <select name="hinhThucThanhToan" id="hinhThucThanhToan" class="form-control">
                                                        <option value="1">Tiền mặt</option>   
                                                        <option value="0">Ngân hàng</option>                                                      
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Ngân hàng hỗ trợ vay (nếu có)</label>
                                                    <select name="tenNganHang" id="tenNganHang" class="form-control">
                                                        <option value="Không" selected>Không</option>       
                                                        <option value="Agribank">Agribank</option>  
                                                        <option value="VietinBank">VietinBank</option>  
                                                        <option value="Vietcombank">Vietcombank</option>  
                                                        <option value="BIDV">BIDV</option>  
                                                        <option value="Techcombank">Techcombank</option>  
                                                        <option value="ACB">ACB</option>  
                                                        <option value="VPBank">VPBank</option>  
                                                        <option value="Sacombank">Sacombank</option>  
                                                        <option value="MBBank">MBBank</option>  
                                                        <option value="MSB">MSB</option>  
                                                        <option value="TPBank">TPBank</option>  
                                                        <option value="HDBank">HDBank</option>  
                                                        <option value="VIB">VIB</option>  
                                                        <option value="SCB">SCB</option>  
                                                        <option value="SeABank">SeABank</option>  
                                                        <option value="LienVietPostBank">LienVietPostBank</option>  
                                                        <option value="ABBank">ABBank</option>  
                                                        <option value="PVcomBank">PVcomBank</option>  
                                                        <option value="SHB">SHB</option>  
                                                        <option value="NamABank">NamABank</option>  
                                                        <option value="Eximbank">Eximbank</option>   
                                                        <option value="HSBC">HSBC</option>  
                                                        <option value="Citibank">Citibank</option>  
                                                        <option value="PGBank">PGBank</option>                                          
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group" style="display:none;">
                                                    <label>Nguồn khách hàng</label>
                                                    <select name="nguonKH" id="nguonKH" class="form-control">
                                                        <option value="Showroom">Showroom</option>   
                                                        <option value="Thị Trường">Thị Trường</option>
                                                        <option value="Online">Online</option>
                                                        <option value="Giới thiệu">Giới thiệu</option>
                                                        <option value="Marketing">Marketing</option>
                                                        <option value="Sự kiện">Sự kiện</option>
                                                        <option value="Công ty">Công ty</option>                                                      
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Giá niêm yết:</label>
                                                    <input name="giaNiemYet" id="giaNiemYet" value="0" placeholder="Nhập giá niêm yết" type="number" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" id="showNiemYet" class="form-control" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Hoa hồng môi giới:</label>
                                                    <input name="hoaHongMoiGioi" id="hoaHongMoiGioi" value="0" placeholder="Nhập hoa hồng môi giới" type="number" class="form-control"/>
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
                                                    <input name="hoTen" id="hoTen" placeholder="Họ tên" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>CMND/CCCD:</label>
                                                    <input name="cmnd" id="cmnd" placeholder="CMND/CCCD" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Điện thoại:</label>
                                                    <input name="dienThoai" id="dienThoai" placeholder="Điện thoại" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" id="taoMau" class="btn btn-warning">B1. TẠO MẪU</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- <button type="button" id="reload"  class="btn btn-info">Tải lại</button><br/><br/> -->
                                <h5>CÁC LOẠI PHÍ</h5>
                                        <!-- <button id="pkCostAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkCost"><span class="fas fa-plus-circle"></span></button><br/><br/> -->
                                        <table class="table table-bordered table-striped">
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Nội dung</th>
                                                <th>Giá</th>
                                                <th>Tác vụ</th>
                                            </tr>
                                            <tbody id="showPKCOST">
                                            </tbody>
                                        </table>
                                        <p>Tổng cộng: <strong id="xtongCost"></strong></p>                                        
                                        <h4 class="text-right">
                                            TỔNG: <strong id="xtotal"></strong>
                                        </h4>
                            </div>
                            <a href="{{route('hd.quanly.denghi')}}" class="btn btn-warning">B2. QUẢN LÝ ĐỀ NGHỊ</a><br/>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- All Medal all -->
    <!-- Medal Add PK Pay-->
    <div class="modal fade" id="addPkPay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">PHỤ KIỆN BÁN</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <form id="addPkFormPay" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="idHD">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="namePkPay" placeholder="Nhập nội dung" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input name="giaPkPay" value="0" placeholder="Nhập giá" type="number" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnAddPK" class="btn btn-primary" form="addPkFormPay">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Medal Add PK Free-->
    <div class="modal fade" id="addPkFree">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">PHỤ KIỆN, KHUYẾN MÃI, QUÀ TẶNG</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <form id="addPkFormFree" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="idHD2">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="namePkFree" placeholder="Nhập nội dung" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input readonly name="giaPkFree" value="0" placeholder="Nhập giá" type="number" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnAddPKFree" class="btn btn-primary" form="addPkFormFree">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Medal Add PK Pay-->
    <div class="modal fade" id="addPkCost">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">CÁC LOẠI PHÍ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <form id="addPkFormCost" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="idHD3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="namePkCost" placeholder="Nhập nội dung" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input name="giaPkCost" value="0" placeholder="Nhập giá" type="number" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnAddPKCost" class="btn btn-primary" form="addPkFormCost">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- ----------------------- -->
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
            // get type guest
            $("#loaiKhachHang").change(function (){
                if ($("#loaiKhachHang").val() == 'personal') {
                    $.ajax({
                        url: "management/hd/get/guest/personal/",
                        dataType: "text",
                        success: function(response) {
                            $('#khachHang').html(response);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không lấy được dữ liệu khách hàng cá nhân"
                            })
                        }
                    });
                }
                if ($("#loaiKhachHang").val() == 'company') {
                    $.ajax({
                        url: "management/hd/get/guest/company/",
                        dataType: "text",
                        success: function(response) {
                            $('#khachHang').html(response);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không lấy được dữ liệu khách hàng doanh nghiệp"
                            })
                        }
                    });
                }
            });

            $("#khachHang").change(function(){
                $.ajax({
                    url: "management/hd/get/guest/" + $("select[name=khachHang]").val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code != 500) {
                            $("#sHoTen").text(response.data.name);
                            $("#sDienThoai").text(response.data.phone);
                            $("#smst").text(response.data.mst);
                            $("#scmnd").text(response.data.cmnd);
                            $("#sNgayCap").text(response.data.ngayCap);
                            $("#sNoiCap").text(response.data.noiCap);
                            $("#sNgaySinh").text(response.data.ngaySinh);
                            $("#sDiaChi").text(response.data.address);
                            $("#sDaiDien").text(response.data.daiDien);
                            $("#sChucVu").text(response.data.chucVu);
                            $("input[name=idGuest]").val(response.data.id);
                        } else {
                            Toast.fire({
                                icon: 'info',
                                title: "Chọn khách hàng để biết thông tin"
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
                            $("input[name=idGuest]").val("");
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không lấy được dữ liệu khách hàng doanh nghiệp"
                        })
                    }
                });
            });

            $('#tamUng').keyup(function(){
                var cos = $('#tamUng').val();
                $('#showCost').val("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaBanXe').keyup(function(){
                var cos = $('#giaBanXe').val();
                $('#showCostCar').val("(" + DOCSO.doc(cos) + ")");
            });

            // $('#giaNiemYet').keyup(function(){
            //     var cos = $('#giaNiemYet').val();
            //     $('#showNiemYet').val("(" + DOCSO.doc(cos) + ")");
            // });

            $('#hoaHongMoiGioi').keyup(function(){
                var cos = $('#hoaHongMoiGioi').val();
                $('#showHoaHongMoiGioi').val("(" + DOCSO.doc(cos) + ")");
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
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkfree[i].name + "</td>" +
                                "<td><button id='delPKFREE' data-sale='"+id+"' data-id='"+response.pkfree[i].id_bh_pk_package+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                        }
                        $("#showPKFREE").html(txt);
                        // Toast.fire({
                        //     icon: 'info',
                        //     title: "Loaded! Free Gift!"
                        // })
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
                                "<td><button id='delPKPAY' data-sale='"+id+"' data-id='"+response.pkban[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                            sum += response.pkban[i].cost;
                        }
                        $("#showPKPAY").html(txt);
                        $("#xtongPay").text(formatNumber(sum));
                        // loadTotal($("select[name=chonHD]").val());
                        // Toast.fire({
                        //     icon: 'info',
                        //     title: "Loaded! Pay Gift!"
                        // })
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
                        for(let i = 0; i < response.pkcost.length; i++) {
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkcost[i].name + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkcost[i].cost)) + "</td>" +
                                "<td><button id='delPKCOST' data-sale='"+id+"' data-id='"+response.pkcost[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                            sum += response.pkcost[i].cost;
                        }
                        $("#showPKCOST").html(txt);
                        $("#xtongCost").text(formatNumber(sum));
                        // loadTotal($("select[name=chonHD]").val());
                        // Toast.fire({
                        //     icon: 'info',
                        //     title: "Loaded! Cost Gift!"
                        // })
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

            $("#taoMau").click(function(e){
                e.preventDefault();
                if ($("select[name=chonXe]").val() == 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: "Vui lòng chọn xe trước khi tạo đề nghị hợp đồng"
                    })
                } else if ($("select[name=chonMauXe]").val() == 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: "Vui lòng chọn màu xe trước khi tạo đề nghị hợp đồng"
                    })
                } else {
                    $.ajax({
                        url: "{{url('management/hd/hd/taomau/')}}",
                        type: "post",
                        dataType: 'json',
                        data: $("#addPkForm").serialize(),
                        success: function(response) {
                            // $("#addPkForm")[0].reset();
                            $("#addPkForm :input").prop("disabled", true);
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("input[name=idHopDong]").val(response.idInserted);
                            $("#taoMau").prop('disabled', true);
                            loadPKFree(response.idInserted);
                            loadPKPay(response.idInserted);
                            loadPKCost(response.idInserted);
                            loadTotal(response.idInserted);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không tạo được mã, vui lòng kiểm tra lại thông tin nhập liệu"
                            })
                        }
                    });
                }
            });

             
            $('#reload').click(function(e) {
                e.preventDefault();
                loadPKFree($("input[name=idHopDong]").val());
                loadPKPay($("input[name=idHopDong]").val());
                loadPKCost($("input[name=idHopDong]").val());
            })

            //Add show pk pay
            $("#pkPayAdd").click(function(){
               $('input[name=idHD]').val($("input[name=idHopDong]").val());
            });

            //Add show pk pay
            $("#pkFreeAdd").click(function(){
                $('input[name=idHD2]').val($("input[name=idHopDong]").val());
            });

            //Add show pk cost
            $("#pkCostAdd").click(function(){
                $('input[name=idHD3]').val($("input[name=idHopDong]").val());
            });

            $("#btnAddPKCost").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/add/pkcost/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addPkFormCost").serialize(),
                    success: function(response) {
                        $("#addPkFormCost")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: " Đã thêm chi phí "
                        })
                        loadPKCost($("input[name=idHopDong]").val());
                        loadTotal($("input[name=idHopDong]").val());
                        $("#addPkCost").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm!"
                        })
                    }
                });
            });

            // Add data
            $("#btnAddPK").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/add/pkpay/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addPkFormPay").serialize(),
                    success: function(response) {
                        $("#addPkFormPay")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: " Đã thêm phụ kiện bán "
                        })
                        loadPKPay($("input[name=idHopDong]").val());
                        loadTotal($("input[name=idHopDong]").val());
                        $("#addPkPay").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm!"
                        })
                    }
                });
            });

            // Add data
            $("#btnAddPKFree").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/add/pkfree/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addPkFormFree").serialize(),
                    success: function(response) {
                        $("#addPkFormFree")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: " Đã thêm phụ kiện quà tặng miễn phí "
                        })
                        loadPKFree($("input[name=idHopDong]").val());
                        loadTotal($("input[name=idHopDong]").val());
                        $("#addPkFree").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm"
                        })
                    }
                });
            });


            // Delete PK Pay
            $(document).on('click','#delPKPAY', function(){
                if(confirm('Bạn có chắc muốn xóa phụ kiện bán?')) {
                    $.ajax({
                        url: "{{url('management/hd/delete/pkpay/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "sale": $(this).data('sale')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadPKPay($("input[name=idHopDong]").val());
                            loadTotal($("input[name=idHopDong]").val());
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Lỗi!"
                            })
                        }
                    });
                }
            });

            $(document).on('click','#delPKFREE', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/hd/delete/pkfree/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "sale": $(this).data('sale')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadPKFree($("input[name=idHopDong]").val());
                            loadTotal($("input[name=idHopDong]").val());
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Lỗi!"
                            })
                        }
                    });
                }
            });

            $(document).on('click','#delPKCOST', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/hd/delete/pkcost/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "sale": $(this).data('sale')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadPKCost($("input[name=idHopDong]").val());
                            loadTotal($("input[name=idHopDong]").val());
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Lỗi!"
                            })
                        }
                    });
                }
            });
        });
    </script>
@endsection
