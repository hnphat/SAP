@extends('admin.index')
@section('title')
   Quản lý đề nghị
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
                        <h1 class="m-0"><strong>Quản lý đề nghị hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Quản lý đề nghị hợp đồng</li>
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
                                    <input type="hidden" name="checkIn" id="checkIn">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Chọn đề nghị</label>
                                                <select name="chonDeNghi" id="chonDeNghi" class="form-control">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5>Số hợp đồng: <strong id="soHD" class="text-danger"></strong></h5>
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
                                                    <label>Xe bán</label>
                                                    <select name="xeBan" id="xeBan" class="form-control">
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
                                                    <select name="mauSac" id="mauSac" class="form-control">
                                                        <option value="Đỏ">Đỏ</option>
                                                        <option value="Xanh">Xanh</option>
                                                        <option value="Trắng">Trắng</option>
                                                        <option value="Vàng">Vàng</option>
                                                        <option value="Ghi">Ghi</option>
                                                        <option value="Nâu">Nâu</option>
                                                        <option value="Bạc">Bạc</option>
                                                        <option value="Xám">Xám</option>
                                                        <option value="Đen">Đen</option>
                                                        <option value="Vàng_cát">Vàng_cát</option>
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
                                        </div>
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
                                                    <input name="cmnd" placeholder="CMND/CCCD" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Điện thoại:</label>
                                                    <input name="dienThoai" id="dienThoai" placeholder="Điện thoại" type="text" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>CHỜ DUYỆT XE:</label>
                                                </div>
                                                <table class="table table-bordered table-striped">
                                                    <tr class="bg-success">
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
                                    </div>
                                </form>
            
                                <!-- <button type="button" id="reload"  class="btn btn-info">Tải lại</button><br/><br/> -->
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
                                        <h5>PHỤ KIỆN KHUYẾN MÃI, QUÀ TẶNG</h5>
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
                                        <h4 class="text-right">
                                            TỔNG: <strong id="xtotal"></strong>
                                        </h4>
                            </div>
                            <button id="deNghiHopDong" class="btn btn-info">ĐỀ NGHỊ T/H HỢP ĐỒNG</button>
                            <!-- <button id="deNghiHuy" class="btn btn-warning" data-toggle="modal" data-target="#requestHuy">YÊU CẦU HỦY</button>
                            <button id="deNghiChinhSua" class="btn btn-success" data-toggle="modal" data-target="#requestEdit">YÊU CẦU CHỈNH SỬA</button> -->
                            <hr>
                            <h5>IN HỢP ĐỒNG</h5>
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
                                                <label>CHỌN MẪU CẦN IN</label>
                                                <select name="mauHD" class="form-control">
                                                    <option value="1">Hợp đồng mua bán</option>
                                                    <option value="2">Phụ lục hợp đồng</option>
                                                    <option value="3">Đề nghị thực hiện hợp đồng</option>
                                                    <option value="4">Yêu cầu PDI xe và cấp hoa</option>
                                                    <option value="5">Đề nghị BHBB & 5 món</option>
                                                    <option value="6">Yêu cầu lắp đặt phụ kiện</option>
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
    <!-- Edit pk cost medal-->
    <div class="modal fade" id="editPkCostMedal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">CHỈNH SỬA CÁC LOẠI PHÍ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <form id="editPkCostForm" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="idSaleHD">
                            <input type="hidden" name="idPkCost">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="endpk" placeholder="Nhập nội dung" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input name="egiapk" value="0" placeholder="Nhập giá" type="number" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnEditPKCost" class="btn btn-primary" form="editPkCostForm">Lưu</button>
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
            // default set
            $("#deNghiHopDong").hide();
            $("#deNghiHuy").hide();
            $("#deNghiChinhSua").hide();
            $("#xoaDeNghi").hide();
            $("#pkCostAdd").hide();
            $("#pkFreeAdd").hide();
            $("#pkPayAdd").hide();
            $("#inForm").hide();
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

            function reloadStatus() {
                $.ajax({
                    url: "management/hd/hd/denghi/chondenghi/" + $("select[name=chonDeNghi]").val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code != 500) {
                            // BUTTON
                            if (response.data.requestCheck == false) {
                                $("#deNghiHopDong").show();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").hide();
                                $("#xoaDeNghi").show();
                                $("#pkCostAdd").show();
                                $("#pkFreeAdd").show();
                                $("#pkPayAdd").show();

                                $("#tamUng").prop('disabled', false);
                                $("#giaBanXe").prop('disabled', false);
                                $("#giaNiemYet").prop('disabled', false);
                                $("#hoaHongMoiGioi").prop('disabled', false);
                                $("#hoTen").prop('disabled', false);
                                $("input[name=cmnd]").prop('disabled', false);
                                $("#dienThoai").prop('disabled', false);

                                $("select[name=mauSac]").prop('disabled', false);
                                $("select[name=xeBan]").prop('disabled', false);
                                $("#inForm").hide();
                            } else if (response.data.requestCheck == true && response.data.admin_check == false && response.data.lead_check == false) {
                                $("#deNghiHopDong").hide();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").hide();
                                $("#xoaDeNghi").show();
                                $("#pkCostAdd").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();

                                $("#tamUng").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);


                                $("select[name=mauSac]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").hide();
                            } else if (response.data.requestCheck == true 
                            && response.data.admin_check == true && response.data.lead_check == false) {
                                $("#deNghiHopDong").hide();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").show();
                                $("#xoaDeNghi").hide();
                                $("#pkCostAdd").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();
                                $("#tamUng").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);

                                $("select[name=mauSac]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").show();
                            } else if (response.data.requestCheck == true 
                            && response.data.admin_check == true && response.data.lead_check == true) {
                                $("#deNghiHopDong").hide();
                                if (response.data.lead_check_cancel == false)
                                    $("#deNghiHuy").show();
                                $("#xoaDeNghi").hide();
                                $("#pkCostAdd").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();
                                $("#tamUng").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);

                                $("select[name=mauSac]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").show();
                            } else {
                                $("#deNghiHopDong").hide();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").hide();
                                $("#xoaDeNghi").hide();
                                $("#pkCostAdd").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();
                                $("#tamUng").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);
                                $("select[name=mauSac]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").hide();
                            }
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không lấy được dữ liệu"
                        })
                        $("#deNghiHopDong").hide();
                        $("#deNghiHuy").hide();
                        $("#deNghiChinhSua").hide();
                        $("#xoaDeNghi").hide();
                        $("#pkCostAdd").hide();
                        $("#pkFreeAdd").hide();
                        $("#pkPayAdd").hide();
                    }
                });
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
                            $("#scmnd").text(response.data.CMND);
                            $("#sNgayCap").text(response.data.ngayCap);
                            $("#sNoiCap").text(response.data.noiCap);
                            $("#sNgaySinh").text(response.data.ngaySinh);
                            $("#sDiaChi").text(response.data.address);
                            $("#sDaiDien").text(response.data.daiDien);
                            $("#sChucVu").text(response.data.chucVu);

                            if (response.data.code == 0)
                                $("#soHD").text("Chưa gán");
                            else
                                $("#soHD").text(response.sohd);

                            if (response.data.lead_check == true)
                                $("input[name=checkIn]").val(1);
                            else
                                $("input[name=checkIn]").val(0);

                            $('select[name=mauSac] option[selected=selected]').removeAttr('selected');
                            $('select[name=mauSac] option[value='+response.data.mau+']').attr('selected','selected');
                            $('select[name=xeBan] option[selected=selected]').removeAttr('selected');
                            $('select[name=xeBan] option[value='+response.data.idcar+']').attr('selected','selected');

                          
                            $("#tamUng").val(response.data.tienCoc);
                            $("#giaBanXe").val(response.data.giaXe);
                            $("#giaNiemYet").val(response.data.giaNiemYet);
                            $("#hoaHongMoiGioi").val(response.data.hoaHongMoiGioi);
                            $("#hoTen").val(response.data.hoTen);
                            $("input[name=cmnd]").val(response.data.CMND2);
                            $("#dienThoai").val(response.data.dienThoai);
                            $("input[name=idHopDong]").val(response.data.id);

                            // BUTTON
                            reloadStatus();
                            showSoTien();
                            Toast.fire({
                                icon: 'info',
                                title: "Đã load dữ liệu"
                            })
                            loadPKFree(response.data.id);
                            loadPKPay(response.data.id);
                            loadPKCost(response.data.id);
                            loadTotal(response.data.id);

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
                            $("#showXeGan").html("");
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

                            $("input[name=mauSac]").prop('disabled', true);
                            $("input[name=xeBan]").prop('disabled', true);
                           
                            $("#tamUng").val("");
                            $("#giaBanXe").val("");
                            $("#giaNiemYet").val("");
                            $("#hoaHongMoiGioi").val("");
                            $("#hoTen").val("");
                            $("input[name=cmnd]").val("");
                            $("#dienThoai").val("");

                            $("#soHD").text("Chưa gán");
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
                            $("#hoaHongMoiGioi").prop('disabled', true);
                            $("#hoTen").prop('disabled', true);
                            $("input[name=cmnd]").prop('disabled', true);
                            $("#dienThoai").prop('disabled', true);
                            $("#showXeGan").html("");
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không lấy được dữ liệu"
                        })
                        $("#showXeGan").html("");
                        $("#soHD").text("Chưa gán");
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
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkfree[i].name + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkfree[i].cost)) +  "</td>" +
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
                            sum += parseInt(response.pkban[i].cost);
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
                                "<td><button id='delPKCOST' data-sale='"+id+"' data-id='"+response.pkcost[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;"
                                +"<button id='editPkCost' data-sale='"+id+"' data-id='"+response.pkcost[i].id+"'  data-toggle='modal' data-target='#editPkCostMedal' class='btn btn-info btn-sm'><span class='fas fa-edit'></span></button>"+
                                "</td>" +
                                "</tr>";
                            sum += parseInt(response.pkcost[i].cost);
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

            //Add show pk cost
            $("#deNghiChinhSua").click(function(){
                $('input[name=idRequestEdit]').val($("input[name=idHopDong]").val());
            });

            $("#deNghiHuy").click(function(){
                $('input[name=idRequestHuy]').val($("input[name=idHopDong]").val());
            });


            $("#requestEditBtn").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/hd/denghi/yeucausua/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#requestEditForm").serialize(),
                    success: function(response) {
                        $("#requestEditForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#requestEdit").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm!"
                        })
                    }
                });
            });

            $("#requestHuyBtn").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/hd/denghi/yeucauhuy/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#requestHuyForm").serialize(),
                    success: function(response) {
                        $("#requestHuyForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#requestHuy").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể thêm!"
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
                                title: "Không thể xóa: Đã duyệt, Đã gửi!"
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
                                title: "Không thể xóa: Đã duyệt, Đã gửi!"
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
                                title: "Không thể xóa: Đã duyệt, Đã gửi!"
                            })
                        }
                    });
                }
            });

            $("#deNghiHopDong").click(function(){
                if(confirm('Xác nhận gửi đề nghị thực hiện hợp đồng?\nLưu ý: Không thể chỉnh sửa sau khi gửi!')) {
                    $.ajax({
                        url: "{{url('management/hd/hd/denghi/guidenghi/')}}",
                        type: "post",
                        dataType: "json",
                        // data: {
                        //     "_token": "{{csrf_token()}}",
                        //     "id": $("input[name=idHopDong]").val(),
                        // },
                        data: $("#addPkForm").serialize(),
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            reloadStatus();
                            loadList();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể gửi đề nghị!"
                            })
                        }
                    });
                }
            });

            $("#xoaDeNghi").click(function(){
                if(confirm('Xác nhận xóa đề nghị (hợp đồng) này!')) {
                    $.ajax({
                        url: "{{url('management/hd/hd/denghi/xoa/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $("input[name=idHopDong]").val(),
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadList();
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
                            $("#hoaHongMoiGioi").val("");
                            $("#hoTen").val("");
                            $("#cmnd").val("");
                            $("#dienThoai").val("");
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
                            $("#hoaHongMoiGioi").prop('disabled', true);
                            $("#hoTen").prop('disabled', true);
                            $("#cmnd").prop('disabled', true);
                            $("#dienThoai").prop('disabled', true);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa đề nghị này!"
                            })
                        }
                    });
                }
            });


            // check chosen hd
            $("#in").click(function(e){
               e.preventDefault();
                if ($("select[name=mauHD]").val() == 1 && $("input[name=checkIn]").val() == 1) {
                    switch (parseInt($("select[name=chonLoaiHD]").val())) {
                        case 1: open("{{url('management/hd/banle/canhan/tienmat/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 2: open("{{url('management/hd/banle/canhan/nganhang/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 3: open("{{url('management/hd/banle/congty/tienmat/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 4: open("{{url('management/hd/banle/congty/nganhang/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                    }
                 } else {
                    Toast.fire({
                        icon: 'warning',
                        title: "Hợp đồng hủy hoặc trưởng phòng chưa duyệt không thể in!"
                    })
                 }

                if ($("select[name=mauHD]").val() == 2 && $("input[name=checkIn]").val() == 1) {
                    switch (parseInt($("select[name=chonLoaiHD]").val())) {
                        case 1: open("{{url('management/hd/banle/phuluc/canhan/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 2: open("{{url('management/hd/banle/phuluc/canhan/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 3: open("{{url('management/hd/banle/phuluc/congty/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 4: open("{{url('management/hd/banle/phuluc/congty/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                    }
                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: "Hợp đồng hủy hoặc trưởng phòng chưa duyệt không thể in!"
                    })
                 }

                if ($("select[name=mauHD]").val() == 3) {
                    switch (parseInt($("select[name=chonLoaiHD]").val())) {
                        case 1: open("{{url('management/hd/banle/denghi/canhan/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 2: open("{{url('management/hd/banle/denghi/canhan/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 3: open("{{url('management/hd/banle/denghi/congty/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                        case 4: open("{{url('management/hd/banle/denghi/congty/down/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                    }
                }

                if ($("select[name=mauHD]").val() == 4 && $("input[name=checkIn]").val() == 1) {
                    open("{{url('management/hd/complete/pdi')}}/" + $("input[name=idHopDong]").val(),"_blank");
                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: "Hợp đồng hủy hoặc trưởng phòng chưa duyệt không thể in!"
                    })
                 }

                 if ($("select[name=mauHD]").val() == 5 && $("input[name=checkIn]").val() == 1) {
                    open("{{url('management/hd/complete/bhbb')}}/" + $("input[name=idHopDong]").val(),"_blank");
                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: "Hợp đồng hủy hoặc trưởng phòng chưa duyệt không thể in!"
                    })
                 }

                 if ($("select[name=mauHD]").val() == 6 && $("input[name=checkIn]").val() == 1) {
                    open("{{url('management/hd/complete/phukien')}}/" + $("input[name=idHopDong]").val(),"_blank");
                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: "Hợp đồng hủy hoặc trưởng phòng chưa duyệt không thể in!"
                    })
                 }
            });


            $(document).on('click','#editPkCost', function(){
                $("input[name=idSaleHD]").val($(this).data('sale'));
                $("input[name=idPkCost]").val($(this).data('id'));
                $.ajax({
                        url: "{{url('management/hd/getedit/pkcost/')}}" + '/' + $(this).data('id'),
                        type: "get",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })   
                            $("input[name=endpk]").val(response.pkcost.name);
                            $("input[name=egiapk]").val(response.pkcost.cost);                                     
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể load chỉnh sửa chi phí!"
                            })
                        }
                    });
            });

            $("#btnEditPKCost").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/postedit/pkcost/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#editPkCostForm").serialize(),
                    success: function(response) {
                        $("#editPkCostForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        loadPKCost($("input[name=idHopDong]").val());
                        loadTotal($("input[name=idHopDong]").val());
                        $("#editPkCostMedal").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể chỉnh sửa"
                        })
                    }
                });
            });
        });
    </script>
@endsection
