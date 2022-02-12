@extends('admin.index')
@section('title')
    Quản lý xin phép
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
                        <h1 class="m-0"><strong>Quản lý xin phép</strong> 
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('lead') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            <a href="{{route('pheduyet.panel')}}" class="btn btn-xs btn-warning">Phê duyệt phép</a> &nbsp;
                            <a href="{{route('tangca.panel')}}" class="btn btn-xs btn-success">Phê duyệt tăng ca</a>
                        @endif
                    </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý xin phép</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
        <div class="container-fluid">
                <div class="row">  
                    <div class="col-md-1">
                        <label>Tháng</label>
                        <select name="thang" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Năm</label>
                        <select name="nam" class="form-control">
                            @for($i = 2021; $i < 2100; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Nhân viên</label>
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') || 
                        \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                        <select name="nhanVien" class="form-control">
                            @foreach($user as $row)
                                @if($row->active == true)
                                    <option value="{{$row->id}}">{{$row->name}} - {{$row->userDetail->surname}}</option>
                                @endif
                            @endforeach
                        </select>
                        @else
                        <select name="nhanVien" class="form-control">
                                <option value="{{Auth::user()->id}}">{{Auth::user()->userDetail->surname}}</option>
                        </select>
                        @endif
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="chon" type="button "class="btn btn-xs btn-info">Chọn</button>
                    </div>
                    <div class="col-md-3">
                        <br/>
                        <label>Phép năm còn lại: <strong id="conLai" class="text-success"></strong> (ngày)</label>
                        <label>Đã sử dụng: <strong id="daSuDung" class="text-danger"></strong> (ngày)</label>
                    </div>
                </div>  
                <br/>
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <th>Ngày xin phép</th>
                        <th>Loại Phép</th>
                        <th>Lý do</th>
                        <th>Buổi</th>
                        <th>Người duyệt</th>
                        <th>Trạng thái</th>
                        <th>Tác vụ</th>
                    </tr>
                    <tbody class="text-center" id="chiTietPhep">
                       
                    </tbody>                    
                </table>                  
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

        // Exe
        $(document).ready(function() {
            function reload() {
                $.ajax({
                    url: "{{url('management/nhansu/xinphep/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){                        
                        $("#chiTietPhep").html(response);                                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
           } 

           $("#chon").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/xinphep/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){                        
                        $("#chiTietPhep").html(response);                                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
           }).click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/xinphep/ajax/getphepnam')}}" + "/" + $("select[name=nhanVien]").val() + "/nam/" + $("select[name=nam]").val(),
                    type: "get",
                    dataType: "json",
                    success: function(response){                        
                        $("#conLai").text(response.conlai - response.dasudung);      
                        $("#daSuDung").text(response.dasudung);                               
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể tải phép năm"
                        })
                    }
                });
           });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/xinphep/ajax/delete/')}}",
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
                            reload();
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
        });
    </script>
@endsection
