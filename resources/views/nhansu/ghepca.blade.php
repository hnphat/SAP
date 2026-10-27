@extends('admin.index')
@section('title')
    Ghép ca chấm công
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .stat-card {
            border-radius: 8px;
            padding: 15px 20px;
            color: #fff;
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .stat-card.active-filter {
            outline: 3px solid #000;
        }
        .badge-shift {
            font-size: 0.95rem;
            padding: 5px 10px;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Ghép ca chấm công</strong></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Ghép ca chấm công</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Thẻ thống kê trực quan -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card bg-info" id="cardFilterAll">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Tổng nhân viên đang hoạt động</h6>
                                    <h2 class="font-weight-bold mb-0" id="statTong">0</h2>
                                </div>
                                <div>
                                    <i class="fas fa-users fa-3x opacity-50"></i>
                                </div>
                            </div>
                            <small class="text-white-50"><i class="fas fa-filter mr-1"></i>Bấm để xem tất cả</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-success" id="cardFilterDaPhanCa">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Đã phân ca làm</h6>
                                    <h2 class="font-weight-bold mb-0" id="statDaPhanCa">0</h2>
                                </div>
                                <div>
                                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                </div>
                            </div>
                            <small class="text-white-50"><i class="fas fa-filter mr-1"></i>Bấm để lọc nhân viên đã có ca</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-danger" id="cardFilterChuaPhanCa">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">CHƯA PHÂN CA</h6>
                                    <h2 class="font-weight-bold mb-0" id="statChuaPhanCa">0</h2>
                                </div>
                                <div>
                                    <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                                </div>
                            </div>
                            <small class="text-white font-weight-bold"><i class="fas fa-filter mr-1"></i>Bấm để lọc nhân viên CHƯA có ca</small>
                        </div>
                    </div>
                </div>

                <!-- Banner Cảnh báo nếu còn nhân viên chưa phân ca -->
                <div id="alertChuaPhanCa" class="alert alert-warning alert-dismissible fade show font-weight-bold" style="display: none;" role="alert">
                    <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
                    <span>CẢNH BÁO: Hiện có <span id="alertCountChuaPhanCa" class="badge badge-danger text-md">0</span> nhân viên đang hoạt động (active = 1) chưa được phân ca làm việc! Vui lòng chọn và ghép ca làm cho những nhân viên này để hệ thống tính công chính xác.</span>
                </div>

                <!-- Bảng Danh sách và Thanh công cụ thao tác hàng loạt -->
                <div class="card card-primary card-outline">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <h3 class="card-title font-weight-bold">
                                    <i class="fas fa-calendar-alt text-primary mr-2"></i>Danh sách nhân viên & Ca làm việc
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <a href="{{ route('calam.panel') }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-cog mr-1"></i> Quản lý danh mục ca làm
                                </a>
                                <button id="btnReload" class="btn btn-secondary btn-sm ml-1">
                                    <i class="fas fa-sync-alt mr-1"></i> Tải lại
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Thanh thao tác hàng loạt (Bulk Action Toolbar) -->
                    <div class="card-body bg-light border-bottom py-2">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="font-weight-bold text-primary">
                                    <i class="fas fa-check-square mr-1"></i>Đã chọn: <span id="selectedCount" class="badge badge-primary font-weight-bold text-md">0</span> nhân viên
                                </span>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-white"><i class="fas fa-clock mr-1"></i>Chọn ca:</span>
                                    </div>
                                    <select id="selectCaLamBulk" class="form-control font-weight-bold">
                                        <option value="">-- Chọn ca làm việc cần gán --</option>
                                        @foreach($caLams as $ca)
                                            <option value="{{ $ca->id }}">
                                                {{ $ca->ten_ca }} ({{ $ca->vao_sang }}-{{ $ca->ra_sang }} | {{ $ca->vao_chieu }}-{{ $ca->ra_chieu }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-2 mt-md-0">
                                <button id="btnApplyBulk" class="btn btn-success btn-sm font-weight-bold">
                                    <i class="fas fa-check mr-1"></i> Gán ca cho mục đã chọn
                                </button>
                                <button id="btnRemoveBulk" class="btn btn-outline-danger btn-sm font-weight-bold ml-1">
                                    <i class="fas fa-times mr-1"></i> Gỡ ca
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr class="bg-cyan">
                                        <th style="width: 40px;" class="text-center">
                                            <input type="checkbox" id="checkAll" style="transform: scale(1.3); cursor: pointer;">
                                        </th>
                                        <th style="width: 50px;">STT</th>
                                        <th>Mã NV</th>
                                        <th>Họ và tên</th>
                                        <th>Phòng ban</th>
                                        <th>Ca làm hiện tại</th>
                                        <th>Khung giờ ca</th>
                                        <th>Trạng thái</th>
                                        <th style="width: 140px;">Tác vụ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal gán ca cho 1 nhân viên -->
    <div class="modal fade" id="modalSingleAssign" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-user-clock mr-2"></i>Phân ca làm cho nhân viên
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="singleUserId">
                    <div class="form-group">
                        <label class="text-muted">Nhân viên:</label>
                        <h5 id="singleUserName" class="font-weight-bold text-primary"></h5>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Chọn ca làm việc: <span class="text-danger">*</span></label>
                        <select id="singleCaLamSelect" class="form-control font-weight-bold">
                            <option value="">-- Chọn ca làm --</option>
                            @foreach($caLams as $ca)
                                <option value="{{ $ca->id }}">
                                    {{ $ca->ten_ca }} ({{ $ca->vao_sang }}-{{ $ca->ra_sang }} | {{ $ca->vao_chieu }}-{{ $ca->ra_chieu }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Ghi chú (tùy chọn):</label>
                        <input type="text" id="singleGhiChu" class="form-control" placeholder="Ghi chú phân ca">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" id="btnSingleSave" class="btn btn-primary font-weight-bold">Xác nhận phân ca</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function(){
            var currentFilterStatus = 'all'; // all, da_phan_ca, chua_phan_ca

            // Tùy biến bộ lọc cho DataTable
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex, rowData, counter) {
                    if (currentFilterStatus === 'all') return true;
                    if (currentFilterStatus === 'da_phan_ca') {
                        return rowData.da_phan_ca === 1;
                    }
                    if (currentFilterStatus === 'chua_phan_ca') {
                        return rowData.da_phan_ca === 0;
                    }
                    return true;
                }
            );

            var table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                pageLength: 25,
                ajax: {
                    url: "{{ url('management/nhansu/ghepca/ajax/getlist') }}",
                    dataSrc: function(json) {
                        // Cập nhật thẻ thống kê
                        if (json.stats) {
                            $("#statTong").text(json.stats.tong);
                            $("#statDaPhanCa").text(json.stats.daPhanCa);
                            $("#statChuaPhanCa").text(json.stats.chuaPhanCa);

                            // Hiển thị cảnh báo nếu có nhân viên chưa phân ca
                            if (json.stats.chuaPhanCa > 0) {
                                $("#alertCountChuaPhanCa").text(json.stats.chuaPhanCa);
                                $("#alertChuaPhanCa").slideDown();
                            } else {
                                $("#alertChuaPhanCa").slideUp();
                            }
                        }
                        return json.data;
                    }
                },
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        className: 'text-center',
                        render: function(data) {
                            return '<input type="checkbox" class="userCheckbox" value="' + data + '" style="transform: scale(1.2); cursor: pointer;">';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { 
                        data: 'manv',
                        render: function(data) {
                            return '<strong>' + (data ? data : '') + '</strong>';
                        }
                    },
                    { 
                        data: 'hoten',
                        render: function(data, type, row) {
                            return '<strong>' + (data ? data : '') + '</strong>';
                        }
                    },
                    { 
                        data: 'phongban',
                        render: function(data) {
                            return data ? ('<span class="badge badge-light border">' + data + '</span>') : '<i class="text-muted">Chưa gán</i>';
                        }
                    },
                    { 
                        data: 'ten_ca',
                        render: function(data, type, row) {
                            if (row.da_phan_ca === 1) {
                                return '<span class="badge badge-success badge-shift"><i class="fas fa-check-circle mr-1"></i>' + data + '</span>';
                            } else {
                                return '<span class="badge badge-danger badge-shift"><i class="fas fa-exclamation-circle mr-1"></i>Chưa phân ca</span>';
                            }
                        }
                    },
                    { 
                        data: 'khung_gio',
                        render: function(data, type, row) {
                            if (row.da_phan_ca === 1) {
                                return '<span class="font-weight-bold text-dark">' + data + '</span>';
                            }
                            return '<span class="text-muted">--:--</span>';
                        }
                    },
                    { 
                        data: 'da_phan_ca',
                        render: function(data) {
                            if (data === 1) {
                                return '<span class="badge badge-pill badge-success px-2 py-1">Đã có ca</span>';
                            }
                            return '<span class="badge badge-pill badge-danger px-2 py-1">Cần phân ca</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            let btnAssign = '<button class="btn btn-primary btn-xs btnSingleAssign mr-1" data-id="' + row.id + '" data-name="' + row.hoten + ' (' + row.manv + ')" data-ca="' + (row.id_ca_lam || '') + '"><i class="fas fa-calendar-check mr-1"></i>Gán ca</button>';
                            let btnRemove = '';
                            if (row.da_phan_ca === 1) {
                                btnRemove = '<button class="btn btn-outline-danger btn-xs btnSingleRemove" data-id="' + row.id + '" data-name="' + row.hoten + '"><i class="fas fa-times"></i> Gỡ</button>';
                            }
                            return btnAssign + btnRemove;
                        }
                    }
                ],
                drawCallback: function() {
                    updateSelectedCount();
                    // Bỏ check checkAll khi chuyển trang hoặc vẽ lại
                    $("#checkAll").prop('checked', false);
                }
            });

            // Cập nhật số lượng checkbox đã chọn
            function updateSelectedCount() {
                let count = $('.userCheckbox:checked').length;
                $('#selectedCount').text(count);
                if (count > 0) {
                    $('#btnApplyBulk').removeClass('disabled');
                    $('#btnRemoveBulk').removeClass('disabled');
                }
            }

            // Xử lý Checkbox Chọn tất cả (trên trang hiện tại)
            $("#checkAll").click(function(){
                let isChecked = $(this).prop('checked');
                $('.userCheckbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            $(document).on('change', '.userCheckbox', function(){
                updateSelectedCount();
            });

            // Lọc nhanh bằng Thẻ thống kê
            $("#cardFilterAll").click(function(){
                currentFilterStatus = 'all';
                $(".stat-card").removeClass('active-filter');
                $(this).addClass('active-filter');
                table.draw();
            });

            $("#cardFilterDaPhanCa").click(function(){
                currentFilterStatus = 'da_phan_ca';
                $(".stat-card").removeClass('active-filter');
                $(this).addClass('active-filter');
                table.draw();
            });

            $("#cardFilterChuaPhanCa").click(function(){
                currentFilterStatus = 'chua_phan_ca';
                $(".stat-card").removeClass('active-filter');
                $(this).addClass('active-filter');
                table.draw();
            });

            // Nút tải lại
            $("#btnReload").click(function(){
                table.ajax.reload();
            });

            // Gán ca hàng loạt
            $("#btnApplyBulk").click(function(){
                let selectedIds = [];
                $('.userCheckbox:checked').each(function(){
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert("Vui lòng tích chọn ít nhất 01 nhân viên để gán ca!");
                    return;
                }

                let idCaLam = $("#selectCaLamBulk").val();
                if (!idCaLam) {
                    alert("Vui lòng chọn ca làm việc cần gán ở danh sách kế bên!");
                    $("#selectCaLamBulk").focus();
                    return;
                }

                let tenCa = $("#selectCaLamBulk option:selected").text();

                if (confirm('Xác nhận gán ca làm việc:\n' + tenCa.trim() + '\ncho ' + selectedIds.length + ' nhân viên đã chọn?')) {
                    $.ajax({
                        url: "{{ url('management/nhansu/ghepca/ajax/assign') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            user_ids: selectedIds,
                            id_ca_lam: idCaLam
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            if (response.code === 200) {
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi khi gán ca làm!"
                            });
                        }
                    });
                }
            });

            // Gỡ ca hàng loạt
            $("#btnRemoveBulk").click(function(){
                let selectedIds = [];
                $('.userCheckbox:checked').each(function(){
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert("Vui lòng tích chọn ít nhất 01 nhân viên để gỡ ca!");
                    return;
                }

                if (confirm('Bạn có chắc chắn muốn gỡ ca làm việc cho ' + selectedIds.length + ' nhân viên đã chọn không?')) {
                    $.ajax({
                        url: "{{ url('management/nhansu/ghepca/ajax/remove') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            user_ids: selectedIds
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            if (response.code === 200) {
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi khi gỡ ca làm!"
                            });
                        }
                    });
                }
            });

            // Gán ca đơn lẻ
            $(document).on('click', '.btnSingleAssign', function(){
                let userId = $(this).data('id');
                let userName = $(this).data('name');
                let currentCa = $(this).data('ca');

                $("#singleUserId").val(userId);
                $("#singleUserName").text(userName);
                $("#singleCaLamSelect").val(currentCa ? currentCa : '');
                $("#singleGhiChu").val('');
                $("#modalSingleAssign").modal('show');
            });

            $("#btnSingleSave").click(function(){
                let userId = $("#singleUserId").val();
                let idCaLam = $("#singleCaLamSelect").val();
                let ghiChu = $("#singleGhiChu").val();

                if (!idCaLam) {
                    alert("Vui lòng chọn ca làm việc!");
                    return;
                }

                $.ajax({
                    url: "{{ url('management/nhansu/ghepca/ajax/assign') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_ids: [userId],
                        id_ca_lam: idCaLam,
                        ghi_chu: ghiChu
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                        if (response.code === 200) {
                            $("#modalSingleAssign").modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi khi gán ca làm!"
                        });
                    }
                });
            });

            // Gỡ ca đơn lẻ
            $(document).on('click', '.btnSingleRemove', function(){
                let userId = $(this).data('id');
                let userName = $(this).data('name');

                if (confirm('Bạn có chắc chắn muốn gỡ ca làm việc của nhân viên [' + userName + '] không?')) {
                    $.ajax({
                        url: "{{ url('management/nhansu/ghepca/ajax/remove') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            user_ids: [userId]
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            if (response.code === 200) {
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi khi gỡ ca làm!"
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
