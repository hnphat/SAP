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
                                            <div class="col-sm-3" style="display: none;">
                                                <div class="form-group">
                                                    <label>Giảm giá:</label>
                                                    <input name="giamGia" id="giamGia" value="0" placeholder="Nhập số tiền giảm" type="number" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6" style="display: none;">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" id="showCostGiamGia" class="form-control" disabled="disabled" />
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
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Tên ngân hàng hỗ trợ vay (nếu có):</label>
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
                                        </div>
                                        <div class="row" style="display: none;">
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
            
                                <h5>CÁC LOẠI PHÍ</h5>                                     
                                        <button id="pkCostAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkCost"><span class="fas fa-plus-circle"></span></button> &nbsp;
                                        <button id="themChiPhi" class="btn btn-primary" data-toggle="modal" data-target="#addPkCost">THÊM CHI PHÍ</button><br/><br/>
                                        <table class="table table-bordered table-striped">
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Nội dung</th>
                                                <th>Tặng</th>
                                                <th>Giá</th>
                                                <th>Tác vụ</th>
                                            </tr>
                                            <tbody id="showPKCOST">
                                            </tbody>
                                        </table>
                                        <p>Tổng cộng: <strong id="xtongCost"></strong></p>
                                        <h5>PHỤ KIỆN BÁN</h5>
                                        <button id="pkPayAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkPay"><span class="fas fa-plus-circle"></span></button>&nbsp;
                                        <button id="themPhuKienBan" class="btn btn-primary" data-toggle="modal" data-target="#addPkPay">THÊM PHỤ KIỆN BÁN</button><br/><br/>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr class="bg-cyan">
                                                    <th>TT</th>
                                                    <th>Nội dung</th>                                                    
                                                    <th>Giá</th>
                                                    <th>Giảm giá (%)</th>
                                                    <th>Thành tiền</th>
                                                    <th>Tác vụ</th>
                                                </tr>
                                                <tbody id="showPKPAY">
                                                </tbody>
                                            </thead>
                                        </table>
                                        <p>
                                            Tổng giá: <strong id="xtongPay"></strong><br/>
                                            <!-- <span class="text-pink">Áp dụng giảm giá cho tất cả phụ kiện:</span> 
                                            <input type="number" name="magiamgia" id="magiamgia" form="addPkForm" value="0" min="0" max="100" step="1"/>
                                            <strong>%</strong> -->
                                            <!-- <select name="magiamgia" id="magiamgia" form="addPkForm">
                                                <option value="0" selected>0</option>
                                                <option value="5">5%</option>
                                            </select>  -->
                                            <!-- <i class="text-danger">(Nhập 0 để thực hiện giảm giá từng loại phụ kiện)</i> -->
                                        <br/>
                                        <!-- <strong>Tổng cộng:</strong>  <strong id="xtongPayGiam"></strong> -->
                                        </p>
                                        <h5>PHỤ KIỆN KHUYẾN MÃI, QUÀ TẶNG</h5>
                                        <button id="pkFreeAdd" class="btn btn-success" data-toggle="modal" data-target="#addPkFree"><span class="fas fa-plus-circle"></span></button>&nbsp;
                                        <button id="themPhuKienKM" class="btn btn-primary" data-toggle="modal" data-target="#addPkFree">THÊM PHỤ KHUYẾN MÃI</button><br/><br/>
                                        <table class="table table-bordered table-striped">
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Nội dung</th>
                                                <th>Loại</th>                                               
                                                <th>Giá</th>
                                                <th>Tác vụ</th>
                                            </tr>
                                            <tbody id="showPKFREE">
                                            </tbody>
                                        </table>
                                        <h5>
                                            Tổng khuyến mãi: <strong id="xtongFree"></strong>
                                        </h5>
                                        <h4 class="text-right">
                                            TỔNG: <strong id="xtotal"></strong>
                                        </h4>
                            </div>
                            <button id="deNghiHopDong" class="btn btn-info">GỬI ĐỀ NGHỊ T/H HỢP ĐỒNG</button>
                            <button id="xoaDeNghi" class="btn btn-danger">XOÁ ĐỀ NGHỊ</button>
                            <!-- <button id="deNghiHuy" class="btn btn-warning" data-toggle="modal" data-target="#requestHuy">YÊU CẦU HỦY</button>
                            <button id="deNghiChinhSua" class="btn btn-success" data-toggle="modal" data-target="#requestEdit">YÊU CẦU CHỈNH SỬA</button> -->
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <button id="xuLyLoi" class="btn btn-warning">[XỬ LÝ LỖI - SYSTEM]</button>
                            @endif
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
                                                    <!-- <option value="3">Đề nghị thực hiện hợp đồng</option> -->
                                                    <option value="8">Worksheet</option>
                                                    <option value="4">Yêu cầu PDI</option>
                                                    <option value="5">Yêu cầu cấp bảo hiểm</option>
                                                    <option value="6">Yêu cầu lắp phụ kiện</option>
                                                    <option value="7">Yêu cầu tặng kèm 03 món</option>
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
        <div class="modal-dialog modal-lg">
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
                            <input type="hidden" name="idLoaiXe">
                            <input type="hidden" name="mapkcost">
                            <input type="hidden" name="isThemPhuKienBan" value="0">
                            <div class="card-body">
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                <div class="form-group">
                                    <label>Dòng xe</label>
                                    <select name="dongXe" class="form-control" readonly="disabled">
                                        @foreach($typecar as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label>Chọn mặt hàng cần bán</label>
                                    <select name="chonHangHoa" id="chonHangHoa" class="form-control">
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="namePkPay" type="text" class="form-control" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input name="giaPkPay" value="0" type="number" class="form-control" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label>Giảm giá (nếu có):</label>
                                    <input name="giamGiaPK" value="0" min="0" max="100" step="1" type="number" class="form-control">
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
        <div class="modal-dialog modal-lg">
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
                            <input type="hidden" name="idLoaiXePKFree">
                            <input type="hidden" name="mapkfree">
                            <input type="hidden" name="mapkmode">
                            <input type="hidden" name="isThemPhuKienKM" value="0">
                            <div class="card-body">
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                <div class="form-group">
                                    <label>Dòng xe</label>
                                    <select name="edongXe" class="form-control" readonly="disabled">
                                        @foreach($typecar as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label>Chọn mặt hàng cần bán</label>
                                    <select name="echonHangHoa" id="echonHangHoa" class="form-control">
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="namePkFree" placeholder="Nhập nội dung" type="text" class="form-control" readonly="readonly">
                                </div>                               
                                <div class="form-group" style="<?php if (!Auth::user()->hasRole('system') && !Auth::user()->hasRole('adminsale') && !Auth::user()->hasRole('boss')) echo "visibility: hidden;"; ?>">
                                    <label>Giá tặng</label>
                                    <input name="giaPkFree" value="0" placeholder="Nhập giá" type="number" class="form-control" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label>Loại</label>
                                    <select name="addfreetang" class="form-control">
                                        <option value="2">Chương trình khuyến mãi</option>
                                        <!-- <option value="1">Kèm theo xe</option> -->
                                        <option value="0">Tặng thêm</option>
                                        <option value="3">Tặng trên giá bán</option>
                                    </select>
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
                            <input type="hidden" name="isThemChiPhi" value="0">
                            <div class="card-body">
                                <!-- <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="namePkCost" placeholder="Nhập nội dung" type="text" class="form-control">
                                </div> -->
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <select name="namePkCost" id="namePkCost" class="form-control">
                                        <option value="Phí trước bạ">Phí trước bạ</option>
                                        <option value="Phí đăng ký xe">Phí đăng ký xe</option>
                                        <option value="Phí đăng kiểm xe">Phí đăng kiểm xe</option>
                                        <option value="Phí đường bộ">Phí đường bộ</option>
                                        <option value="Bảo hiểm TNDS">Bảo hiểm TNDS</option>
                                        <option value="Bảo hiểm vật chất">Bảo hiểm vật chất</option>
                                        <option value="Hỗ trợ đăng ký - đăng kiểm">Hỗ trợ đăng ký - đăng kiểm</option>
                                        <option value="Chi phí khác">Chi phí khác</option>
                                    </select>
                                    <!-- <input name="namePkCost" placeholder="Nhập nội dung" type="text" class="form-control"> -->
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input name="giaPkCost" value="0" placeholder="Nhập giá" type="number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Tặng</label>
                                    <select name="tang" class="form-control">
                                        <option value="0">Không</option>
                                        <option value="1">Có</option>
                                    </select>
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
                                    <input readonly="readonly" name="endpk" placeholder="Nhập nội dung" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Giá</label>
                                    <input name="egiapk" value="0" placeholder="Nhập giá" type="number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Tặng</label>
                                    <select name="etang" class="form-control">
                                        <option value="0">Không</option>
                                        <option value="1">Có</option>
                                    </select>
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

     <!-- Edit pk cost medal-->
     <div class="modal fade" id="editPkFreeMedal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">CHỈNH SỬA QUÀ TẶNG, KHUYẾN MÃI</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <form id="editPkFreeForm" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="idSaleHDFree">
                            <input type="hidden" name="idPkFree">
                            <input type="hidden" name="emapkfree">
                            <input type="hidden" name="emapkmode">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Chọn mặt hàng cần bán</label>
                                    <select name="eechonHangHoa" id="eechonHangHoa" class="form-control">
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <input name="ndfree" placeholder="Nhập nội dung" type="text" class="form-control" readonly="readonly">
                                </div>
                                <div class="form-group" style="<?php if (!Auth::user()->hasRole('system') && !Auth::user()->hasRole('adminsale')) echo "visibility: hidden;"; ?>">
                                    <label>Giá</label>
                                    <input name="giafree" value="0" placeholder="Nhập giá" type="number" class="form-control" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label>Loại</label>
                                    <select name="freetang" class="form-control">
                                        <option value="2">Chương trình khuyến mãi</option>
                                        <!-- <option value="1">Kèm theo xe</option> -->
                                        <option value="0">Tặng thêm</option>
                                        <option value="3">Tặng trên giá bán</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button id="btnEditPKFree" class="btn btn-primary" form="editPkCostForm">Lưu</button>
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
            $("#xoaDeNghi").hide();
            $("#deNghiHuy").hide();
            $("#deNghiChinhSua").hide();
            $("#pkCostAdd").hide();
            $("#themChiPhi").hide();
            $("#themPhuKienBan").hide();
            $("#themPhuKienKM").hide();
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
                // var cos = $('#giaNiemYet').val();
                // $('#showNiemYet').val("(" + DOCSO.doc(cos) + ")");
                var cos = $('#hoaHongMoiGioi').val();
                $('#showHoaHongMoiGioi').val("(" + DOCSO.doc(cos) + ")");
                var cos = $('#giamGia').val();
                $('#showCostGiamGia').val("(" + DOCSO.doc(cos) + ")");
            }

            $('#giamGia').keyup(function(){
                var cos = $('#giamGia').val();
                $('#showCostGiamGia').val("(" + DOCSO.doc(cos) + ")");
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

            function reloadStatus() {
                $.ajax({
                    url: "management/hd/hd/denghi/chondenghi/" + $("select[name=chonDeNghi]").val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code != 500) {
                            // BUTTON
                            if (response.data.requestCheck == false) {
                                $("#deNghiHopDong").show();
                                $("#xoaDeNghi").show();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").hide();
                                $("#pkCostAdd").show();
                                $("#themChiPhi").hide();
                                $("#themPhuKienBan").hide();
                                $("#themPhuKienKM").hide();
                                $("#pkFreeAdd").show();
                                $("#pkPayAdd").show();

                                $("#tamUng").prop('disabled', false);
                                $("#giamGia").prop('disabled', false);
                                $("#giaBanXe").prop('disabled', false);
                                $("#giaNiemYet").prop('disabled', false);
                                $("#tenNganHang").prop('disabled', false);
                                $("#hinhThucThanhToan").prop('disabled', false);
                                $("#nguonKH").prop('disabled',false);
                                $("#tenNganHang").prop('disabled',false);
                                $("#hoaHongMoiGioi").prop('disabled', false);
                                $("#hoTen").prop('disabled', false);
                                $("input[name=cmnd]").prop('disabled', false);
                                $("#dienThoai").prop('disabled', false);

                                $("select[name=mauSac]").prop('disabled', false);
                                $("input[name=magiamgia]").prop('disabled', false);
                                $("select[name=xeBan]").prop('disabled', false);
                                $("#inForm").hide();
                            } else if (response.data.requestCheck == true 
                            && response.data.admin_check == false 
                            && response.data.lead_check == false) {
                                $("#deNghiHopDong").hide();
                                $("#xoaDeNghi").hide();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").hide();
                                $("#pkCostAdd").hide();
                                $("#themChiPhi").hide();
                                $("#themPhuKienBan").hide();
                                $("#themPhuKienKM").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();

                                $("#tamUng").prop('disabled', true);
                                $("#giamGia").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#tenNganHang").prop('disabled', true);
                                $("#hinhThucThanhToan").prop('disabled', true);
                                $("#nguonKH").prop('disabled',true);
                                $("#tenNganHang").prop('disabled',true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);


                                $("select[name=mauSac]").prop('disabled', true);
                                $("input[name=magiamgia]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").hide();
                            } else if (response.data.requestCheck == true 
                            && response.data.admin_check == true 
                            && response.data.lead_check == false) {
                                $("#deNghiHopDong").hide();
                                $("#xoaDeNghi").hide();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").show();
                                $("#pkCostAdd").hide();
                                $("#themChiPhi").hide();
                                $("#themPhuKienBan").hide();
                                $("#themPhuKienKM").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();
                                $("#tamUng").prop('disabled', true);
                                $("#giamGia").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#tenNganHang").prop('disabled', true);
                                $("#hinhThucThanhToan").prop('disabled', true);
                                $("#nguonKH").prop('disabled',true);
                                $("#tenNganHang").prop('disabled',true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);

                                $("select[name=mauSac]").prop('disabled', true);
                                $("input[name=magiamgia]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").show();
                            } else if (response.data.requestCheck == true 
                            && response.data.admin_check == true 
                            && response.data.lead_check == true) {
                                $("#deNghiHopDong").hide();
                                $("#xoaDeNghi").hide();
                                if (response.data.lead_check_cancel == false)
                                    $("#deNghiHuy").show();
                                $("#pkCostAdd").hide();
                                 if (response.data.lead_check_cancel == false) {
                                    $("#themChiPhi").show();
                                    $("#themPhuKienBan").show();
                                    $("#themPhuKienKM").show();
                                 }
                                 else {
                                    $("#themChiPhi").hide();
                                    $("#themPhuKienBan").hide();
                                    $("#themPhuKienKM").hide();
                                 }
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();
                                $("#tamUng").prop('disabled', true);
                                $("#giamGia").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#tenNganHang").prop('disabled', true);
                                $("#hinhThucThanhToan").prop('disabled', true);
                                $("#nguonKH").prop('disabled',true);
                                $("#tenNganHang").prop('disabled',true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);

                                $("select[name=mauSac]").prop('disabled', true);
                                $("input[name=magiamgia]").prop('disabled', true);
                                $("select[name=xeBan]").prop('disabled', true);
                                $("#inForm").show();
                            } else {
                                $("#deNghiHopDong").hide();
                                $("#xoaDeNghi").hide();
                                $("#deNghiHuy").hide();
                                $("#deNghiChinhSua").hide();
                                $("#pkCostAdd").hide();
                                $("#themChiPhi").hide();
                                $("#themPhuKienBan").hide();
                                $("#themPhuKienKM").hide();
                                $("#pkFreeAdd").hide();
                                $("#pkPayAdd").hide();
                                $("#tamUng").prop('disabled', true);
                                $("#giamGia").prop('disabled', true);
                                $("#giaBanXe").prop('disabled', true);
                                $("#giaNiemYet").prop('disabled', true);
                                $("#tenNganHang").prop('disabled', true);
                                $("#hinhThucThanhToan").prop('disabled', true);
                                $("#nguonKH").prop('disabled',true);
                                $("#tenNganHang").prop('disabled',true);
                                $("#hoaHongMoiGioi").prop('disabled', true);
                                $("#hoTen").prop('disabled', true);
                                $("input[name=cmnd]").prop('disabled', true);
                                $("#dienThoai").prop('disabled', true);
                                $("select[name=mauSac]").prop('disabled', true);
                                $("input[name=magiamgia]").prop('disabled', true);
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
                        $("#xoaDeNghi").hide();
                        $("#deNghiHuy").hide();
                        $("#deNghiChinhSua").hide();
                        $("#pkCostAdd").hide();
                        $("#themChiPhi").hide();
                        $("#themPhuKienKM").hide();
                        $("#themPhuKienBan").hide();
                        $("#pkFreeAdd").hide();
                        $("#pkPayAdd").hide();
                    }
                });
            }
            
            function autoloadCostFromPK(mahang) {
                $.ajax({
                    url: 'management/hd/hd/denghi/chonhanghoa',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "mahang": mahang
                    },
                    success: function(response){
                        $("input[name=namePkPay]").val(response.data.noiDung);
                        $("input[name=giaPkPay]").val(response.data.donGia);
                        $("input[name=mapkcost]").val(response.data.id);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi!"
                        })
                    }
                });
            }
            
            function autoloadCostFromPKFree(mahang) {
                $.ajax({
                    url: 'management/hd/hd/denghi/chonhanghoa',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "mahang": mahang
                    },
                    success: function(response){
                        $("input[name=namePkFree]").val(response.data.noiDung);
                        $("input[name=giaPkFree]").val(response.data.giaTang == 0 ? response.data.giaVon : response.data.giaTang);
                        $("input[name=mapkfree]").val(response.data.id);
                        let chosen = $("select[name=addfreetang]").val();
                        switch(parseInt(chosen)) {
                            case 0: $("input[name=mapkmode]").val("TANGTHEM"); break;
                            case 1: $("input[name=mapkmode]").val("KEMTHEOXE"); break;
                            case 2: $("input[name=mapkmode]").val("CTKM"); break;
                            case 3: $("input[name=mapkmode]").val("GIABAN"); break;
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi!"
                        })
                    }
                });
            }

            function autoloadCostFromPKFreeEdit(mahang) {
                $.ajax({
                    url: 'management/hd/hd/denghi/chonhanghoa',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "mahang": mahang
                    },
                    success: function(response){
                        $("input[name=ndfree]").val(response.data.noiDung);
                        $("input[name=giafree]").val(response.data.giaTang == 0 ? response.data.giaVon : response.data.giaTang);
                        $("input[name=emapkfree]").val(response.data.id);
                        let chosen = $("select[name=freetang]").val();
                        switch(parseInt(chosen)) {
                            case 0: $("input[name=emapkmode]").val("TANGTHEM"); break;
                            case 1: $("input[name=emapkmode]").val("KEMTHEOXE"); break;
                            case 2: $("input[name=emapkmode]").val("CTKM"); break;
                            case 3: $("input[name=emapkmode]").val("GIABAN"); break;
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi!"
                        })
                    }
                });
            }

            $("select[name=addfreetang]").change(function(){
                let chosen = $("select[name=addfreetang]").val();
                switch(parseInt(chosen)) {
                    case 0: $("input[name=mapkmode]").val("TANGTHEM"); break;
                    case 1: $("input[name=mapkmode]").val("KEMTHEOXE"); break;
                    case 2: $("input[name=mapkmode]").val("CTKM"); break;
                    case 3: $("input[name=mapkmode]").val("GIABAN"); break;
                }
            });

            $("select[name=freetang]").change(function(){
                let chosen = $("select[name=freetang]").val();
                switch(parseInt(chosen)) {
                    case 0: $("input[name=emapkmode]").val("TANGTHEM"); break;
                    case 1: $("input[name=emapkmode]").val("KEMTHEOXE"); break;
                    case 2: $("input[name=emapkmode]").val("CTKM"); break;
                    case 3: $("input[name=emapkmode]").val("GIABAN"); break;
                }
            });

            function autoloadPkPay() {
                idtypecar = $("input[name=idLoaiXe]").val();
                $.ajax({
                    url: 'management/hd/hd/denghi/loadpkpayfromtypecar',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "idtypecar": idtypecar
                    },
                    success: function(response){
                        txt = "";
                        result =  response.data;

                        // Sắp xếp theo noiDung alphabetic
                        result.sort((a, b) => a.noiDung.localeCompare(b.noiDung));
                        
                        for(let i = 0; i < result.length; i++) {
                            txt += `<option value="${result[i].ma}">${result[i].noiDung}</option>`;
                        }
                        $("#chonHangHoa").html(txt);
                        setTimeout(() => {
                            autoloadCostFromPK($("select[name=chonHangHoa]").val());
                        }, 500);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tải"
                        })
                    }
                });
            }
            function autoloadPkFree() {
                idtypecar = $("input[name=idLoaiXePKFree]").val();
                $.ajax({
                    url: 'management/hd/hd/denghi/loadpkpayfromtypecar',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "idtypecar": idtypecar
                    },
                    success: function(response){
                        txt = "";
                        result =  response.data;
                        result.sort((a, b) => a.noiDung.localeCompare(b.noiDung));
                        txt += `<option value="0">Chọn</option>`;
                        for(let i = 0; i < result.length; i++) {
                            txt += `<option value="${result[i].ma}">${result[i].noiDung}</option>`;
                        }
                        $("#echonHangHoa").html(txt);
                        $("#eechonHangHoa").html(txt);
                        setTimeout(() => {
                            autoloadCostFromPKFree($("select[name=echonHangHoa]").val());
                        }, 500);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tải"
                        })
                    }
                });
            }
            $("#chonHangHoa").change(function(){
                mahang = $("select[name=chonHangHoa]").val();
                autoloadCostFromPK(mahang);
            });
            $("#echonHangHoa").change(function(){
                mahang = $("select[name=echonHangHoa]").val();
                autoloadCostFromPKFree(mahang);
            });
            $("#eechonHangHoa").change(function(){
                mahang = $("select[name=eechonHangHoa]").val();
                autoloadCostFromPKFreeEdit(mahang);
            });

            $("#chonDeNghi").change(function(){
                $.ajax({
                    url: "management/hd/hd/denghi/chondenghi/" + $("select[name=chonDeNghi]").val(),
                    dataType: "json",
                    success: function(response) {
                        if (response.code != 500) {           
                            $("select[name=dongXe]").val(response.loaiXe);    
                            $("select[name=edongXe]").val(response.loaiXe); 
                            $("input[name=idLoaiXe]").val(response.loaiXe);     
                            $("input[name=idLoaiXePKFree]").val(response.loaiXe);
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
                            $('input[name=magiamgia] option[selected=selected]').removeAttr('selected');
                            $('input[name=magiamgia] option[value='+response.data.magiamgia+']').attr('selected','selected');
                            $('select[name=xeBan] option[selected=selected]').removeAttr('selected');
                            $('select[name=xeBan] option[value='+response.data.idcar+']').attr('selected','selected');
                            $('select[name=hinhThucThanhToan] option[selected=selected]').removeAttr('selected');
                            $('select[name=hinhThucThanhToan] option[value='+response.data.isTienMat+']').attr('selected','selected');

                            $("#nguonKH").val(response.data.nguonKH);
                            $("#tenNganHang").val(response.data.tenNganHang);
                            $("#tamUng").val(response.data.tienCoc);
                            $("#giamGia").val(response.data.magiamgia);
                            $("#giaBanXe").val(response.data.giaXe);
                            $("#giaNiemYet").val(response.data.giaNiemYet);
                            $("#tenNganHang").val(response.data.tenNganHang);
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
                            $("#showPKFREE").html("");
                            $("#showPKCOST").html("");
                            $("#showPKPAY").html("");
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
                            $("input[name=magiamgia]").prop('disabled', true);
                            $("input[name=xeBan]").prop('disabled', true);
                            $("input[name=hinhThucThanhToan]").prop('disabled', true);
                            $("input[name=nguonKH]").prop('disabled', true);
                            $("input[name=tenNganHang]").prop('disabled', true);

                            
                            $("#tamUng").val("");
                            $("#giamGia").val("");
                            $("#giaBanXe").val("");
                            $("#giaNiemYet").val("");
                            $("#tenNganHang").val("");
                            $("#hoaHongMoiGioi").val("");
                            $("#hoTen").val("");
                            $("input[name=cmnd]").val("");
                            $("#dienThoai").val("");

                            $("#soHD").text("Chưa gán");
                            loadPKFree(null);
                            loadPKPay(null);
                            loadPKCost(null);
                            $("#deNghiHopDong").hide();
                            $("#xoaDeNghi").hide();
                            $("#deNghiHuy").hide();
                            $("#deNghiChinhSua").hide();
                            $("#pkCostAdd").hide();
                            $("#themChiPhi").hide();
                            $("#themPhuKienKM").hide();
                            $("#themPhuKienBan").hide();
                            $("#pkFreeAdd").hide();
                            $("#pkPayAdd").hide();
                            $("#tamUng").prop('disabled', true);
                            $("#giamGia").prop('disabled', true);
                            $("#giaBanXe").prop('disabled', true);
                            $("#giaNiemYet").prop('disabled', true);
                            $("#tenNganHang").prop('disabled', true);
                            $("#hinhThucThanhToan").prop('disabled', true);
                            $("#nguonKH").prop('disabled', true);
                            $("#tenNganHang").prop('disabled', true);
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
                        $("#showPKFREE").html("");
                        $("#showPKCOST").html("");
                        $("#showPKPAY").html("");
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
                        let sum = 0;
                        $("#xtongFree").text("0");
                        for(let i = 0; i < response.pkfree.length; i++) {
                            let mode = "";                            
                            statusLanSau = "";
                            isGiaTang = "";
                            if (response.pkfree[i].isLanDau == false 
                            && response.pkfree[i].isDuyetLanSau == false) {
                                statusLanSau = " <span class='text-danger'><b>(Chưa duyệt)</b></span>"
                            } else if (response.pkfree[i].isLanDau == false     
                            && response.pkfree[i].isDuyetLanSau == true) {
                                statusLanSau = " <span class='text-success'><b>(Đã duyệt)</b></span>"
                            } else {
                                statusLanSau = "";
                            } 
                            switch(response.pkfree[i].mode) {
                                case "KEMTHEOXE": mode = "<strong class='text-secondary'>Kèm theo xe</strong>"; break;
                                case "GIABAN": mode = "<strong class='text-pink'>Tặng trên giá bán</strong>"; break;
                                case "CTKM": mode = "<strong class='text-info'>Chương trình khuyến mãi</strong>"; break;
                                case "TANGTHEM": mode = "<strong class='text-success'>Tặng thêm</strong>"; break;
                                default: mode = "";
                            }
                            if (response.pkfree[i].giaTang == 0) {
                                isGiaTang = "<br/><span class='text-warning'>(Chưa cập nhật giá tặng lấy giá theo mặc định)</span>";
                            } else {
                                isGiaTang = "";
                            }
                            mode = (mode != "") ? mode : (response.pkfree[i].free_kem == true ? "<strong class='text-secondary'>Kèm theo xe</strong>" : "<strong class='text-success'>Tặng thêm</strong>");
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkfree[i].name + " " + statusLanSau +  " " + isGiaTang + "</td>" +
                                "<td>" + mode + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkfree[i].giaTang == 0 ? 0 : response.pkfree[i].giaTang)) + "</td>" +
                                "<td><button id='delPKFREE' data-sale='"+id+"' data-id='"+response.pkfree[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>"+
                                "</tr>";
                            sum += parseInt(response.pkfree[i].giaTang);
                        }
                        $("#showPKFREE").html(txt);
                        $("#xtongFree").text(formatNumber(sum));
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
                        statusLanSau = "";
                        for(i = 0; i < response.pkban.length; i++) {
                            if (response.pkban[i].isLanDau == false 
                            && response.pkban[i].isDuyetLanSau == false) {
                                statusLanSau = " <span class='text-danger'><b>(Chưa duyệt)</b></span>"
                            } else if (response.pkban[i].isLanDau == false 
                            && response.pkban[i].isDuyetLanSau == true) {
                                statusLanSau = " <span class='text-success'><b>(Đã duyệt)</b></span>"
                            } else {
                                statusLanSau = "";
                            }
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkban[i].name + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkban[i].cost)) + " " + statusLanSau + "</td>" +
                                "<td>" + response.pkban[i].giamGia + "%</td>" +
                                "<td>" + formatNumber(parseInt(response.pkban[i].cost - (response.pkban[i].cost*response.pkban[i].giamGia/100))) + "</td>" +
                                "<td><button id='delPKPAY' data-sale='"+id+"' data-id='"+response.pkban[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button></td>" +
                                "</tr>";
                            if (response.pkban[i].isLanDau == true || (response.pkban[i].isDuyetLanSau == true && response.pkban[i].isLanDau == false)) {
                                sum += parseInt(response.pkban[i].cost - (response.pkban[i].cost*response.pkban[i].giamGia/100));
                            }
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
                        statusLanSau = "";
                        for(let i = 0; i < response.pkcost.length; i++) {
                            if (response.pkcost[i].isLanDau == false 
                            && response.pkcost[i].isDuyetLanSau == false) {
                                statusLanSau = " <span class='text-danger'><b>(Chưa duyệt)</b></span>"
                            } else if (response.pkcost[i].isLanDau == false 
                            && response.pkcost[i].isDuyetLanSau == true) {
                                statusLanSau = " <span class='text-success'><b>(Đã duyệt)</b></span>"
                            } else {
                                statusLanSau = "";
                            }
                            txt += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.pkcost[i].name + "</td>" +
                                "<td>" + (response.pkcost[i].cost_tang == true ? "<strong class='text-success'>Có</strong>" : "Không") + "</td>" +
                                "<td>" + formatNumber(parseInt(response.pkcost[i].cost)) + " " + statusLanSau + "</td>" +
                                "<td><button id='delPKCOST' data-sale='"+id+"' data-id='"+response.pkcost[i].id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;"
                                +"<button id='editPkCost' data-sale='"+id+"' data-id='"+response.pkcost[i].id+"'  data-toggle='modal' data-target='#editPkCostMedal' class='btn btn-info btn-sm'><span class='fas fa-edit'></span></button>"+
                                "</td>" +
                                "</tr>";
                            if (response.pkcost[i].isLanDau == true || (response.pkcost[i].isDuyetLanSau == true && response.pkcost[i].isLanDau == false)) {
                               sum += parseInt(response.pkcost[i].cost);
                               if (response.pkcost[i].cost_tang == true) 
                                    tru += parseInt(response.pkcost[i].cost);
                            }                           
                        }
                        let totalTang = sum - tru;
                        $("#showPKCOST").html(txt);
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

            $('#reload').click(function(e) {
                e.preventDefault();
                loadPKFree($("input[name=idHopDong]").val());
                loadPKPay($("input[name=idHopDong]").val());
                loadPKCost($("input[name=idHopDong]").val());
            })

            //Add show pk pay
            $("#pkPayAdd").click(function(){
               $('input[name=idHD]').val($("input[name=idHopDong]").val());
               $('input[name=isThemPhuKienBan]').val(0);
               autoloadPkPay();
            });

            $("#themPhuKienBan").click(function(){
               $('input[name=idHD]').val($("input[name=idHopDong]").val());
               $('input[name=isThemPhuKienBan]').val(1);
               autoloadPkPay();
            });

            //Add show pk pay
            $("#pkFreeAdd").click(function(){
                $('input[name=idHD2]').val($("input[name=idHopDong]").val());
                $('input[name=isThemPhuKienKM]').val(0);
                autoloadPkFree();
            });

             $("#themPhuKienKM").click(function(){
                $('input[name=idHD2]').val($("input[name=idHopDong]").val());
                $('input[name=isThemPhuKienKM]').val(1);
                autoloadPkFree();
            });

            //Add show pk cost
            $("#pkCostAdd").click(function(){
                $('input[name=idHD3]').val($("input[name=idHopDong]").val());
                $('input[name=isThemChiPhi]').val(0);
            });

            $("#themChiPhi").click(function(){
                $('input[name=idHD3]').val($("input[name=idHopDong]").val());
                $('input[name=isThemChiPhi]').val(1);
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
                        if (response.code == 200) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadPKCost($("input[name=idHopDong]").val());
                            loadTotal($("input[name=idHopDong]").val());
                            $("#addPkCost").modal('hide');
                        }                        
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
                        if (response.code == 200) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadPKPay($("input[name=idHopDong]").val());
                            loadTotal($("input[name=idHopDong]").val());
                            $("#addPkPay").modal('hide');
                        }                        
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
                        if (response.code == 200) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            loadPKFree($("input[name=idHopDong]").val());
                            loadTotal($("input[name=idHopDong]").val());
                            $("#addPkFree").modal('hide');
                        }
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
                            if (response.code == 200) {
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                })
                                loadPKPay($("input[name=idHopDong]").val());
                                loadTotal($("input[name=idHopDong]").val());
                            }                           
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
                            if (response.code == 200) {
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                })
                                loadPKFree($("input[name=idHopDong]").val());
                                loadTotal($("input[name=idHopDong]").val());
                            }                            
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
                            if (response.code == 200) {
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                })
                                loadPKCost($("input[name=idHopDong]").val());
                                loadTotal($("input[name=idHopDong]").val());
                            }                            
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

            $("#xuLyLoi").click(function(){
                if(confirm('Xử lý chỉ thực hiện khi xảy ra lỗi bị treo giai đoạn phê duyệt của admin sale!')) {
                    $.ajax({
                        url: "{{url('management/hd/hd/denghi/xulyloi/')}}",
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
                            $("#magiamgia").val("");
                            $("#xeBan").val("");
                            $("#tamUng").val("");
                            $("#giamGia").val("");
                            $("#giaBanXe").val("");
                            $("#giaNiemYet").val("");
                            $("#tenNganHang").val("");
                            $("#hoaHongMoiGioi").val("");
                            $("#hoTen").val("");
                            $("#cmnd").val("");
                            $("#dienThoai").val("");
                            $("#deNghiHopDong").hide();
                            $("#xoaDeNghi").hide();
                            $("#deNghiHuy").hide();
                            $("#deNghiChinhSua").hide();
                            $("#xoaDeNghi").hide();
                            $("#deNghiHopDong").hide();
                            $("#xoaDeNghi").hide();
                            $("#pkCostAdd").hide();
                            $("#pkFreeAdd").hide();
                            $("#themChiPhi").hide();
                            $("#themPhuKienBan").hide();
                            $("#pkPayAdd").hide();
                            $("#tamUng").prop('disabled', true);
                            $("#giamGia").prop('disabled', true);
                            $("#giaBanXe").prop('disabled', true);
                            $("#giaNiemYet").prop('disabled', true);
                            $("#tenNganHang").prop('disabled', true);
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
                // Step check
                $.ajax({
                    url: "{{url('management/hd/hd/denghi/checkbeforeprint/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $("input[name=idHopDong]").val(),
                    },
                    success: function(response) {
                        if (response.code == 200) {
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

                            if ($("select[name=mauHD]").val() == 7 && $("input[name=checkIn]").val() == 1) {
                                open("{{url('management/hd/complete/phukienkemtheoxe')}}/" + $("input[name=idHopDong]").val(),"_blank");
                            } else {
                                Toast.fire({
                                    icon: 'warning',
                                    title: "Hợp đồng hủy hoặc trưởng phòng chưa duyệt không thể in!"
                                })
                            }

                            if ($("select[name=mauHD]").val() == 8) {
                                switch (parseInt($("select[name=chonLoaiHD]").val())) {
                                    case 1: open("{{url('management/hd/banle/denghi/canhan/down/worksheet/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                                    case 2: open("{{url('management/hd/banle/denghi/canhan/down/worksheet/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                                    case 3: open("{{url('management/hd/banle/denghi/canhan/down/worksheet/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                                    case 4: open("{{url('management/hd/banle/denghi/canhan/down/worksheet/')}}/" + $("input[name=idHopDong]").val(),"_blank"); break;
                                }
                            }
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: "Hợp đồng này tồn tại các hạng mục chưa được phê duyệt! Không thể in"
                            })
                        }                        
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi"
                        })
                    }
                });                
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
                            $("select[name=etang]").val(response.pkcost.cost_tang);                                        
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể load chỉnh sửa chi phí!"
                            })
                        }
                    });
            });

            $(document).on('click','#editPkFree', function(){
                autoloadPkFree();
                $("input[name=idSaleHDFree]").val($(this).data('sale'));
                $("input[name=idPkFree]").val($(this).data('id'));
                $("input[name=emapkfree]").val($(this).data('mapkfree'));
                $("input[name=emapkmode]").val($(this).data('mapkmode'));
                $.ajax({
                        url: "{{url('management/hd/getedit/pkfree/')}}" + '/' + $(this).data('id'),
                        type: "get",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })   
                            $("input[name=ndfree]").val(response.pkfree.name);
                            $("input[name=giafree]").val(response.pkfree.cost); 
                            let chose = response.pkfree.mode ? response.pkfree.mode : "";
                            switch(chose) {
                                case "GIABAN": $("select[name=freetang]").val(3);  break;
                                case "KEMTHEOXE": $("select[name=freetang]").val(1);  break;
                                case "TANGTHEM": $("select[name=freetang]").val(0);  break;
                                case "CTKM": $("select[name=freetang]").val(2);  break;
                                default: chose = "";
                            }

                            if (chose == "") {
                                (response.pkfree.free_kem == true) ? $("select[name=freetang]").val(1) : $("select[name=freetang]").val(0);
                            }
                            // $("select[name=eechonHangHoa]").val(response.pkfree.mapk);                                        
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể load chỉnh sửa quà tặng!"
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
            $("#btnEditPKFree").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/hd/postedit/pkfree/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#editPkFreeForm").serialize(),
                    success: function(response) {
                        $("#editPkFreeForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        loadPKFree($("input[name=idSaleHDFree]").val());
                        loadTotal($("input[name=idSaleHDFree]").val());
                        $("#editPkFreeMedal").modal('hide');
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
