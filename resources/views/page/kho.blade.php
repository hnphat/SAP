@extends('admin.index')
@section('title')
    Kho xe
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
                        <h1 class="m-0"><strong>Quản lý kho xe</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Kho xe</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true"><strong>Kho xe</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><strong>Đã xuất bán</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-3-tab" data-toggle="pill" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false"><strong>Đang đặt hàng</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-4-tab" data-toggle="pill" href="#tab-4" role="tab" aria-controls="tab-4" aria-selected="false"><strong>Đang lên hợp đồng</strong></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-gray">
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Năm</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Năm</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <table id="dataTableOut" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-success">
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Năm</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Năm</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tab-3-tab">
                                <table id="dataTableOrder" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Năm</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Năm</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="tab-4-tab">
                                <table id="dataTableSetup" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Sale bán</th>
                                        <th>Xe bán</th>
                                        <th>Số VIN</th>
                                        <th>Số máy</th>
                                        <th>Màu</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>TT</th>
                                        <th>Sale bán</th>
                                        <th>Xe bán</th>
                                        <th>Số VIN</th>
                                        <th>Số máy</th>
                                        <th>Màu</th>
                                        <th>Nhiên liệu</th>
                                        <th>Giá</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!--  MEDAL -->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nhập kho xe</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="addForm" autocomplete="off">
                                {{csrf_field()}}
                                <div class="card-body row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Tên xe</label>
                                            <select name="tenXe" class="form-control">
                                                @foreach($typecar as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Năm sản xuất</label>
                                            <select name="nam" class="form-control">
                                                <option value="0">Chọn</option>
                                                @for($i = 2000; $i <= 2100; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Số VIN</label>
                                            <input name="vin" placeholder="VIN" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Số máy</label>
                                            <input name="frame" placeholder="Số máy" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Màu sắc</label>
                                            <select name="color" class="form-control">
                                                <option value="0">Chọn</option>
                                                <option value="Đỏ">Đỏ</option>
                                                <option value="Xanh">Xanh</option>
                                                <option value="Trắng">Trắng</option>
                                                <option value="Vàng">Vàng</option>
                                                <option value="Ghi">Ghi</option>
                                                <option value="Nâu">Nâu</option>
                                                <option value="Bạc">Bạc</option>
                                                <option value="Xám">Xám</option>
                                                <option value="Đen">Đen</option>
                                                <option value="Vàng cát">Vàng cát</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Hộp số</label>
                                            <select name="gear" class="form-control">
                                                <option value="0">Chọn</option>
                                                <option value="MT">MT</option>
                                                <option value="AT">AT</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Động cơ</label>
                                            <input name="machine" placeholder="Động cơ 1.0 1.2 .." type="number" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Số chỗ ngồi</label>
                                            <select name="seat" class="form-control">
                                                <option value="0">Chọn</option>
                                                <option value="4">4 chỗ</option>
                                                <option value="5">5 chỗ</option>
                                                <option value="7">7 chỗ</option>
                                                <option value="9">9 chỗ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nhiên liệu</label>
                                            <select name="fuel" class="form-control">
                                                <option value="0">Chọn</option>
                                                <option value="Xăng">Xăng</option>
                                                <option value="Dầu">Dầu</option>
                                                <option value="Điện">Điện</option>
                                                <option value="Điện/Dầu">Điện/Dầu</option>
                                                <option value="Điện/Xăng">Điện/Xăng</option>
                                            </select>
                                        </div>
{{--                                        <div class="form-group">--}}
{{--                                            <label>Giá xe</label>--}}
{{--                                            <input name="cost" value="0" id="cost" type="number" class="form-control" placeholder="Giá xe tối đa 2 tỷ 1">--}}
{{--                                            <span id="showCost"></span>--}}
{{--                                        </div>--}}
                                        <div class="form-group">
                                            <input name="exist" id="oop1" type="radio" value="1" checked="checked">
                                            <label for="oop1">Đang có xe</label>
                                        </div>
                                        <div class="form-group">
                                            <input name="exist" id="oop2" type="radio" value="2">
                                            <label for="oop2">Đang đặt hàng</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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
        <!-- Medal Edit -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Chỉnh sửa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="editForm" autocomplete="off">
                                {{csrf_field()}}
                                <input type="hidden" name="eid"/>
                                <div class="card-body row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Tên xe</label>
                                            <select name="etenXe" class="form-control">
                                                @foreach($typecar as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Năm sản xuất</label>
                                            <select name="enam" class="form-control">
                                                @for($i = 2000; $i <= 2100; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Số VIN</label>
                                            <input name="evin" placeholder="VIN" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Số máy</label>
                                            <input name="eframe" placeholder="Số máy" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Màu sắc</label>
                                            <select name="ecolor" class="form-control">
                                                <option value="Đỏ">Đỏ</option>
                                                <option value="Xanh">Xanh</option>
                                                <option value="Trắng">Trắng</option>
                                                <option value="Vàng">Vàng</option>
                                                <option value="Ghi">Ghi</option>
                                                <option value="Nâu">Nâu</option>
                                                <option value="Bạc">Bạc</option>
                                                <option value="Xám">Xám</option>
                                                <option value="Đen">Đen</option>
                                                <option value="Vàng cát">Vàng cát</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Hộp số</label>
                                            <select name="egear" class="form-control">
                                                <option value="MT">MT</option>
                                                <option value="AT">AT</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Động cơ</label>
                                            <input name="emachine" placeholder="Động cơ 1.0 1.2 .." type="number" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Số chỗ ngồi</label>
                                            <select name="eseat" class="form-control">
                                                <option value="4">4 chỗ</option>
                                                <option value="5">5 chỗ</option>
                                                <option value="7">7 chỗ</option>
                                                <option value="9">9 chỗ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nhiên liệu</label>
                                            <select name="efuel" class="form-control">
                                                <option value="Xăng">Xăng</option>
                                                <option value="Dầu">Dầu</option>
                                                <option value="Điện">Điện</option>
                                                <option value="Điện/Dầu">Điện/Dầu</option>
                                                <option value="Điện/Xăng">Điện/Xăng</option>
                                            </select>
                                        </div>
{{--                                        <div class="form-group">--}}
{{--                                            <label>Giá xe</label>--}}
{{--                                            <input name="ecost" id="ecost" type="number" class="form-control" placeholder="Giá xe">--}}
{{--                                            <span id="eshowCost"></span>--}}
{{--                                        </div>--}}
                                        <div class="form-group">
                                            <input name="eexist" id="exist1" type="radio" value="1" checked="checked">
                                            <label for="exist1">Đang có xe</label>
                                        </div>
                                        <div class="form-group">
                                            <input name="eexist" id="exist2" type="radio" value="2">
                                            <label for="exist2">Đang đặt hàng</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnUpdate" class="btn btn-primary" form="editForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();

        // show data
        $(document).ready(function() {

            $('#cost').keyup(function(){
                var cos = $('#cost').val();
                $('#showCost').text(formatNumber(cos) + " (" + DOCSO.doc(cos) + ")");
            });

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/kho/get/list') }}",
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
                    { "data": "ten" },
                    { "data": "year" },
                    { "data": "color" },
                    { "data": "vin" },
                    { "data": "frame" },
                    { "data": "gear" },
                    { "data": "machine" },
                    { "data": "seat" },
                    { "data": "fuel" },
                    { "data": "cost", render: $.fn.dataTable.render.number(',','.',0,'')},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            // return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#edit' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                            //     "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
                            return "<div class='btn-group'>" +
                                "<button type='button' class='btn btn-info btn-sm dropdown-toggle dropdown-icon' data-toggle='dropdown'></button>" +
                            "<div class='dropdown-menu' role='menu'>" +
                                "<button id='btnEdit' data-id='"+row.id+"'  data-toggle='modal' data-target='#editModal' class='dropdown-item' data-id='"+row.id+"'>Sửa</button>" +
                                "<button id='delete' data-id='"+row.id+"' class='dropdown-item'>Xóa</button>" +
                            "</div>" +
                        "</div>";
                        }
                    }
                ]
            });
            var tableOut = $('#dataTableOut').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/kho/get/list/out/') }}",
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
                    { "data": "ten" },
                    { "data": "year" },
                    { "data": "color" },
                    { "data": "vin" },
                    { "data": "frame" },
                    { "data": "gear" },
                    { "data": "machine" },
                    { "data": "seat" },
                    { "data": "fuel" },
                    { "data": "cost", render: $.fn.dataTable.render.number(',','.',0,'')}
                    // {
                    //     "data": null,
                    //     render: function(data, type, row) {
                    //         // return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#edit' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                    //         //     "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
                    //         return "<div class='btn-group'>" +
                    //             "<button type='button' class='btn btn-info btn-sm dropdown-toggle dropdown-icon' data-toggle='dropdown'></button>" +
                    //             "<div class='dropdown-menu' role='menu'>" +
                    //             "<button id='btnEdit' data-id='"+row.id+"'  data-toggle='modal' data-target='#editModal' class='dropdown-item' data-id='"+row.id+"'>Sửa</button>" +
                    //             "<button id='delete' data-id='"+row.id+"' class='dropdown-item'>Xóa</button>" +
                    //             "</div>" +
                    //             "</div>";
                    //     }
                    // }
                ]
            });
            var tableOrder = $('#dataTableOrder').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/kho/get/list/order/') }}",
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
                    { "data": "ten" },
                    { "data": "year" },
                    { "data": "color" },
                    { "data": "vin" },
                    { "data": "frame" },
                    { "data": "gear" },
                    { "data": "machine" },
                    { "data": "seat" },
                    { "data": "fuel" },
                    { "data": "cost", render: $.fn.dataTable.render.number(',','.',0,'')},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            // return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#edit' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                            //     "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
                            return "<div class='btn-group'>" +
                                "<button type='button' class='btn btn-info btn-sm dropdown-toggle dropdown-icon' data-toggle='dropdown'></button>" +
                                "<div class='dropdown-menu' role='menu'>" +
                                "<button id='btnEdit' data-id='"+row.id+"'  data-toggle='modal' data-target='#editModal' class='dropdown-item' data-id='"+row.id+"'>Sửa</button>" +
                                "<button id='delete' data-id='"+row.id+"' class='dropdown-item'>Xóa</button>" +
                                "</div>" +
                                "</div>";
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

            tableOut.on( 'order.dt search.dt', function () {
                tableOut.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    tableOut.cell(cell).invalidate('dom');
                } );
            } ).draw();

            tableOrder.on( 'order.dt search.dt', function () {
                tableOrder.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    tableOrder.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Add data
            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/kho/add/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: " Đã nhập kho xe "
                        })
                        table.ajax.reload();
                        tableOut.ajax.reload();
                        tableOrder.ajax.reload();
                        $("#addModal").modal('hide');
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
                        url: "{{url('management/kho/delete/')}}",
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
                            tableOut.ajax.reload();
                            tableOrder.ajax.reload();
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
                    url: "{{url('management/kho/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        $("select[name=etenXe]").val(response.data.id_type_car_detail);
                        $("select[name=enam]").val(response.data.year);
                        $("input[name=evin]").val(response.data.vin);
                        $("input[name=eframe]").val(response.data.frame);
                        $("select[name=ecolor]").val(response.data.color);
                        $("select[name=egear]").val(response.data.gear);
                        $("input[name=emachine]").val(response.data.machine);
                        $("select[name=eseat]").val(response.data.seat);
                        $("select[name=efuel]").val(response.data.fuel);
                        // $("input[name=ecost]").val(response.data.cost);
                        if (response.data.exist == 1)
                            $("#exist1").prop('checked',true);
                        if (response.data.order == 1)
                            $("#exist2").prop('checked',true);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $('#ecost').keyup(function(){
                var cos = $('#ecost').val();
                $('#eshowCost').text(formatNumber(cos) + " (" + DOCSO.doc(cos) + ")");
            });

            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/kho/update/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#editForm").serialize(),
                    success: function(response) {
                        $("#editForm")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: "Đã cập nhật!"
                        })
                        table.ajax.reload();
                        tableOut.ajax.reload();
                        tableOrder.ajax.reload();
                        $("#editModal").modal('hide');
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
