@extends('admin.index')
@section('title')
    Model xe
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
                        <h1 class="m-0"><strong>MODEL XE</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Model xe</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="row container">
                <div class="col-sm-3">
                    <form id="addForm" autocomplete="off">
                        {{csrf_field()}}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="tenXe">Tên xe</label>
                                <input type="text" class="form-control" placeholder="Tên dòng xe" name="tenXe">
                            </div>
                            <div class="form-group">
                                <button id="btnAdd" class="btn btn-primary">Thêm mới</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-5">
                    <table id="dataTable" class="display" style="width:100%">
                        <thead>
                        <tr class="bg-cyan">
                            <th>Dòng xe</th>
                            <th>Sửa</th>
                            <th>Xóa</th>
                            <th>Mở rộng</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Dòng xe</th>
                            <th>Sửa</th>
                            <th>Xóa</th>
                            <th>Mở rộng</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-sm-4" id="showMore">
                </div>
                <!-- Medal Edit -->
                <div class="modal fade" id="edit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- general form elements -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">CHỈNH SỬA DÒNG XE</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="editForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="idMasterXe">
                                            <div class="form-group">
                                                <label for="tenXeE">Tên dòng xe</label>
                                                <input type="text" class="form-control" id="tenXeE" placeholder="Tên dòng xe" name="tenXeE">
                                            </div>
                                            <div class="form-group">
                                                <button id="btnUpdate" class="btn btn-primary">Cập nhật</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Medal Add Plus -->
                <div class="modal fade" id="addPlusModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- general form elements -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">THÊM LOẠI XE</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="addPlusForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="idAddPlus">
                                            <div class="form-group">
                                                <label for="tenDongXe">Dòng xe</label>
                                                <input type="text" class="form-control" id="tenDongXe" disabled="disabled" name="tenDongXe">
                                            </div>
                                            <div class="form-group">
                                                <label for="loaiXe">Tên loại xe</label>
                                                <input type="text" class="form-control" id="loaiXe" placeholder="Tên loại xe" name="loaiXe">
                                            </div>
                                            <div class="form-group">
                                                <button id="btnAddPlus" class="btn btn-primary">Thêm</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Medal Edit Add Plus-->
                <div class="modal fade" id="editPlusModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- general form elements -->
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">CHỈNH SỬA LOẠI XE</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="editAddPlusForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="idMaster"/>
                                            <input type="hidden" name="idEditAddPlus">
                                            <div class="form-group">
                                                <label for="_tenLoaiXe">Tên loại xe</label>
                                                <input type="text" class="form-control" id="_tenLoaiXe" placeholder="Tên loại xe" name="_tenLoaiXe">
                                            </div>
                                            <div class="form-group">
                                                <button id="btnUpdateAddPlus" class="btn btn-primary">Cập nhật</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
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
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            //Display data onload
            var table = $('#dataTable').DataTable({
                ajax: "{{ url('management/typecar/getlist/') }}",
                columns: [
                    { "data": "name"},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button data-id='"+row.id+"' class='btn btn-success btn-sm' data-toggle='modal' data-target='#edit' id='btnEdit'><span class='far fa-edit'></span></button>&nbsp;";

                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button data-id='"+row.id+"' class='btn btn-danger btn-sm' id='delete'><span class='fas fa-times-circle'></span></button>&nbsp;";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button data-id='"+row.id+"' class='btn btn-info btn-sm' id='more'>Chi tiết</button>&nbsp;" +
                                "<button data-id='"+row.id+"' class='btn btn-success btn-sm' id='addplus' data-toggle='modal' data-target='#addPlusModal'><span class='fas fa-plus-circle'></span></button>&nbsp;";
                        }
                    }
                ]
            });

            //Insert data
            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/typecar/add/')}}",
                    type: "POST",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: "Đã thêm!"
                        })
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Trùng tên hoặc chưa nhập liệu"
                        })
                        $("#addForm")[0].reset();
                    }
                });
            });

            // Edit data
            $(document).on('click','#btnEdit', function (){
               $.ajax({
                   url: "{{url('management/typecar/edit/')}}",
                   type: "post",
                   dataType: "json",
                   data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                   },
                   success: function(response) {
                       console.log(response.data);
                       $("input[name=tenXeE]").val(response.data.name);
                       $("input[name=idMasterXe]").val(response.data.id);
                   }
               });
            });

            // Update data
            $('#btnUpdate').click(function(e){
                e.preventDefault();
                if(confirm('Bạn có chắc muốn thay đổi')) {
                    $.ajax({
                       url: '{{url('management/typecar/update')}}',
                       type: 'post',
                       dataType: 'json',
                       data: $('#editForm').serialize(),
                       success: function(response) {
                           $('#editForm')[0].reset();
                           $('#edit').modal('hide');
                           Toast.fire({
                               icon: 'success',
                               title: "Đã cập nhật!"
                           })
                           table.ajax.reload();
                       },
                       error: function() {
                           Toast.fire({
                               icon: 'warning',
                               title: "Lỗi 500 Cập nhật không thành công!"
                           })
                       }
                    });
                }
            });

            //Delete data
            $(document).on("click","#delete",function(){
               if (confirm("Bạn có chắc muốn xóa?")) {
                   $.ajax({
                      url: "{{url('management/typecar/delete')}}",
                      type: "post",
                      dataType: "json",
                      data: {
                          "_token": "{{csrf_token()}}",
                          "id": $(this).data('id')
                      },
                      success: function(response){
                          console.log(response);
                          Toast.fire({
                              icon: 'success',
                              title: "Đã xóa!"
                          })
                          table.ajax.reload();
                      },
                      error: function() {
                          Toast.fire({
                              icon: 'warning',
                              title: "Dữ liệu đang được sử dụng ở nơi khác, không thể xóa!"
                          })
                      }
                   });
               }
            });

            // show plus data
            $(document).on('click','#more', function(){
                $.get("management/typecar/more/" + $(this).data('id'), function(data){
                    $("#showMore").html(data);
                });
            });

            // delete plus more
            $(document).on('click', '#deletePlus', function(){
                if (confirm("Bạn có chắc muốn xóa?")) {
                    $.ajax({
                        url: "{{url('management/typecar/more/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "master": $(this).data('idmaster')
                        },
                        success: function(response){
                            console.log(response);
                            Toast.fire({
                                icon: 'success',
                                title: "Đã xóa!"
                            })
                            $.get("management/typecar/more/" + response.id, function(data){
                                $("#showMore").html(data);
                            });
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Dữ liệu đang được sử dụng ở nơi khác, không thể xóa!"
                            })
                        }
                    });
                }
            });

            // Add plus show data
            $(document).on('click','#addplus', function(){
                $.ajax({
                    url: "{{url('management/typecar/more/add/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'id': $(this).data('id')
                    },
                    success: function(response){
                        console.log(response.data);
                        $("input[name=idAddPlus]").val(response.data.id);
                        $("input[name=tenDongXe]").val(response.data.name);
                    }
                });
            });

            // Add plus data
            $("#btnAddPlus").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/typecar/more/addplus/')}}",
                    type: "POST",
                    dataType: "json",
                    data: $("#addPlusForm").serialize(),
                    success: function(response) {
                        console.log(response);
                        Toast.fire({
                            icon: 'success',
                            title: "Đã thêm loại xe! "
                        })
                        $("#addPlusForm")[0].reset();
                        $("#addPlusModal").modal('hide');
                        $.get("management/typecar/more/" + response.id, function(data){
                            $("#showMore").html(data);
                        });
                    }
                });
            });

            // edit add plus show form
            $(document).on('click','#showEditPlus', function(){
                $.ajax({
                    url: "{{url('management/typecar/more/editshowplus/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'id': $(this).data('id')
                    },
                    success: function(response){
                        $("input[name=idEditAddPlus]").val(response.data.id);
                        $("input[name=idMaster]").val(response.data.id_type_car);
                        $("input[name=_tenLoaiXe]").val(response.data.name);
                    }
                });
            });

            // update add plus data
            $("#btnUpdateAddPlus").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/typecar/more/editaddplus/')}}",
                    type: "POST",
                    dataType: "json",
                    data: $("#editAddPlusForm").serialize(),
                    success: function(response) {
                        console.log(response);
                        Toast.fire({
                            icon: 'success',
                            title: "Đã sữa loại xe! "
                        })
                        $("#editAddPlusForm")[0].reset();
                        $("#editPlusModal").modal('hide');
                        $.get("management/typecar/more/" + response.id, function(data){
                            $("#showMore").html(data);
                        });
                    }
                });
            });
        });
    </script>
@endsection