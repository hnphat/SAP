@extends('admin.index')
@section('title')
    Quản lý saler
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý saler</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Quản lý saler</li>
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
                                    <strong>Quản lý saler</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                              <form>
                                <div class="card-body row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Chọn nhân viên</label>
                                                <select name="nhanVien" class="form-control"> 
                                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('mkt'))
                                                    <option value="0">Tất cả</option>                                                               
                                                    @foreach($user as $row)
                                                        @if($row->hasRole('sale') && $row->active)
                                                            <option value="{{$row->id}}">{{$row->name}} - {{$row->userDetail->surname}}</option>
                                                        @endif
                                                    @endforeach   
                                                @else   
                                                    <option value="{{$iduser}}">{{$nameuser}}</option>
                                                @endif
                                                </select> <br/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Từ</label>
                                                <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Đến</label>
                                                <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <br/>
                                                <button id="xemReport" type="button" class="btn btn-info" class="form-control">Xem</button>
                                            </div>
                                        </div>
                                </div>
                              </form>
                              <hr/>
                              <div id="all">
       
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

       $(document).ready(function(){
            // $("#taiReport").click(function(){
            //     if(confirm('Xác nhận xuất dữ liệu excel')) {
            //         open("{{url('management/dichvu/exportexcel/')}}" 
            //     + "/" 
            //     + $("input[name=chonNgayOne]").val() 
            //     + "/den/" 
            //     + $("input[name=chonNgayTwo]").val()
            //     + "/loaibaocao/" 
            //     +  $("select[name=chonBaoCao]").val()
            //     + "/u/" 
            //     +  $("select[name=nhanVien]").val()
            //     ,'_blank');
            //     }
            // });


         $("#xemReport").click(function(){
            $.ajax({
                type: "post",
                url: "{{url('management/guest/loadbaocaokhachhang/')}}",
                dataType: "text",
                data: {
                    "_token": "{{csrf_token()}}",
                    "nhanVien": $("select[name=nhanVien]").val(),
                    "tu": $("input[name=chonNgayOne]").val(),
                    "den": $("input[name=chonNgayTwo").val()
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'info',
                        title: " Đã gửi yêu cầu! "
                    })
                    $("#all").html(response);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                }
            });
         });  
       });
    </script>
@endsection