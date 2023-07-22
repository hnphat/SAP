@extends('admin.index')
@section('title')
    Khách hàng MKT
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Khách hàng MKT</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng MKT</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Khách hàng MKT</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                            <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button>
                              <form>
                                <div class="card-body row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Từ</label>
                                                <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Đến</label>
                                                <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br/>
                                                <button id="xemReport" type="button" class="btn btn-info btn-xs">XEM</button>
                                            </div>
                                        </div>
                                </div>
                              </form>
                              <hr/>
                              <div id="all">
       
                              </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>


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
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">THÊM KHÁCH HÀNG</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editForm" autocomplete="off">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="hidden" name="eid"/>
                                    <input type="hidden" name="edienThoaiCopy"/>
                                    <div class="card-body">                                        
                                        <div class="form-group">
                                            <label>Tên khách hàng</label>
                                            <input name="eten" type="text" class="form-control" placeholder="Tên khách hàng">
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input name="edienThoai" type="number" class="form-control" placeholder="Số điện thoại">
                                        </div>
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input name="ediaChi" type="text" class="form-control" placeholder="Địa chỉ">
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <div class="col-sm-4">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Ngày sinh</label>
                                            <input name="engaySinh" type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>CMND</label>
                                            <input name="ecmnd" type="text" class="form-control" placeholder="CMND">
                                        </div>
                                        <div class="form-group">
                                            <label>Ngày cấp</label>
                                            <input name="engayCap" type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Nơi cấp</label>
                                            <input name="enoiCap" type="text" class="form-control" placeholder="Nơi cấp">
                                        </div>
                                    </div>
                                </div>                                
                            </div>                               
                            <div class="card-footer">
                                <button id="btnUpdate" class="btn btn-success">Cập nhật</button>
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

       $(document).ready(function(){
         $("#xemReport").click(function(){
            $.ajax({
                type: "post",
                url: "{{url('management/marketing/loadbaocao/')}}",
                dataType: "text",
                data: {
                    "_token": "{{csrf_token()}}",
                    "tu": $("input[name=chonNgayOne]").val(),
                    "den": $("input[name=chonNgayTwo").val()
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'info',
                        title: " Đã gửi yêu cầu! "
                    })
                    $("#all").html(response);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                }
            });
         });  
       });
    </script>
@endsection
