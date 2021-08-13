@extends('admin.index')
@section('title')
    Loại công - Phụ tùng
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Loại công - Loại phụ tùng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Loại công - loại phụ tùng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-5 col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true"><button class="btn btn-info">Loại công</button></a>
                            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false"><button class="btn btn-info">Loại phụ tùng</button></a>
                        </div>
                    </div>
                    <div class="col-7 col-sm-9">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <form id="addForm" autocomplete="off">
                                                {{csrf_field()}}
                                                <div class="card-body">
                                                    <input type="hidden" name="idObject">
                                                    <div class="form-group">
                                                        <label>Loại công</label>
                                                        <input name="loaiCong" type="text" class="form-control" placeholder="Nhập loại công" autofocus="autofocus">
                                                    </div>
                                                    <div class="form-group">
                                                        <button id="btnAdd" class="btn btn-primary">Thêm mới</button>
                                                        <button id="btnUpdate" style="display: none;" disabled="disabled" class="btn btn-success">Cập nhật</button>
                                                        <button id="btnCancel" style="display: none;" disabled="disabled" class="btn btn-secondary">Hủy</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-7">
                                            <table id="dataTable" class="display" style="width:100%">
                                                <thead>
                                                <tr class="bg-cyan">
                                                    <th>TT</th>
                                                    <th>Loại công</th>
                                                    <th>Sửa</th>
                                                    <th>Xóa</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>TT</th>
                                                    <th>Loại công</th>
                                                    <th>Sửa</th>
                                                    <th>Xóa</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                            <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <form id="addForm2" autocomplete="off">
                                            {{csrf_field()}}
                                            <div class="card-body">
                                                <input type="hidden" name="idObject2">
                                                <div class="form-group">
                                                    <label>Loại phụ tùng</label>
                                                    <input name="loaiCong2" type="text" class="form-control" placeholder="Nhập loại phụ tùng" autofocus="autofocus">
                                                </div>
                                                <div class="form-group">
                                                    <button id="btnAdd2" class="btn btn-primary">Thêm mới</button>
                                                    <button id="btnUpdate2" style="display: none;" disabled="disabled" class="btn btn-success">Cập nhật</button>
                                                    <button id="btnCancel2" style="display: none;" disabled="disabled" class="btn btn-secondary">Hủy</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-7">
                                        <table id="dataTable2" class="display" style="width:100%">
                                            <thead>
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Loại phụ tùng</th>
                                                <th>Sửa</th>
                                                <th>Xóa</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>TT</th>
                                                <th>Loại phụ tùng</th>
                                                <th>Sửa</th>
                                                <th>Xóa</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        // show data
        $(document).ready(function() {

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                ajax: "{{ url('management/cong/get/list') }}",
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
                    { "data": "id" },
                    { "data": "name" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#edit' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;";
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
                    url: "{{url('management/cong/add/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: " Đã thêm " + response.noidung
                        })
                        table.ajax.reload();
                        $('input[name=loaiCong]').focus();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Lỗi nhập liệu; Lỗi xử lý dữ liệu"
                        })
                    }
                });
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/cong/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: "Đã xóa"
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

            // edit data
            $(document).on('click','#btnEdit', function(){
                $("#btnAdd").hide();
                $("#btnAdd").prop("disabled", true);
                $("#btnUpdate").show();
                $("#btnUpdate").prop("disabled", false);
                $("#btnCancel").show();
                $("#btnCancel").prop("disabled", false);
                $.ajax({
                    url: "{{url('management/cong/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response);
                        $("input[name=idObject]").val(response.data.id);
                        $("input[name=loaiCong]").val(response.data.name);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnCancel").click(function(){
                $("#btnAdd").show();
                $("#btnAdd").prop("disabled", false);
                $("#btnUpdate").hide();
                $("#btnUpdate").prop("disabled", true);
                $(this).hide();
                $(this).prop("disabled", true);
                $("#addForm")[0].reset();
                $("input[name=loaiCong]").focus();
            });

            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cong/update/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: "Đã cập nhật!"
                        })
                        $("#btnAdd").show();
                        $("#btnAdd").prop("disabled", false);
                        $("#btnUpdate").hide();
                        $("#btnUpdate").prop("disabled", true);
                        $("#btnCancel").hide();
                        $("#btnCancel").prop("disabled", true);
                        table.ajax.reload();
                        $('input[name=loaiCong]').focus();
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            //--------------------------------------------------- Phu Tung ----------------
            var table2 = $('#dataTable2').DataTable({
                // paging: false,    use to show all data
                ajax: "{{ url('management/phutung/get/list') }}",
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
                    { "data": "id" },
                    { "data": "name" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnEdit2' data-id='"+row.id+"' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='delete2' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;";
                        }
                    }
                ]
            });

            table2.on( 'order.dt search.dt', function () {
                table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table2.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Add data
            $("#btnAdd2").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/phutung/add/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addForm2").serialize(),
                    success: function(response) {
                        $("#addForm2")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: " Đã thêm " + response.noidung
                        })
                        table2.ajax.reload();
                        $('input[name=loaiCong2]').focus();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Lỗi nhập liệu; Lỗi xử lý dữ liệu"
                        })
                    }
                });
            });

            //Delete data
            $(document).on('click','#delete2', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/phutung/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: "Đã xóa"
                            })
                            table2.ajax.reload();
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

            // edit data
            $(document).on('click','#btnEdit2', function(){
                $("#btnAdd2").hide();
                $("#btnAdd2").prop("disabled", true);
                $("#btnUpdate2").show();
                $("#btnUpdate2").prop("disabled", false);
                $("#btnCancel2").show();
                $("#btnCancel2").prop("disabled", false);
                $.ajax({
                    url: "{{url('management/phutung/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response);
                        $("input[name=idObject2]").val(response.data.id);
                        $("input[name=loaiCong2]").val(response.data.name);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnCancel2").click(function(){
                $("#btnAdd2").show();
                $("#btnAdd2").prop("disabled", false);
                $("#btnUpdate2").hide();
                $("#btnUpdate2").prop("disabled", true);
                $(this).hide();
                $(this).prop("disabled", true);
                $("#addForm2")[0].reset();
                $("input[name=loaiCong2]").focus();
            });

            $("#btnUpdate2").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/phutung/update/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm2").serialize(),
                    success: function(response) {
                        $("#addForm2")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: "Đã cập nhật!"
                        })
                        $("#btnAdd2").show();
                        $("#btnAdd2").prop("disabled", false);
                        $("#btnUpdate2").hide();
                        $("#btnUpdate2").prop("disabled", true);
                        $("#btnCancel2").hide();
                        $("#btnCancel2").prop("disabled", true);
                        table2.ajax.reload();
                        $('input[name=loaiCong2]').focus();
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });
            //-------------------------------------------------------- end Phu Tung--------
        });
    </script>
@endsection
