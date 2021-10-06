
@extends('admin.index')
@section('title')
    Đăng ký sử dụng
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
                        <h1 class="m-0"><strong>Đăng ký sử dụng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản lý lái thử</li>
                            <li class="breadcrumb-item active">Đăng ký sử dụng</li>
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
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Đăng ký sử dụng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="so-01-tab" data-toggle="pill" href="#so-01" role="tab" aria-controls="so-01" aria-selected="true">Trả xe</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
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
                                                    <h3 class="card-title">ĐĂNG KÝ</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <!-- form start -->
                                                <form id="addForm" action="{{route('reg.post')}}" method="post" autocomplete="off">
                                                    {{csrf_field()}}
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Chọn xe</label>
                                                            <select name="xe" class="form-control">
                                                                @foreach($car as $row)
                                                                    <option value="{{$row->id}}">{{$row->name}}; Biển số: {{$row->number_car}}; Màu: {{$row->mau}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Lý do sử dụng</label>
                                                            <input type="text" name="lyDo" placeholder="Lý do sử dụng" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Số km hiện tại (km)</label>
                                                            <input type="number" name="km" placeholder="Số km hiện tại" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Số xăng hiện tại (km xăng)</label>
                                                            <input type="number" name="xang" placeholder="Số xăng hiện tại" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tình trạng xe</label>
                                                            <input type="text" name="trangThaiXe" placeholder="Tình trạng xe" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Thời gian đi</label>
                                                            <input type="datetime-local" name="timeGo" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Thời gian về</label>
                                                            <input type="datetime-local" name="timeReturn" class="form-control">
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
                                    <th>Ngày đk</th>
                                    <th>Sử dụng</th>
                                    <th>Xe</th>
                                    <th>Lý do</th>
                                    <th>Km</th>
                                    <th>Xăng</th>
                                    <th>Tình trạng xe</th>
                                    <th>TG Đi</th>
                                    <th>Trạng thái</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reg as $row)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{\HelpFunction::revertCreatedAt($row->created_at)}}</td>
                                        <td>
                                            @if($row->user !== null)
                                                {{$row->user->userDetail->surname}}
                                            @else
                                                Không
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->xeLaiThu !== null)
                                                {{$row->xeLaiThu->name}}
                                            @else
                                                Không
                                            @endif
                                        </td>
                                        <td>{{$row->lyDo}}</td>
                                        <td>{{$row->km_current}}</td>
                                        <td>{{$row->fuel_current}}</td>
                                        <td>{{$row->car_status}}</td>
                                        <td>{{\HelpFunction::revertTimeInput($row->date_go)}}</td>
                                        <td>
                                            @if($row->allow == 1)
                                                <span class="btn btn-info btn-xs">Đã duyệt</span>
                                            @else
                                                <span class="btn btn-warning btn-xs">Đợi duyệt</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->allow == 1)
                                            @else
                                                <button id="del" data-id="{{$row->id}}" class="btn btn-danger btn-xs">Xóa</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="so-01" role="tabpanel" aria-labelledby="so-01-tab">
                            <table id="dataTable2" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Ngày đk</th>
                                    <th>Xe</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày trả</th>
                                    <th>Km</th>
                                    <th>Xăng</th>
                                    <th>Stt xe</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($traXe as $row)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{\HelpFunction::revertCreatedAt($row->created_at)}}</td>
                                        <td>
                                            @if($row->xeLaiThu !== null)
                                                {{$row->xeLaiThu->name}};
                                                {{$row->xeLaiThu->number_car}};
                                                {{$row->xeLaiThu->mau}}
                                            @else
                                                Không
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->tra_allow == 1)
                                                <span class="btn btn-success btn-xs">Đã trả</span>
                                            @else
                                                <span class="btn btn-warning btn-xs">Đang sử dụng</span>
                                            @endif
                                        </td>
{{--                                        <td>{{\HelpFunction::revertTimeInput($row->date_go)}}</td>--}}
                                        <td>{{$row->date_return}}</td>
                                        <td>{{$row->tra_km_current}}</td>
                                        <td>{{$row->tra_fuel_current}}</td>
                                        <td>{{$row->tra_car_status}}</td>
                                        <td>
                                            @if($row->request_tra == 0)
                                                <button id="tra" data-toggle="modal" data-target="#offCar" data-id="{{$row->id}}" class="btn btn-success btn-xs">Trả xe</button>
                                            @else
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- Medal trả xe -->
                            <div class="modal fade" id="offCar">
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
                                                    <h3 class="card-title">TRẢ XE</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <!-- form start -->
                                                <form id="offForm" action="{{route('reg.pay.post')}}" method="post" autocomplete="off">
                                                    {{csrf_field()}}
                                                    <div class="card-body">
                                                        <input type="hidden" name="_idOff" id="_idOff">
                                                        <div class="form-group">
                                                            <label>Số km hiện tại (km)</label>
                                                            <input type="number" name="_km" placeholder="Số km hiện tại" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Số xăng hiện tại (km xăng)</label>
                                                            <input type="number" name="_xang" placeholder="Số xăng hiện tại" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tình trạng xe</label>
                                                            <input type="text" name="_trangThaiXe" placeholder="Tình trạng xe" class="form-control">
                                                        </div>
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <div class="card-footer">
                                                        <button id="btnOff" class="btn btn-success">Xác nhận trả</button>
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
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.content -->
    </div>
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

        $(document).ready(function() {
            $("#dataTable2").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        //Delete data
        $(document).on('click','#del', function(){
            if(confirm('Bạn có chắc muốn xóa?')) {
                $.ajax({
                    url: "{{url('management/reg/del/')}}",
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
                            open('{{route('laithu.reg')}}','_self');
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

        //Trả xe
        $(document).on('click','#tra', function(){
            $.ajax({
                url: "management/reg/pay/" + $(this).data('id'),
                dataType: "json",
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                    $("input[name=_km]").val(response.data.tra_km_current);
                    $("input[name=_xang]").val(response.data.tra_fuel_current);
                    $("input[name=_trangThaiXe]").val(response.data.tra_car_status);
                    $("input[name=_idOff]").val(response.data.id);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: "Lỗi client liên hệ IT để được hỗ trợ!"
                    })
                }
            });
        });
    </script>
@endsection
