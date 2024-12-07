@extends('admin.index')
@section('title')
    Báo cáo tiến độ
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        thead th { position: sticky; top: 0;}
    </style>
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
                                                    <!-- <option value="1">Đang thực hiện (báo giá)</option>
                                                    <option value="2">Hoàn tất (báo giá)</option>
                                                    <option value="3">Đã hủy (báo giá)</option>
                                                    <option value="4">Chưa thu tiền (báo giá)</option>
                                                    <option value="5">Đã thu tiền (báo giá)</option> -->
                                                    <!-- <option value="6">Quá hạn (công việc)</option> -->
                                                    <option value="7">Chưa thực hiện (công việc)</option>
                                                    <option value="8">Đã thực hiện (công việc)</option>
                                                </select>
                                            </div>
                                            <p>
                                                <strong>Doanh thu tặng:</strong> <span class="text-primary text-bold" id="doanhthutang">.....</span><br/> 
                                                <strong>Doanh thu bán:</strong> <span class="text-info text-bold" id="doanhthuban">.....</span><br/> 
                                                <strong>Thực thu:</strong> <span class="text-success text-bold" id="thucthu">.....</span><br/> 
                                            </p>
                                        </div>                                   
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Chọn nhân viên</label>
                                                <select name="nhanVien" class="form-control">                                                     
                                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))  
                                                    <option value="0">Tất cả</option>                                                          
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
                              <div>
                                <!-- <div style='overflow:auto;'> -->
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                            <tr>
                                                <th class="table-light">STT</th>
                                                <th class="table-light">Trạng thái</th>   
                                                <th class="table-light">Thu tiền</th>
                                                <th class="table-light">Tác vụ</th>            
                                                <th class="table-light">Loại báo giá</th>
                                                <th class="table-light">Ngày tạo</th>
                                                <th class="table-light">Sale</th>
                                                <th class="table-light">Mã lệnh</th>
                                                <th class="table-light">Biển số</th>
                                                <th class="table-light">Số khung</th>
                                                <th class="table-light">Khách hàng</th>
                                                <th class="table-light">Thông tin xe</th>
                                                <th class="table-light">Công việc</th>  
                                                <th class="table-light">Phân loại</th>      
                                                <th class="table-light">Tặng</th>  
                                                <th class="table-light">Giá trị</th>                   
                                                <th class="table-light">Xe vào</th>
                                                <th class="table-light">Xe ra (dự kiến)</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody id="all">
                                        </tbody>
                                    </table>
                                <!-- </div> -->
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();


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

            $.ajax({
                type: "post",
                url: "{{url('management/dichvu/loadbaocaotiendo/loaddoanhthu')}}",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "nhanVien": $("select[name=nhanVien]").val(),
                    "tu": $("input[name=chonNgayOne]").val(),
                    "den": $("input[name=chonNgayTwo").val(),
                    "loai": $("select[name=trangThai").val()
                },
                success: function(response) {
                    $("#doanhthutang").text(formatNumber(parseInt(response.doanhthutang)));
                    $("#doanhthuban").text(formatNumber(parseInt(response.doanhthuban)));
                    $("#thucthu").text(formatNumber(parseInt(response.thucthu)));
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
