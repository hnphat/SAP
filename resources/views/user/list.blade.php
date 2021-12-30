@extends('admin.index')
@section('title')
    Quản lý người dùng
@endsection
@section('script_head')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>QUẢN TRỊ NGƯỜI DÙNG</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Người dùng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                @if(session('loi'))
                    <div class="alert alert-danger">
                        {{session('loi')}}
                    </div>
                @endif
                <button class="btn btn-success" data-toggle="modal" data-target="#add"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <div class="card">
{{--                    <div class="card-header">--}}
{{--                        <h3 class="card-title"><strong>QUẢN TRỊ NGƯỜI DÙNG</strong></h3>--}}
{{--                    </div>--}}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tài khoản</th>
                                <th>Tên người dùng</th>
                                <th>Email</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody id="dataLoad">
                            @foreach($user as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>
                                        @if($row->userDetail != null)
                                            {{$row->userDetail->surname}}
                                        @endif
                                    </td>
                                    <td>{{$row->email}}</td>
                                    <td>
                                        <button onclick="edit('{{$row->id}}','{{$row->name}}','{{$row->email}}')" data-toggle="modal" data-target="#edit" class="btn btn-success btn-sm"><span class="far fa-edit"></span></button>
                                        <button onclick="lock('{{$row->id}}')" class="btn btn-dark btn-sm">
                                            @if($row->active == 1)
                                                <span class="fas fa-lock text-success"></span>
                                            @else
                                                <span class="fas fa-unlock text-danger"></span>
                                            @endif
                                        </button>
                                        <button onclick="xoa('{{$row->id}}')" class="btn btn-danger btn-sm"><span class="fas fa-times-circle"></span></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>

    <!-- Medal Add -->
    <div class="modal fade" id="add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">THÊM MỚI</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="ajaxform" autocomplete="off">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="acc">Tài khoản</label>
                                    <input required="required" type="text" class="form-control" id="acc" placeholder="Tên đăng nhập" name="acc">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="pass">Mật khẩu</label>
                                    <input type="password" class="form-control" id="pass" placeholder="Mật khẩu" name="pass">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="active" name="active">
                                    <label class="form-check-label" for="active">Kích hoạt</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="addBtn" type="button" class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Medal edit -->
    <div class="modal fade" id="edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">CHỈNH SỬA THÔNG TIN</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="ajaxform2" autocomplete="off">
                            <div class="card-body">
                                <input type="hidden" id="_id" name="_id"/>
                                <div class="form-group">
                                    <label for="acc">Tài khoản</label>
                                    <input required="required" type="text" class="form-control" id="acc2" placeholder="Tên đăng nhập" name="acc2">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email2" placeholder="Email" name="email2">
                                </div>
                                <div class="form-group">
                                    <label for="pass">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="pass2" placeholder="Mật khẩu" name="pass2">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="editBtn" type="button" class="btn btn-success">CẬP NHẬT</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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

        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#addBtn").click(function () {
                let name = $("input[name=acc]").val();
                let email = $("input[name=email]").val();
                let password = $("input[name=pass]").val();
                let active = 0;
                if ($('#active').is(":checked"))
                {
                    active = 1;
                }
                if (!$("input[name=acc]").val() || !$("input[name=pass]").val()) {
                    alert('Vui lòng nhập tài khoản hoặc mật khẩu');
                } else {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:'POST',
                        url:"{{ route('ajax.user.create') }}",
                        data:{
                            name: name,
                            email: email,
                            password: password,
                            active: active
                        },
                        success:function(data){
                            // alert(data.success);
                            $("#ajaxform")[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: data.success
                            })
                            setTimeout(function(){
                                open("{{route('user.list')}}","_self");
                            }, 1000);
                        }
                    });
                }
            });

            $("#editBtn").click(function () {
                let id = $("input[name=_id]").val();
                let name = $("input[name=acc2]").val();
                let email = $("input[name=email2]").val();
                let password = $("input[name=pass2]").val();
                 if (!$("input[name=pass2]").val() ) {
                    if (confirm('Bạn chưa nhập mật khẩu mới cho người dùng, nếu tiếp tục sẽ để mặc định là 123456')) {
                        password = 123456;
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type:'POST',
                            url:"{{ route('ajax.user.update') }}",
                            data:{
                                id: id,
                                name: name,
                                email: email,
                                password: password
                            },
                            success:function(data){
                                // alert(data.success);
                                $("#ajaxform2")[0].reset();
                                Toast.fire({
                                    icon: 'success',
                                    title: data.success
                                })
                                setTimeout(function(){
                                    open("{{route('user.list')}}","_self");
                                }, 1000);
                            }
                        });
                    }
                } else {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:'POST',
                        url:"{{ route('ajax.user.update') }}",
                        data:{
                            id: id,
                            name: name,
                            email: email,
                            password: password
                        },
                        success:function(data){
                            // alert(data.success);
                            $("#ajaxform2")[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: data.success
                            })
                            setTimeout(function(){
                                open("{{route('user.list')}}","_self");
                            }, 1000);
                        }
                    });
                }
            });
        });

        function xoa(id) {
            if (confirm("Bạn có chắc muốn xóa")){
                Toast.fire({
                    icon: 'info',
                    title: 'Đang thực hiện xóa'
                })
                setTimeout(function(){
                    open('management/user/del/' + id,'_self');
                }, 1000);
            }
        }

        function lock(id) {
            Toast.fire({
                icon: 'warning',
                title: 'Đã thay đổi kích hoạt'
            })
            setTimeout(function(){
                open('management/user/lock/' + id,'_self');
            }, 1000);
        }

        function edit(id,name,email) {
            document.getElementById("_id").value = id;
            document.getElementById("acc2").value = name;
            document.getElementById("email2").value = email;
        }

        //     $('.swalDefaultSuccess').click(function () {
        //         Toast.fire({
        //             icon: 'success',
        //             title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        //         })
        //     });
        //
        //     $('.swalDefaultInfo').click(function() {
        //         Toast.fire({
        //             icon: 'info',
        //             title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        //         })
        //     });
        //     $('.swalDefaultError').click(function() {
        //         Toast.fire({
        //             icon: 'error',
        //             title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        //         })
        //     });
        //     $('.swalDefaultWarning').click(function() {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        //         })
        //     });
        //     $('.swalDefaultQuestion').click(function() {
        //         Toast.fire({
        //             icon: 'question',
        //             title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        //         })
        //     });
        // });
    </script>
@endsection
