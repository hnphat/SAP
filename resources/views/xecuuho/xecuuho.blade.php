@extends('admin.index')
@section('title')
    Quản lý xe cứu hộ
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
                        <h1 class="m-0"><strong>Quản lý xe cứu hộ</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản lý xe</li>
                            <li class="breadcrumb-item active">Quản lý xe cứu hộ</li>
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
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Quản lý xe cứu hộ</strong>
                                </a>
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
                                        <th>ID</th>
                                        <th>Ngày nhập</th>
                                        <th>Người nhập</th>
                                        <th>Khách hàng</th>
                                        <th>Yêu cầu</th>
                                        <th>Hình thức</th>
                                        <th>Báo giá</th>
                                        <th>Địa điểm đi</th>
                                        <th>Map</th>
                                        <th>Thời gian đi (dự kiến)</th>
                                        <th>Thời gian về (dự kiến)</th>
                                        <th>Doanh thu (nếu có)</th>
                                        <th>Ghi chú</th>
                                        <th>Trạng thái</th>
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
                        <h4 class="modal-title">Tạo lệnh cứu hộ/kéo xe</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            <div class="form-group">
                               <label>Họ tên khách hàng:</label> <br/>
                               <input type="text" name="khachHang" class="form-control" placeholder="Họ tên khách hàng" required>
                            </div>
                            <!-- <div class="form-group">
                               <label>Số điện thoại:</label> <br/>
                               <input type="text" name="sdt" class="form-control" placeholder="Họ tên khách hàng" required>
                            </div> -->
                            <div class="form-group">
                               <label>Yêu cầu:</label> <br/>
                               <input type="text" name="yeuCau" class="form-control" placeholder="Yêu cầu khách hàng" required>
                            </div>
                            <div class="form-group">
                               <label>Hình thức: </label> 
                               <select name="hinhThuc" class="form-control">
                                    <option value="Kéo xe kinh doanh">Kéo xe kinh doanh</option>
                                    <option value="Cứu hộ dịch vụ">Cứu hộ dịch vụ</option>
                                    <option value="Kết hợp">Kết hợp</option>                                    
                               </select>
                            </div>
                            <!-- <div class="form-group">
                               <label>Báo giá <span class="text-danger"><i>(.pdf, .docx, .jpg, .png không quá 5MB)</i></span></label> 
                               <input type="file" name="baoGia" class="form-control" required>
                            </div> -->
                            <div class="form-group">
                               <label>Địa điểm đi:</label> <br/>
                               <input type="text" name="diaDiemDi" class="form-control" placeholder="Địa điểm đi" required>
                            </div>
                            <div class="form-group">
                               <label>Thời gian đi:</label> <br/>
                               <input type="datetime-local" name="thoiGianDi" class="form-control" placeholder="Thời gian đi dự kiến" required>
                            </div>
                            <div class="form-group">
                               <label>Thời gian về:</label> <br/>
                               <input type="datetime-local" name="thoiGianVe" class="form-control" placeholder="Thời gian về dự kiến" required>
                            </div>
                            <div class="form-group">
                               <label>Ghi chú (nếu có)</label> 
                               <input type="text" name="ghiChu" class="form-control" placeholder="Ghi chú">
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
    </div>
    <!----------------------->
    <!--  MEDAL -->
    <div>
        <!-- Medal EDIT -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cập nhật thông tin</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" id="editForm" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eid">
                            <div class="form-group">
                               <label>Họ tên khách hàng:</label> <br/>
                               <input type="text" name="ekhachHang" class="form-control" placeholder="Họ tên khách hàng" required>
                            </div>
                            <!-- <div class="form-group">
                               <label>Số điện thoại:</label> <br/>
                               <input type="text" name="sdt" class="form-control" placeholder="Họ tên khách hàng" required>
                            </div> -->
                            <div class="form-group">
                               <label>Yêu cầu:</label> <br/>
                               <input type="text" name="eyeuCau" class="form-control" placeholder="Yêu cầu khách hàng" required>
                            </div>
                            <div class="form-group">
                               <label>Hình thức: </label> 
                               <select name="ehinhThuc" class="form-control">
                                    <option value="Kéo xe kinh doanh">Kéo xe kinh doanh</option>
                                    <option value="Cứu hộ dịch vụ">Cứu hộ dịch vụ</option>
                                    <option value="Kết hợp">Kết hợp</option>                                    
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Địa điểm đi:</label> <br/>
                               <input type="text" name="ediaDiemDi" class="form-control" placeholder="Địa điểm đi" required>
                            </div>
                            <div class="form-group">
                               <label>Thời gian đi:</label> <br/>
                               <input type="datetime-local" name="ethoiGianDi" class="form-control" placeholder="Thời gian đi dự kiến" required>
                            </div>
                            <div class="form-group">
                               <label>Thời gian về:</label> <br/>
                               <input type="datetime-local" name="ethoiGianVe" class="form-control" placeholder="Thời gian về dự kiến" required>
                            </div>
                            <div class="form-group">
                               <label>Doanh thu (nếu có):</label> <br/>
                               <input type="number" id="edoanhThu" name="edoanhThu" class="form-control" placeholder="Doanh thu (nếu có)" min="0">
                               <br/>
                               <input type="text" id="showDoanhThu" class="form-control" readonly> 
                            </div>
                            <div class="form-group">
                               <label>Ghi chú (nếu có)</label> 
                               <input type="text" name="eghiChu" class="form-control" placeholder="Ghi chú">
                            </div>
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') || \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))
                            <div class="form-group">
                               <label>Trạng thái</label> 
                               <select name="eallow" class="form-control">
                                   <option value="1">Ghi sổ</option>   
                                   <option value="0">Chưa ghi sổ</option>   
                               </select>
                            </div>
                            @endif
                        </form>
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
    <!--  MEDAL -->
    <div>
        <!-- Medal EDIT -->
        <div class="modal fade" id="upModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ĐÍNH KÈM BÁO GIÁ</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" id="upForm" enctype="multipart/form-data" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eidUp">                            
                            <div class="form-group">
                               <label>File báo giá đính kèm</label> 
                               <input type="file" name="edinhKem" class="form-control" required>
                            </div>                            
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnUp" class="btn btn-primary" form="upForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->

    <!--  MEDAL -->
    <div>
        <!-- Medal EDIT -->
        <div class="modal fade" id="upModalMap">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ĐÍNH KÈM FILE MAP (BẢN ĐỒ)</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" id="upFormMap" enctype="multipart/form-data" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eidUpMap">                            
                            <div class="form-group">
                               <label>File Map (bản đồ)</label> 
                               <input type="file" name="edinhKemMap" class="form-control" required>
                            </div>                            
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnUpMap" class="btn btn-primary" form="upFormMap">Lưu</button>
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

        // Exe
        $(document).ready(function() {
            $("#pressAdd").click(function() {
                setTimeout(() => {      
                    $("input[name=khachHang]").focus();
                }, 500);
            });

            $('#edoanhThu').keyup(function(){
                var cos = $('#edoanhThu').val();
                $('#showDoanhThu').val("(" + DOCSO.doc(cos) + ")");
            });

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/xecuuho/danhsach/') }}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 1, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "id" },
                    { "data": "newday" },
                    { "data": "nguoinhap" },
                    { "data": "khachHang" },
                    // { "data": "sdt" },
                    { "data": "yeuCau" },
                    { "data": "hinhThuc" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.baoGia) {
                                return "<a href='{{ asset('upload/xecuuho/')}}/"+row.baoGia+"' target='_blank' title='Xem file đính kèm'>Xem</a>"
                                + "&nbsp;<button id='xoaFile' data-id='"+row.id+"' class='btn btn-danger btn-sm' title='Xoá file đính kèm'><span class='fas fa-times-circle'></span></button>";
                            } else {
                                return "<button id='upFileBtn' data-id='"+row.id+"' data-toggle='modal' data-target='#upModal' class='btn btn-info btn-sm' title='Cập nhật file đính kèm'><span class='fas fa-upload'></span></button>";
                            }
                        }
                    },
                    { "data": "diaDiemDi" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.map) {
                                return "<a href='{{ asset('upload/xecuuho/')}}/"+row.map+"' target='_blank' title='Xem map'>Xem</a>"
                                + "&nbsp;<button id='xoaFileMap' data-id='"+row.id+"' class='btn btn-danger btn-sm' title='Xoá map'><span class='fas fa-times-circle'></span></button>";
                            } else {
                                return "<button id='upFileMapBtn' data-id='"+row.id+"' data-toggle='modal' data-target='#upModalMap' class='btn btn-info btn-sm' title='Cập nhật file map'><span class='fas fa-upload'></span></button>";
                            }
                        }
                    },
                    { "data": "newtimedi" },
                    { "data": "newtimeve" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.doanhThu) {
                                return "<strong class='text-success'>"+formatNumber(row.doanhThu)+"</strong>";
                            } else {
                                return "<strong class='text-danger'>Chưa có</strong>";
                            }
                        }
                    },
                    { "data": "ghiChu" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.allow) {
                                return "<span class='text-success'><strong>Đã ghi sổ</strong></span>";
                            } else {
                                return "<span class='text-danger'><strong>Chưa ghi sổ</strong></span>";
                            }
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.allow) {
                                return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>";
                            } else {
                                return "&nbsp;<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>"
                                + "&nbsp;<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>" +  "&nbsp;<button id='checkBlock' data-id='"+row.id+"' class='btn btn-primary btn-sm'><span class='fas fa-check-circle'></span></button>"; 
                            }
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
            $("#btnAdd").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#addForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ route('xecuuho.them')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Thao tác client có vấn đề'
                            })
                            $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/xecuuho/delete/')}}",
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

            //Delete data
            $(document).on('click','#xoaFile', function(){
                if(confirm('Xác nhận xoá báo giá đính kèm?')) {
                    $.ajax({
                        url: "{{url('management/xecuuho/delete/filescan')}}",
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

            //Delete map
            $(document).on('click','#xoaFileMap', function(){
                if(confirm('Xác nhận xoá map (bản đồ) đính kèm?')) {
                    $.ajax({
                        url: "{{url('management/xecuuho/delete/map')}}",
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
                    url: "{{url('management/xecuuho/getedit/')}}" + "/" + $(this).data('id'),
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        $("input[name=ekhachHang]").val(response.data.khachHang);
                        $("input[name=eyeuCau]").val(response.data.yeuCau);
                        $("select[name=ehinhThuc]").val(response.data.hinhThuc); 
                        $("input[name=ediaDiemDi]").val(response.data.diaDiemDi);
                        $("input[name=ethoiGianDi]").val(response.data.thoiGianDi);
                        $("input[name=ethoiGianVe]").val(response.data.thoiGianVe);
                        $("input[name=edoanhThu]").val(response.data.doanhThu);
                        $('#showDoanhThu').val("(" + DOCSO.doc(response.data.doanhThu) + ")");
                        $("input[name=eghiChu]").val(response.data.ghiChu);
                        $("select[name=eallow]").val(response.data.allow);
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $(document).on('click','#checkBlock', function(){
                if (confirm("Thực hiện ghi sổ?")) {
                    $.ajax({
                        url: "{{route('xecuuho.ghiso')}}",
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
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Error 500!"
                            })
                        }
                    });
                }
            });
            
            $(document).on('click','#upFileBtn', function(){
                $("input[name=eidUp]").val($(this).data('id'));
            });

            $(document).on('click','#upFileMapBtn', function(){
                $("input[name=eidUpMap]").val($(this).data('id'));
            });

            // Up file scan
            $(document).one('click','#btnUp', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#upForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ route('xecuuho.upfile')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnUp").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#upModal").modal('hide');
                            $("#btnUp").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'error',
                                 title: ' Lỗi: ' + response.responseJSON.message
                            })
                            $("#upModal").modal('hide');
                            $("#btnUp").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });

            // Up file map
            $(document).one('click','#btnUpMap', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#upFormMap').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ route('xecuuho.upfile.map')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnUpMap").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#upModalMap").modal('hide');
                            $("#btnUpMap").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'error',
                                title: ' Lỗi: ' + response.responseJSON.message
                            })
                            $("#upModalMap").modal('hide');
                            $("#btnUpMap").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });

            // Add data
            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('xecuuho.post.update')}}",
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
