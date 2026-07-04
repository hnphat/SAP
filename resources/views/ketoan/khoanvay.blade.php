@extends('admin.index')
@section('title')
    Đơn hàng/xe bảo lãnh
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
                        <h1 class="m-0"><strong>Đơn hàng/Xe bảo lãnh</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kế toán</li>
                            <li class="breadcrumb-item active">Đơn hàng/Xe bảo lãnh</li>
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
                                    <strong>Đơn hàng/Xe bảo lãnh</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalThemKhoanVay">
                                            <i class="fas fa-plus"></i> Thêm mới
                                        </button>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Từ ngày</span>
                                            </div>
                                            <input type="date" id="filter_tu_ngay" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Đến ngày</span>
                                            </div>
                                            <input type="date" id="filter_den_ngay" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button id="btnFilter" class="btn btn-info btn-block">
                                            <i class="fas fa-search"></i> TRA CỨU
                                        </button>
                                    </div>
                                </div>
                                <hr/>
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Số khoản vay</th>
                                        <th>Nội dung vay</th>
                                        <th>Ngày vay</th>
                                        <th>Lãi suất</th>
                                        <th>Tiền vay</th>
                                        <th>Lãi ngày</th>
                                        <th>Lãi tháng</th>
                                        <th>Lãi đến hôm nay</th>
                                        <th>Số tiền đã trả</th>
                                        <th>Ngân hàng vay</th>
                                        <th>Ghi chú</th>
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
        <!-- /.content -->
    </div>
<div class="modal fade" id="modalThemKhoanVay" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formThemKhoanVay">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Khoản Vay Mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Số khoản vay <span class="text-danger">*</span></label>
                            <input type="text" name="so_khoan_vay" class="form-group form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ngân hàng vay <span class="text-danger">*</span></label>
                            <input type="text" name="ngan_hang_vay" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ngày vay <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_vay" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Lãi suất (%/năm) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="lai_suat" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tiền vay (VNĐ) <span class="text-danger">*</span></label>
                            <input type="text" id="them_tien_vay" name="tien_vay" class="form-control text-right font-weight-bold" placeholder="Nhập số tiền..." required>
                            <span id="them_tien_vay_text" class="text-success text-sm font-italic"></span>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Nội dung vay <span class="text-danger">*</span></label>
                            <textarea name="noi_dung_vay" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Ghi chú</label>
                            <input type="text" name="ghi_chu" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSuaKhoanVay" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formSuaKhoanVay">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Khoản Vay</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Số khoản vay <span class="text-danger">*</span></label>
                            <input type="text" id="edit_so_khoan_vay" name="so_khoan_vay" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ngân hàng vay <span class="text-danger">*</span></label>
                            <input type="text" id="edit_ngan_hang_vay" name="ngan_hang_vay" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ngày vay <span class="text-danger">*</span></label>
                            <input type="date" id="edit_ngay_vay" name="ngay_vay" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Lại suất (%/năm) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="edit_lai_suat" name="lai_suat" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tiền vay (VNĐ) <span class="text-danger">*</span></label>
                            <input type="text" id="edit_tien_vay" name="tien_vay" class="form-control text-right font-weight-bold" placeholder="Nhập số tiền..." required>
                            <span id="edit_tien_vay_text" class="text-success text-sm font-italic"></span>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Nội dung vay <span class="text-danger">*</span></label>
                            <textarea id="edit_noi_dung_vay" name="noi_dung_vay" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Ghi chú</label>
                            <input type="text" id="edit_ghi_chu" name="ghi_chu" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Cập nhật thay đổi</button>
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
            if (num === "" || num === null || num === undefined) return "";
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        function formatDate(dateStr) {
            if (!dateStr) return "";
            let date = new Date(dateStr);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function formatCurrency(num) {
            if (num === null || num === undefined) return "0 VNĐ";
            return formatNumber(num) + " VNĐ";
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();
        
        function CountTheDays(date_1, date_2) {
            // Set both to midnight to count full days
            let d1 = new Date(date_1.getFullYear(), date_1.getMonth(), date_1.getDate());
            let d2 = new Date(date_2.getFullYear(), date_2.getMonth(), date_2.getDate());
            let difference = d1.getTime() - d2.getTime();
            let TotalDays = Math.ceil(difference / (1000 * 3600 * 24));
            return TotalDays;
        }

        function initTienVayFormat(inputId, textId) {
            $(inputId).on('input', function() {
                // Get raw value (numbers only)
                let val = $(this).val().replace(/[^0-9]/g, '');
                
                // Format with commas
                let formatted = formatNumber(val);
                $(this).val(formatted);
                
                // Update text below
                if (formatted !== "") {
                    let docSo = DOCSO.doc(parseInt(val));
                    $(textId).text(`(${docSo} đồng)`);
                } else {
                    $(textId).text('');
                }
            });
        }

        $(document).ready(function() {            
            // Set default date values to today
            let today = new Date().toISOString().split('T')[0];
            $('#filter_tu_ngay').val(today);
            $('#filter_den_ngay').val(today);

            // Filter button action
            $('#btnFilter').click(function() {
                table.ajax.reload();
            });

            // Initialize formatting for money inputs
            initTienVayFormat('#them_tien_vay', '#them_tien_vay_text');
            initTienVayFormat('#edit_tien_vay', '#edit_tien_vay_text');

            table = $('#dataTable').DataTable({
                responsive: true,
                ajax: {
                    url: "{{route('ketoan.khoanvay.get')}}",
                    type: "GET",
                    data: function(d) {
                        d.tu_ngay = $('#filter_tu_ngay').val();
                        d.den_ngay = $('#filter_den_ngay').val();
                    },
                    dataSrc: (json) => Array.isArray(json) ? json : (json.data || [])
                },
                order: [[1, 'desc']],
                columns: [
                    { 
                        "data": null, 
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    { "data": "soKhoanVay" },
                    { "data": "noiDungVay" },
                    { 
                        "data": "ngayNhanNo",
                        render: (data) => data ? formatDate(data) : ""
                    },
                    { 
                        "data": "laiSuat",
                        render: (data) => `<strong>${data}%/năm</strong>`
                    },
                    { 
                        "data": "tienVay",
                        render: (data) => formatCurrency(data)
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            let daily = (row.tienVay * (row.laiSuat / 100)) / 365;
                            return formatCurrency(Math.round(daily));
                        }
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            let monthly = (row.tienVay * (row.laiSuat / 100)) / 12;
                            return formatCurrency(Math.round(monthly));
                        }
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            let daily = (row.tienVay * (row.laiSuat / 100)) / 365;
                            let days = CountTheDays(new Date(), new Date(row.ngayNhanNo));
                            if (days < 0) days = 0;
                            let totalInterest = daily * days;
                            return formatCurrency(Math.round(totalInterest)) + ` (${days} ngày)`;
                        }
                    },
                    { 
                        "data": "tienDaTra",
                        render: (data) => formatCurrency(data)
                    },
                    { "data": "nganHangVay" },
                    { "data": "ghiChu" },
                    {
                        "data": null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}"><i class="fas fa-edit"></i> Sửa</button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}"><i class="fas fa-trash"></i> Xóa</button>
                            `;
                        }
                    }
                ]
            });

            // Submit Form Them Khoan Vay
            $("#formThemKhoanVay").submit(function(e) {
                e.preventDefault();
                let data = $(this).serializeArray();
                data.forEach(function(item) {
                    if (item.name === 'tien_vay') {
                        item.value = item.value.replace(/,/g, '');
                    }
                });
                $.ajax({
                    url: "{{route('ketoan.khoanvay.store')}}",
                    type: "POST",
                    dataType: "json",
                    data: $.param(data) + "&_token={{csrf_token()}}",
                    success: function(response) {
                        if (response.code == 200) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            table.ajax.reload();
                            $("#modalThemKhoanVay").modal('hide');
                            $("#formThemKhoanVay")[0].reset();
                            $("#them_tien_vay_text").text("");
                        } else {
                            Toast.fire({
                                icon: response.type || 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'warning',
                            title: xhr.responseJSON ? xhr.responseJSON.message : "Error 500!"
                        });
                    }
                });
            });

            // Edit Click Show Data
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{route('ketoan.khoanvay.edit')}}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": id
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            $("#edit_id").val(response.data.id);
                            $("#edit_so_khoan_vay").val(response.data.soKhoanVay);
                            $("#edit_ngan_hang_vay").val(response.data.nganHangVay);
                            $("#edit_ngay_vay").val(response.data.ngayNhanNo);
                            $("#edit_lai_suat").val(response.data.laiSuat);
                            $("#edit_tien_vay").val(formatNumber(response.data.tienVay));
                            if (response.data.tienVay) {
                                let docSo = DOCSO.doc(parseInt(response.data.tienVay));
                                $("#edit_tien_vay_text").text(`(${docSo} đồng)`);
                            } else {
                                $("#edit_tien_vay_text").text('');
                            }
                            $("#edit_noi_dung_vay").val(response.data.noiDungVay);
                            $("#edit_ghi_chu").val(response.data.ghiChu);

                            $("#modalSuaKhoanVay").modal('show');
                        } else {
                            Toast.fire({
                                icon: response.type || 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể lấy thông tin khoản vay!"
                        });
                    }
                });
            });

            // Submit Form Update Khoan Vay
            $("#formSuaKhoanVay").submit(function(e) {
                e.preventDefault();
                let data = $(this).serializeArray();
                data.forEach(function(item) {
                    if (item.name === 'tien_vay') {
                        item.value = item.value.replace(/,/g, '');
                    }
                });
                $.ajax({
                    url: "{{route('ketoan.khoanvay.update')}}",
                    type: "POST",
                    dataType: "json",
                    data: $.param(data) + "&_token={{csrf_token()}}",
                    success: function(response) {
                        if (response.code == 200) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            table.ajax.reload();
                            $("#modalSuaKhoanVay").modal('hide');
                            $("#formSuaKhoanVay")[0].reset();
                            $("#edit_tien_vay_text").text("");
                        } else {
                            Toast.fire({
                                icon: response.type || 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'warning',
                            title: xhr.responseJSON ? xhr.responseJSON.message : "Error 500!"
                        });
                    }
                });
            });

            // Delete Khoan Vay
            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Bạn chắc chắn muốn xóa?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Đồng ý xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{route('ketoan.khoanvay.delete')}}",
                            type: "POST",
                            dataType: "json",
                            data: {
                                "_token": "{{csrf_token()}}",
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
                            error: function(xhr) {
                                Toast.fire({
                                    icon: 'warning',
                                    title: xhr.responseJSON ? xhr.responseJSON.message : "Error 500!"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
