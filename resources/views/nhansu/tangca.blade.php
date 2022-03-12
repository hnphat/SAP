@extends('admin.index')
@section('title')
    Phê duyệt tăng ca
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
                        <h1 class="m-0"><strong>Phê duyệt tăng ca</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý xin phép - Phê duyệt tăng ca</li>
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
                            <th>STT</th>
                            <th>Người xin</th>
                            <th>Tạo phiếu</th>
                            <th>Ngày xin</th>
                            <th>Lý do xin</th>
                            <th>Người duyệt</th>
                            <th>Trạng thái</th>
                            <th>Phê duyệt</th>
                            <th>Giờ vào</th>
                            <th>Giờ ra</th>
                            <th>Hệ số</th>
                            <th>Tác vụ</th>
                        </tr>
                    </thead>
                </table>
        </div>
        <!-- /.content -->
    </div>
     <!-- Medal Add -->
     <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cập nhật giờ công tăng ca</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            {{csrf_field()}}         
                            <input type="hidden" name="idTangCa">   
                            <div class="form-group">
                               <label>Giờ vào</label> 
                               <input type="text" name="gioVao" class="form-control" placeholder="VD: 18:00">
                            </div>
                            <div class="form-group">
                               <label>Giờ ra</label> 
                               <input type="text" name="gioRa" class="form-control" placeholder="VD: 21:00">
                            </div>
                            <div class="form-group">
                               <label>Hệ số lương</label> 
                               <select name="heSo" class="form-control">
                                    <option value="1">Bình thường (x 1.0)</option>
                                    <option value="1.5">Ngoài giờ (x 1.5)</option>
                                    <option value="2">Chủ nhật (x 2.0)</option>
                                    <option value="3">Lễ, Tết (x 3.0)</option>
                               </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnAdd" class="btn btn-primary" form="addForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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

        // Exe
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/nhansu/pheduyettangca/ajax/getlist') }}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "nguoixin" },
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
                                return "<button id='delAdmin' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Xóa</button>";
                            else
                                return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Không</button>&nbsp;&nbsp;" + 
                            "<button id='duyet' data-id='"+row.id+"' class='btn btn-success btn-sm'>Duyệt</button>";
                        }
                    },
                    { "data": "time1" },
                    { "data": "time2" },
                    { "data": "heSo" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.user_duyet == false)
                                return "";
                            else
                                return "<button id='capNhat' data-from='"+row.time1+"' data-to='"+row.time2+"'  data-id='"+row.id+"' data-toggle='modal' data-target='#addModal' class='btn btn-info btn-sm'>Cập nhật</button>";
                        }
                    },
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Xác nhận không duyệt và xóa phiếu?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/pheduyettangca/ajax/delete/')}}",
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

            //Delete data admin
            $(document).on('click','#delAdmin', function(){
                if(confirm('Xóa tăng ca này?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/pheduyettangca/ajax/deleteadmin/')}}",
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
                if(confirm('Xác nhận phê duyệt?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/pheduyettangca/ajax/pheduyet/')}}",
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

            // Cập nhật tăng ca
            $(document).on('click','#capNhat', function(){
                $("input[name=idTangCa]").val($(this).data('id'));
                $("input[name=gioVao]").val($(this).data('from'));
                $("input[name=gioRa]").val($(this).data('to'));
           });

           $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/nhansu/capnhattangca/ajax/capnhat')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response){
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addModal").modal('hide');
                        table.ajax.reload();
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể cập nhật giờ công tăng ca"
                        })
                    }
                });
           });
        });
    </script>
@endsection
