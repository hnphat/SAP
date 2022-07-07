
@extends('admin.index')
@section('title')
    Quản lý xe lái thử
@endsection
@section('script_head')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý xe lái thử</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản lý lái thử</li>
                            <li class="breadcrumb-item active">Quản lý xe</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            @if(session('succ'))
                <div class="alert alert-success">
                    {{session('succ')}}
                </div>
            @endif
                @if(session('err'))
                    <div class="alert alert-warning">
                        {{session('err')}}
                    </div>
                @endif
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Quản lý xe lái thử</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                            @endif
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
                                                <form id="addForm" action="{{route('laithu.post')}}" method="post" autocomplete="off">
                                                    {{csrf_field()}}
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Tên xe</label>
                                                            <input type="text" name="tenXe" placeholder="Nhập tên xe" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Biển số</label>
                                                            <input type="text" name="bienSo" placeholder="Nhập biển số" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Màu sắc</label>
                                                            <select name="color" class="form-control">
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
                                                                <option value="Xe tải">Xe tải</option>
                                                            </select>
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
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Ngày tạo</th>
                                    <th>Tên xe</th>
                                    <th>Biển số</th>
                                    <th>Màu sắc</th>
                                    <th>Người sử dụng cuối</th>
                                    <th>Trạng thái</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($car as $row)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$row->created_at}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>{{$row->number_car}}</td>
                                            <td>{{$row->mau}}</td>
                                            <td>
                                                @if($row->user !== null)
                                                    {{$row->user->userDetail->surname}}
                                                @else
                                                    Không
                                                @endif
                                            </td>
                                            <td>
                                                @if($row->status == 'T')
                                                    <span id="setBoss" data-id="{{$row->id}}" class="btn btn-info btn-xs">
                                                        Trống                                                        
                                                    </span>
                                                @elseif($row->status == 'DSD')
                                                    <span class="btn btn-warning btn-xs">Đang sử dụng</span>
                                                @elseif($row->status == 'S')
                                                    <span id="setBlank" data-id="{{$row->id}}" class="btn btn-secondary btn-xs">Boss</span>
                                                @else
                                                    <span class="btn btn-danger btn-xs">Đang sửa chữa</span>
                                                @endif
                                            </td>
                                            <td>
                                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                                <button  data-toggle="modal" data-target="#editModal" id="edit" data-id="{{$row->id}}" class="btn btn-success btn-xs">
                                                        <span class="fas fa-edit">&nbsp;</span>
                                                </button>
                                                <button id="del" data-id="{{$row->id}}" class="btn btn-danger btn-xs">Xóa</button>
                                            @endif
                                                <button id="change" data-id="{{$row->id}}" class="btn btn-warning btn-xs">&nbsp;<span class="fas fa-exclamation">&nbsp;</span></button>
                                                <button id="showNow" data-id="{{$row->id}}" class="btn btn-info btn-xs">&nbsp;
                                                    @if($row->active == true)
                                                        <span title="Tắt hiển thị" class="fas fa-eye-slash">&nbsp;</span>
                                                    @else
                                                        <span title="Cho phép hiển thị" class="fas fa-eye">&nbsp;</span>
                                                    @endif
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- Medal -->
    <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Chỉnh sửa xe demo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form id="editForm" autocomplete="off">
                            {{csrf_field()}}         
                            <input type="hidden" name="idCar">    
                            <div class="form-group">
                                <label>Tên xe</label>
                                <input type="text" placeholder="Tên xe" name="etenXe" class="form-control">    
                            </div>
                            <div class="form-group">
                                <label>Biển số</label>
                                <input type="text" placeholder="Biển số" name="ebienSo" class="form-control">    
                            </div>
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
                                    <option value="Xe tải">Xe tải</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnEdit" class="btn btn-primary" form="editForm">Lưu</button>
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- Page specific script -->
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

        $(document).ready(function() {
            $("#dataTable").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        //Delete data
        $(document).on('click','#del', function(){
            if(confirm('Bạn có chắc muốn xóa?')) {
                $.ajax({
                    url: "{{url('management/laithu/del/')}}",
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
                        setTimeout(function(){
                            open('{{route('laithu.list')}}','_self');
                        }, 1000);
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

        //Get edit data
        $(document).on('click','#edit', function(){
            $.ajax({
                url: "{{url('management/laithu/getedit/')}}",
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
                    $("input[name=idCar]").val(response.data.id);
                    $("input[name=etenXe]").val(response.data.name);
                    $("input[name=ebienSo]").val(response.data.number_car);
                    $("select[name=ecolor]").val(response.data.mau);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: "Không thể xóa lúc này!"
                    })
                }
            });
        });


        //Change data
        $(document).on('click','#change', function(){
            if(confirm('Chuyển trạng thái xe?')) {
                $.ajax({
                    url: "{{url('management/laithu/change/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('laithu.list')}}','_self');
                        }, 2000);
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

        //Set boss
        $(document).on('click','#setBoss', function(){
            if(confirm('Chuyển trạng thái xe cho lãnh đạo sử dụng?')) {
                $.ajax({
                    url: "{{url('management/laithu/setboss/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('laithu.list')}}','_self');
                        }, 2000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không chuyển trạng thái lúc này!"
                        })
                    }
                });
            }
        });

         //Set blank
         $(document).on('click','#setBlank', function(){
            if(confirm('Xác nhận lãnh đạo đã trả xe?')) {
                $.ajax({
                    url: "{{url('management/laithu/setblank/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('laithu.list')}}','_self');
                        }, 2000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không chuyển trạng thái lúc này!"
                        })
                    }
                });
            }
        });

        // Post edit car
        $("#btnEdit").click(function(e){
            e.preventDefault();
            $.ajax({
                url: "{{url('management/laithu/update/')}}",
                type: "post",
                dataType: 'json',
                data: $("#editForm").serialize(),
                success: function(response) {
                    $("#editForm")[0].reset();
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })
                    $("#editModal").hide();
                    setTimeout(function(){
                            open('{{route('laithu.list')}}','_self');
                    }, 2000);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: "Lỗi"
                    })
                }
            });
        });

        //Show car
        $(document).on('click','#showNow', function(){
            if(confirm('Chuyển trạng thái hiển thị xe?')) {
                $.ajax({
                    url: "{{url('management/laithu/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('laithu.list')}}','_self');
                        }, 2000);
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
    </script>
@endsection
