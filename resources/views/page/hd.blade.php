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
                        <h1 class="m-0"><strong>Quản lý hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Hợp đồng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Đề nghị hợp đồng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="so-01-tab" data-toggle="pill" href="#so-03" role="tab" aria-controls="so-01" aria-selected="true">Đề nghị chờ duyệt</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="so-02-tab" data-toggle="pill" href="#so-01" role="tab" aria-controls="so-02" aria-selected="false">Hợp đồng đã duyệt</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="so-03-tab" data-toggle="pill" href="#so-02" role="tab" aria-controls="so-03" aria-selected="false">Tạo hợp đồng bán lẻ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="so-04-tab" data-toggle="pill" href="#so-04" role="tab" aria-controls="so-04" aria-selected="false">In hợp đồng</a>
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
                                        <p>CMND: <strong id="scmnd"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày Cấp: <strong id="sNgayCap"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi cấp: <strong id="sNoiCap"></strong></p>
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
                                                        <option value="0">Chọn</option>
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
                                                        <option value="Đen">Đen</option>
                                                        <option value="Vàng cát">Vàng cát</option>
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
{{--                                        <table class="table table-bordered table-striped">--}}
{{--                                            <tr class="bg-cyan">--}}
{{--                                                <th>TT</th>--}}
{{--                                                <th>Tên xe</th>--}}
{{--                                                <th>Số vin</th>--}}
{{--                                                <th>Số máy</th>--}}
{{--                                                <th>Thông tin khác</th>--}}
{{--                                                <th>Giá xe</th>--}}
{{--                                                <th>Trạng thái xe</th>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <td>1</td>--}}
{{--                                                <td id="x_ten"></td>--}}
{{--                                                <td id="x_vin"></td>--}}
{{--                                                <td id="x_frame"></td>--}}
{{--                                                <td id="x_detail"></td>--}}
{{--                                                <td id="x_cost"></td>--}}
{{--                                                <td id="x_status"></td>--}}
{{--                                            </tr>--}}
{{--                                        </table>--}}
                                    </div>
                                    <div class="form-group">
                                        <button id="addCodeHD" class="btn btn-success">ĐỀ NGHỊ HỢP ĐỒNG</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="so-01" role="tabpanel" aria-labelledby="so-01-tab">
                                <div class="table-responsive">
                                    <table id="dataTableCode" class="display" style="width:100%">
                                        <thead>
                                        <tr class="bg-gradient-lightblue">
                                            <th>TT</th>
                                            <th>Ngày</th>
                                            <th>Mã hợp đồng</th>
                                            <th>Khách hàng</th>
                                            <th>Xe bán</th>
                                            <th>Giá</th>
                                            <th>Cọc</th>
                                            <th>Admin duyệt</th>
                                            <th>Quản lý duyệt</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="so-02" role="tabpanel" aria-labelledby="so-02-tab">
                                <div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>CHỌN HỢP ĐỒNG <button id="loadHD" class="btn btn-info btn-sm">Tải lại</button></label>
                                            <select name="chonHD" id="chonHD" class="form-control">

                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>THÔNG TIN HỢP ĐỒNG</h5>
                                    <div>
                                        <p>Họ và tên: <strong id="xHoTen"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày sinh: <strong id="xNgaySinh"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Số điện thoại: <strong id="xDienThoai"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MST: <strong id="xmst"></strong></p>
                                        <p>CMND: <strong id="xcmnd"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày Cấp: <strong id="xNgayCap"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi cấp: <strong id="xNoiCap"></strong></p>
                                        <p>Địa chỉ: <strong id="xDiaChi"></strong></p>
                                        <p>Đại diện: <strong id="xDaiDien"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chức vụ: <strong id="xChucVu"></strong></p>
                                    </div>
                                    <hr>
                                    <h5>THÔNG TIN XE BÁN</h5>
                                    <table class="table table-bordered table-striped">
                                        <tr class="bg-orange">
                                            <th>TT</th>
                                            <th>Tên xe</th>
                                            <th>Số vin</th>
                                            <th>Số máy</th>
                                            <th>Thông tin khác</th>
                                            <th>Giá xe</th>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td id="xx_ten"></td>
                                            <td id="xx_vin"></td>
                                            <td id="xx_frame"></td>
                                            <td id="xx_detail"></td>
                                            <td id="xx_cost"></td>
                                        </tr>
                                    </table>
                                    <p>Tiền cọc: <strong id="xtamUng"></strong></p>
                                    <hr>
                                    <h5>PHỤ KIỆN BÁN</h5>
                                    <button id="pkPayAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkPay"><span class="fas fa-plus-circle"></span></button><br/><br/>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Nội dung</th>
                                                <th>Giá</th>
                                                <th>Tác vụ</th>
                                            </tr>
                                            <tbody id="showPKPAY">
                                            </tbody>
                                        </thead>
                                    </table>
                                    <p>Tổng cộng: <strong id="xtongPay"></strong></p>
                                </div>
                                <div>
                                    <h5>PHỤ KIỆN, KHUYẾN MÃI, QUÀ TẶNG</h5>
                                    <button id="pkFreeAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkFree"><span class="fas fa-plus-circle"></span></button><br/><br/>
                                    <table class="table table-bordered table-striped">
                                        <tr class="bg-cyan">
                                            <th>TT</th>
                                            <th>Nội dung</th>
                                            <th>Giá</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                        <tbody id="showPKFREE">
                                        </tbody>
                                    </table>
                                    <p>Tổng cộng: <strong id="xtongFree"></strong> (Miễn phí)</p>
                                </div>
                                <div>
                                    <h5>CÁC LOẠI PHÍ</h5>
                                    <button id="pkCostAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkCost"><span class="fas fa-plus-circle"></span></button><br/><br/>
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
                                </div>
                                <h4 class="text-right">
                                    TỔNG: <strong id="xtotal"></strong>
                                </h4>
                            </div>
                            <div class="tab-pane fade" id="so-03" role="tabpanel" aria-labelledby="so-03-tab">
                                <div class="table-responsive">
                                    <table id="dataTableCodeWait" class="display" style="width:100%">
                                        <thead>
                                        <tr class="bg-gradient-lightblue">
                                            <th>TT</th>
                                            <th>Sale bán</th>
                                            <th>Khách hàng</th>
                                            <th>Xe bán</th>
                                            <th>Màu sắc</th>
                                            <th>Giá</th>
                                            <th>Tiền cọc</th>
                                            <th>Admin duyệt</th>
                                            <th>Thao tác</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="so-04" role="tabpanel" aria-labelledby="so-04-tab">
                                <form id="inForm">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>CHỌN LOẠI HỢP ĐỒNG</label>
                                                <select name="chonLoaiHD" class="form-control">
                                                    <option value="1">Cá nhân tiền mặt</option>
                                                    <option value="2">Cá nhân ngân hàng</option>
                                                    <option value="3">Công ty tiền mặt</option>
                                                    <option value="4">Công ty ngân hàng</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>CHỌN HỢP ĐỒNG <button id="loadHDIN" type="button" class="btn btn-info btn-sm">Tải lại</button></label>
                                                <select name="chonHDIN" id="chonHDIN" class="form-control">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>CHỌN MẪU CẦN IN</label>
                                                <select name="mauHD" class="form-control">
                                                    <option value="1">Hợp đồng mua bán</option>
                                                    <option value="2">Phụ lục hợp đồng</option>
                                                    <option value="3">Chi tiết phụ kiện bán</option>
                                                    <option value="4">Chi tiết phụ kiện, quà tặng</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button id="in" class="btn btn-success"><span class="fas fa-print"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                                    <input name="giaPkFree" value="0" placeholder="Nhập giá" type="number" class="form-control">
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

            // $("#chonXe").change(function(){
            //     // $.ajax({
            //     //     url: "management/hd/get/car/" + $("select[name=chonXe]").val(),
            //     //     dataType: "json",
            //     //     success: function(response) {
            //     //         if (response.code != 500) {
            //     //             $("#x_ten").text(response.name_car);
            //     //             $("#x_vin").text(response.data.vin);
            //     //             $("#x_frame").text(response.data.frame);
            //     //             let detail = "Màu xe: " + response.data.color +
            //     //                 "; động cơ: " + response.data.machine +
            //     //                 "; Số: " + response.data.gear +
            //     //                 "; Chỗ ngồi: " + response.data.seat + " chỗ" +
            //     //                 "; Nhiên liệu: " + response.data.fuel;
            //     //             $("#x_detail").text(detail);
            //     //             $("#x_cost").text(response.cost);
            //     //             $("#x_status").html(response.status);
            //     //             $("input[name=idCarSale]").val(response.data.id);
            //     //
            //     //         } else {
            //     //             Toast.fire({
            //     //                 icon: 'warning',
            //     //                 title: "Vui lòng chọn xe để lên hợp đồng"
            //     //             })
            //     //             $("#x_ten").text("");
            //     //             $("#x_vin").text("");
            //     //             $("#x_frame").text("");
            //     //             $("#x_detail").text("");
            //     //             $("#x_cost").text("");
            //     //             $("#x_status").text("");
            //     //             $("input[name=idCarSale]").val("");
            //     //         }
            //     //     },
            //     //     error: function() {
            //     //         Toast.fire({
            //     //             icon: 'warning',
            //     //             title: "Không lấy được dữ liệu xe cần bán"
            //     //         })
            //     //     }
            //     // });
            // });

            $('#tamUng').keyup(function(){
                var cos = $('#tamUng').val();
                $('#showCost').val("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaBanXe').keyup(function(){
                var cos = $('#giaBanXe').val();
                $('#showCostCar').val("(" + DOCSO.doc(cos) + ")");
            });

            var table = $('#dataTableCode').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/hd/get/list/code') }}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return row.created_at.toString().slice(0, 10) ;
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "HAGI-0" + row.id + "/HDMB-PA";
                        }
                    },
                    { "data": "surname" },
                    { "data": "name" },
                    { "data": "giaXe", render: $.fn.dataTable.render.number(',','.',0,'')},
                    { "data": "tamUng", render: $.fn.dataTable.render.number(',','.',0,'')},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.admin_check == 0)
                                return "<button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>";
                            else
                                return "<button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.lead_sale_check == 0)
                                return "<button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>";
                            else
                                return "<button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>";
                        }
                    }
                    // ,
                    // {
                    //     "data": null,
                    //     render: function (data, type, row) {
                    //         if (row.complete == 0)
                    //             return "<button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>";
                    //         else
                    //             return "<button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>";
                    //     }
                    // }
                    // },
                    // {
                    //     "data": null,
                    //     render: function(data, type, row) {
                    //         if (row.admin_check == 1 || row.lead_sale_check == 1 || row.complete == 1) {
                    //             return "<span class='badge badge-info'>Không được xóa</span>";
                    //         }
                    //         else {
                    //             return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
                    //         }
                    //     }
                    // }
                ]
            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            var tableWait = $('#dataTableCodeWait').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/hd/get/list/wait') }}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "user_name" },
                    { "data": "guestname" },
                    { "data": "carname" },
                    { "data": "color" },
                    { "data": "giaXe", render: $.fn.dataTable.render.number(',','.',0,'')},
                    { "data": "tamUng", render: $.fn.dataTable.render.number(',','.',0,'')},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.admin_check == 0)
                                return "<button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>";
                            else
                                return "<button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.admin_check == 1) {
                               // return "<span class='badge badge-info'>Không được xóa</span>";
                                return "";
                            }
                            else {
                                return "<button id='deleteWait' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
                            }
                        }
                    }
                ]
            });

            tableWait.on( 'order.dt search.dt', function () {
                tableWait.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    tableWait.cell(cell).invalidate('dom');
                } );
            } ).draw();

            $("#addCodeHD").click(function(e){
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
                        url: "{{url('management/hd/add/code/')}}",
                        type: "post",
                        dataType: 'json',
                        data: $("#addPkForm").serialize(),
                        success: function(response) {
                            $("#addPkForm")[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: " Đã tạo đề nghị hợp đồng "
                            })
                            table.ajax.reload();
                            tableWait.ajax.reload();
                            $("input[name=idCarSale]").val(0);
                            $("input[name=idGuest]").val(0);
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
                            $("#x_ten").text("");
                            $("#x_vin").text("");
                            $("#x_frame").text("");
                            $("#x_detail").text("");
                            $("#x_cost").text("");
                            $("#x_status").text("");
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
                                "<td>" + formatNumber(response.pkban[i].cost) + "</td>" +
                                "<td><button id='delPKPAY' data-sale='"+id+"' data-id='"+response.pkban[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                            sum += response.pkban[i].cost;
                        }
                        $("#showPKPAY").html(txt);
                        $("#xtongPay").text(formatNumber(sum));
                        loadTotal($("select[name=chonHD]").val());
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể làm mới phụ kiện bán"
                        })
                    }
                });
            }
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
                                "<td>" + formatNumber(response.pkfree[i].cost) + "</td>" +
                                "<td><button id='delPKFREE' data-sale='"+id+"' data-id='"+response.pkfree[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                            sum += response.pkfree[i].cost;
                        }
                        $("#showPKFREE").html(txt);
                        $("#xtongFree").text(formatNumber(sum));
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể làm mới phụ kiện bán"
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
                                "<td>" + formatNumber(response.pkcost[i].cost) + "</td>" +
                                "<td><button id='delPKCOST' data-sale='"+id+"' data-id='"+response.pkcost[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                            sum += response.pkcost[i].cost;
                        }
                        $("#showPKCOST").html(txt);
                        $("#xtongCost").text(formatNumber(sum));
                        loadTotal($("select[name=chonHD]").val());
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể làm mới các loại phí"
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
                            title: "Lỗi không thể làm mới các loại phí"
                        })
                    }
                });
            }

            //Add show pk pay
            $("#pkPayAdd").click(function(){
               $('input[name=idHD]').val($("select[name=chonHD]").val());
            });

            //Add show pk pay
            $("#pkFreeAdd").click(function(){
                $('input[name=idHD2]').val($("select[name=chonHD]").val());
            });

            //Add show pk cost
            $("#pkCostAdd").click(function(){
                $('input[name=idHD3]').val($("select[name=chonHD]").val());
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
                        loadPKPay($("select[name=chonHD]").val());
                        $("#addPkPay").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm phụ kiện bán"
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
                        loadPKFree($("select[name=chonHD]").val());
                        $("#addPkFree").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm phụ kiện miễn phí"
                        })
                    }
                });
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
                        loadPKCost($("select[name=chonHD]").val());
                        $("#addPkCost").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm các loại chi phí"
                        })
                    }
                });
            });

            // Load HD
            $("#loadHD").click(function(){
                $.ajax({
                   url: "management/hd/get/load/hd/",
                   dataType: "text",
                   success: function(response) {
                       Toast.fire({
                           icon: 'info',
                           title: "Đã tải hợp đồng"
                       })
                       $("#chonHD").html(response);
                   },
                   error: function() {
                       Toast.fire({
                           icon: 'error',
                           title: "Lỗi tải dữ liệu hợp đồng"
                       })
                   }
                });
            });

            // Load HD
            $("#loadHDIN").click(function(){
                $.ajax({
                    url: "management/hd/get/load/hd/",
                    dataType: "text",
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: "Đã tải hợp đồng"
                        })
                        $("#chonHDIN").html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi tải dữ liệu hợp đồng"
                        })
                    }
                });
            });

            // Select HD
            $("#chonHD").change(function(){
                $.ajax({
                    url: "management/hd/get/detail/hd/" + $("select[name=chonHD]").val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code != 500) {
                            $("#xHoTen").text(response.data.surname);
                            $("#xDienThoai").text(response.data.phone);
                            $("#xmst").text(response.data.mst);
                            $("#xcmnd").text(response.data.cmnd);
                            $("#xNgayCap").text(response.data.ngayCap);
                            $("#xNoiCap").text(response.data.noiCap);
                            $("#xNgaySinh").text(response.data.ngaySinh);
                            $("#xDiaChi").text(response.data.address);
                            $("#xDaiDien").text(response.data.daiDien);
                            $("#xChucVu").text(response.data.chucVu);
                            $("#xtamUng").text(formatNumber(response.data.tamUng));
                            $("#xx_ten").text(response.data.name_car);
                            $("#xx_vin").text(response.data.vin);
                            $("#xx_frame").text(response.data.frame);
                            let detail = "Màu xe: " + response.data.color +
                                "; động cơ: " + response.data.machine +
                                "; Số: " + response.data.gear +
                                "; Chỗ ngồi: " + response.data.seat + " chỗ" +
                                "; Nhiên liệu: " + response.data.fuel;
                            $("#xx_detail").text(detail);
                            $("#xx_cost").text(formatNumber(response.data.giaXe));
                            loadPKPay($("select[name=chonHD]").val());
                            loadPKFree($("select[name=chonHD]").val());
                            loadPKCost($("select[name=chonHD]").val());
                            loadTotal($("select[name=chonHD]").val());
                            if (response.data.lead_sale_check == 1) {
                                $("#pkPayAdd").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkCostAdd").hide();
                            } else {
                                $("#pkPayAdd").show();
                                $("#pkFreeAdd").show();
                                $("#pkCostAdd").show();
                            }
                        } else {
                            Toast.fire({
                                icon: 'info',
                                title: "Chọn hợp đồng để biết thông tin"
                            })
                            $("#xHoTen").text("");
                            $("#xDienThoai").text("");
                            $("#xmst").text("");
                            $("#xcmnd").text("");
                            $("#xNgayCap").text("");
                            $("#xNoiCap").text("");
                            $("#xNgaySinh").text("");
                            $("#xDiaChi").text("");
                            $("#xDaiDien").text("");
                            $("#xChucVu").text("");
                            $("#xx_ten").text("");
                            $("#xx_vin").text("");
                            $("#xx_frame").text("");
                            $("#xx_detail").text("");
                            $("#xx_cost").text("")
                            $("#xtamUng").text("");
                            $("#showPKCOST").text("");
                            $("#showPKPAY").text("");
                            $("#showPKFREE").text("");
                            $("#xtongCost").text("");
                            $("#xtongFree").text("");
                            $("#xtongPay").text("");
                            $("#xtotal").text("");
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Lỗi tải thông tin chi tiết hợp đồng từ máy chủ"
                        })
                    }
                });
            });

            // Delete PK Pay
            $(document).on('click','#delPKPAY', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
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
                                icon: 'success',
                                title: "Đã xóa"
                            })
                            loadPKPay($("select[name=chonHD]").val());
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa phụ kiện bán!"
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
                                icon: 'success',
                                title: "Đã xóa"
                            })
                            loadPKFree($("select[name=chonHD]").val());
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa phụ kiện bán!"
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
                                icon: 'success',
                                title: "Đã xóa"
                            })
                            loadPKCost($("select[name=chonHD]").val());
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa phụ kiện bán!"
                            })
                        }
                    });
                }
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/hd/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: "Đã xóa"
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            //Delete data wait
            $(document).on('click','#deleteWait', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/hd/deleteWait/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: "Đã xóa"
                            })
                            tableWait.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            // check chosen hd
            $("#in").click(function(e){
               e.preventDefault();
               if ($("select[name=chonHDIN]").val() !== null && $("select[name=chonHDIN]").val() != 0) {
                    switch (parseInt($("select[name=chonLoaiHD]").val())) {
                        case 1: open("{{url('management/hd/banle/canhan/tienmat/down/')}}/" + $("select[name=chonHDIN]").val(),"_blank"); break;
                        case 2: open("{{url('management/hd/banle/canhan/nganhang/down/')}}/" + $("select[name=chonHDIN]").val(),"_blank"); break;
                        case 3: open("{{url('management/hd/banle/congty/tienmat/down/')}}/" + $("select[name=chonHDIN]").val(),"_blank"); break;
                        case 4: open("{{url('management/hd/banle/congty/nganhang/down/')}}/" + $("select[name=chonHDIN]").val(),"_blank"); break;
                    }
               } else {
                   Toast.fire({
                       icon: 'warning',
                       title: "Cần chọn hợp đồng trước khi in!"
                   })
               }
            });
        });
    </script>
@endsection
