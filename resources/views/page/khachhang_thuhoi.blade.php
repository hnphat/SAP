@extends('admin.index')
@section('title')
    Khách hàng Thu hồi
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .badge-reason {
            font-size: 0.9rem;
            padding: 6px 10px;
            white-space: normal;
            display: inline-block;
            text-align: left;
            line-height: 1.4;
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
                        <h1 class="m-0 text-danger"><strong><i class="fas fa-user-clock mr-2"></i>Bể lưu trữ: Khách hàng Thu hồi</strong></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng Thu hồi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title text-bold"><i class="fas fa-filter mr-1"></i> Bộ lọc & Tìm kiếm</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Thu hồi từ ngày</label>
                                        <input type="date" name="chonNgayOne" class="form-control" value="{{ date('Y-m-01') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Đến ngày</label>
                                        <input type="date" name="chonNgayTwo" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center mt-3">
                                    <button type="button" id="btnFilter" class="btn btn-primary mr-2"><i class="fas fa-search mr-1"></i> Lọc dữ liệu</button>
                                    <button type="button" id="btnReloadAll" class="btn btn-secondary mr-2"><i class="fas fa-sync-alt mr-1"></i> Xem tất cả</button>
                                    <span class="badge badge-info p-2"><i class="fas fa-info-circle mr-1"></i> Tự động quét khách quá hạn CS 1h - 24h - 72h</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list mr-1"></i> Danh sách khách hàng bị thu hồi</h3>
                    </div>
                    <div class="card-body">
                        <!-- Modal Chuyển khách -->
                        <div class="modal fade" id="movingModal">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-exchange-alt mr-1"></i> Chuyển khách từ Bể Thu hồi</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="movingForm" autocomplete="off">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <input type="hidden" name="idguestmove">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-1"></i> <strong>Lưu ý:</strong> Khi chuyển cho sale mới, đồng hồ chăm sóc (CS 1h, 24h, 72h) sẽ được tính lại từ đầu cho sale nhận khách.
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Chọn nhân viên nhận khách:</label>
                                                <select name="nhanVienRecieve" class="form-control"> 
                                                    @foreach($groupsale as $row)                       
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option> 
                                                    @endforeach   
                                                </select>
                                            </div>
                                        </div>                                                                        
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                            <button id="btnUpdateMove" class="btn btn-success"><i class="fas fa-check mr-1"></i> Xác nhận chuyển</button>
                                        </div>                                                           
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal -->

                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped table-hover" style="width:100%">
                                <thead>
                                <tr class="bg-danger text-white text-center">
                                    <th>Ngày nhập</th>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Nguồn</th>
                                    <th>Loại</th>
                                    <th>Điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Xe quan tâm</th>
                                    <th>Màu sắc</th>
                                    <th>Người nhập</th>
                                    <th style="min-width: 250px;">Lý do thu hồi</th>
                                    <th style="width: 110px;">Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>                                    
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
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
            timer: 3500
        });

        $(document).ready(function() {
            let ajaxUrl = "{{ url('management/guest/thuhoi/getdata') }}";

            var table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: ajaxUrl,
                    data: function(d) {
                        d.from = $("input[name=chonNgayOne]").val();
                        d.to = $("input[name=chonNgayTwo]").val();
                    }
                },
                order: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            if (!row.created_at) return "";
                            let arr = row.created_at.split("T")[0].split("-");
                            return arr[2] + "-" + arr[1] + "-" + arr[0];
                        } 
                    },
                    { "data": "idmaster" },
                    { "data": "name" },
                    { "data": "nguon" },
                    { "data": "type" },
                    { "data": "phone" },
                    { "data": "address" },
                    { "data": "xeQuanTam" },
                    { "data": "mauSac" },
                    { "data": "sale" },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            let reason = row.ly_do_thu_hoi ? row.ly_do_thu_hoi : "Chưa chăm sóc đúng hạn";
                            let thoiGian = "";
                            if (row.thoi_gian_thu_hoi) {
                                thoiGian = "<br/><small class='text-muted'><i class='far fa-clock mr-1'></i>" + row.thoi_gian_thu_hoi + "</small>";
                            }
                            return "<span class='badge badge-danger badge-reason'><i class='fas fa-exclamation-triangle mr-1'></i>" + reason + "</span>" + thoiGian;
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnMoving' data-id='" + row.idmaster + "' data-name='" + row.name + "' data-toggle='modal' data-target='#movingModal' class='btn btn-success btn-sm btn-block'><i class='fas fa-exchange-alt mr-1'></i> Chuyển</button>";
                        }
                    }
                ],
                language: {
                    emptyTable: "Hiện tại không có khách hàng nào bị thu hồi",
                    info: "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ khách hàng",
                    infoEmpty: "Hiển thị 0 đến 0 trong 0 khách hàng",
                    lengthMenu: "Hiển thị _MENU_ dòng",
                    search: "Tìm kiếm:",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    }
                }
            });

            // Lọc dữ liệu theo ngày
            $("#btnFilter").click(function() {
                table.ajax.reload();
            });

            // Xem tất cả không giới hạn ngày
            $("#btnReloadAll").click(function() {
                $("input[name=chonNgayOne]").val("");
                $("input[name=chonNgayTwo]").val("");
                table.ajax.reload();
            });

            // Mở modal chuyển khách
            $(document).on('click', '#btnMoving', function(){
                let id = $(this).data('id');
                let name = $(this).data('name');
                $("input[name=idguestmove]").val(id);
            });

            // Xác nhận chuyển khách
            $("#btnUpdateMove").click(function(e){
                e.preventDefault();
                let idGuest = $("input[name=idguestmove]").val();
                let idSale = $("select[name=nhanVienRecieve]").val();

                if (!idGuest || !idSale) {
                    Toast.fire({
                        icon: 'warning',
                        title: "Vui lòng chọn nhân viên sale nhận khách!"
                    });
                    return;
                }

                $(this).prop('disabled', true).html("<i class='fas fa-spinner fa-spin mr-1'></i> Đang xử lý...");

                $.ajax({
                    url: "{{ url('management/guest/thuhoi/chuyenkhach') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'idguest': idGuest,
                        'idsale': idSale
                    },
                    success: function(response) {
                        $("#btnUpdateMove").prop('disabled', false).html("<i class='fas fa-check mr-1'></i> Xác nhận chuyển");
                        $("#movingForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                        table.ajax.reload();
                        $("#movingModal").modal('hide');
                    },
                    error: function(xhr){
                        $("#btnUpdateMove").prop('disabled', false).html("<i class='fas fa-check mr-1'></i> Xác nhận chuyển");
                        let msg = "Lỗi 500 khi xử lý trên máy chủ!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    }
                });
            });
        });
    </script>
@endsection
