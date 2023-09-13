@extends('admin.index')
@section('title')
    Khách hàng kinh doanh v2
@endsection
@section('script_head')
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css"> -->

    <!-- New -->
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
                        <h1 class="m-0"><strong>Khách hàng kinh doanh</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng kinh doanh</li>
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
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Tất cả khách</a>
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
                                                                                <label>Loại khách hàng</label>
                                                                                <select name="loai" class="form-control">
                                                                                    @foreach($typeGuest as $row)
                                                                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Tên khách hàng <span class="text-danger"><strong>(*)</strong></span></label>
                                                                                <input name="ten" type="text" class="form-control" placeholder="Tên khách hàng">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Số điện thoại <span class="text-danger"><strong>(*)</strong></span></label>
                                                                                <input name="dienThoai" type="number" class="form-control" placeholder="Số điện thoại">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Địa chỉ <span class="text-danger"><strong>(*)</strong></span></label>
                                                                                <input name="diaChi" type="text" class="form-control" placeholder="Địa chỉ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label>Ngày sinh</label>
                                                                                <input name="ngaySinh" type="date" class="form-control">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>CMND</label>
                                                                                <input name="cmnd" type="text" class="form-control" placeholder="CMND">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Ngày cấp</label>
                                                                                <input name="ngayCap" type="date" class="form-control">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Nơi cấp</label>
                                                                                <input name="noiCap" type="text" class="form-control" placeholder="Nơi cấp">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label>Mã số thuế (nếu có)</label>
                                                                                <input name="mst" type="number" class="form-control" placeholder="Mã số thuế">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Đại diện (nếu có)</label>
                                                                                <input name="daiDien" type="text" class="form-control" placeholder="Đại diện">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chức vụ (nếu có)</label>
                                                                                <input name="chucVu" type="text" class="form-control" placeholder="Chức vụ">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Lên hợp đồng</label>
                                                                                <select name="lenHopDong" id="lenHopDong" class="form-control">
                                                                                    <option value="0">KHÔNG</option>
                                                                                    <option value="1">CÓ</option>
                                                                                </select>
                                                                            </div>                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <h5>CHĂM SÓC KHÁCH HÀNG</h5>
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label>Nguồn khách hàng</label>
                                                                                <select name="nguon" id="nguon" class="form-control">
                                                                                    <option value="Showroom">Showroom</option>   
                                                                                    <option value="Thị Trường">Thị Trường</option>
                                                                                    <option value="Online">Online</option>
                                                                                    <option value="Giới thiệu">Giới thiệu</option>
                                                                                    <option value="Marketing">Marketing</option>
                                                                                    <option value="Sự kiện">Sự kiện</option>
                                                                                    <option value="Công ty">Công ty</option>                                                          
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Xe quan tâm</label>
                                                                                <input name="quanTam" type="text" class="form-control" placeholder="Xe quan tâm">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Đánh giá</label>
                                                                                <select name="danhGia" id="danhGia" class="form-control">
                                                                                    <option value="HOT">HOT</option>
                                                                                    <option value="WARM">WARM</option>
                                                                                    <option value="COLD" selected>COLD</option>
                                                                                    <option value="FAIL">FAIL</option>                                                      
                                                                                </select>
                                                                            </div>                                                                                                                                          
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 1</label>
                                                                                <input name="cs1" type="text" class="form-control" placeholder="Chăm sóc lần 1">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 2</label>
                                                                                <input name="cs2" type="text" class="form-control" placeholder="Chăm sóc lần 2">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 3</label>
                                                                                <input name="cs3" type="text" class="form-control" placeholder="Chăm sóc lần 3">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 4</label>
                                                                                <input name="cs4" type="text" class="form-control" placeholder="Chăm sóc lần 4">
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
                                        <!-- Medal Add -->
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
                                                        <div class="card card-success">
                                                            <div class="card-header">
                                                                <h3 class="card-title">CHỈNH SỬA</h3>
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
                                                                                <label>Loại khách hàng</label>
                                                                                <select name="eloai" class="form-control">
                                                                                    @foreach($typeGuest as $row)
                                                                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
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
                                                                    <div class="col-sm-4">
                                                                        <div class="card-body">
                                                                            <div class="form-group">
                                                                                <label>Mã số thuế (nếu có)</label>
                                                                                <input name="emst" type="number" class="form-control" placeholder="Mã số thuế">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Đại diện (nếu có)</label>
                                                                                <input name="edaiDien" type="text" class="form-control" placeholder="Đại diện">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chức vụ (nếu có)</label>
                                                                                <input name="echucVu" type="text" class="form-control" placeholder="Chức vụ">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Lên hợp đồng</label>
                                                                                <select name="elenHopDong" id="elenHopDong" class="form-control">
                                                                                    <option value="0">KHÔNG</option>
                                                                                    <option value="1">CÓ</option>
                                                                                </select>
                                                                            </div>
                                                                          </div>
                                                                        </div>
                                                                    </div>
                                                                </div>    
                                                                <h5>CHĂM SÓC KHÁCH HÀNG</h5>
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <label>Nguồn khách hàng</label>
                                                                                <select name="enguon" id="enguon" class="form-control">
                                                                                    <option value="Showroom">Showroom</option>   
                                                                                    <option value="Thị Trường">Thị Trường</option>
                                                                                    <option value="Online">Online</option>
                                                                                    <option value="Giới thiệu">Giới thiệu</option>
                                                                                    <option value="Marketing">Marketing</option>
                                                                                    <option value="Sự kiện">Sự kiện</option>
                                                                                    <option value="Công ty">Công ty</option>                                                          
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Xe quan tâm</label>
                                                                                <input name="equanTam" type="text" class="form-control" placeholder="Xe quan tâm">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Đánh giá</label>
                                                                                <select name="edanhGia" id="edanhGia" class="form-control">
                                                                                    <option value="HOT">HOT</option>
                                                                                    <option value="WARM">WARM</option>
                                                                                    <option value="COLD">COLD</option> 
                                                                                    <option value="FAIL">FAIL</option>                                                          
                                                                                </select>
                                                                            </div>                                                                                                                                          
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 1</label>
                                                                                <input name="ecs1" type="text" class="form-control" placeholder="Chăm sóc lần 1">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 2</label>
                                                                                <input name="ecs2" type="text" class="form-control" placeholder="Chăm sóc lần 2">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 3</label>
                                                                                <input name="ecs3" type="text" class="form-control" placeholder="Chăm sóc lần 3">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Chăm sóc lần 4</label>
                                                                                <input name="ecs4" type="text" class="form-control" placeholder="Chăm sóc lần 4">
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
                                        <!-- Medal Add -->
                                        <div class="modal fade" id="movingModal">
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
                                                                <h3 class="card-title">Chuyển khách</h3>
                                                            </div>
                                                            <!-- /.card-header -->
                                                            <!-- form start -->
                                                            <form id="movingForm" autocomplete="off">
                                                                {{csrf_field()}}
                                                                <div class="card-body">
                                                                    <input type="hidden" name="idguestmove">
                                                                    <div class="form-group">
                                                                        <label>Chọn nhân viên nhận khách</label>
                                                                        <select name="nhanVienRecieve" class="form-control"> 
                                                                            @foreach($groupsale as $row)                       
                                                                                <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option> 
                                                                            @endforeach   
                                                                        </select>
                                                                    </div>
                                                                </div>                                                                        
                                                                <div class="card-footer">
                                                                    <button id="btnUpdateMove" class="btn btn-success">Xác nhận</button>
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
                                                <!-- <th>TT</th> -->
                                                <th>Ngày nhập</th>
                                                <th>ID</th>
                                                <th>Tên</th>
                                                <th>Nguồn</th>
                                                <th>Loại</th>
                                                <th>Điện thoại</th>
                                                <th>Địa chỉ</th>                                                
                                                <th>Đánh giá</th>
                                                <th>Xe quan tâm</th>
                                                <th>CSL1</th>
                                                <th>CSL2</th>
                                                <th>CSL3</th>
                                                <th>CSL4</th>
                                                <th>Người nhập</th>
                                                <th>Tác vụ</th>
                                                <th>Ngày sinh</th>
                                                <th>MST</th>
                                                <th>CMND</th>
                                                <th>Ngày cấp</th>
                                                <th>Nơi cấp</th>
                                                <th>Đại diện</th>
                                                <th>Chức vụ</th>
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
@endsection
@section('script')
    <!-- jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->
    <!-- SweetAlert2 -->
    <!-- <script src="plugins/sweetalert2/sweetalert2.min.js"></script> -->
    <!-- Bootstrap 4 -->
    <!-- <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
    <!-- AdminLTE App -->
    <!-- <script src="dist/js/adminlte.min.js"></script> -->
    <!-- Below is plugin for datatables -->
    <!-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> -->

    <!-- New code -->
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

        // show data
        $(document).ready(function() {

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // processing: true,
                // serverSide: true,
                ajax: "{{ url('management/guest/get/list') }}",
                // "columnDefs": [ {
                //     "searchable": false,
                //     "orderable": false,
                //     "targets": 0
                // } ],
                "order": [
                    [ 1, 'desc' ]
                ],
                // lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    // { "data": null },
                    { "data": null,
                        render: function(data, type, row) {
                            let arr = row.created_at.split("T")[0].split("-");
                            return arr[2] + "-" + arr[1] + "-" + arr[0];
                        } 
                    },
                    { "data": "idmaster" },
                    { "data": "name" },
                    { "data": "nguon" },
                    { "data": "type" },
                    { "data": "phone" },
                    { "data": "address" },
                    { "data": null,
                        render: function(data, type, row) {
                            let stt = "";
                            switch(row.danhGia) {
                                case "COLD": stt = "<strong class='text-blue'>" + row.danhGia + "</strong>"; break;
                                case "WARM": stt = "<strong class='text-orange'>" + row.danhGia + "</strong>"; break;
                                case "HOT": stt = "<strong class='text-red'>" + row.danhGia + "</strong>"; break;
                                case "FAIL": stt = "<strong class='text-secondary'>" + row.danhGia + "</strong>"; break;
                            }              
                            return stt;
                        }
                    },
                    { "data": "xeQuanTam" },
                    { "data": "cs1" },
                    { "data": "cs2" },
                    { "data": "cs3" },
                    { "data": "cs4" },
                    { "data": "sale" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            return "<button id='btnEdit' data-id='"+row.idmaster+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button> &nbsp; " +
                                "<button id='delete' data-id='"+row.idmaster+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;" +
                                "<button id='moving' data-id='"+row.idmaster+"' data-toggle='modal' data-target='#movingModal' class='btn btn-info btn-sm'>Chuyển</button>&nbsp;";
                            @else
                            return "<button id='btnEdit' data-id='"+row.idmaster+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button> &nbsp; " +
                                "<button id='delete' data-id='"+row.idmaster+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;";
                            @endif
                        }
                    },
                    { "data": "ngaySinh" },
                    { "data": "mst" },
                    { "data": "cmnd" },
                    { "data": "ngayCap" },
                    { "data": "noiCap" },
                    { "data": "daiDien" },
                    { "data": "chucVu" },
                ]
            });
            // table.on( 'order.dt search.dt', function () {
            //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            //         cell.innerHTML = i+1;
            //         table.cell(cell).invalidate('dom');
            //     } );
            // } ).draw();

            // var table2 = $('#dataTableOut').DataTable({
            //     // paging: false,    use to show all data
            //     responsive: true,
            //     dom: 'Blfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel', 'pdf', 'print'
            //     ],
            //     "columnDefs": [ {
            //         "searchable": false,
            //         "orderable": false,
            //         "targets": 0
            //     } ],
            //     "order": [
            //         [ 0, 'desc' ]
            //     ],
            //     lengthMenu:  [5, 10, 25, 50, 75, 100 ]
            // });
            // table2.on( 'order.dt search.dt', function () {
            //     table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            //         cell.innerHTML = i+1;
            //         table2.cell(cell).invalidate('dom');
            //     } );
            // } ).draw();

            // Add data
            $("#btnAdd").click(function(e){
                e.preventDefault();
                let flag = true;
                if($("input[name=dienThoai]").val().match(/\d/g).length===10) {
                    $.ajax({
                       url: "management/guest/check/" + $("input[name=dienThoai]").val().replace(',','').replace('.',''),
                       dataType: "text",
                       success: function(responce) {
                           let obj = JSON.parse(responce);
                           if (parseInt(obj.check) === 1) {
                               flag = false;
                               alert('Số điện thoại ' + obj.phone + ' đã được tạo bởi ' + obj.user);
                           } 
                       },
                       async: false
                    });
                    if (flag) {
                        $("input[name=dienThoai]").val($("input[name=dienThoai]").val().replace(',','').replace('.',''));
                        $.ajax({
                            url: "{{url('management/guest/add/')}}",
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
                    }
                } else {
                    alert('Số điện thoại không đúng định dạng');
                }
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/guest/delete/')}}",
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

            // edit data
            $(document).on('click','#btnEdit', function(){
                $.ajax({
                    url: "{{url('management/guest/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response);
                        $("input[name=eid]").val(response.data.id);
                        $("select[name=eloai]").val(response.data.id_type_guest);
                        $("select[name=enguon]").val(response.data.nguon);
                        $("input[name=eten]").val(response.data.name);
                        $("input[name=engaySinh]").val(response.data.ngaySinh);
                        $("input[name=emst]").val(response.data.mst);
                        $("input[name=engayCap]").val(response.data.ngayCap);
                        $("input[name=enoiCap]").val(response.data.noiCap);
                        $("input[name=ecmnd]").val(response.data.cmnd);
                        $("input[name=edaiDien]").val(response.data.daiDien);
                        $("input[name=echucVu]").val(response.data.chucVu);
                        $("input[name=edienThoai]").val(response.data.phone);
                        $("input[name=edienThoaiCopy]").val(response.data.phone);
                        $("input[name=ediaChi]").val(response.data.address);
                        //-----------------
                        $("input[name=equanTam]").val(response.data.xeQuanTam);
                        $("select[name=edanhGia]").val(response.data.danhGia);
                        $("select[name=elenHopDong]").val(response.data.lenHopDong);
                        $("input[name=ecs1]").val(response.data.cs1);
                        $("input[name=ecs2]").val(response.data.cs2);
                        $("input[name=ecs3]").val(response.data.cs3);
                        $("input[name=ecs4]").val(response.data.cs4);
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
                    if (parseInt($("input[name=edienThoaiCopy]").val()) != parseInt($("input[name=edienThoai]").val())) {
                        $.ajax({
                            url: "management/guest/check/" + $("input[name=edienThoai]").val().replace(',','').replace('.',''),
                            dataType: "text",
                            success: function(responce) {
                                let obj = JSON.parse(responce);
                                if (parseInt(obj.check) === 1) {
                                    flag = false;
                                    alert('Số điện thoại ' + obj.phone + ' đã được tạo bởi ' + obj.user);
                                }
                            },
                            async: false
                        });
                    }
                    if (flag) {
                        $("input[name=edienThoai]").val($("input[name=edienThoai]").val().replace(',','').replace('.',''));
                        $.ajax({
                            url: "{{url('management/guest/update/')}}",
                            type: "post",
                            dataType: "json",
                            data: {
                                '_token': '{{csrf_token()}}',
                                'eid': $("input[name=eid]").val(),
                                'eloai': $("select[name=eloai]").val(),
                                'eten': $("input[name=eten]").val(),
                                'ecmnd': $("input[name=ecmnd]").val(),
                                'edienThoai': $("input[name=edienThoai]").val(),
                                'emst': $("input[name=emst]").val(),
                                'engayCap': $("input[name=engayCap]").val(),
                                'enoiCap': $("input[name=enoiCap]").val(),
                                'engaySinh': $("input[name=engaySinh]").val(),
                                'ediaChi': $("input[name=ediaChi]").val(),
                                'edaiDien': $("input[name=edaiDien]").val(),
                                'echucVu': $("input[name=echucVu]").val(),
                                'enguon': $("select[name=enguon]").val(),
                                'elenHopDong': $("select[name=elenHopDong]").val(),
                                'edanhGia': $("select[name=edanhGia]").val(),
                                'equanTam': $("input[name=equanTam]").val(),
                                'ecs1': $("input[name=ecs1]").val(),
                                'ecs2': $("input[name=ecs2]").val(),
                                'ecs3': $("input[name=ecs3]").val(),
                                'ecs4': $("input[name=ecs4]").val(),
                            },
                            success: function(response) {
                                $("#editForm")[0].reset();
                                Toast.fire({
                                    icon: response.type,
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
                    }
                } else {
                    alert('Số điện thoại không đúng định dạng');
                }
            });
            
            $(document).on('click','#moving', function(){
                $("input[name=idguestmove]").val($(this).data('id'));
            });

            $("#btnUpdateMove").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/guest/update/moving')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        '_token': '{{csrf_token()}}',
                        'idguest': $("input[name=idguestmove]").val(),
                        'idsale': $("select[name=nhanVienRecieve]").val()
                    },
                    success: function(response) {
                        $("#movingForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        table.ajax.reload();
                        $("#movingModal").modal('hide');
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });
        });
    </script>
@endsection
