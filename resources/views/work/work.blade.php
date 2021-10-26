
@extends('admin.index')
@section('title')
   Công việc
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
                        <h1 class="m-0"><strong>CÔNG VIỆC</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Báo cáo</li>
                            <li class="breadcrumb-item active">Công việc</li>
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
            <div class="container">
                 <div class="card card-info">
                        <div class="card-header">
                            <h5>CÔNG VIỆC</h5>
                        </div>
                        <div class="card-body">
                            <button id="addWorkBtn" type="button" data-toggle="modal" data-target="#addWork" class="btn btn-success">Thêm</button><br><br>
                            <table id="dataTable" class="display" style="width:100%">
                                <thead>
                                <tr class="bg-gray">
                                    <th>STT</th>
                                    <th>Ngày</th>
                                    <th>Tên công việc</th>
                                    <th>Tiến độ</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Ghi chú</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- Medal Add Work-->
                    <div class="modal fade" id="addWork">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">THÊM CÔNG VIỆC</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="addWorkForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <input type="hidden" name="idReport4">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Tên công việc</label>
                                                    <input name="tenCongViec" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tiến độ</label>
                                                    <input value="0" oninput="this.nextElementSibling.value = this.value" name="tienDo" placeholder="% hoàn thành" min="0" max="100" type="range" class="form-control">
                                                    <output></output>%
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày bắt đầu</label>
                                                    <input name="ngayStart" type="date" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày kết thúc</label>
                                                    <input name="ngayEnd" type="date" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <input name="ghiChu" placeholder="Nếu có" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnAddWork" class="btn btn-primary" form="addWorkForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                     <div class="card card-info">
                        <div class="card-header">
                            <h5>CÔNG VIỆC TỔNG HỢP</h5>
                        </div>
                        <div class="card-body">
                           
                        </div>
                        <!-- /.card-body -->
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
