
@extends('admin.index')
@section('title')
   Báo cáo ngày
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
                        <h1 class="m-0"><strong>BÁO CÁO CÔNG VIỆC</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Báo cáo</li>
                            <li class="breadcrumb-item active">Báo cáo công việc</li>
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
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <form id="phongBan">
                            <div class="form-group">
                                <select name="chonUser" id="chonUser" class="form-control">
                                    @foreach($user as $row)
                                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                            \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                            \Illuminate\Support\Facades\Auth::user()->hasRole('watch'))
                                            @if($row->hasRole('report'))
                                                <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                            @endif
                                        @elseif($row->hasRole('report') && $row->id == \Illuminate\Support\Facades\Auth::user()->id)
                                            <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <form id="xemFull">
                            <div class="form-group row">
                                <div class="col-4">
                                    <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                </div>
                                <div class="col-4">
                                    <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                </div>
                                <div class="col-2 text-center">
                                    <input type="checkbox" name="_all"> <br/>Tất cả
                                </div>
                                <div class="col-2">
                                    <button type="button" id="watchFull" class="btn btn-success">Xem</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="container" id="show">
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
        $(document).ready(function(){
            $("#watchFull").click(function(){
                let url = "";
                let _check = false;
                if ($('input[name=_all]').is(":checked"))
                {
                    _check = true;
                }
                $.ajax({
                    url: "management/overview/reportworkadmin/" +$("select[name=chonUser]").val() + "/date/"+ $('input[name=chonNgayOne]').val() + "/to/" + $('input[name=chonNgayTwo]').val() + "/check/" + _check,
                    type: "get",
                    dataType: 'text',
                    success: function(response) {
                        $('#show').html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy báo cáo!"
                        })
                    }
                });
            });
        });
    </script>
@endsection
