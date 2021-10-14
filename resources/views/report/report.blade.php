
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
                <h5><strong>Trạng thái: </strong> Chưa báo cáo</h5>
                <h5><strong>Phòng ban: </strong>Phòng kinh doanh</h5>
                <button class="btn btn-success">KHỞI TẠO BÁO CÁO</button>
                <br><br>
                <form id="reportForm" action="#" method="post" enctype="multipart/form-data">
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
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="imageBaoHiem">Theo dõi bảo hiểm: </label>
                                    <input id="imageBaoHiem" name="imageBaoHiem" type="file" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>XƯỞNG</h3>
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
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>MARKETING</h3>
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
                    <div class="card card-blue">
                        <div class="card-header">
                            <h3>Hành chính - nhân sự</h3>
                        </div>
                        <div class="card-body">
                            <div class="row p-1">
                                <div class="col-md-3">
                                    <label for="imageOne">Hình ảnh 1: </label>
                                    <input id="imageOne" name="imageOne" type="file" class="form-control" required="required">
                                </div>
                                <div class="col-md-3">
                                    <label for="imageTwo">Hình ảnh 2: </label>
                                    <input id="imageTwo" name="imageTwo" type="file" class="form-control" required="required">
                                </div>
                                <div class="col-md-3">
                                    <label for="imageThree">Hình ảnh 3: </label>
                                    <input id="imageThree" name="imageThree" type="file" class="form-control" required="required">
                                </div>
                                <div class="col-md-3">
                                    <label for="imageFour">Hình ảnh 4: </label>
                                    <input id="imageFour" name="imageFour" type="file" class="form-control" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <button class="btn btn-info">LƯU</button>
                    <button class="btn btn-warning">GỬI BÁO CÁO</button>
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

    </script>
@endsection
