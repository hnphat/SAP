
@extends('admin.index')
@section('title')
    Đang thực hiện
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
                        <h1 class="m-0"><strong>Đang thực hiện</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Công việc</li>
                            <li class="breadcrumb-item active">Đang thực hiện</li>
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
            <div class="card card-info">
                <div class="card-header">
                    <h5>Đang thực hiện</h5>
                </div>
                <div class="card-body">
                    <table id="dataTable" class="display" style="width:100%">
                        <thead>
                        <tr class="bg-gray">
                            <th>STT</th>
                            <th>Báo cáo</th>
                            <th>Ngày</th>
                            <th>Tên công việc</th>
                            <th>Tiến độ</th>
                            <th>Deadline</th>
                            <th>Kết quả</th>
                            <th>Ghi chú</th>
                            <th>Loại</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Medal Edit Work-->
            <div class="modal fade" id="editWork">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">SỬA CÔNG VIỆC</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <form id="editWorkForm" autocomplete="off">
                                    {{csrf_field()}}
                                    <input type="hidden" name="idReport4">
                                    <div class="card-body">
                                        <input type="hidden" name="_id">
                                        <div class="form-group">
                                            <label>Tên công việc</label>
                                            <input name="_tenCongViec" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Tiến độ</label>
                                            <input value="0" oninput="this.nextElementSibling.value = this.value" name="_tienDo" placeholder="% hoàn thành" min="0" max="100" type="range" class="form-control">
                                            <output></output>%
                                        </div>
                                        <div class="form-group">
                                            <label>Kết quả</label>
                                            <input name="_ketQua" placeholder="Kết quả công việc" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Ghi chú</label>
                                            <input name="_ghiChu" placeholder="Nếu có" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Phản hồi từ người giao việc (Nếu có)</label>
                                            <input name="_phanHoi" readonly="readonly" type="text" class="form-control">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                            <button id="btnEditWork" class="btn btn-primary" form="addWorkForm">Lưu</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
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

        function revertDate(str) {
            let myArr = str.split("-");
            return myArr[2] + "-" + myArr[1] + "-" + myArr[0];
        }

        $(document).ready(function() {
            //Display data onload
            var table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/work/working/list/') }}",
                "columnDefs": [
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    },{
                        "targets": 1,
                        "className": "text-center",
                    },{
                        "targets": 8,
                        "className": "text-center",
                    }
                ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.isReport == true)
                                return "<input id='_check' type='checkbox' checked='checked' data-id="+row.id+">";
                            else
                                return "<input id='_check' type='checkbox' data-id="+row.id+">";
                        }
                    },
                    { "data": "ngayTao"},
                    { "data": "tenCongViec"},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tienDo < 100)
                                return "<span class='text-danger'><strong>"+row.tienDo+"%</strong></span>";
                            else
                                return "<span class='text-info'><strong>"+row.tienDo+"%</strong></span>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return revertDate(row.ngayStart)+"<br/>"+revertDate(row.ngayEnd)+"";
                        }
                    },
                    { "data": "ketQua"},
                    { "data": "ghiChu"},
                    {  "data": null,
                        render: function(data, type, row) {
                            if (row.isPersonal == true)
                                return "<span class='text-info'>Cá nhân";
                            else
                                return "<span class='text-pink'>Được giao</span><br/><i>("+row.surname+")</i>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.isPersonal == true)
                                return "<button id='delWork' data-id="+row.id+" class='btn btn-danger btn-sm'>Xóa</button>&nbsp;<button id='showEdit' data-id="+row.id+" class='btn btn-success btn-sm' data-toggle='modal' data-target='#editWork'>Cập nhật</button>";
                            else
                                return "<button id='showEdit' data-id="+row.id+" class='btn btn-success btn-sm' data-toggle='modal' data-target='#editWork'>Cập nhật</button>";
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

            // Sửa công việc
            $('#btnEditWork').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/work/editworking')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#editWorkForm").serialize(),
                    success: function(response) {
                        $("#editWorkForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $("#editWork").modal('hide');
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: " Lỗi! Không thể chỉnh sửa công việc!"
                        })
                    }
                });
            });

            $(document).on("click","#delWork", function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/work/delwork/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: " " + response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: "Lỗi: Không thể xóa!"
                            })
                        }
                    });
                }
            });

            $(document).on("change","#_check", function(){
                $.ajax({
                    url: "{{url('management/work/check/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi: Không thể xóa!"
                        })
                    }
                });
            });

            $(document).on("click","#showEdit", function(){
                $.ajax({
                    url: "{{url('management/work/getworkedit/')}}" + "/" + $(this).data('id'),
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $('input[name=_id]').val(response.data.id);
                        $('input[name=_tienDo]').val(response.data.tienDo);
                        $('input[name=_ketQua]').val(response.data.ketQua);
                        $('input[name=_ghiChu]').val(response.data.ghiChu);
                        if (response.data.isPersonal == true) {
                            $('input[name=_tenCongViec]').val(response.data.tenCongViec);
                            $('input[name=_tenCongViec]').prop('readonly', false);
                         }   
                         else {
                            $('input[name=_tenCongViec]').val(response.data.tenCongViec);
                            $('input[name=_tenCongViec]').prop('readonly', true);
                         }
                          $('input[name=_phanHoi]').val(response.data.replyWork);
                            
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi: Không tải được công việc!"
                        })
                    }
                });
            });
        });
    </script>
@endsection
