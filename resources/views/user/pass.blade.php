@extends('admin.index')
@section('title')
    Đổi mật khẩu
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>THAY ĐỔI THÔNG TIN</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Thay đổi thông tin</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div>
                @if(session('loi'))
                    <div class="alert alert-danger">
                        {{session('loi')}}
                    </div>
                @endif
                <div class="row">
                        <div class="col-md-4">
                            <form id="changeInfo" autocomplete="off">
                                {{csrf_field()}}
                                <label>
                                    <input type="checkbox" name="passRequest" id="passRequest" />
                                    Đổi mật khẩu
                                </label>
                                <div class="form-group">
                                    <label for="oldPass">Mật khẩu cũ</label>
                                    <input autofocus="autofocus" type="password" name="oldPass" id="oldPass" class="form-control pass"/>
                                </div>
                                <div class="form-group">
                                    <label for="newPass">Mật khẩu mới</label>
                                    <input type="password" name="newPass" id="newPass" class="form-control pass"/>
                                </div>
                                <div class="form-group">
                                    <label for="newPassAgain">Nhập lại mật khẩu mới</label>
                                    <input type="password" name="newPassAgain" id="newPassAgain" class="form-control pass"/>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" value="{{$user->userDetail->phone}}" name="phone" id="phone" class="form-control" required="required" />
                                </div>
                            </form> 
                        </div>
                        <div class="col-md-4">
                               <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" value="{{$user->email}}" name="email" id="email" class="form-control" form="changeInfo" required="required" >
                                </div>
                                <div class="form-group">
                                    <label for="birthday">Ngày sinh</label>
                                    <input type="text" value="{{$user->userDetail->birthday}}" name="birthday" id="birthday" class="form-control" form="changeInfo"  required="required"/>
                                </div>
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" value="{{$user->userDetail->address}}" name="address" id="address" class="form-control" required="required" form="changeInfo"/>
                                </div>
                                <form id="upForm" autocomplete="off">
                                    {{csrf_field()}}
                                    <input type="hidden" name="up_id" value="{{$user->userDetail->id}}"/>   
                                    <div class="form-group">
                                        <label for="fileAnh">Ảnh đại diện</label>
                                        <input type="file" class="form-control" name="fileAnh" id="fileAnh">
                                                    <span>Tối đa 10MB (jpg,png,JPG,PNG)</span>
                                    </div>
                                    <div class="form-group">
                                        <button id="upload" class="btn btn-sm btn-info">CẬP NHẬT ẢNH</button> 
                                    </div>
                                </form>
                        </div>
                        <div class="col-md-4">
                            @if($user->userDetail && $user->userDetail->anh != null)
                                <img id="yourImage" style="max-width:400px; height:auto;" src="upload/hoso/{{$user->userDetail->anh}}" alt="Hình ảnh" />
                            @else
                                <h2><span class="badge badge-danger">Chưa có ảnh đại diện</span></h2>
                            @endif
                        </div>
                </div>
                <button id="updateInfo" type="button" class="btn btn-success" form="changeInfo">CẬP NHẬT THÔNG TIN</button>
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
    <!-- Page specific script -->
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $(document).ready(function(){
           $('.pass').attr('disabled','disabled');
           $("#updateInfo").click(function(e){
            e.preventDefault();
                $.ajax({
                    url: "{{url('management/user/change/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#changeInfo").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        if (response.code == 200) {
                            $('#passRequest').prop('checked', false); 
                            $('#oldPass').val("");
                            $('#newPass').val("");
                            $('#newPassAgain').val("");
                            $('.pass').attr('disabled','disabled');                            
                        }
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
           });
        });

        $(document).on('change','#passRequest', function(){
            if($(this).is(':checked'))
                $('.pass').removeAttr('disabled');
            else
                $('.pass').attr('disabled','disabled');
        });

        //upload
        $(document).one('click','#upload',function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#upForm').submit(function(e) {
                e.preventDefault();   
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/user/ajax/posttep/')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#upload").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => {
                        this.reset();
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        $("#upload").attr('disabled', false).html("CẬP NHẬT ẢNH");
                        setTimeout(() => {
                            $("#yourImage").attr('src','upload/hoso/'+response.newimage);
                        }, 3000);
                        console.log(response);
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Có lỗi Ảnh: ' + response.responseJSON.errors.fileAnh + " Hồ sơ: " + response.responseJSON.errors.fileHoso 
                        })
                        $("#upload").attr('disabled', false).html("CẬP NHẬT ẢNH");
                        console.log(response);
                    }
                });
            });                
        });
    </script>
@endsection
