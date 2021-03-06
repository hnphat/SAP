@extends('admin.index')
@section('title')
    Quản lý loại phép
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
                        <h1 class="m-0"><strong>Quản lý loại phép</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý loại phép</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên phép</th>
                            <th>Mã phép</th>
                            <th>Loại phép</th>
                            <th>Mô tả</th>
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
                        <h4 class="modal-title">Thêm phép</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            {{csrf_field()}}
                            <div class="form-group">
                               <label>Tên phép</label> 
                               <input type="text" name="tenPhep" class="form-control" placeholder="Nhập tên phép">
                            </div>
                            <div class="form-group">
                               <label>Mã phép</label> 
                               <input type="text" name="maPhep" class="form-control" placeholder="VD: QCC, PN,..">
                            </div>
                            <div class="form-group">
                               <label>Loại phép</label> 
                               <select name="loaiPhep" class="form-control">
                                   <option value="COLUONG">Có lương</option>
                                   <option value="KHONGLUONG">Không lương</option>
                                   <option value="PHEPNAM">Phép năm</option>
                                   <option value="QCC">Quên chấm công</option>
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Mô tả</label> 
                               <input type="text" name="moTa" class="form-control" placeholder="Nhập mô tả cho phép">
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
                ajax: "{{ url('management/nhansu/quanlyphep/ajax/getlist') }}",
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
                    { "data": "tenPhep" },
                    { "data": "maPhep" },
                    { "data": "loaiPhep" },
                    { "data": "moTa" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            // return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                            // "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
                            return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
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

            // Add data
            $("#btnAdd").click(function(e){   
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/nhansu/quanlyphep/ajax/post')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        table.ajax.reload();
                        $("#addModal").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: "error",
                            title: "Không thể thêm dữ liệu"
                        })
                    }
                });
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/quanlyphep/ajax/delete/')}}",
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

            // // edit data
            // $(document).on('click','#btnEdit', function(){
            //     $.ajax({
            //         url: "{{url('management/hanhchinh/ajax/getedit/')}}" + "/" + $(this).data('id'),
            //         type: "post",
            //         dataType: "json",
            //         data: {
            //             "_token": "{{csrf_token()}}",
            //             "id": $(this).data('id')
            //         },
            //         success: function(response) {
            //             $("input[name=eid]").val(response.data.id);
            //             $("input[name=etieuDe]").val(response.data.tieuDe);
            //             $("select[name=eloaiFile]").val(response.data.type);
            //             $("input[name=emoTa]").val(response.data.moTa);
            //             $("input[name=eghiChu]").val(response.data.ghiChu);
            //             $("select[name=eallow]").val(response.data.allow);
            //                 Toast.fire({
            //                     icon: response.type,
            //                     title: response.message
            //                 })
            //         },
            //         error: function(){
            //             Toast.fire({
            //                 icon: 'warning',
            //                 title: "Error 500!"
            //             })
            //         }
            //     });
            // });

            // $("#btnUpdate").click(function(e){
            //     e.preventDefault();
            //     $.ajax({
            //         url: "{{url('management/hanhchinh/ajax/update/')}}",
            //         type: "post",
            //         dataType: "json",
            //         data: $("#editForm").serialize(),
            //         success: function(response) {
            //             $("#editForm")[0].reset();
            //             Toast.fire({
            //                     icon: response.type,
            //                     title: response.message
            //             })
            //             table.ajax.reload();
            //             $("#editModal").modal('hide');
            //         },
            //         error: function(){
            //             Toast.fire({
            //                 icon: 'warning',
            //                 title: "Error 500!"
            //             })
            //         }
            //     });
            // });
        });
    </script>
@endsection
