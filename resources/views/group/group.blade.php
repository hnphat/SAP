@extends('admin.index')
@section('title')
    Quản lý Nhóm - Saler
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
                        <h1 class="m-0"><strong>QUẢN LÝ Nhóm/Saler</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Quản lý Nhóm/Saler</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="row">
                <div class="col-sm-2">
                    <form id="addForm" autocomplete="off">
                        {{csrf_field()}}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="tenPhong">Tên nhóm</label>
                                <input type="text" class="form-control" placeholder="Tên phòng ban" name="tenPhong">
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
                            <th>Nhóm</th>                           
                            <th>Sửa</th>
                            <th>Xóa</th>
                            <th>Mở rộng</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-sm-5" id="showMore">
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
                                        <h3 class="card-title">CHỈNH SỬA NHÓM</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="editForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="idMasterPhong">
                                            <div class="form-group">
                                                <label for="etenPhong">Tên nhóm</label>
                                                <input type="text" class="form-control" id="etenPhong" placeholder="Tên phòng ban" name="etenPhong">
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
                                        <h3 class="card-title">THÊM NHÂN SALE VÀO NHÓM</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="addPlusForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="idAddPlus">
                                            <div class="form-group">
                                                <label for="mtenPhong">Nhóm</label>
                                                <input type="text" class="form-control" id="mtenPhong" disabled="disabled" name="mtenPhong">
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Nhân viên</label>
                                                <select name="nhanVien" class="form-control">
                                                    @foreach($user as $row)
                                                       <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Trạng thái</label>
                                                <select name="trangThai" class="form-control">
                                                    <option value="0">Nhân viên</option>
                                                    <option value="1">Trưởng nhóm</option>
                                                </select>
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
                                        <h3 class="card-title">CHỈNH SỬA SALE TRONG NHÓM</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form id="editAddPlusForm" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="card-body">
                                            <input type="hidden" name="idMaster"/>
                                            <input type="hidden" name="idEditAddPlus">
                                            <div class="form-group">
                                                <label for="emtenPhong">Nhóm</label>
                                                <input type="text" class="form-control" id="emtenPhong" disabled="disabled" name="emtenPhong">
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Nhân viên</label>
                                                <select name="enhanVien" class="form-control">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Trạng thái</label>
                                                <select name="etrangThai" class="form-control">
                                                    <option value="0">Nhân viên</option>
                                                    <option value="1">Quản lý</option>
                                                </select>
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
                ajax: "{{ url('management/group/getlist/') }}",
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
                if (!$("input[name=tenPhong]").val()) 
                    alert("Bạn chưa nhập thông tin nhóm");
                else {
                    $.ajax({
                        url: "{{url('management/group/add/')}}",
                        type: "POST",
                        dataType: "json",
                        data: $("#addForm").serialize(),
                        success: function(response) {
                            $("#addForm")[0].reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
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
                }               
            });

            // Edit data
            $(document).on('click','#btnEdit', function (){
               $.ajax({
                   url: "{{url('management/group/edit/')}}",
                   type: "post",
                   dataType: "json",
                   data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                   },
                   success: function(response) {
                       console.log(response.data);
                       $("input[name=etenPhong]").val(response.data.name);
                       $("input[name=idMasterPhong]").val(response.data.id);
                   }
               });
            });

            // Update data
            $('#btnUpdate').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/group/update')}}",
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
            });

            //Delete data
            $(document).on("click","#delete",function(){
               if (confirm("Bạn có chắc muốn xóa?")) {
                   $.ajax({
                      url: "{{url('management/group/delete')}}",
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
                $.get("management/group/more/" + $(this).data('id'), function(data){
                    $("#showMore").html(data);
                });
            });

            // delete plus more
            $(document).on('click', '#deletePlus', function(){
                if (confirm("Bạn có chắc muốn xóa nhân viên này ra khỏi nhóm?")) {
                    $.ajax({
                        url: "{{url('management/group/more/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idUser": $(this).data('iduser'),
                            "idNhom": $(this).data('idnhom')
                        },
                        success: function(response){
                            console.log(response);
                            Toast.fire({
                                icon: 'success',
                                title: "Đã xóa!"
                            })
                            $.get("management/group/more/" + response.id, function(data){
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
                    url: "{{url('management/group/more/add/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'id': $(this).data('id')
                    },
                    success: function(response){
                        console.log(response.data);
                        $("input[name=idAddPlus]").val(response.data.id);
                        $("input[name=mtenPhong]").val(response.data.name);
                    }
                });
            });

            // Add plus data
            $("#btnAddPlus").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/group/more/addplus/')}}",
                    type: "POST",
                    dataType: "json",
                    data: $("#addPlusForm").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addPlusForm")[0].reset();
                        $("#addPlusModal").modal('hide');
                        $.get("management/group/more/" + response.id, function(data){
                            $("#showMore").html(data);
                        });
                    }
                });
            });

            // edit add plus show form
            $(document).on('click','#showEditPlus', function(){
                $.ajax({
                    url: "{{url('management/group/more/editshowplus/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'id': $(this).data('id')
                    },
                    success: function(response){
                        $('select[name=_fuel] option[selected=selected]').removeAttr('selected');
                        $('option[value='+response.data.fuel+']').attr('selected','selected');
                        $('select[name=_seat] option[selected=selected]').removeAttr('selected');
                        $('option[value='+response.data.seat+']').attr('selected','selected');
                        $('select[name=_gear] option[selected=selected]').removeAttr('selected');
                        $('option[value='+response.data.gear+']').attr('selected','selected');

                        $("input[name=idEditAddPlus]").val(response.data.id);
                        $("input[name=idMaster]").val(response.data.id_type_car);
                        $("input[name=_tenLoaiXe]").val(response.data.name);
                        $("select[name=_fuel]").val(response.data.fuel);
                        $("select[name=_seat]").val(response.data.seat);
                        $("input[name=_machine]").val(response.data.machine);
                        $("select[name=_gear]").val(response.data.gear);
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
