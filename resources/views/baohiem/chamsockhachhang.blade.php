@extends('admin.index')
@section('title')
    CHĂM SÓC KHÁCH HÀNG
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
                        <h1 class="m-0"><strong>CHĂM SÓC KHÁCH HÀNG BẢO HIỂM</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Bảo hiểm</li>
                            <li class="breadcrumb-item active">CHĂM SÓC KHÁCH HÀNG BẢO HIỂM</li>
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
                                       DANH SÁCH KHÁCH HÀNG CẦN CHĂM SÓC
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">                
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
                                                <th>Ngày chăm sóc</th>
                                                <th>Tên khách hàng</th>
                                                <th>Điện thoại</th>
                                                <th>Phân loại</th>
                                                <th>Bán tiếp BH?</th>
                                                <th>Loại bảo hiểm</th>
                                                <th>Thông tin xe</th>
                                                <th>Đơn vị bảo hiểm</th>
                                                <th>Ngày mua</th>
                                                <th>Ngày hết hạn</th>
                                                <th>Kết quả chăm sóc</th>
                                                <th>Người chăm sóc</th>
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
            if (isNaN(date.getTime())) return dateString;
            let d = String(date.getDate()).padStart(2, '0');
            let m = String(date.getMonth() + 1).padStart(2, '0');
            let y = date.getFullYear();
            return `${d}/${m}/${y}`;
        }

        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'lfrtip',
                ajax: {
                    url: "{{ url('management/baohiem/chamsockhachhang/list') }}",
                    data: function(d) {
                        d.from = $("#from_date").val();
                        d.to = $("#to_date").val();
                    }
                },
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": [0]
                } ],
                "order": [
                    [ 1, 'desc' ]
                ],
                lengthMenu: [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { 
                        "data": "ngayChamSoc",
                        render: function(data) {
                            return formatDate(data);
                        }
                    },
                    { "data": "tenKhachHang" },
                    { 
                        "data": "dienThoai",
                        render: function(data) {
                            if (!data) return '';
                            return `<a href="tel:${data}" class="btn btn-sm btn-success d-inline-block" style="border-radius: 20px;"><i class="fas fa-phone-alt mr-1"></i> ${data}</a>`;
                        }
                    },
                    { 
                        "data": "phanLoai",
                        render: function(data) {
                            if (data === 'DAMUA') return `<span class="badge badge-success px-2 py-1">Đã mua</span>`;
                            if (data === 'CHUAMUA') return `<span class="badge badge-warning px-2 py-1">Chưa mua</span>`;
                            if (data === 'KHONGMUA') return `<span class="badge badge-danger px-2 py-1">Không mua</span>`;
                            return data;
                        }
                    },
                    { 
                        "data": "coTheBanBaoHiem",
                        render: function(data) {
                            return data ? `<span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i> Có</span>` : `<span class="badge badge-secondary px-2 py-1"><i class="fas fa-times"></i> Không</span>`;
                        }
                    },
                    { "data": "loaiBaoHiem", defaultContent: '' },
                    { "data": "thongTinXe", defaultContent: '' },
                    { "data": "donViBaoHiem", defaultContent: '' },
                    { 
                        "data": "ngayMua",
                        render: function(data) {
                            return formatDate(data);
                        },
                        defaultContent: ''
                    },
                    { 
                        "data": "ngayHetHan",
                        render: function(data) {
                            return formatDate(data);
                        },
                        defaultContent: ''
                    },
                    { "data": "ketQuaChamSoc" },
                    { "data": "creator", defaultContent: '' }
                ],
                language: {
                    search: "Tìm kiếm nhanh:",
                    lengthMenu: "Hiển thị _MENU_ dòng",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ khách hàng",
                    infoEmpty: "Không có dữ liệu",
                    infoFiltered: "(lọc từ _MAX_ khách hàng)",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    emptyTable: "Không có dữ liệu khách hàng chăm sóc"
                }
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
        });
    </script>
@endsection
