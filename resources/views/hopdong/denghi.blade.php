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
                                            <button id="taoMau" class="btn btn-warning">B1. TẠO MẪU</button>
                                        </div>
                                    </div>
                                </form>
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
                                                <th>Tác vụ</th>
                                            </tr>
                                            <tbody id="showPKFREE">
                                            </tbody>
                                        </table>
                                        <h4 class="text-right">
                                            TỔNG: <strong id="xtotal"></strong>
                                        </h4>
                                        <button id="addCodeHD" class="btn btn-success">B2. ĐỀ NGHỊ HỢP ĐỒNG</button>

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

        });
    </script>
@endsection
