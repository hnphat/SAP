@extends('admin.index')
@section('title')
    Khách hàng bảo hiểm
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
                        <h1 class="m-0"><strong>Khách hàng bảo hiểm</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Bảo hiểm</li>
                            <li class="breadcrumb-item active">Khách hàng bảo hiểm</li>
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
                                            Khách hàng bảo hiểm
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span> Thêm mới</button><br/><br/>
                                        
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
                                                    <th>TT</th>
                                                    <th>Họ Tên</th>
                                                    <th>Điện thoại</th>
                                                    <th>MST</th>
                                                    <th>Địa chỉ</th>
                                                    <th>Biển số</th>
                                                    <th>Số khung</th>
                                                    <th>Số máy</th>
                                                    <th>Thông tin xe</th>
                                                    <th>Tài xế</th>
                                                    <th>SĐT Tài xế</th>
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

    <!-- Modal Add -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">THÊM KHÁCH HÀNG BẢO HIỂM</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" name="hoTen" class="form-control" required placeholder="Họ và Tên">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="dienThoai" class="form-control" required placeholder="Số điện thoại" pattern="[0-9]{10}" title="Số điện thoại phải gồm 10 chữ số (Ví dụ: 0918222333)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mã số thuế</label>
                                <input type="text" name="mst" class="form-control" placeholder="Mã số thuế">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" name="diaChi" class="form-control" required placeholder="Địa chỉ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Biển số</label>
                                <input type="text" name="bienSo" class="form-control" placeholder="Biển số xe">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Số khung</label>
                                <input type="text" name="soKhung" class="form-control" placeholder="Số khung">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Số máy</label>
                                <input type="text" name="soMay" class="form-control" placeholder="Số máy">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Thông tin xe</label>
                                <select name="thongTinXe" class="form-control">
                                    <option value="">-- Chọn xe --</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->name }}">{{ $car->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tài xế</label>
                                <input type="text" name="taiXe" class="form-control" placeholder="Tên tài xế">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Điện thoại tài xế</label>
                                <input type="text" name="dienThoaiTaiXe" class="form-control" placeholder="Số điện thoại tài xế">
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
                    <h5 class="modal-title">SỬA KHÁCH HÀNG BẢO HIỂM</h5>
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
                                <label>Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" name="ehoTen" class="form-control" required placeholder="Họ và Tên">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="edienThoai" class="form-control" required placeholder="Số điện thoại" pattern="[0-9]{10}" title="Số điện thoại phải gồm 10 chữ số (Ví dụ: 0918222333)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mã số thuế</label>
                                <input type="text" name="emst" class="form-control" placeholder="Mã số thuế">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" name="ediaChi" class="form-control" required placeholder="Địa chỉ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Biển số</label>
                                <input type="text" name="ebienSo" class="form-control" placeholder="Biển số xe">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Số khung</label>
                                <input type="text" name="esoKhung" class="form-control" placeholder="Số khung">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Số máy</label>
                                <input type="text" name="esoMay" class="form-control" placeholder="Số máy">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Thông tin xe</label>
                                <select name="ethongTinXe" class="form-control">
                                    <option value="">-- Chọn xe --</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->name }}">{{ $car->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tài xế</label>
                                <input type="text" name="etaiXe" class="form-control" placeholder="Tên tài xế">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Điện thoại tài xế</label>
                                <input type="text" name="edienThoaiTaiXe" class="form-control" placeholder="Số điện thoại tài xế">
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

        $(document).ready(function() {
            let from = $("#from_date").val();
            let to = $("#to_date").val();

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
                    url: "{{ url('management/baohiem/khachhangbaohiem/list') }}",
                    data: function(d) {
                        d.from = $("#from_date").val();
                        d.to = $("#to_date").val();
                    }
                },
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu: [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "hoTen" },
                    { "data": "dienThoai" },
                    { "data": "mst" },
                    { "data": "diaChi" },
                    { "data": "bienSo" },
                    { "data": "soKhung" },
                    { "data": "soMay" },
                    { "data": "thongTinXe" },
                    { "data": "taiXe" },
                    { "data": "dienThoaiTaiXe" },
                    { "data": "creator" },
                    { 
                        "data": "created_at",
                        render: function(data, type, row) {
                            if (!data) return '';
                            let date = new Date(data);
                            let d = String(date.getDate()).padStart(2, '0');
                            let m = String(date.getMonth() + 1).padStart(2, '0');
                            let y = date.getFullYear();
                            return `${d}/${m}/${y}`;
                        }
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
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Search trigger
            $("#btnSearch").click(function(){
                table.ajax.reload();
            });

            // Form Add submit
            $("#addForm").submit(function(e) {
                e.preventDefault();
                let phone = $("#addForm input[name=dienThoai]").val();
                if (!/^[0-9]{10}$/.test(phone)) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Số điện thoại không hợp lệ! Phải gồm 10 chữ số (Ví dụ: 0918222333).'
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
                    url: "{{ url('management/baohiem/khachhangbaohiem/edit/show') }}",
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
                            $("#editForm input[name=ehoTen]").val(data.hoTen);
                            $("#editForm input[name=edienThoai]").val(data.dienThoai);
                            $("#editForm input[name=emst]").val(data.mst);
                            $("#editForm input[name=ediaChi]").val(data.diaChi);
                            $("#editForm input[name=ebienSo]").val(data.bienSo);
                            $("#editForm input[name=esoKhung]").val(data.soKhung);
                            $("#editForm input[name=esoMay]").val(data.soMay);
                            $("#editForm select[name=ethongTinXe]").val(data.thongTinXe);
                            $("#editForm input[name=etaiXe]").val(data.taiXe);
                            $("#editForm input[name=edienThoaiTaiXe]").val(data.dienThoaiTaiXe);
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
                            title: 'Không thể tải dữ liệu khách hàng!'
                        });
                    }
                });
            });

            // Form Edit submit
            $("#editForm").submit(function(e) {
                e.preventDefault();
                let phone = $("#editForm input[name=edienThoai]").val();
                if (!/^[0-9]{10}$/.test(phone)) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Số điện thoại không hợp lệ! Phải gồm 10 chữ số (Ví dụ: 0918222333).'
                    });
                    return;
                }
                $.ajax({
                    url: "{{ url('management/baohiem/khachhangbaohiem/update') }}",
                    type: "POST",
                    dataType: "json",
                    data: $(this).serialize(),
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

            // Delete guest
            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                if (confirm("Bạn có chắc muốn xóa khách hàng bảo hiểm này?")) {
                    $.ajax({
                        url: "{{ url('management/baohiem/khachhangbaohiem/delete') }}",
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
                                title: 'Không thể xóa khách hàng bảo hiểm lúc này!'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
