
@extends('admin.index')
@section('title')
    Duyệt trả xe
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
                        <h1 class="m-0"><strong>Duyệt trả</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản lý xe demo</li>
                            <li class="breadcrumb-item active">Duyệt trả xe</li>
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
                            <a class="nav-link" id="so-01-tab" data-toggle="pill" href="#so-01" role="tab" aria-controls="so-01" aria-selected="true">Duyệt trả xe</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-01" role="tabpanel" aria-labelledby="so-01-tab">
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Sử dụng</th>
                                        <th>Ngày đi</th>
                                        <th>Ngày trả</th>
                                        <th>Xe</th>
                                        <th>Km</th>
                                        <th>Xăng</th>
                                        <th>Tình trạng</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($traXe as $row)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                @if($row->user !== null)
                                                    {{$row->user->userDetail->surname}}
                                                @else
                                                    Không xác định
                                                @endif
                                            </td>
                                            <td>{{\HelpFunction::revertDate($row->date_go)}}</td>
                                            <td>{{$row->date_return}}</td>
                                            <td>
                                                @if($row->xeLaiThu !== null)
                                                    {{$row->xeLaiThu->name}};
                                                    {{$row->xeLaiThu->number_car}};
                                                    {{$row->xeLaiThu->mau}}
                                                @else
                                                    Không
                                                @endif
                                            </td>
                                            {{--                                        <td>{{\HelpFunction::revertTimeInput($row->date_go)}}</td>--}}
                                            <td>{{$row->tra_km_current}}</td>
                                            <td>{{$row->tra_fuel_current}}</td>
                                            <td>{{$row->tra_car_status}}</td>
                                            <td>
                                                @if($row->request_tra == true && $row->tra_allow == false)
                                                    <button id="duyetTra" data-id="{{$row->id}}" class="btn btn-success btn-xs">Duyệt trả</button>
                                                @else
                                                    <button class="btn btn-warning btn-xs">Đã trả xe</button>
                                                @endif
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
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });

        //Duyệt trả
        $(document).on('click','#duyetTra', function(){
            if(confirm('Phê duyệt trả xe và nhận xe!')) {
                $.ajax({
                    url: "{{url('management/duyet/approve/')}}",
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
                            open('{{route('laithu.duyet.pay')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không phê duyệt lúc này!"
                        })
                    }
                });
            }
        });
    </script>
@endsection
