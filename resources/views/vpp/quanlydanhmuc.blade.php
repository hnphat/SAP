@extends('admin.index')
@section('title')
    Quản lý danh mục hàng hóa
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
                        <h1 class="m-0"><strong>Quản lý danh mục hàng hóa</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Quản lý nhập kho - quản lý danh mục</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                    <span class="fas fa-plus-circle"></span>
                </button><br/><br/>
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Quản lý danh mục hàng hóa</strong>
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
                                        <th>Tên nhóm hàng</th>
                                        <th>Tên danh mục</th>
                                        <th>Đơn vị tính</th>
                                        <th>Mô tả</th>
                                        <th>Loại</th>
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
                        <h4 class="modal-title">Thêm danh mục</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form id="addForm" autocomplete="off">
                            {{csrf_field()}}
                            <div class="form-group">
                               <label>Chọn nhóm hàng</label> 
                               <select name="nhomHang" class="form-control">
                                   @foreach($dm as $row)
                                        <option value="{{$row->id}}">{{$row->tenNhom}}</option>
                                   @endforeach
                               </select>
                            </div>          
                            <div class="form-group">
                               <label>Tên hàng hóa</label> 
                               <input type="text" class="form-control" name="tenSanPham" placeholder="Tên hàng hóa">
                            </div>     
                            <div class="form-group">
                               <label>Đơn vị tính</label> 
                               <select name="donViTinh" class="form-control">
                                    <option value="cái">cái</option>
                                    <option value="hộp">hộp</option>
                                    <option value="gói">gói</option>
                                    <option value="bộ">bộ</option>
                                    <option value="bao">bao</option>
                                    <option value="cây">cây</option>
                                    <option value="cục">cục</option>
                                    <option value="thùng">thùng</option>
                                    <option value="viên">viên</option>
                                    <option value="cặp">cặp</option>
                                    <option value="kg">kg</option>
                                    <option value="lít">lít</option>
                                    <option value="chai">chai</option>
                                    <option value="lọ">lọ</option>
                                    <option value="gram">gram</option>
                                    <option value="tờ">tờ</option>
                                    <option value="cuộn">cuộn</option>
                                    <option value="xấp">xấp</option>
                                    <option value="bịt">bịt</option>
                                    <option value="bình">bình</option>
                               </select>
                            </div>    
                            <div class="form-group">
                               <label>Mô tả</label> 
                               <input type="text" class="form-control" name="moTa" placeholder="Mô tả về hàng hóa">
                            </div>   
                            <div class="form-group">
                               <label>Loại sản phẩm<br/>Công cụ: Các thiết bị xuất kho sử dụng hao mòn trả lại kho khi không sử dụng<br/>Dụng cụ: Các dụng cụ xuất kho sử dụng 01 lần không trả lại kho</label> 
                               <select name="isCongCu" id="isCongCu" class="form-control" >
                                <option value="1">Công cụ  (Máy tính, tablet, điện thoại, sim, bộ đàm, ...)</option>
                                <option value="0" selected>Dụng cụ (VPP, giấy, bao thư, mực in, viết, kim bấm,...) </option>
                               </select>
                            </div>                       
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
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
                        <h4 class="modal-title">Cập nhật danh mục</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form id="editForm" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eid">
                            <div class="form-group">
                               <label>Chọn nhóm hàng</label> 
                               <select name="enhomHang" class="form-control">
                                   @foreach($dm as $row)
                                        <option value="{{$row->id}}">{{$row->tenNhom}}</option>
                                   @endforeach
                               </select>
                            </div>          
                            <div class="form-group">
                               <label>Tên hàng hóa</label> 
                               <input type="text" class="form-control" name="etenSanPham" placeholder="Tên hàng hóa">
                            </div>     
                            <div class="form-group">
                               <label>Đơn vị tính</label> 
                               <select name="edonViTinh" class="form-control">
                                    <option value="cái">cái</option>
                                    <option value="hộp">hộp</option>
                                    <option value="gói">gói</option>
                                    <option value="bộ">bộ</option>
                                    <option value="bao">bao</option>
                                    <option value="cây">cây</option>
                                    <option value="cục">cục</option>
                                    <option value="thùng">thùng</option>
                                    <option value="viên">viên</option>
                                    <option value="cặp">cặp</option>
                                    <option value="kg">kg</option>
                                    <option value="lít">lít</option>
                                    <option value="chai">chai</option>
                                    <option value="lọ">lọ</option>
                                    <option value="gram">gram</option>
                                    <option value="tờ">tờ</option>
                                    <option value="cuộn">cuộn</option>
                                    <option value="xấp">xấp</option>
                                    <option value="bịt">bịt</option>
                                    <option value="bình">bình</option>
                               </select>
                            </div>    
                            <div class="form-group">
                               <label>Mô tả</label> 
                               <input type="text" class="form-control" name="emoTa" placeholder="Mô tả về hàng hóa">
                            </div>            
                            <div class="form-group">
                               <label>Loại sản phẩm<br/>Công cụ: Các thiết bị xuất kho sử dụng hao mòn trả lại kho khi không sử dụng<br/>Dụng cụ: Các dụng cụ xuất kho sử dụng 01 lần không trả lại kho</label> 
                               <select name="eisCongCu" id="eisCongCu" class="form-control" >
                                <option value="1">Công cụ  (Máy tính, tablet, điện thoại, sim, bộ đàm, ...)</option>
                                <option value="0" selected>Dụng cụ (VPP, giấy, bao thư, mực in, viết, kim bấm,...) </option>
                               </select>
                            </div>                        
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
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

        // Exe
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/vpp/quanlydanhmuc/loaddanhmuc/') }}",
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
                    { "data": "tenNhom" },   
                    { "data": "tenSanPham" },  
                    { "data": "donViTinh" }, 
                    { "data": "moTa" },           
                    {
                        "data": null,
                        render: function(data, type, row) {
                           return (row.isCongCu) ? "<strong class='text-info'>Công cụ</strong>" : "<strong class='text-primary'>Dụng cụ</strong>";
                        }
                    },                    
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                            "<button id='btnDelete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
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
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/vpp/quanlydanhmuc/post/')}}",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: (response) => {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        table.ajax.reload();
                        $("#addModal").modal('hide');
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi: ' + response.responseJSON.message.toString()
                        })
                    }
                });
            });

            //Delete data
            $(document).on('click','#btnDelete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/vpp/quanlydanhmuc/delete/')}}",
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
                    url: "{{url('management/vpp/quanlydanhmuc/edit/')}}" + "/" + $(this).data('id'),
                    type: "get",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        $("select[name=enhomHang]").val(response.data.id_nhom);
                        $("select[name=edonViTinh]").val(response.data.donViTinh);
                        $("input[name=etenSanPham]").val(response.data.tenSanPham);
                        $("select[name=eisCongCu]").val(response.data.isCongCu);
                        $("input[name=emoTa]").val(response.data.moTa);
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

            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/vpp/quanlydanhmuc/update/')}}",
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
                            title: 'Lỗi: ' + response.responseJSON.message.toString()
                        })
                    }
                });
            });
        });
    </script>
@endsection
