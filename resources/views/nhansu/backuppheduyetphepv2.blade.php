@extends('admin.index')
@section('title')
    Phê duyệt phép v2
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
                        <h1 class="m-0"><strong>Phê duyệt phép</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý xin phép - Phê duyệt phép</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <!-- <th>STT</th> -->
                            <th>Người xin</th>
                            <th>Tạo phiếu</th>
                            <th>Ngày xin</th>
                            <th>Thời gian</th>
                            <th>Loại phép</th>
                            <th>Lý do xin</th>
                            <th>Người duyệt</th>
                            <th>Trạng thái</th>
                            <th>Tác vụ</th>
                        </tr>
                    </thead>
                </table>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
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

        // Exe
        $(document).ready(function() {
           let table = $('#dataTable').DataTable({
                // // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // processing: true,
                // serverSide: true,
                ajax: "{{ url('management/nhansu/pheduyet/ajax/getlist') }}",
                order: [[8, 'asc']],
                // "columnDefs": [ {
                //     "searchable": false,
                //     "orderable": false,
                //     "targets": 0
                // } ],
                // "order": [
                //     [ 0, 'desc' ]
                // ],
                // lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            return "N " + row.nguoixin;                            
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            let theDate = row.created_at.toString().slice(0, 19).split('T')[0].split('-');
                            let newDate = theDate[2] + "/" +  theDate[1] + "/" + theDate[0];
                            return "<span class='text-secondary'>"+newDate+"</span>";

                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            return row.ngay.toString().padStart(2, '0') + "/" + row.thang.toString().padStart(2, '0') + "/" + row.nam;
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            switch(row.buoi.toString()) {
                                case "SANG": return "Buổi sáng"; break;
                                case "CHIEU": return "Buổi chiều"; break;
                                case "CANGAY": return "Cả ngày"; break;
                            }
                        } 
                    },
                    { "data": "loaiphep" },
                    { "data": "lyDo" },
                    { "data": "nguoiduyet" },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            let theDate = row.updated_at.toString().slice(0, 19).split('T')[0].split('-');
                            let newDate = theDate[2] + "/" +  theDate[1] + "/" + theDate[0];
                            if (row.user_duyet == false)
                                return "<span class='text-danger'>Chưa duyệt</span>";
                            else
                                return "<span class='text-success'>Đã duyệt ("+newDate+")</span>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.user_duyet == true)
                                return "<button id='xoa' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Xóa</button>";
                            else
                                return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Không duyệt</button>&nbsp;" + 
                            "<button id='duyet' data-id='"+row.id+"' class='btn btn-success btn-sm'>Duyệt</button>";
                        }
                    }
                ]
            });
            // table.on( 'order.dt search.dt', function () {
            //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            //         cell.innerHTML = i+1;
            //         table.cell(cell).invalidate('dom');
            //     } );
            // } ).draw();

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Xác nhận không duyệt phép này và xóa phiếu?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/xinphep/ajax/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            // Delete phép đã duyệt
            $(document).on('click','#xoa', function(){
                if(confirm('Xác nhận xóa phiếu đã duyệt?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/xinphep/ajax/deleteadmin/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            //Duyệt
            $(document).on('click','#duyet', function(){
                if(confirm('Xác nhận phê duyệt phép này?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/pheduyet/ajax/pheduyet/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        beforeSend: function () {
                            Toast.fire({
                                icon: 'info',
                                title: 'Đang xử lý vui lòng đợi........'
                            })
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Lỗi phê duyệt vui lòng liên hệ bộ phận kỹ thuật!"
                            })
                        }
                    });
                }
            });
        });
    </script>
@endsection
