@extends('admin.index')
@section('title')
    Quản lý ca làm
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý ca làm</strong></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý ca làm</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Danh sách ca làm việc</h3>
                        <div class="card-tools">
                            <button id="pressAdd" class="btn btn-success btn-sm font-weight-bold" data-toggle="modal" data-target="#addModal">
                                <i class="fas fa-plus-circle mr-1"></i> Thêm ca làm mới
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr class="bg-cyan">
                                    <th>STT</th>
                                    <th>Tên ca làm</th>
                                    <th>Vào sáng</th>
                                    <th>Ra sáng</th>
                                    <th>Vào chiều</th>
                                    <th>Ra chiều</th>
                                    <th>Số NV áp dụng</th>
                                    <th>Ghi chú</th>
                                    <th>Tác vụ</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Ca Làm -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title font-weight-bold"><i class="fas fa-clock mr-2"></i>Thêm ca làm việc mới</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="addForm" autocomplete="off">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="font-weight-bold">Tên ca làm <span class="text-danger">*</span></label>
                            <input type="text" name="ten_ca" class="form-control" placeholder="VD: Ca hành chính, Ca showroom, Ca bảo dưỡng..." required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header p-2">
                                        <strong class="text-info"><i class="fas fa-sun mr-1"></i>Buổi sáng</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Giờ vào sáng <span class="text-danger">*</span></label>
                                            <input type="time" name="vao_sang" value="08:00" class="form-control text-center font-weight-bold" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Giờ ra sáng <span class="text-danger">*</span></label>
                                            <input type="time" name="ra_sang" value="12:00" class="form-control text-center font-weight-bold" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-outline card-warning">
                                    <div class="card-header p-2">
                                        <strong class="text-warning"><i class="fas fa-cloud-sun mr-1"></i>Buổi chiều</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Giờ vào chiều <span class="text-danger">*</span></label>
                                            <input type="time" name="vao_chieu" value="13:00" class="form-control text-center font-weight-bold" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Giờ ra chiều <span class="text-danger">*</span></label>
                                            <input type="time" name="ra_chieu" value="17:00" class="form-control text-center font-weight-bold" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label>Ghi chú</label>
                            <input type="text" name="ghi_chu" class="form-control" placeholder="Ghi chú thêm về ca làm (tùy chọn)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button id="btnAdd" class="btn btn-success font-weight-bold" form="addForm">Lưu ca làm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Ca Làm -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Chỉnh sửa ca làm việc</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editForm" autocomplete="off">
                        {{csrf_field()}}
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label class="font-weight-bold">Tên ca làm <span class="text-danger">*</span></label>
                            <input type="text" name="ten_ca" id="edit_ten_ca" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header p-2">
                                        <strong class="text-info"><i class="fas fa-sun mr-1"></i>Buổi sáng</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Giờ vào sáng <span class="text-danger">*</span></label>
                                            <input type="time" name="vao_sang" id="edit_vao_sang" class="form-control text-center font-weight-bold" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Giờ ra sáng <span class="text-danger">*</span></label>
                                            <input type="time" name="ra_sang" id="edit_ra_sang" class="form-control text-center font-weight-bold" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-outline card-warning">
                                    <div class="card-header p-2">
                                        <strong class="text-warning"><i class="fas fa-cloud-sun mr-1"></i>Buổi chiều</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Giờ vào chiều <span class="text-danger">*</span></label>
                                            <input type="time" name="vao_chieu" id="edit_vao_chieu" class="form-control text-center font-weight-bold" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Giờ ra chiều <span class="text-danger">*</span></label>
                                            <input type="time" name="ra_chieu" id="edit_ra_chieu" class="form-control text-center font-weight-bold" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label>Ghi chú</label>
                            <input type="text" name="ghi_chu" id="edit_ghi_chu" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button id="btnUpdate" class="btn btn-primary font-weight-bold" form="editForm">Cập nhật</button>
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
            // Mở modal thêm
            $("#pressAdd").click(function(){
                $("#addForm")[0].reset();
                $("#addModal").modal('show');
            });

            var table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                ajax: {
                    url: "{{ url('management/nhansu/calam/ajax/getlist') }}",
                    dataSrc: 'data'
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { 
                        data: 'ten_ca',
                        render: function(data) {
                            return '<strong>' + data + '</strong>';
                        }
                    },
                    { 
                        data: 'vao_sang',
                        render: function(data) {
                            return '<span class="badge badge-info text-md">' + (data ? data : '--:--') + '</span>';
                        }
                    },
                    { 
                        data: 'ra_sang',
                        render: function(data) {
                            return '<span class="badge badge-secondary text-md">' + (data ? data : '--:--') + '</span>';
                        }
                    },
                    { 
                        data: 'vao_chieu',
                        render: function(data) {
                            return '<span class="badge badge-warning text-md">' + (data ? data : '--:--') + '</span>';
                        }
                    },
                    { 
                        data: 'ra_chieu',
                        render: function(data) {
                            return '<span class="badge badge-secondary text-md">' + (data ? data : '--:--') + '</span>';
                        }
                    },
                    {
                        data: 'phan_ca_count',
                        render: function(data) {
                            if (data > 0) {
                                return '<span class="badge badge-success">' + data + ' nhân viên</span>';
                            }
                            return '<span class="badge badge-light">0</span>';
                        }
                    },
                    { 
                        data: 'ghi_chu',
                        render: function(data) {
                            return data ? data : '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-primary btn-xs btnEdit mr-1" data-id="' + row.id + '"><i class="fas fa-edit"></i> Sửa</button>' +
                                   '<button class="btn btn-danger btn-xs btnDelete" data-id="' + row.id + '" data-name="' + row.ten_ca + '"><i class="fas fa-trash"></i> Xóa</button>';
                        }
                    }
                ]
            });

            // Thêm mới
            $("#btnAdd").click(function(e){
                e.preventDefault();
                let tenCa = $("input[name=ten_ca]").val();
                if (!tenCa) {
                    alert("Vui lòng nhập tên ca làm việc!");
                    return;
                }

                $.ajax({
                    url: "{{ url('management/nhansu/calam/ajax/post') }}",
                    type: "POST",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                        if (response.code == 200) {
                            $("#addModal").modal('hide');
                            $("#addForm")[0].reset();
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi thêm ca làm việc!"
                        });
                    }
                });
            });

            // Mở modal sửa
            $(document).on('click', '.btnEdit', function(){
                let id = $(this).data('id');
                let allData = table.rows().data().toArray();
                let currentItem = allData.find(item => item.id == id);
                if (currentItem) {
                    $("#edit_id").val(currentItem.id);
                    $("#edit_ten_ca").val(currentItem.ten_ca);
                    $("#edit_vao_sang").val(currentItem.vao_sang);
                    $("#edit_ra_sang").val(currentItem.ra_sang);
                    $("#edit_vao_chieu").val(currentItem.vao_chieu);
                    $("#edit_ra_chieu").val(currentItem.ra_chieu);
                    $("#edit_ghi_chu").val(currentItem.ghi_chu);
                    $("#editModal").modal('show');
                }
            });

            // Lưu sửa
            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ url('management/nhansu/calam/ajax/update') }}",
                    type: "POST",
                    dataType: "json",
                    data: $("#editForm").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                        if (response.code == 200) {
                            $("#editModal").modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi cập nhật ca làm việc!"
                        });
                    }
                });
            });

            // Xóa ca làm
            $(document).on('click', '.btnDelete', function(){
                let id = $(this).data('id');
                let name = $(this).data('name');
                if (confirm('Bạn có chắc chắn muốn xóa ca làm [' + name + '] không?')) {
                    $.ajax({
                        url: "{{ url('management/nhansu/calam/ajax/delete') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
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
                                icon: 'error',
                                title: xhr.responseJSON ? xhr.responseJSON.message : "Lỗi xóa ca làm việc!"
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
