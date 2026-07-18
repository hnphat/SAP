@extends('admin.index')
@section('title')
    ĐƠN HÀNG BẢO HIỂM
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
                        <h1 class="m-0"><strong>Quản lý ĐƠN HÀNG BẢO HIỂM</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Bảo hiểm</li>
                            <li class="breadcrumb-item active">ĐƠN HÀNG BẢO HIỂM</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div>
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">
                                        Danh sách ĐƠN HÀNG BẢO HIỂM
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                    <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span> Thêm đơn hàng</button>
                                    @if(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss'))
                                        <button id="pressImport" class="btn btn-primary" data-toggle="modal" data-target="#importModal"><span class="fas fa-file-import"></span> Nhập Excel</button>
                                    @endif
                                    <button id="btnCreateSettlement" class="btn btn-info"><span class="fas fa-file-word"></span> Tạo Quyết toán</button>
                                    <br/><br/>
                                    
                                    <!-- Search form -->
                                    <form id="searchForm" class="form-row mb-3 align-items-end">
                                        <div class="col-sm-3">
                                            <label>Từ ngày:</label>
                                            <input type="date" id="from_date" name="from_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Đến ngày:</label>
                                            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" id="btnSearch" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tìm kiếm</button>
                                        </div>
                                    </form>
                                    <hr/>

                                    <table id="dataTable" class="display table table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr class="bg-cyan">
                                                <th><input type="checkbox" id="checkAll"> Check</th>
                                                <th>TT</th>
                                                <th>Số Quyết toán</th>
                                                <th>Khách hàng</th>
                                                <th>SĐT</th>
                                                <th>Đơn vị bảo hiểm</th>
                                                <th>Loại hình</th>
                                                <th>Tổng phí</th>
                                                <th>Loại xe</th>
                                                <th>Năm sản xuất</th>
                                                <th>Giá trị xe</th>
                                                <th>Ngày cấp</th>
                                                <th>Hiệu lực</th>
                                                <th>Kết thúc</th>
                                                <th>Nhân viên KD</th>
                                                <th>Người tạo</th>
                                                <th>Ngày tạo</th>
                                                <th>Tác vụ</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>                                    
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>   

    @if(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss'))
    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">NHẬP ĐƠN HÀNG BẢO HIỂM TỪ EXCEL</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="importForm" autocomplete="off" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Chọn tệp Excel (.xlsx, .xls) <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" name="excel" class="custom-file-input" id="excelFile" accept=".xlsx, .xls" required>
                                <label class="custom-file-label" for="excelFile" data-browse="Chọn tệp">Chọn tệp Excel...</label>
                            </div>
                        </div>
                        <div class="form-group text-center mt-3">
                            <a href="{{ asset('upload/baohiem/samplebaohiem.xlsx') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-download"></i> Tải tệp mẫu (.xlsx)
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="btnImport" class="btn btn-primary">Nhập dữ liệu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Add -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">THÊM MỚI ĐƠN HÀNG BẢO HIỂM</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Chọn khách hàng <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="add_guest_search" class="form-control" list="add_guest_list" placeholder="Nhập SĐT tìm khách hàng..." required autocomplete="off">
                                    <datalist id="add_guest_list">
                                        @foreach($guests as $guest)
                                            <option value="{{ $guest->dienThoai }}" data-id="{{ $guest->id }}" data-name="{{ $guest->hoTen }}">{{ $guest->hoTen }}</option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" name="id_guest_baohiem" id="id_guest_baohiem" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#quickAddGuestModal" title="Thêm nhanh khách hàng">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <small id="add_guest_info" class="form-text text-muted">Nhập số điện thoại để tìm khách hàng.</small>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Đơn vị Bảo hiểm <span class="text-danger">*</span></label>
                                <select name="donViBaoHiem" class="form-control" required>
                                    <option value="">-- Chọn đơn vị --</option>
                                    <option value="MIC">MIC</option>
                                    <option value="TASCO">TASCO</option>
                                    <option value="DBV Nam Sông Hậu">DBV Nam Sông Hậu</option>
                                    <option value="BẢO VIỆT">BẢO VIỆT</option>
                                    <option value="PVI">PVI</option>
                                    <option value="PJICO">PJICO</option>
                                    <option value="DBV An Giang">DBV An Giang</option>
                                    <option value="BẢO MINH">BẢO MINH</option>
                                    <option value="PTI">PTI</option>
                                    <option value="BSH">BSH</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                            <label>Loại hình bảo hiểm <span class="text-danger">*</span></label>
                            <select name="loaiHinhBaoHiem" class="form-control" required>
                                <option value="">-- Chọn loại hình --</option>
                                <option value="VCX">VCX</option>
                                <option value="TNDS">TNDS</option>
                            </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tổng phí (VNĐ) <span class="text-danger">*</span></label>
                                <input type="text" name="tongPhi" class="form-control" required placeholder="Tổng phí bảo hiểm" value="0">
                                <small id="add_tongPhi_chu" class="form-text text-success font-italic">Không đồng</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Loại xe</label>
                                <select name="loaiXe" class="form-control">
                                    <option value="">-- Chọn loại xe --</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->name }}">{{ $car->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Năm sản xuất</label>
                                <input type="number" name="namSanXuat" class="form-control" placeholder="Năm sản xuất" min="1900" max="2100">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Giá trị xe (VNĐ)</label>
                                <input type="text" name="giaTriXe" class="form-control" placeholder="Giá trị xe" value="0">
                                <small id="add_giaTriXe_chu" class="form-text text-success font-italic">Không đồng</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Ngày cấp bảo hiểm</label>
                                <input type="date" name="ngayCap" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Ngày hiệu lực bảo hiểm</label>
                                <input type="date" name="ngayHieuLuc" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Ngày kết thúc bảo hiểm</label>
                                <input type="date" name="ngayKetThuc" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Nhân viên kinh doanh</label>
                                <select name="nvKinhDoanh" class="form-control">
                                    <option value="">-- Chọn nhân viên kinh doanh --</option>
                                    @foreach($sales as $sale)
                                        <option value="{{ $sale->surname }}">{{ $sale->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="btnAdd" class="btn btn-success">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">SỬA THÔNG TIN ĐƠN HÀNG BẢO HIỂM</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Chọn khách hàng <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="edit_guest_search" class="form-control" list="edit_guest_list" placeholder="Nhập SĐT tìm khách hàng..." required autocomplete="off">
                                    <datalist id="edit_guest_list">
                                        @foreach($guests as $guest)
                                            <option value="{{ $guest->dienThoai }}" data-id="{{ $guest->id }}" data-name="{{ $guest->hoTen }}">{{ $guest->hoTen }}</option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" name="eid_guest_baohiem" id="eid_guest_baohiem" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#quickAddGuestModal" title="Thêm nhanh khách hàng">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <small id="edit_guest_info" class="form-text text-muted"></small>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Đơn vị Bảo hiểm <span class="text-danger">*</span></label>
                                <select name="edonViBaoHiem" class="form-control" required>
                                    <option value="">-- Chọn đơn vị --</option>
                                    <option value="MIC">MIC</option>
                                    <option value="TASCO">TASCO</option>
                                    <option value="DBV Nam Sông Hậu">DBV Nam Sông Hậu</option>
                                    <option value="BẢO VIỆT">BẢO VIỆT</option>
                                    <option value="PVI">PVI</option>
                                    <option value="PJICO">PJICO</option>
                                    <option value="DBV An Giang">DBV An Giang</option>
                                    <option value="BẢO MINH">BẢO MINH</option>
                                    <option value="PTI">PTI</option>
                                    <option value="BSH">BSH</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                            <label>Loại hình bảo hiểm <span class="text-danger">*</span></label>
                            <select name="eloaiHinhBaoHiem" class="form-control" required>
                                <option value="">-- Chọn loại hình --</option>
                                <option value="VCX">VCX</option>
                                <option value="TNDS">TNDS</option>
                            </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tổng phí (VNĐ) <span class="text-danger">*</span></label>
                                <input type="text" name="etongPhi" class="form-control" required placeholder="Tổng phí bảo hiểm">
                                <small id="edit_tongPhi_chu" class="form-text text-success font-italic">Không đồng</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Loại xe</label>
                                <select name="eloaiXe" class="form-control">
                                    <option value="">-- Chọn loại xe --</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->name }}">{{ $car->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Năm sản xuất</label>
                                <input type="number" name="enamSanXuat" class="form-control" placeholder="Năm sản xuất" min="1900" max="2100">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Giá trị xe (VNĐ)</label>
                                <input type="text" name="egiaTriXe" class="form-control" placeholder="Giá trị xe">
                                <small id="edit_giaTriXe_chu" class="form-text text-success font-italic">Không đồng</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Ngày cấp bảo hiểm</label>
                                <input type="date" name="engayCap" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Ngày hiệu lực bảo hiểm</label>
                                <input type="date" name="engayHieuLuc" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Ngày kết thúc bảo hiểm</label>
                                <input type="date" name="engayKetThuc" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Nhân viên kinh doanh</label>
                                <select name="envKinhDoanh" class="form-control">
                                    <option value="">-- Chọn nhân viên kinh doanh --</option>
                                    @foreach($sales as $sale)
                                        <option value="{{ $sale->surname }}">{{ $sale->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="btnEdit" class="btn btn-info">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Quick Add Guest -->
    <div class="modal fade" id="quickAddGuestModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">THÊM NHANH KHÁCH HÀNG BẢO HIỂM</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="quickAddGuestForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" name="hoTen" class="form-control" required placeholder="Họ và Tên">
                        </div>
                        <div class="form-group">
                            <label>Điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="dienThoai" class="form-control" required placeholder="Số điện thoại" pattern="[0-9]{10}" title="Số điện thoại phải gồm 10 chữ số">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" name="diaChi" class="form-control" required placeholder="Địa chỉ">
                        </div>
                        <div class="form-group">
                            <label>Biển số</label>
                            <input type="text" name="bienSo" class="form-control" placeholder="Biển số xe (nếu có)">
                        </div>
                        <div class="form-group">
                            <label>Số khung</label>
                            <input type="text" name="soKhung" class="form-control" placeholder="Nhập số khung (nếu có)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="btnQuickAddGuest" class="btn btn-success">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Settlement -->
    <div class="modal fade" id="settlementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">TẠO QUYẾT TOÁN BẢO HIỂM</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="settlementForm" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="selected_ids" id="selected_contract_ids">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Đang chọn <strong id="selected_count">0</strong> đơn hàng bảo hiểm để quyết toán.
                        </div>
                        <div class="form-group">
                            <label>Yêu cầu Quyết toán <span class="text-danger">*</span></label>
                            <textarea name="yeuCau" id="yeuCau" class="form-control" rows="3" placeholder="Nhập yêu cầu Quyết toán..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="btnSubmitSettlement" class="btn btn-info">Tạo Quyết toán (.docx)</button>
                    </div>
                </form>
            </div>
        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        // Hàm định dạng ngày d/m/y
        function formatDate(dateString) {
            if (!dateString) return '';
            let date = new Date(dateString);
            let d = String(date.getDate()).padStart(2, '0');
            let m = String(date.getMonth() + 1).padStart(2, '0');
            let y = date.getFullYear();
            return `${d}/${m}/${y}`;
        }

        // Hàm đọc số thành chữ bằng tiếng Việt
        function docSoThanhChu(so) {
            if (so === 0) return 'Không đồng';
            
            const chuSo = ["không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín"];
            
            function docGroup3(n, showZero) {
                let tr = Math.floor(n / 100);
                let ch = Math.floor((n % 100) / 10);
                let dv = n % 10;
                let res = "";
                
                if (tr > 0 || showZero) {
                    res += chuSo[tr] + " trăm ";
                }
                
                if (ch > 0) {
                    if (ch === 1) res += "mười ";
                    else res += chuSo[ch] + " mươi ";
                } else if (tr > 0 && dv > 0) {
                    res += "lẻ ";
                }
                
                if (dv > 0) {
                    if (dv === 1 && ch > 1) {
                        res += "mốt";
                    } else if (dv === 5 && ch > 0) {
                        res += "lăm";
                    } else {
                        res += chuSo[dv];
                    }
                }
                return res.trim();
            }
            
            let str = Math.floor(so).toString();
            let groups = [];
            while (str.length > 0) {
                groups.push(str.slice(-3));
                str = str.slice(0, -3);
            }
            
            const units = ["", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ"];
            let result = "";
            
            for (let i = groups.length - 1; i >= 0; i--) {
                let g = parseInt(groups[i]);
                if (g > 0) {
                    let showZero = (i < groups.length - 1);
                    let gStr = docGroup3(g, showZero);
                    result += gStr + units[i] + " ";
                }
            }
            
            result = result.trim();
            if (result === "") return "";
            
            result = result.charAt(0).toUpperCase() + result.slice(1) + " đồng";
            result = result.replace(/\s+/g, ' ');
            return result;
        }

        // Định dạng tiền tệ
        function formatNumber(num) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num);
        }

        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                responsive: true,
                @if(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss'))
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                @else
                dom: 'lfrtip',
                @endif
                ajax: {
                    url: "{{ url('management/baohiem/hopdongbaohiem/list') }}",
                    data: function(d) {
                        d.from = $("#from_date").val();
                        d.to = $("#to_date").val();
                    }
                },
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": [0, 1]
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu: [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { 
                        "data": "id",
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        },
                        orderable: false,
                        searchable: false,
                        width: "40px"
                    },
                    { "data": null },
                    { "data": "soQuyetToan" },
                    { "data": "guest_name" },
                    { "data": "guest_phone" },
                    { "data": "donViBaoHiem" },
                    { "data": "loaiHinhBaoHiem" },
                    { 
                        "data": "tongPhi",
                        render: function(data) {
                            return formatNumber(data);
                        }
                    },
                    { "data": "loaiXe", defaultContent: '' },
                    { "data": "namSanXuat", defaultContent: '' },
                    { 
                        "data": "giaTriXe",
                        render: function(data) {
                            return data ? formatNumber(data) : '';
                        },
                        defaultContent: ''
                    },
                    { 
                        "data": "ngayCap",
                        render: function(data) { return formatDate(data); }
                    },
                    { 
                        "data": "ngayHieuLuc",
                        render: function(data) { return formatDate(data); }
                    },
                    { 
                        "data": "ngayKetThuc",
                        render: function(data) { return formatDate(data); }
                    },
                    { "data": "nvKinhDoanh", defaultContent: '' },
                    { "data": "creator", defaultContent: '' },
                    { 
                        "data": "created_at",
                        render: function(data) { return formatDate(data); }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            let html = '';
                            if ({{ (Auth::user()->hasRole('system') || Auth::user()->hasRole('boss')) ? 'true' : 'false' }} || row.id_user_create == {{ Auth::user()->id }}) {
                                html += `<button class="btn btn-info btn-sm btn-edit" data-id="${row.id}"><i class="fas fa-edit"></i></button> `;
                            }
                            if ({{ (Auth::user()->hasRole('system') || Auth::user()->hasRole('boss')) ? 'true' : 'false' }}) {
                                html += `<button class="btn btn-danger btn-sm btn-delete" data-id="${row.id}"><i class="fas fa-trash"></i></button>`;
                            }
                            return html;
                        }
                    }
                ]
            });

            table.on( 'order.dt search.dt', function () {
                table.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Reset checkAll on redraw/search
            table.on('draw.dt', function() {
                $('#checkAll').prop('checked', false);
            });

            // Search trigger
            $("#btnSearch").click(function(){
                table.ajax.reload();
            });

            // Form Add submit
            $("#addForm").submit(function(e) {
                e.preventDefault();
                let serializedData = $(this).serializeArray();
                let postData = {};
                serializedData.forEach(function(item) {
                    if (item.name === 'tongPhi' || item.name === 'giaTriXe') {
                        postData[item.name] = item.value.replace(/,/g, '');
                    } else {
                        postData[item.name] = item.value;
                    }
                });
                $.ajax({
                    url: "{{ url('management/baohiem/hopdongbaohiem/add') }}",
                    type: "POST",
                    dataType: "json",
                    data: postData,
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });

                        if (response.code == 200) {
                            $("#addForm")[0].reset();
                            $("#addModal").modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Có lỗi xảy ra, vui lòng thử lại!';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });

            // Load edit modal
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('management/baohiem/hopdongbaohiem/edit/show') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            let data = response.data;
                            $("#editForm input[name=id]").val(data.id);
                            
                            // Tìm khách hàng tương ứng trong datalist để điền SĐT và thông tin
                            let option = $('#edit_guest_list option').filter(function() {
                                return $(this).data('id') == data.id_guest_baohiem;
                            });
                            
                            if (option.length) {
                                let phone = option.val();
                                let name = option.data('name');
                                $('#edit_guest_search').val(phone);
                                $('#eid_guest_baohiem').val(data.id_guest_baohiem);
                                $('#edit_guest_info').html('Khách hàng đã chọn: <strong>' + name + '</strong>').removeClass('text-danger').addClass('text-success');
                            } else {
                                $('#edit_guest_search').val('');
                                $('#eid_guest_baohiem').val(data.id_guest_baohiem);
                                $('#edit_guest_info').html('Không tìm thấy thông tin khách hàng!').removeClass('text-success').addClass('text-danger');
                            }
                            $("#editForm select[name=edonViBaoHiem]").val(data.donViBaoHiem);
                            $("#editForm select[name=eloaiHinhBaoHiem]").val(data.loaiHinhBaoHiem);
                            
                            // Định dạng số và gán đọc chữ cho Tổng Phí
                            let formattedTongPhi = String(data.tongPhi || 0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            $("#editForm input[name=etongPhi]").val(formattedTongPhi);
                            $('#edit_tongPhi_chu').text(docSoThanhChu(data.tongPhi || 0));
                            
                            $("#editForm select[name=eloaiXe]").val(data.loaiXe);
                            $("#editForm input[name=enamSanXuat]").val(data.namSanXuat);
                            
                            // Định dạng số và gán đọc chữ cho Giá trị xe
                            let formattedGiaTriXe = String(data.giaTriXe || 0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            $("#editForm input[name=egiaTriXe]").val(formattedGiaTriXe);
                            $('#edit_giaTriXe_chu').text(docSoThanhChu(data.giaTriXe || 0));
                            $("#editForm input[name=engayCap]").val(data.ngayCap);
                            $("#editForm input[name=engayHieuLuc]").val(data.ngayHieuLuc);
                            $("#editForm input[name=engayKetThuc]").val(data.ngayKetThuc);
                            $("#editForm select[name=envKinhDoanh]").val(data.nvKinhDoanh);
                            $("#editModal").modal('show');
                        } else {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Không thể tải dữ liệu ĐƠN HÀNG BẢO HIỂM!'
                        });
                    }
                });
            });

            // Form Edit submit
            $("#editForm").submit(function(e) {
                e.preventDefault();
                let serializedData = $(this).serializeArray();
                let postData = {};
                serializedData.forEach(function(item) {
                    if (item.name === 'etongPhi' || item.name === 'egiaTriXe') {
                        postData[item.name] = item.value.replace(/,/g, '');
                    } else {
                        postData[item.name] = item.value;
                    }
                });
                $.ajax({
                    url: "{{ url('management/baohiem/hopdongbaohiem/update') }}",
                    type: "POST",
                    dataType: "json",
                    data: postData,
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });

                        if (response.code == 200) {
                            $("#editForm")[0].reset();
                            $("#editModal").modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Có lỗi xảy ra, vui lòng thử lại!';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });

            // Submit form thêm nhanh khách hàng
            $("#quickAddGuestForm").submit(function(e) {
                e.preventDefault();
                let phone = $("#quickAddGuestForm input[name=dienThoai]").val();
                if (!/^[0-9]{10}$/.test(phone)) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Số điện thoại không hợp lệ! Phải gồm 10 chữ số.'
                    });
                    return;
                }
                $.ajax({
                    url: "{{ url('management/baohiem/khachhangbaohiem/add') }}",
                    type: "POST",
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });

                        if (response.code == 200) {
                            let data = response.data;
                            
                            // Thêm Option mới vào cả 2 datalist
                            let newOptAdd = $('<option>').val(data.dienThoai).attr('data-id', data.id).attr('data-name', data.hoTen).text(data.hoTen);
                            let newOptEdit = $('<option>').val(data.dienThoai).attr('data-id', data.id).attr('data-name', data.hoTen).text(data.hoTen);
                            
                            $('#add_guest_list').append(newOptAdd);
                            $('#edit_guest_list').append(newOptEdit);
                            
                            // Tự động điền và chọn ở form Add
                            $('#add_guest_search').val(data.dienThoai);
                            $('#id_guest_baohiem').val(data.id);
                            $('#add_guest_info').html('Khách hàng đã chọn: <strong>' + data.hoTen + '</strong>').removeClass('text-danger').addClass('text-success');
                            
                            // Reset form & ẩn modal thêm nhanh
                            $("#quickAddGuestForm")[0].reset();
                            $("#quickAddGuestModal").modal('hide');
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Có lỗi xảy ra, vui lòng thử lại!';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });

            // Xử lý đồng bộ datalist khách hàng form thêm mới
            $(document).on('input', '#add_guest_search', function() {
                let val = $(this).val();
                let option = $('#add_guest_list option').filter(function() {
                    return this.value === val;
                });
                
                if (option.length) {
                    let id = option.data('id');
                    let name = option.data('name');
                    $('#id_guest_baohiem').val(id);
                    $('#add_guest_info').html('Khách hàng đã chọn: <strong>' + name + '</strong>').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#id_guest_baohiem').val('');
                    $('#add_guest_info').html('Nhập SĐT để khớp khách hàng.').removeClass('text-success').addClass('text-danger');
                }
            });

            // Xử lý đồng bộ datalist khách hàng form chỉnh sửa
            $(document).on('input', '#edit_guest_search', function() {
                let val = $(this).val();
                let option = $('#edit_guest_list option').filter(function() {
                    return this.value === val;
                });
                
                if (option.length) {
                    let id = option.data('id');
                    let name = option.data('name');
                    $('#eid_guest_baohiem').val(id);
                    $('#edit_guest_info').html('Khách hàng đã chọn: <strong>' + name + '</strong>').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#eid_guest_baohiem').val('');
                    $('#edit_guest_info').html('Nhập SĐT để khớp khách hàng.').removeClass('text-success').addClass('text-danger');
                }
            });

            // Định dạng phân tách nghìn và hiển thị đọc số thành chữ khi nhập
            $(document).on('input', 'input[name=tongPhi], input[name=giaTriXe], input[name=etongPhi], input[name=egiaTriXe]', function() {
                let name = $(this).attr('name');
                let val = $(this).val().replace(/[^0-9]/g, '');
                
                // Cập nhật lại giá trị input đã được định dạng dấu phẩy
                $(this).val(val.replace(/\B(?=(\d{3})+(?!\d))/g, ','));
                
                let num = parseInt(val) || 0;
                let text = docSoThanhChu(num);
                
                // Gán chữ số đọc được vào thẻ nhỏ phía dưới
                if (name === 'tongPhi') {
                    $('#add_tongPhi_chu').text(text);
                } else if (name === 'giaTriXe') {
                    $('#add_giaTriXe_chu').text(text);
                } else if (name === 'etongPhi') {
                    $('#edit_tongPhi_chu').text(text);
                } else if (name === 'egiaTriXe') {
                    $('#edit_giaTriXe_chu').text(text);
                }
            });

            // Delete contract
            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                if (confirm("Bạn có chắc muốn xóa ĐƠN HÀNG BẢO HIỂM này?")) {
                    $.ajax({
                        url: "{{ url('management/baohiem/hopdongbaohiem/delete') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            if (response.code == 200) {
                                table.ajax.reload();
                            }
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: 'Không thể xóa ĐƠN HÀNG BẢO HIỂM lúc này!'
                            });
                        }
                    });
                }
            });

            // Hiển thị tên file khi chọn file excel
            $(document).on('change', '.custom-file-input', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Form Import submit
            $("#importForm").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                
                // Hiển thị màn hình chờ loading
                Swal.fire({
                    title: 'Đang xử lý dữ liệu...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: "{{ url('management/baohiem/hopdongbaohiem/import') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });

                        if (response.code == 200) {
                            $("#importForm")[0].reset();
                            $("#importForm .custom-file-label").html('Chọn tệp Excel...');
                            $("#importModal").modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        let msg = 'Có lỗi xảy ra, vui lòng thử lại!';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });

            // Xử lý chọn tất cả / bỏ chọn tất cả
            $(document).on('change', '#checkAll', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            $(document).on('change', '.row-checkbox', function() {
                if (!this.checked) {
                    $('#checkAll').prop('checked', false);
                } else if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#checkAll').prop('checked', true);
                }
            });

            // Xử lý sự kiện click nút "Tạo Quyết toán"
            $('#btnCreateSettlement').click(function() {
                let selectedRows = [];
                let hasErrorAlreadySettled = false;
                let guestId = null;
                let sameGuest = true;

                $('.row-checkbox:checked').each(function() {
                    let rowData = table.row($(this).closest('tr')).data();
                    if (rowData) {
                        selectedRows.push(rowData);
                        if (rowData.soQuyetToan !== null && rowData.soQuyetToan !== '') {
                            hasErrorAlreadySettled = true;
                        }
                        if (guestId === null) {
                            guestId = rowData.id_guest_baohiem;
                        } else if (guestId != rowData.id_guest_baohiem) {
                            sameGuest = false;
                        }
                    }
                });

                if (selectedRows.length === 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'vui lòng chọn ít nhất một bản ghi từ danh sách dữ liệu để thực hiện tạo Quyết toán'
                    });
                    return;
                }

                if (hasErrorAlreadySettled) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Dữ liệu vừa chọn đã tạo Quyết toán rồi vui lòng chọn dữ liệu chưa tạo quyết toán'
                    });
                    return;
                }

                if (!sameGuest) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Các đơn hàng được chọn phải thuộc về cùng một khách hàng!'
                    });
                    return;
                }

                // Reset form và hiển thị modal
                $('#settlementForm')[0].reset();
                $('#selected_count').text(selectedRows.length);
                let selectedIds = selectedRows.map(row => row.id);
                $('#selected_contract_ids').val(selectedIds.join(','));
                $('#settlementModal').modal('show');
            });

            // Xử lý submit form Quyết toán để xuất file Word
            $('#settlementForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                // Hiển thị màn hình chờ loading
                Swal.fire({
                    title: 'Đang tạo quyết toán...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ url('management/baohiem/hopdongbaohiem/create-settlement') }}",
                    type: "POST",
                    data: formData,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response, status, xhr) {
                        Swal.close();

                        // Kiểm tra Content-Type để phát hiện lỗi JSON được bọc trong blob
                        let contentType = xhr.getResponseHeader("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            let reader = new FileReader();
                            reader.onload = function() {
                                let errObj = JSON.parse(this.result);
                                Toast.fire({
                                    icon: errObj.type || 'error',
                                    title: errObj.message || 'Có lỗi xảy ra!'
                                });
                            };
                            reader.readAsText(response);
                            return;
                        }

                        // Thực hiện tải file xuống
                        let blob = new Blob([response], { type: "application/vnd.openxmlformats-officedocument.wordprocessingml.document" });
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);

                        let filename = "QuyetToan.docx";
                        let disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            let matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) {
                                filename = matches[1].replace(/['"]/g, '');
                            }
                        }
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        Toast.fire({
                            icon: 'success',
                            title: 'Tạo Quyết toán thành công!'
                        });

                        $('#settlementModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.close();
                        Toast.fire({
                            icon: 'error',
                            title: 'Không thể tạo Quyết toán lúc này, vui lòng thử lại!'
                        });
                    }
                });
            });

            // Xử lý cuộn trang (scroll) khi đóng modal con mà modal cha vẫn hiển thị
            $(document).on('hidden.bs.modal', '.modal', function () {
                if ($('.modal.show').length > 0) {
                    $('body').addClass('modal-open');
                }
            });
        });
    </script>
@endsection
