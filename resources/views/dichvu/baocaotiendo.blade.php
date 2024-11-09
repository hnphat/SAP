@extends('admin.index')
@section('title')
    Báo cáo tiến độ
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
                        <h1 class="m-0"><strong>Báo cáo tiến độ</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Báo cáo tiến độ</li>
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
                                    <strong>Báo cáo tiến độ</strong>
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
                                                <label>Trạng thái</label>
                                                <select name="trangThai" id="trangThai" class="form-control"> 
                                                    <option value="0" selected>Tất cả</option>
                                                    <option value="1">Đang thực hiện (báo giá)</option>
                                                    <option value="2">Hoàn tất (báo giá)</option>
                                                    <option value="3">Đã hủy (báo giá)</option>
                                                    <option value="4">Chưa thu tiền (báo giá)</option>
                                                    <option value="5">Đã thu tiền (báo giá)</option>
                                                    <option value="6">Quá hạn (công việc)</option>
                                                    <option value="7">Chưa thực hiện (công việc)</option>
                                                    <option value="8">Đã thực hiện (công việc)</option>
                                                </select>
                                            </div>
                                            <p>
                                                <strong>Doanh thu tặng:</strong> <span class="text-primary text-bold">Đợi cập nhật</span><br/> 
                                                <strong>Doanh thu bán:</strong> <span class="text-info text-bold">Đợi cập nhật</span><br/> 
                                                <strong>Thực thu:</strong> <span class="text-success text-bold">Đợi cập nhật</span><br/> 
                                            </p>
                                        </div>                                   
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Chọn nhân viên</label>
                                                <select name="nhanVien" class="form-control"> 
                                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))                                                            
                                                    @foreach($user as $row)
                                                        @if($row->hasRole('to_phu_kien'))
                                                            <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                                        @endif
                                                    @endforeach   
                                                @else   
                                                    <option value="{{$iduser}}">{{$nameuser}}</option>
                                                @endif
                                                </select> <br/>
                                                <button id="xemReport" type="button" class="btn btn-info btn-xs">XEM</button>
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
         $("#xemReport").click(function(){
            $.ajax({
                type: "post",
                url: "{{url('management/dichvu/loadbaocaotiendo/')}}",
                dataType: "text",
                data: {
                    "_token": "{{csrf_token()}}",
                    "nhanVien": $("select[name=nhanVien]").val(),
                    "tu": $("input[name=chonNgayOne]").val(),
                    "den": $("input[name=chonNgayTwo").val(),
                    "loai": $("select[name=trangThai").val()
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
         
         function autoLoad() {
            $.ajax({
                type: "post",
                url: "{{url('management/dichvu/loadbaocaotiendo/')}}",
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
         }

        $(document).on('click','#hoanTat',function() {   
            if (confirm("Xác nhận hoàn tất công việc?\nLưu ý: Không thể hoàn lại sau khi đã xác nhận công việc!")) {
                $.ajax({
                    type: "post",
                    url: "{{url('management/dichvu/hoantatcongviec/')}}",
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
                        setTimeout(() => {
                            autoLoad();
                        }, 2000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi!"
                        })
                    }
                });
            }
        });

        $(document).on('click','#revert',function() {   
            if (confirm("Xác nhận hoàn trạng công việc?\nLưu ý: Sử dụng quyền hoàn trạng phù hợp!")) {
                $.ajax({
                    type: "post",
                    url: "{{url('management/dichvu/hoantrangcongviec/')}}",
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
                        setTimeout(() => {
                            autoLoad();
                        }, 2000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi!"
                        })
                    }
                });
            }
        });
    });
    </script>
@endsection
