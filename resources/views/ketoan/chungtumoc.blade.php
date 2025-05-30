@extends('admin.index')
@section('title')
    Quản lý dấu/mộc
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
                        <h1 class="m-0"><strong>Quản lý chứng từ/mộc</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kế toán</li>
                            <li class="breadcrumb-item active">Quản lý dấu/mộc</li>
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
                                    <strong>Quản lý chứng từ/mộc</strong>
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
                                        <th>Ngày nhập</th>
                                        <th>File đính kèm</th>
                                        <th>Nội dung</th>
                                        <th>Số lượng</th>
                                        <th>Người ký</th>
                                        <th>Người yêu cầu</th>
                                        <th>Bộ phận</th>
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
                        <h4 class="modal-title">Thêm chứng từ mộc/dấu</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            <div class="form-group">
                               <label>Nội dung văn bản</label> <br/>
                               <i><strong class="text-danger">Lưu ý: Nội dung không được trùng tên với các nội dung khác</strong></i>
                               <input type="text" name="noiDung" class="form-control" placeholder="Nội dung văn bản" required>
                            </div>
                            <div class="form-group">
                               <label>Người ký</label> 
                               <select name="lanhDao" class="form-control">
                                    <option value="Huỳnh Tấn Tài">Huỳnh Tấn Tài</option>
                                    <option value="Nguyễn Thị Bích Ngân">Nguyễn Thị Bích Ngân</option>
                                    <option value="Nguyễn Quốc Đạt">Nguyễn Quốc Đạt</option>
                                    <option value="Triệu Quang Trung">Triệu Quang Trung</option>
                                    <option value="Trương Thị Kim Cúc">Trương Thị Kim Cúc</option>
                                    <option value="Khác">Khác</option>
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Số lượng bản đóng dấu</label> 
                               <input type="number" name="soLuong" class="form-control" min="1" max="1000" step="1" value="1" required placeholder="Số lượng bản đóng dấu">
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
                        <h4 class="modal-title">Điều chỉnh đề nghị mộc/dấu</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" id="editForm" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eid">
                            <div class="form-group">
                               <label>Nội dung văn bản</label> <br/>
                               <input type="text" name="enoiDung" class="form-control" placeholder="Nội dung văn bản" required>
                            </div>
                            <div class="form-group">
                               <label>Người ký</label> 
                               <select name="elanhDao" class="form-control">
                                    <option value="Huỳnh Tấn Tài">Huỳnh Tấn Tài</option>
                                    <option value="Nguyễn Thị Bích Ngân">Nguyễn Thị Bích Ngân</option>
                                    <option value="Nguyễn Quốc Đạt">Nguyễn Quốc Đạt</option>
                                    <option value="Triệu Quang Trung">Triệu Quang Trung</option>
                                    <option value="Trương Thị Kim Cúc">Trương Thị Kim Cúc</option>
                                    <option value="Khác">Khác</option>
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Số lượng bản đóng dấu</label> 
                               <input type="text" name="esoLuong" min="1" max="1000" step="1" value="1" class="form-control" placeholder="Số lượng bản đóng dấu" required>
                            </div>
                            <div class="form-group">
                               <label>Ghi chú (nếu có)</label> 
                               <input type="text" name="eghiChu" class="form-control" placeholder="Ghi chú">
                            </div>
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            <div class="form-group">
                               <label>Trạng thái</label> 
                               <select name="eallow" class="form-control">
                                   <option value="1">Đã tiếp nhận</option>   
                                   <option value="0">Chưa tiếp nhận</option>   
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
                        <h4 class="modal-title">CẬP NHẬT FILE SCAN</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" id="upForm" enctype="multipart/form-data" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="eidUp">                            
                            <div class="form-group">
                               <label>File đính kèm</label> 
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
                ajax: "{{ url('management/hanhchinh/ajax/loadchungtu/') }}",
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
                    { "data": "ngay" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.url !== null) {
                               return "<a href='upload/chungtu/"+row.url+"' target='_blank'>Tải về</a>&nbsp;<button data-id='"+row.id+"' id='xoaFile' class='btn btn-light btn-sm'>Xóa</button>";
                            }
                            else 
                               return "<strong class='text-warning'>Chưa có file</strong>&nbsp;<button id='upFileBtn' data-id='"+row.id+"' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#upModal'>Update</button>";
                        }
                    },
                    { "data": "noiDung" },
                    { "data": "soLuong" },
                    { "data": "nguoiKy" },
                    { "data": "nguoiYeuCau" },
                    { "data": "boPhan" },
                    { "data": "ghiChu" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.allow == true)
                                return "<strong class='text-success'>Đã tiếp nhận</strong>";
                            else
                                return "<strong class='text-danger'>Chưa tiếp nhận</strong>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.allow == true) {
                                return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>";
                            } else {
                                return "<button id='checkBlock' data-id='"+row.id+"' class='btn btn-primary btn-sm'><span class='fas fa-check-circle'></span></button>"
                                + "&nbsp;<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>"
                                + "&nbsp;<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>"; 
                                // return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;" +
                                // "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";       
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
                        url: "{{ route('denghichungtu.post')}}",
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
                        url: "{{url('management/hanhchinh/chungtuajax/delete/')}}",
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
                if(confirm('Xác nhận xoá file scan?')) {
                    $.ajax({
                        url: "{{url('management/hanhchinh/chungtuajax/delete/filescan')}}",
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
                    url: "{{url('management/hanhchinh/chungtuajax/getedit/')}}" + "/" + $(this).data('id'),
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        $("input[name=enoiDung]").val(response.data.noiDung);
                        $("input[name=esoLuong]").val(response.data.soLuong);
                        $("input[name=enguoiYeuCau]").val(response.data.nguoiYeuCau); 
                        $("select[name=elanhDao]").val(response.data.nguoiKy);      
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
                if (confirm("Thực hiện tiếp nhận yêu cầu?\nLưu ý: Sau khi tiếp nhận không thể thực hiện xoá yêu cầu.")) {
                    $.ajax({
                        url: "{{route('chungtu.block')}}",
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
                        url: "{{ route('upfile.post')}}",
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
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#upModal").modal('hide');
                            $("#btnUp").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Thao tác client có vấn đề'
                            })
                            $("#upModal").modal('hide');
                            $("#btnUp").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });

            // Add data
            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('chungtu.post.update')}}",
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
