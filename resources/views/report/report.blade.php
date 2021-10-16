
@extends('admin.index')
@section('title')
   Báo cáo ngày
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
                        <h1 class="m-0"><strong>BÁO CÁO NGÀY</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Báo cáo</li>
                            <li class="breadcrumb-item active">Báo cáo ngày</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            @if(session('succ'))
                <div class="alert alert-success">
                    {{session('succ')}}
                </div>
            @endif
            @if(session('err'))
                <div class="alert alert-warning">
                    {{session('err')}}
                </div>
            @endif
            <div class="container">
                <h5><strong>Ngày: </strong> <?php echo Date('d-m-Y');?></h5>
                <h5><strong>Thời gian báo cáo: </strong> <span id="setTime"></span></h5>
                <button id="khoiTao" class="btn btn-success">KHỞI TẠO BÁO CÁO</button>
                <br><br>
                <form id="reportForm" action="#" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>Phòng kinh doanh</h3>
                        </div>
                        <div class="card-body">
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="doanhSoThang">Doanh số tháng: </label>
                                    <input id="doanhSoThang" name="doanhSoThang" type="number" class="form-control" required="required">
                                </div>
                                <div class="col-md-3">
                                    <label for="thiPhanThang">Thị phần tháng: </label>
                                    <input id="thiPhanThang" name="thiPhanThang" type="number" class="form-control" required="required">
                                </div>
                            </div>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="xuatHoaDon">Xuất hóa đơn: </label>
                                    <input id="xuatHoaDon" name="xuatHoaDon" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="xuatTrongTinh">Xuất trong tỉnh: </label>
                                    <input id="xuatTrongTinh" name="xuatTrongTinh" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="xuatNgoaiTinh">Xuất ngoài tỉnh: </label>
                                    <input id="xuatNgoaiTinh" name="xuatNgoaiTinh" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="hdHuy">Hợp đồng hủy: </label>
                                    <input id="hdHuy" name="hdHuy" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">KHTN CÔNG TY</h5>
                            <div class="row p-1">
                                <div class="col-md-2">
                                    <label for="ctInternet">Internet: </label>
                                    <input id="ctInternet" name="ctInternet" type="number" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="ctShowroom">Showroom: </label>
                                    <input id="ctShowroom" name="ctShowroom" type="number" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="ctHotline">Hotline: </label>
                                    <input id="ctHotline" name="ctHotline" type="number" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="ctSuKien">Sự kiện: </label>
                                    <input id="ctSuKien" name="ctSuKien" type="number" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="ctBLD">Ban lãnh đạo: </label>
                                    <input id="ctBLD" name="ctBLD" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">KHTN SALER</h5>
                            <div class="row p-1">
                                <div class="col-md-2">
                                    <label for="saleInternet">Internet: </label>
                                    <input id="saleInternet" name="saleInternet" type="number" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="saleMoiGioi">Môi giới: </label>
                                    <input id="saleMoiGioi" name="saleMoiGioi" type="number" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label for="saleThiTruong">Thị trường: </label>
                                    <input id="saleThiTruong" name="saleThiTruong" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="row p-1">
                                <div class="col-md-4">
                                    <label for="khShowRoom">Lượt khách showroom: </label>
                                    <input id="khShowRoom" name="khShowRoom" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('tpdv'))
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>Phòng dịch vụ</h3>
                        </div>
                        <div class="card-body">
                            <h5 class="text-blue">LƯỢT XE</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="baoDuong">Bảo dưỡng: </label>
                                    <input id="baoDuong" name="baoDuong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="suaChua">Sửa chửa: </label>
                                    <input id="suaChua" name="suaChua" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dong">Đồng: </label>
                                    <input id="dong" name="dong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="son">Sơn: </label>
                                    <input id="son" name="son" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">DOANH THU DỊCH VỤ</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="congBaoDuong">Công bảo dưỡng: </label>
                                    <input id="congBaoDuong" name="congBaoDuong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="congSuaChuaChung">Công sửa chửa: </label>
                                    <input id="congSuaChuaChung" name="congSuaChuaChung" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="congDong">Công đồng: </label>
                                    <input id="congDong" name="congDong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="congSon">Công Sơn: </label>
                                    <input id="congSon" name="congSon" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">DOANH THU PHỤ TÙNG - DẦU NHỚT</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="dtPhuTung">Phụ tùng sửa chữa: </label>
                                    <input id="dtPhuTung" name="dtPhuTung" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dtDauNhot">Dầu nhớt sửa chữa: </label>
                                    <input id="dtDauNhot" name="dtDauNhot" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dtPhuTungBan">Phụ tùng bán ngoài: </label>
                                    <input id="dtPhuTungBan" name="dtPhuTungBan" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dtDauNhotBan">Dầu nhớt bán ngoài: </label>
                                    <input id="dtDauNhotBan" name="dtDauNhotBan" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">MUA PHỤ TÙNG/DẦU NHỚT HTV/TST</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="phuTungMua">Tiền mua phụ tùng: </label>
                                    <input id="phuTungMua" name="phuTungMua" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dauNhotMua">Tiền mua dầu nhớt: </label>
                                    <input id="dauNhotMua" name="dauNhotMua" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('xuong'))
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>Xưởng</h3>
                        </div>
                        <div class="card-body">
                            <h5 class="text-blue">XE TỒN</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="tonBaoDuong">Bảo dưỡng: </label>
                                    <input id="tonBaoDuong" name="tonBaoDuong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tonSuaChuaChung">Sửa chửa chung: </label>
                                    <input id="tonSuaChuaChung" name="tonSuaChuaChung" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tonDong">Đồng: </label>
                                    <input id="tonDong" name="tonDong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tonSong">Sơn: </label>
                                    <input id="tonSong" name="tonSong" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">LƯỢT XE TIẾP NHẬN</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="tiepNhanBaoDuong">Bảo dưỡng: </label>
                                    <input id="tiepNhanBaoDuong" name="tiepNhanBaoDuong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tiepNhanSuaChuaChung">Sửa chửa chung: </label>
                                    <input id="tiepNhanSuaChuaChung" name="tiepNhanSuaChuaChung" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tiepNhanDong">Đồng: </label>
                                    <input id="tiepNhanDong" name="tiepNhanDong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tiepNhanSon">Sơn: </label>
                                    <input id="tiepNhanSon" name="tiepNhanSon" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">HOÀN THÀNH</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="hoanThanhBaoDuong">Bảo dưỡng: </label>
                                    <input id="hoanThanhBaoDuong" name="hoanThanhBaoDuong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="hoanThanhSuaChuaChung">Sửa chửa chung: </label>
                                    <input id="hoanThanhSuaChuaChung" name="hoanThanhSuaChuaChung" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="hoanThanhDong">Đồng: </label>
                                    <input id="hoanThanhDong" name="hoanThanhDong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="hoanThanhSon">Sơn: </label>
                                    <input id="hoanThanhSon" name="hoanThanhSon" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('cskh'))
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>CSKH</h3>
                        </div>
                        <div class="card-body">
                            <h5 class="text-blue">NHẮC BẢO DƯỠNG / ĐẶT HẸN</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="callDatHenSuccess">Cuộc gọi thành công: </label>
                                    <input id="callDatHenSuccess" name="callDatHenSuccess" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="callDatHenFail">Cuộc gọi không thành công: </label>
                                    <input id="callDatHenFail" name="callDatHenFail" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="datHen">Đặt hẹn: </label>
                                    <input id="datHen" name="datHen" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">THEO DÕI SAU DỊCH VỤ</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="dvHaiLong">Khách hàng hài lòng: </label>
                                    <input id="dvHaiLong" name="dvHaiLong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dvKhongHaiLong">Khách hàng không hài lòng: </label>
                                    <input id="dvKhongHaiLong" name="dvKhongHaiLong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dvKhongThanhCong">Cuộc gọi không thành công: </label>
                                    <input id="dvKhongThanhCong" name="dvKhongThanhCong" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">THEO DÕI SAU MUA XE</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="muaXeSuccess">Cuộc gọi thành công: </label>
                                    <input id="muaXeSuccess" name="muaXeSuccess" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="muaXeFail">Cuộc gọi không thành công: </label>
                                    <input id="muaXeFail" name="muaXeFail" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="duyetBanLe">Duyệt kiểm chứng bán lẻ: </label>
                                    <input id="duyetBanLe" name="duyetBanLe" type="number" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-blue">KHIẾU NẠI</h5>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="knThaiDo">Thái độ nhân viên:  </label>
                                    <input id="knThaiDo" name="knThaiDo" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="knChatLuong">Chất lượng sửa chữa: </label>
                                    <input id="knChatLuong" name="knChatLuong" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="knThoiGian">Thời gian sửa chữa: </label>
                                    <input id="knThoiGian" name="knThoiGian" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="knVeSinh">Vệ sinh: </label>
                                    <input id="knVeSinh" name="knVeSinh" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="knGiaCa">Giá cả:  </label>
                                    <input id="knGiaCa" name="knGiaCa" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="knKhuyenMai">Hậu mãi - khuyến mãi: </label>
                                    <input id="knKhuyenMai" name="knKhuyenMai" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="knDatHen">Đặt hẹn - tiếp nhận: </label>
                                    <input id="knDatHen" name="knDatHen" type="number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="knTraiNghiem">Trải nghiệm KH: </label>
                                    <input id="knTraiNghiem" name="knTraiNghiem" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('mkt'))
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>Marketing</h3>
                        </div>
                        <div class="card-body">
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="khBanGiao">KHTN Bàn giao: </label>
                                    <input id="khBanGiao" name="khBanGiao" type="number" class="form-control" required="required">
                                </div>
                                <div class="col-md-3">
                                    <label for="khSuKien">KHTN Sự kiện: </label>
                                    <input id="khSuKien" name="khSuKien" type="number" class="form-control" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>Hành chính - nhân sự</h3>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                    <div class="card card-fuchsia">
                        <div class="card-header">
                            <h5>CHI TIẾT HỢP ĐỒNG KÝ</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" data-toggle="modal" data-target="#addCar" class="btn btn-success">Thêm</button>
                            <br><br>
                            <div id="showCar" class="table-responsive">

                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- Medal Add Car-->
                    <div class="modal fade" id="addCar">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">CHI TIẾT HỢP ĐỒNG</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="addCarForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <input type="hidden" name="idReport">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Số lượng</label>
                                                    <input name="soLuong" type="number" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Dòng xe</label>
                                                    <select name="dongXe" class="form-control">
                                                        @foreach($typeCar as $row)
                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnAddCar" class="btn btn-primary" form="addCarForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                       \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h5>NHẬP KHO</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" data-toggle="modal" data-target="#addNhap" class="btn btn-success">Thêm</button>
                            <br><br>
                            <div id="showNhap" class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>STT</th>
                                        <th>Nhà cung cấp</th>
                                        <th>Hạn mục</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tồn</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>T&D</td>
                                        <td>VPP</td>
                                        <td>10</td>
                                        <td>0</td>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm">Xóa</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- Medal Add Nhap-->
                    <div class="modal fade" id="addNhap">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">NHẬP KHO</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="addNhapForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <input type="hidden" name="idReport2">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Nhà cung cấp</label>
                                                    <input name="nhaCungCap" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Hạn mục</label>
                                                    <select name="hanMuc" class="form-control">
                                                        <option value="">VPP</option>
                                                        <option value="">Covid</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Số lượng</label>
                                                    <input name="soLuong" type="number" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tổng tồn</label>
                                                    <input name="tongTon" type="number" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <input name="ghiChu" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnAddNhap" class="btn btn-primary" form="addNhapForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h5>XUẤT KHO</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" data-toggle="modal" data-target="#addXuat" class="btn btn-success">Thêm</button>
                            <br><br>
                            <div id="showXuat" class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên nhân viên</th>
                                        <th>Hạn mục</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tồn</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Nguyễn Hải Đăng</td>
                                        <td>VPP</td>
                                        <td>10</td>
                                        <td>0</td>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm">Xóa</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- Medal Add Xuat-->
                    <div class="modal fade" id="addXuat">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">XUẤT KHO</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="addXuatForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <input type="hidden" name="idReport3">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Tên nhân viên</label>
                                                    <input name="tenNhanVien" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Hạn mục</label>
                                                    <select name="hanMuc" class="form-control">
                                                        <option value="">VPP</option>
                                                        <option value="">Covid</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Số lượng</label>
                                                    <input name="soLuong" type="number" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tổng tồn</label>
                                                    <input name="tongTon" type="number" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <input name="ghiChu" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnAddXuat" class="btn btn-primary" form="addXuatForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    @endif
                    <div class="card card-warning">
                        <div class="card-header">
                            <h5>CÔNG VIỆC TRONG NGÀY</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" data-toggle="modal" data-target="#addWork" class="btn btn-success">Thêm</button>
                            <br><br>
                            <div id="showCV" class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Loại</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Mua cơm cháy chiên</td>
                                        <td>100%</td>
                                        <td>Công việc</td>
                                        <td>15/10/2021</td>
                                        <td>Hoàn thành</td>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm">Xóa</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- Medal Add Work-->
                    <div class="modal fade" id="addWork">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">THÊM CÔNG VIỆC</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="addWorkForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <input type="hidden" name="idReport4">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Tên công việc</label>
                                                    <input name="tenCongViec" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tiến độ</label>
                                                    <input value="0" oninput="this.nextElementSibling.value = this.value" name="tienDo" placeholder="% hoàn thành" min="0" max="100" type="range" class="form-control">
                                                    <output></output>%
                                                </div>
                                                <div class="form-group">
                                                    <label>Deadline</label>
                                                    <input name="deadLine" type="date" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Kết quả</label>
                                                    <input name="ketQua" placeholder="Nhập kết quả công việc" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <input name="ghiChu" placeholder="Nếu có" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnAddWork" class="btn btn-primary" form="addWorkForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <button id="saveReport" class="btn btn-info" form="reportForm">LƯU</button>
                    <button id="sendReport" class="btn btn-warning" form="reportForm">GỬI BÁO CÁO</button>
                </form>
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
            function load() {
                /// load report
                $.ajax({
                    url: "{{url('management/report/load')}}",
                    type: "get",
                    dataType: 'json',
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#setTime").text(response.data.timeReport);

                        $('input[name=idReport]').val(response.data.id);
                        $('input[name=idReport2]').val(response.data.id);
                        $('input[name=idReport3]').val(response.data.id);
                        $('input[name=idReport4]').val(response.data.id);

                        loadHD(response.data.id);

                        //---------PKD
                        $('input[name=doanhSoThang]').val(response.data.doanhSoThang);
                        $('input[name=thiPhanThang]').val(response.data.thiPhanThang);
                        if (response.ds == 1)
                            $('input[name=doanhSoThang]').prop('readonly', true);
                        if (response.tp == 1)
                            $('input[name=thiPhanThang]').prop('readonly', true);

                        $('input[name=xuatHoaDon]').val(response.data.xuatHoaDon);
                        $('input[name=xuatTrongTinh]').val(response.data.xuatTrongTinh);
                        $('input[name=xuatNgoaiTinh]').val(response.data.xuatNgoaiTinh);
                        $('input[name=hdHuy]').val(response.data.hdHuy);
                        $('input[name=ctInternet]').val(response.data.ctInternet);
                        $('input[name=ctShowroom]').val(response.data.ctShowroom);
                        $('input[name=ctHotline]').val(response.data.ctHotline);
                        $('input[name=ctSuKien]').val(response.data.ctSuKien);
                        $('input[name=ctBLD]').val(response.data.ctBLD);
                        $('input[name=saleInternet]').val(response.data.saleInternet);
                        $('input[name=saleMoiGioi]').val(response.data.saleMoiGioi);
                        $('input[name=saleThiTruong]').val(response.data.saleThiTruong);
                        $('input[name=khShowRoom]').val(response.data.khShowRoom);

                        //---------PDV
                        $('input[name=baoDuong]').val(response.data.baoDuong);
                        $('input[name=suaChua]').val(response.data.suaChua);
                        $('input[name=dong]').val(response.data.Dong);
                        $('input[name=son]').val(response.data.Son);
                        $('input[name=congBaoDuong]').val(response.data.congBaoDuong);
                        $('input[name=congSuaChuaChung]').val(response.data.congSuaChuaChung);
                        $('input[name=congDong]').val(response.data.congDong);
                        $('input[name=congSon]').val(response.data.congSon);
                        $('input[name=dtPhuTung]').val(response.data.dtPhuTung);
                        $('input[name=dtDauNhot]').val(response.data.dtDauNhot);
                        $('input[name=dtPhuTungBan]').val(response.data.dtPhuTungBan);
                        $('input[name=dtDauNhotBan]').val(response.data.dtDauNhotBan);
                        $('input[name=phuTungMua]').val(response.data.phuTungMua);
                        $('input[name=dauNhotMua]').val(response.data.dauNhotMua);

                        //---------XUONG
                        $('input[name=tonBaoDuong]').val(response.data.tonBaoDuong);
                        $('input[name=tonSuaChuaChung]').val(response.data.tonSuaChuaChung);
                        $('input[name=tonDong]').val(response.data.tonDong);
                        $('input[name=tonSong]').val(response.data.tonSong);
                        $('input[name=tiepNhanBaoDuong]').val(response.data.tiepNhanBaoDuong);
                        $('input[name=tiepNhanSuaChuaChung]').val(response.data.tiepNhanSuaChuaChung);
                        $('input[name=tiepNhanDong]').val(response.data.tiepNhanDong);
                        $('input[name=tiepNhanSon]').val(response.data.tiepNhanSon);
                        $('input[name=hoanThanhBaoDuong]').val(response.data.hoanThanhBaoDuong);
                        $('input[name=hoanThanhSuaChuaChung]').val(response.data.hoanThanhSuaChuaChung);
                        $('input[name=hoanThanhDong]').val(response.data.hoanThanhDong);
                        $('input[name=hoanThanhSon]').val(response.data.hoanThanhSon);

                        //---------CSKH
                        $('input[name=callDatHenSuccess]').val(response.data.callDatHenSuccess);
                        $('input[name=callDatHenFail]').val(response.data.callDatHenFail);
                        $('input[name=datHen]').val(response.data.datHen);
                        $('input[name=dvHaiLong]').val(response.data.dvHaiLong);
                        $('input[name=dvKhongHaiLong]').val(response.data.dvKhongHaiLong);
                        $('input[name=dvKhongThanhCong]').val(response.data.dvKhongThanhCong);
                        $('input[name=muaXeSuccess]').val(response.data.muaXeSuccess);
                        $('input[name=muaXeFail]').val(response.data.muaXeFail);
                        $('input[name=duyetBanLe]').val(response.data.duyetBanLe);
                        $('input[name=knThaiDo]').val(response.data.knThaiDo);
                        $('input[name=knChatLuong]').val(response.data.knChatLuong);
                        $('input[name=knThoiGian]').val(response.data.knThoiGian);
                        $('input[name=knVeSinh]').val(response.data.knVeSinh);
                        $('input[name=knGiaCa]').val(response.data.knGiaCa);
                        $('input[name=knKhuyenMai]').val(response.data.knKhuyenMai);
                        $('input[name=knDatHen]').val(response.data.knDatHen);
                        $('input[name=knTraiNghiem]').val(response.data.knTraiNghiem);
                        $('input[name=knTraiNghiem]').val(response.data.knTraiNghiem);

                        //---------MKT
                        $('input[name=khBanGiao]').val(response.data.khBanGiao);
                        $('input[name=khSuKien]').val(response.data.khSuKien);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi máy chủ!"
                        })
                    }
                });
            }

            function loadHD(id) {
                /// load hợp đồng xe
                $.ajax({
                    url: 'management/report/loadaddcar/' + id,
                    type: "get",
                    dataType: 'text',
                    success: function(response) {
                        $('#showCar').html(response);

                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi máy chủ!"
                        })
                    }
                });
            }

            load();
            // Khởi tạo report mẫu
            $('#khoiTao').click(function(){
                if(confirm("Bạn có chắc muốn khởi tạo báo cáo?\nLưu ý: Thời gian khởi tạo sẽ bằng với thời gian bắt đầu báo cáo")) {
                    $.ajax({
                        url: "{{url('management/report/khoitao')}}",
                        type: "post",
                        dataType: 'json',
                        data: {
                            "_token": "{{csrf_token()}}"
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: " " + response.message
                            })
                            setTimeout(load, 2000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Lỗi! Không thể khởi tạo báo cáo!"
                            })
                        }
                    });
                }
            });

            // Lưu report
            $('#saveReport').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/report/save')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#reportForm").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        setTimeout(load, 2000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi! Không thể lưu report!"
                        })
                    }
                });
            });

            // Lưu hợp đồng xe
            $('#btnAddCar').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/report/addcar')}}",
                    type: "post",
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "idReport": $("input[name=idReport]").val(),
                        "soLuong": $("input[name=soLuong]").val(),
                        "typeCar": $("select[name=dongXe]").val()
                    },
                    success: function(response) {
                        $("input[name=soLuong]").val(null);
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $("#addCar").modal('hide');
                        setTimeout(load, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi! Không thể lưu hợp đồng chi tiết!"
                        })
                    }
                });
            });

            // Delete hợp đồng xe chi tiết
            $(document).on('click','#delCar', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/report/deletecar/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            })
                            setTimeout(load, 1000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa!"
                            })
                        }
                    });
                }
            });
        });


    </script>
@endsection
