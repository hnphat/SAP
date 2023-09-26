@extends('admin.index')
@section('title')
    Danh sách hạng mục
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
                        <h1 class="m-0"><strong>Danh mục phụ kiện</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Danh mục phụ kiện</li>
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
                                            Quản lý hạng mục
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button> 
                                        <button id="import" class="btn btn-primary" data-toggle="modal" data-target="#importModal">Import Excel</button>
                                        <br/><br/>

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
                                                                    <!-- <div class="form-group">
                                                                        <label>Loại dịch vụ</label>
                                                                        <select name="isPK" class="form-control">
                                                                            <option value="1">Phụ kiện</option>
                                                                            <option value="0">Bảo hiểm</option>
                                                                        </select>
                                                                    </div> -->
                                                                    <div class="form-group">
                                                                        <label>Dòng xe</label>
                                                                        <select name="typeCar" class="form-control">
                                                                            @foreach($typecar as $row)
                                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Hình thức</label>
                                                                        <select name="loai" class="form-control">
                                                                            <option value="KTV lắp đặt">KTV lắp đặt</option>
                                                                            <option value="Gia công ngoài">Gia công ngoài</option>
                                                                            <option value="Tặng kèm">Tặng kèm</option>
                                                                            <option value="Bán thêm">Bán thêm</option>
                                                                        </select>
                                                                    </div>  
                                                                    <div class="form-group">
                                                                        <label>Mã</label>
                                                                        <input name="ma" type="text" class="form-control" placeholder="Mã">
                                                                    </div>   
                                                                    <div class="form-group">
                                                                        <label>Tên hạng mục</label>
                                                                        <input name="noiDung" type="text" class="form-control" placeholder="Tên hạng mục">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Đơn vị tính</label>
                                                                        <select name="dvt" class="form-control">
                                                                            <option value="Bộ">Bộ</option>
                                                                            <option value="Cái">Cái</option>
                                                                            <option value="Gói">Gói</option>
                                                                            <option value="Công">Công</option>
                                                                        </select>
                                                                    </div>                
                                                                    <div class="form-group">
                                                                        <label>Giá bán:</label>
                                                                        <input id="donGia" name="donGia" value="0" type="number" class="form-control" placeholder="Giá">
                                                                        <i id="showGia"></i>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Giá vốn:</label>
                                                                        <input id="giaVon" name="giaVon" value="0" type="number" class="form-control" placeholder="Giá">
                                                                        <i id="showGiaVon"></i>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Công KTV:</label>
                                                                        <input id="congKTV" name="congKTV" value="0" type="number" class="form-control" placeholder="Giá">
                                                                        <i id="showCongKTV"></i>
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
                                        
                                        <!-- Medal Export -->
                                        <div class="modal fade" id="importModal">
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
                                                                <h3 class="card-title">IMPORT EXCEL</h3>
                                                            </div>
                                                            <!-- /.card-header -->
                                                            <!-- form start -->
                                                            <form id="importForm" autocomplete="off" enctype>
                                                                {{csrf_field()}}
                                                                <div class="card-body">    
                                                                    <div class="form-group">
                                                                        <label>File mẫu</label>
                                                                        <a href="{{asset('store/ImportSampleDMPK.xlsx')}}" download>TẢI VỀ</a>
                                                                    </div>                                                                
                                                                    <!-- <div class="form-group">
                                                                        <label>Phương thức nhập</label>
                                                                        <select name="mode" class="form-control">
                                                                            <option value="0">Cập nhật dữ liệu đang có</option>
                                                                            <option value="1">Tạo mới</option>
                                                                        </select>
                                                                    </div> -->
                                                                    <div class="form-group">
                                                                        <label>Tệp</label>
                                                                        <input type="file" class="form-control" name="fileBase" id="fileBase">
                                                                        <span>Tối đa 10MB (xls, xlsx)</span>
                                                                    </div>                                                                   
                                                                </div>
                                                                <!-- /.card-body -->
                                                                <div class="card-footer">
                                                                    <button id="btnImport" class="btn btn-primary">Import</button>
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
                                                <th>Dòng xe</th>    
                                                <th>Loại</th>                                                
                                                <th>Mã</th>
                                                <th>Nội dung</th>
                                                <th>Đơn vị tính</th>
                                                <th>Giá vốn</th>
                                                <th>Giá bán</th>
                                                <th>Công KTV</th>                                               
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">CHỈNH SỬA</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editForm" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eid">
                            <div class="card-body">
                                <!-- <div class="form-group">
                                    <label>Loại dịch vụ</label>
                                    <select name="eisPK" class="form-control">
                                        <option value="1">Phụ kiện</option>
                                        <option value="0">Bảo hiểm</option>
                                    </select>
                                </div> -->
                                <div class="form-group">
                                    <label>Loại xe</label>
                                    <select name="etypeCar" class="form-control">
                                        @foreach($typecar as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Hình thức</label>
                                    <select name="eloai" class="form-control">
                                        <option value="KTV lắp đặt">KTV lắp đặt</option>
                                        <option value="Gia công ngoài">Gia công ngoài</option>
                                        <option value="Tặng kèm">Tặng kèm</option>
                                        <option value="Bán thêm">Bán thêm</option>
                                    </select>
                                </div>  
                                <div class="form-group">
                                    <label>Mã</label>
                                    <input name="ema" type="text" class="form-control" placeholder="Mã">
                                </div>   
                                <div class="form-group">
                                    <label>Tên hạng mục</label>
                                    <input name="enoiDung" type="text" class="form-control" placeholder="Tên hạng mục">
                                </div>
                                <div class="form-group">
                                    <label>Đơn vị tính</label>
                                    <select name="edvt" class="form-control">
                                        <option value="Bộ">Bộ</option>
                                        <option value="Cái">Cái</option>
                                        <option value="Gói">Gói</option>
                                        <option value="Công">Công việc</option>
                                    </select>
                                </div>                
                                <div class="form-group">
                                    <label>Giá bán:</label>
                                    <input id="edonGia" name="edonGia" type="number" class="form-control" placeholder="Giá">
                                    <i id="eshowGia"></i>
                                </div>   
                                <div class="form-group">
                                    <label>Giá vốn:</label>
                                    <input id="egiaVon" name="egiaVon" type="number" class="form-control" placeholder="Giá">
                                    <i id="eshowGiaVon"></i>
                                </div>  
                                <div class="form-group">
                                    <label>Công KTV:</label>
                                    <input id="econgKTV" name="econgKTV" type="number" class="form-control" placeholder="Giá">
                                    <i id="eshowCongKTV"></i>
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();

        // show data
        $(document).ready(function() {

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/dichvu/hangmuc/get/list') }}",
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
                    { "data": "namecar" },
                    { "data": "loai" },
                    { "data": "ma" },
                    { "data": "noiDung" },
                    { "data": "dvt" },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.giaVon);
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.donGia);
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.congKTV);
                        } 
                    },                 
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
          
            $('#donGia').keyup(function(){
                var cos = $(this).val();
                $('#showGia').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaVon').keyup(function(){
                var cos = $(this).val();
                $('#showGiaVon').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#congKTV').keyup(function(){
                var cos = $(this).val();
                $('#showCongKTV').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#edonGia').keyup(function(){
                var cos = $(this).val();
                $('#eshowGia').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#egiaVon').keyup(function(){
                var cos = $(this).val();
                $('#eshowGiaVon').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#econgKTV').keyup(function(){
                var cos = $(this).val();
                $('#eshowCongKTV').text("(" + DOCSO.doc(cos) + ")");
            });

            // Add data
            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/dichvu/hangmuc/guest/add/')}}",
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
                        url: "{{url('management/dichvu/hangmuc/guest/delete/')}}",
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
                    url: "{{url('management/dichvu/hangmuc/guest/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        // $("select[name=eisPK]").val(response.data.isPK);
                        $("input[name=ema]").val(response.data.ma);
                        $("input[name=enoiDung]").val(response.data.noiDung);
                        $("select[name=edvt]").val(response.data.dvt);
                        $("input[name=edonGia]").val(response.data.donGia);
                        $("input[name=egiaVon]").val(response.data.giaVon);
                        $("input[name=econgKTV]").val(response.data.congKTV);
                        $("select[name=eloai]").val(response.data.loai);
                        $("select[name=etypeCar]").val(response.data.loaiXe);
                        $('#eshowGia').text("(" + DOCSO.doc(response.data.donGia) + ")");
                        $('#eshowGiaVon').text("(" + DOCSO.doc(response.data.giaVon) + ")");
                        $('#eshowCongKTV').text("(" + DOCSO.doc(response.data.congKTV) + ")");
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
                    url: "{{url('management/dichvu/hangmuc/guest/update/')}}",
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
            });
        });

         //upload
         $(document).one('click','#btnImport',function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#importForm').submit(function(e) {
                e.preventDefault();   
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/dichvu/hangmuc/ajax/importfile/')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#btnImport").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => {
                        this.reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                        $("#btnImport").attr('disabled', false).html("IMPORT");                      
                        $("#importModal").modal('hide');
                        setTimeout(() => {
                            open("{{route('dichvu.hangmuc')}}","_self");
                        }, 3000);
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Có lỗi: ' + response.responseJSON.errors.fileBase
                        })
                        $("#btnImport").attr('disabled', false).html("IMPORT");  
                        console.log(response);
                    }
                });
            });                
        });
    </script>
@endsection
