@extends('admin.index')
@section('title')
    TRA CỨU NÂNG CAO
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
                        <h1 class="m-0"><strong>Tra cứu nâng cao</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhật ký</li>
                            <li class="breadcrumb-item active">Nhật ký truy cập - Tra cứu nâng cao</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Tra cứu nâng cao</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <form method="post" action="{{route('nhatky.loadnhatkyv2')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nội dung tìm</label>
                                            <input type="text"
                                            @if(isset($noiDung))
                                            value="{{$noiDung}}"
                                            @endif name="str_find" class="form-control" placeholder="Nội dung thực hiện cần tìm">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Cần lấy bao nhiêu dữ liệu</label>
                                            <input type="number"
                                            @if(isset($soLuong))
                                            value="{{$soLuong}}"
                                            @endif name="num_row" value="50" min="0" max="2000" step="1" placeholder="Từ 1 đến 2000 bản ghi" class="form-control">
                                        </div>
                                        <div class="col-md-1">
                                            <br/>
                                            <input type="submit" value="TÌM KIẾM" name="gui" class="btn btn-success">
                                        </div>
                                    </div>
                                </form>
                                <hr/>
                                <div style="overflow:auto;">
                                    <table id="dataTable" style="width:100%">
                                        <thead>
                                        <tr class="bg-gradient-lightblue">
                                            <th>TT</th>
                                            <th>Thời gian</th>
                                            <th>Ngày</th>
                                            <th>Tài khoản</th>
                                            <th>Chức năng thao tác</th>
                                            <th>Nội dung thực hiện</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($nk))
                                            @foreach($nk as $row)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{\HelpFunction::revertCreatedAtGetTime($row->created_at)}}</td>
                                                    <td>{{\HelpFunction::getDateRevertCreatedAt($row->created_at)}}</td>
                                                    <td>{{(isset($row->user->userDetail) ? $row->user->userDetail->surname : "Not know user")}}</td>
                                                    <td>{{$row->chucNang}}</td>
                                                    <td>{!! $row->noiDung!!}</td>
                                                    <td>{{$row->ghiChu}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        // Exe
        $(document).ready(function() {
            $("#dataTable").DataTable({
                "responsive": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });                     
        });
    </script>
@endsection
