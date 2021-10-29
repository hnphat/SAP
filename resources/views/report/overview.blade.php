
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
                        <h1 class="m-0"><strong>TỔNG HỢP BÁO CÁO</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Báo cáo</li>
                            <li class="breadcrumb-item active">TỔNG HỢP BÁO CÁO</li>
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
                                <select name="chonPhong" id="chonPhong" class="form-control">
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                                        <option value="pkd">Phòng kinh doanh</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('tpdv'))
                                        <option value="pdv">Phòng dịch vụ</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                                        <option value="ketoan">Phòng kế toán</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('xuong'))
                                        <option value="xuong">Xưởng</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('mkt'))
                                        <option value="mkt">Marketing</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('cskh'))
                                        <option value="cskh">CSKH</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                        \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))
                                        <option value="hcns">Hành chính - Nhân sự</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('it'))
                                        <option value="it">IT</option>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('watch')||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('drp'))
                                        <option value="ptdl">Phát triển đại lý</option>
                                    @endif
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
                                <div class="col-4">
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
                switch ($("select[name=chonPhong]").val()) {
                    case "pkd": {
                        url = "management/overview/getpkdall/";
                    } break;
                    case "pdv": {
                        url = "management/overview/getpdvall/";
                    } break;
                    case "xuong": {
                        url = "management/overview/getxuongall/";
                    } break;
                    case "mkt": {
                        url = "management/overview/getmktall/";
                    } break;
                    case "cskh": {
                        url = "management/overview/getcskhall/";
                    } break;
                    case "hcns": {
                        url = "management/overview/gethcnsall/";
                    } break;
                    case "it": {
                        url = "management/overview/getitall/";
                    } break;
                    case "ptdl": {
                        url = "management/overview/getptdlall/";
                    } break;
                    case "ketoan": {
                        url = "management/overview/getketoanall/";
                    } break;
                }
                $.ajax({
                    url: url + $('input[name=chonNgayOne]').val() + "/to/" + $('input[name=chonNgayTwo]').val(),
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

            function loadStatus() {
                $.ajax({
                    url: "management/overview/status/",
                    type: "get",
                    dataType: 'text',
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: " Đã tải thông tin báo cáo!"
                        })
                        $('#show').html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy báo cáo!"
                        })
                    }
                });
            }

            loadStatus();

            $(document).on("click","#watchMonthStatus",function(){
                $.ajax({
                    url: "management/overview/statusmonth/" + $('input[name=monthStatus]').val() + "/" + "room" + "/" + $('select[name=chonPhong]').val(),
                    type: "get",
                    dataType: 'text',
                    success: function(response) {
                        $('#show').html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tải thông tin tình trạng báo cáo!"
                        })
                    }
                });
            });
        });
    </script>
@endsection
