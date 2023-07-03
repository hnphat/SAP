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
                        <h1 class="m-0"><strong>Quản lý kho</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kho xe</li>
                            <li class="breadcrumb-item active">Quản lý kho</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false"><strong>Quản lý kho</strong></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Trạng thái</th>
                                        <th>Tên xe</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>GPS</th>
                                        <th class="bg-secondary">Số đơn hàng</th>
                                        <th class="bg-secondary">Ngày đặt</th>
                                        <th class="bg-secondary">Số bảo lãnh</th>
                                        <th class="bg-secondary">Ngày nhận xe</th>
                                        <th class="bg-secondary">Ngân hàng</th>
                                        <th>Năm SX</th>
                                        <!-- <th>Hộp số</th>
                                        <th>Động cơ</th>
                                        <th>Số ghế</th>
                                        <th>Nhiên liệu</th> -->
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
        <!-- /.content -->
    </div>
    <!--  MEDAL -->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">KHO XE</h4>
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
                                            <label>Số VIN</label>
                                            <input name="vin" placeholder="VIN" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-danger">Trạng thái</label>
                                            <select name="trangThai" class="form-control">
                                            'ORDER', 'P/O', 'MAP', 'HD', 'STORE', 'AGENT'
                                                <option value="ORDER">ORDER</option>
                                                <option value="P/O">P/O</option>
                                                <option value="MAP">MAP</option>
                                                <option value="STORE">STORE</option>
                                            </select>
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
                                                <option value="Xám_kim_loại">Xám_kim_loại</option>
                                                <option value="Đen">Đen</option>
                                                <option value="Vàng_cát">Vàng_cát</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Số máy</label>
                                            <input name="frame" placeholder="Số máy" type="text" class="form-control">
                                        </div>
                                        <div class="form-group" style="display:none;">
                                            <label>Ghi chú</label>
                                            <input name="ghiChu" placeholder="Ghi chú" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
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
                                            <label>GPS</label>
                                            <input name="gps" placeholder="GPS" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Vị trí</label>
                                            <select name="viTri" id="viTri" class="form-control">
                                                <option value="NULL">NULL</option>
                                                <option value="KHO">KHO_MOI</option>
                                                <option value="CONG_TY">CONG_TY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5>&nbsp;&nbsp;&nbsp;THÔNG TIN ĐƠN HÀNG HTV</h5>
                                <div class="card-body row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Số đơn hàng</label>
                                            <input name="soDonHang" placeholder="Số đơn hàng" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Số bảo lãnh NH</label>
                                            <input name="soBaoLanh" placeholder="Số bảo lãnh ngân hàng" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Ngày đặt</label>
                                            <input name="ngayDat" placeholder="Ngày đặt" type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Ngân hàng</label>
                                            <input name="nganHang" placeholder="Ngân hàng" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Ngày nhận xe</label>
                                            <input name="ngayNhanXe" placeholder="Ngày nhận xe" type="date" class="form-control">
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
                                            <label>Số VIN</label>
                                            <input name="evin" placeholder="VIN" type="text" class="form-control">
                                        </div>                                         
                                        <div class="form-group">
                                            <label class="text-danger">Trạng thái</label>
                                            <select name="etrangThai" class="form-control">
                                                <option value="ORDER">ORDER</option>
                                                <option value="P/O">P/O</option>
                                                <option value="MAP">MAP</option>
                                                <option value="STORE">STORE</option>
                                                <option value="HD">HD</option>
                                            </select>
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
                                                <option value="Xám_kim_loại">Xám_kim_loại</option>
                                                <option value="Đen">Đen</option>
                                                <option value="Vàng_cát">Vàng_cát</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Số máy</label>
                                            <input name="eframe" placeholder="Số máy" type="text" class="form-control">
                                        </div>
                                        <div class="form-group" style="display:none;">
                                            <label>Ghi chú</label>
                                            <input name="eghiChu" placeholder="Ghi chú" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Năm sản xuất</label>
                                            <select name="enam" class="form-control">
                                                @for($i = 2000; $i <= 2100; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>    
                                        <div class="form-group">
                                            <label>GPS</label>
                                            <input name="egps" placeholder="GPS" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Vị trí</label>
                                            <select name="eviTri" class="form-control">
                                                <option value="NULL">NULL</option>
                                                <option value="KHO">KHO_MOI</option>
                                                <option value="CONG_TY">CONG_TY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h5>&nbsp;&nbsp;&nbsp;THÔNG TIN ĐƠN HÀNG HTV</h5>
                                <div class="card-body row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Số đơn hàng</label>
                                            <input name="esoDonHang" placeholder="Số đơn hàng" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Số bảo lãnh NH</label>
                                            <input name="esoBaoLanh" placeholder="Số bảo lãnh ngân hàng" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Ngày đặt</label>
                                            <input name="engayDat" placeholder="Ngày đặt" type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Ngân hàng</label>
                                            <input name="enganHang" placeholder="Ngân hàng" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Ngày nhận xe</label>
                                            <input name="engayNhanXe" placeholder="Ngày nhận xe" type="date" class="form-control">
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
                ajax: "{{ url('management/kho/getkho/list') }}",
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
                    { "data": "type" },
                    { "data": "ten" },
                    { "data": "color" },
                    { "data": "vin" },
                    { "data": "frame" },
                    { "data": "gps" },
                    { "data": "soDonHang" },
                    { "data": "ngayDat" },
                    { "data": "soBaoLanh" },
                    { "data": "ngayNhanXe" },
                    { "data": "nganHang" },
                    { "data": "year" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                                "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
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
                // prevent double click
                var el = $(this);
                el.prop('disabled', true);
                setTimeout(function(){el.prop('disabled', false); }, 3000);
                // prevent double click   
                $.ajax({
                    url: "{{url('management/kho/getkho/add/')}}",
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
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/kho/getkho/delete/')}}",
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
                    url: "{{url('management/kho/getkho/edit/show/')}}",
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
                        $("input[name=esoDonHang]").val(response.data.soDonHang);
                        $("input[name=esoBaoLanh]").val(response.data.soBaoLanh);
                        $("input[name=engayDat]").val(response.data.ngayDat);
                        $("input[name=engayNhanXe]").val(response.data.ngayNhanXe);
                        $("input[name=enganHang]").val(response.data.nganHang);
                        $("select[name=etrangThai]").val(response.data.type);
                        $("input[name=eghiChu]").val(response.data.ghiChu);
                        $("input[name=egps]").val(response.data.gps);
                        $("select[name=eviTri]").val(response.data.viTri);
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        // $("input[name=ecost]").val(response.data.cost);
                        // if (response.data.exist == 1)
                        //     $("#exist1").prop('checked',true);
                        // if (response.data.order == 1)
                        //     $("#exist2").prop('checked',true);
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
                $.ajax({
                    url: "{{url('management/kho/getkho/update/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#editForm").serialize(),
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
            });
        });
    </script>
@endsection
