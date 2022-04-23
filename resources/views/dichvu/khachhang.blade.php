@extends('admin.index')
@section('title')
    Quản lý khách hàng
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
                        <h1 class="m-0"><strong>Quản lý khách hàng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Quản lý khách hàng</li>
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
                                            Quản lý khách hàng
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                                        <!-- Medal Add -->
                                        <div class="modal fade" id="addModal">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- general form elements -->
                                                        <div class="card card-primary">
                                                            <div class="card-header">
                                                                <h3 class="card-title">THÊM MỚI</h3>
                                                            </div>
                                                            <!-- /.card-header -->
                                                            <!-- form start -->
                                                            <form id="addForm" autocomplete="off">
                                                                {{csrf_field()}}
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-4">                                                                           
                                                                            <div class="form-group">
                                                                                <label>Tên khách hàng</label>
                                                                                <input name="hoTen" type="text" class="form-control" placeholder="Tên khách hàng">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Số điện thoại</label>
                                                                                <input name="dienThoai" type="number" class="form-control" placeholder="Số điện thoại">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Địa chỉ</label>
                                                                                <input name="diaChi" type="text" class="form-control" placeholder="Địa chỉ">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Mã số thuế</label>
                                                                                <input name="mst" type="text" class="form-control" placeholder="Mã số thuế">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">                                                                           
                                                                            <div class="form-group">
                                                                                <label>Biển số</label>
                                                                                <input name="bienSo" type="text" class="form-control" placeholder="Biển số xe">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Số khung</label>
                                                                                <input name="soKhung" type="text" class="form-control" placeholder="Số khung">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Số máy</label>
                                                                                <input name="soMay" type="text" class="form-control" placeholder="Số máy">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Thông tin xe</label>
                                                                                <input name="thongTinXe" type="text" class="form-control" placeholder="VD: Hyundai - Màu đỏ - 05 chỗ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label>Người liên hệ</label>
                                                                                <input name="taiXe" type="text" class="form-control" placeholder="Người liên hệ">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Số điện thoại</label>
                                                                                <input name="dienThoaiTaiXe" type="number" class="form-control" placeholder="Điện thoại">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- /.card-body -->
                                                                <div class="card-footer">
                                                                    <button id="btnAdd" class="btn btn-primary">Lưu</button>
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
                                        <table id="dataTable" class="display" style="width:100%">
                                            <thead>
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Họ tên</th>
                                                <th>Điện thoại</th>
                                                <th>Mã số thuế</th>
                                                <th>Địa chỉ</th>
                                                <th>Biển số xe</th>
                                                <th>Số khung</th>
                                                <th>Số máy</th>
                                                <th>Chi tiết xe</th>
                                                <th>Liên hệ</th>
                                                <th>Điện thoại</th>
                                                <th>Tác vụ</th>
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
     <!-- Medal edit -->
     <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Chỉnh sửa thông tin</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editForm" autocomplete="off">
                            <input type="hidden" name="eid">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">                                                                           
                                        <div class="form-group">
                                            <label>Tên khách hàng</label>
                                            <input name="ehoTen" type="text" class="form-control" placeholder="Tên khách hàng">
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input name="edienThoai" type="number" class="form-control" placeholder="Số điện thoại">
                                        </div>
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input name="ediaChi" type="text" class="form-control" placeholder="Địa chỉ">
                                        </div>
                                        <div class="form-group">
                                            <label>Mã số thuế</label>
                                            <input name="emst" type="text" class="form-control" placeholder="Mã số thuế">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">                                                                           
                                        <div class="form-group">
                                            <label>Biển số</label>
                                            <input name="ebienSo" type="text" class="form-control" placeholder="Biển số xe">
                                        </div>
                                        <div class="form-group">
                                            <label>Số khung</label>
                                            <input name="esoKhung" type="text" class="form-control" placeholder="Số khung">
                                        </div>
                                        <div class="form-group">
                                            <label>Số máy</label>
                                            <input name="esoMay" type="text" class="form-control" placeholder="Số máy">
                                        </div>
                                        <div class="form-group">
                                            <label>Thông tin xe</label>
                                            <input name="ethongTinXe" type="text" class="form-control" placeholder="VD: Hyundai - Màu đỏ - 05 chỗ">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Người liên hệ</label>
                                            <input name="etaiXe" type="text" class="form-control" placeholder="Người liên hệ">
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input name="edienThoaiTaiXe" type="number" class="form-control" placeholder="Điện thoại">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnUpdate" class="btn btn-primary">Lưu</button>
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

        // show data
        $(document).ready(function() {

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/dichvu/get/list') }}",
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
                    { "data": "hoTen" },
                    { "data": "dienThoai" },
                    { "data": "mst" },
                    { "data": "diaChi" },
                    { "data": "bienSo" },
                    { "data": "soKhung" },
                    { "data": "soMay" },
                    { "data": "thongTinXe" },
                    { "data": "taiXe" },
                    { "data": "dienThoaiTaiXe" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button> &nbsp; " +
                                "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;";
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
                let flag = true;
                if($("input[name=dienThoai]").val().match(/\d/g).length===10) {
                    $.ajax({
                        url: "{{url('management/dichvu/guest/add/')}}",
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
                                icon: 'warning',
                                title: "Lỗi nhập liệu; Lỗi xử lý dữ liệu"
                            })
                        }
                    });
                } else {
                    alert('Số điện thoại không đúng định dạng 10 số');
                }
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/dichvu/guest/delete/')}}",
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
                $.ajax({
                    url: "{{url('management/dichvu/guest/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response);
                        $("input[name=eid]").val(response.data.id);
                        $("input[name=ehoTen]").val(response.data.hoTen);
                        $("input[name=edienThoai]").val(response.data.dienThoai);
                        $("input[name=emst]").val(response.data.mst);
                        $("input[name=ediaChi]").val(response.data.diaChi);
                        $("input[name=esoKhung]").val(response.data.soKhung);
                        $("input[name=esoMay]").val(response.data.soMay);
                        $("input[name=ebienSo]").val(response.data.bienSo);
                        $("input[name=ethongTinXe]").val(response.data.thongTinXe);
                        $("input[name=etaiXe]").val(response.data.taiXe);
                        $("input[name=edienThoaiTaiXe]").val(response.data.dienThoaiTaiXe);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnUpdate").click(function(e){
                e.preventDefault();
                let flag = true;
                if($("input[name=edienThoai]").val().match(/\d/g).length===10) {
                    $.ajax({
                        url: "{{url('management/dichvu/guest/update/')}}",
                        type: "post",
                        dataType: "json",
                        data: $("#editForm").serialize(),
                        success: function(response) {
                            $("#editForm")[0].reset();
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#editModal").modal('hide');
                        },
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Error 500!"
                            })
                        }
                    });
                } else {
                    alert('Số điện thoại không đúng định dạng');
                }
            });
        });
    </script>
@endsection
