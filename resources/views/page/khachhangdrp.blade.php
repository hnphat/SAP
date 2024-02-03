@extends('admin.index')
@section('title')
    Khách hàng DRP
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
                        <h1 class="m-0"><strong>Khách hàng DRP</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng DRP</li>
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
                                    <strong>Khách hàng DRP</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <button class="btn btn-info">Tiếp nhận</button>
                                &nbsp;&nbsp;
                                <a href="{{route('khachhang.question.drp')}}" class="btn btn-primary">Bảng câu hỏi</a>
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
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('cskh') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))
                                                    <option value="0">Tất cả</option>                                                               
                                                    @foreach($groupsale as $row)    
                                                        @if (!\Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))                                                    
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @elseif ($row['group'] == $groupid)
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @else
                                                        
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
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                        <tr class="bg-gradient-lightblue">
                                            <th>TT</th>
                                            <th>Người tạo</th>
                                            <th>Khách hàng</th>
                                            <th>Điện thoại</th>
                                            <th>Địa chỉ</th>
                                            <th>Xe quan tâm</th>
                                            <th>Trạng thái</th>
                                            <th>File đính kèm</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                    </thead>
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();
       
        $(document).ready(function(){
        //  $("#xemReport").click(function(){
        //     $.ajax({
        //         type: "post",
        //         url: "{{url('management/guest/loadkhachhangdrp/')}}",
        //         dataType: "text",
        //         data: {
        //             "_token": "{{csrf_token()}}",
        //             "nhanVien": $("select[name=nhanVien]").val(),
        //             "tu": $("input[name=chonNgayOne]").val(),
        //             "den": $("input[name=chonNgayTwo").val()
        //         },
        //         success: function(response) {
        //             Toast.fire({
        //                 icon: 'info',
        //                 title: " Đã gửi yêu cầu! "
        //             })
        //             $("#all").html(response);
        //         },
        //         error: function() {
        //             Toast.fire({
        //                 icon: 'warning',
        //                 title: " Lỗi!"
        //             })
        //         }
        //     });
        //  }); 
       });
    </script>
@endsection
