@extends('admin.index')
@section('title')
    Chi tiết cuộc họp
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Chi tiết cuộc họp</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Quản lý cuộc họp - Chi tiết cuộc họp</li>
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
                                    <strong>Chi tiết cuộc họp</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <input type="hidden" name="idHop1" value="{{$hop->id}}">

                                <h4>Cuộc họp: <span class="text-primary text-bold">{{$hop->tenCuocHop}}</span></h4>
                                <h5>Ngày tạo: 
                                    <span class="text-pink text-bold">
                                        Ngày {{$hop->ngay}} tháng {{$hop->thang}} năm {{$hop->nam}}
                                    </span>
                                </h5>
                                <button class="btn btn-secondary" data-toggle='modal' data-target='#addModal'>THÊM VẤN ĐỀ</button>
                                <hr>
                                <main id="noiDungChiTiet">

                                </main>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>   
    
    <!-- Medal Add -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">THÊM VẤN ĐỀ</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="addForm" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <div class="card-body">
                                <input type="hidden" name="idHop2" value="{{$hop->id}}">
                                <div class="form-group">
                                    <label>Tên vấn đề:</label>                                                        
                                    <input placeholder="Tên vấn đề" type="text" name="tenVanDe" class="form-control"/>
                                </div>                                    
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnAdd" class="btn btn-secondary">XÁC NHẬN</button>
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

     <!-- Medal Add Thành viên -->
     <div class="modal fade" id="addModalMem">
        <div class="modal-dialog modal-lg">
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
                            <h3 class="card-title">THÀNH VIÊN THAM GIA</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="addFormMem" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <div class="card-body">
                                <input type="hidden" name="idNoiDung">
                                <label>Thành viên tham gia:</label>     
                                <h5 id="memShow">
                                </h5>
                                <div class="form-group">
                                    <label>Bổ sung:</label>    
                                    <select name="thanhVien" id="thanhVien" class="form-control">
                                        @foreach($user as $row)
                                            <option value="{{$row->user->id}}">{{$row->user->userDetail->surname}}</option>
                                        @endforeach
                                    </select>                                                   
                                </div>      
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnAddMem" class="btn btn-primary">XÁC NHẬN</button>
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
       
        // show data
        $(document).ready(function() {   
            function loadMem(noidung) {
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadchitietmem/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "noidung": noidung
                    },
                    success: function(response) {                        
                        $("#memShow").empty();
                        let txt = ``;
                        if (response.code == 200) {
                            let arr = response.data;
                            arr.forEach(function(x){
                                txt += `<span>${x.surname} <button id="deleteMem" data-idhop="${x.id_hop}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
                            });
                            $("#memShow").append(txt);
                        }
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }

            function load() {
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadchitiet/')}}",
                    type: "post",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $("input[name=idHop1]").val()
                    },
                    success: function(response) {
                        $("#noiDungChiTiet").html(response);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }        
            load();
            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/postvande/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#addModal").modal('hide');  
                        load();           
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnAddMem").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/postmem/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addFormMem").serialize(),
                    success: function(response) {
                        $("#addFormMem")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })       
                        load();                 
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

        $(document).on('click','#themThanhVien', function(){
            $("input[name=idNoiDung]").val($(this).data('idnoidung'));
            function loadMem(noidung) {
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadchitietmem/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "noidung": noidung
                    },
                    success: function(response) {                        
                        $("#memShow").empty();
                        let txt = ``;
                        if (response.code == 200) {
                            let arr = response.data;
                            arr.forEach(function(x){
                                txt += `<span>${x.surname} <button id="deleteMem" data-idhop="${x.id_hop}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
                            });
                            $("#memShow").append(txt);
                        }
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }
            
            loadMem($(this).data('idnoidung'));
        });

        // delete data
        $(document).on('click','#delVanDe', function(){

            function load() {
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadchitiet/')}}",
                    type: "post",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $("input[name=idHop1]").val()
                    },
                    success: function(response) {
                        $("#noiDungChiTiet").html(response);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }        
        
            if(confirm("Bạn có chắc muốn xoá?")) {
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/xoavande/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('idvande')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        load();
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }
        });
    </script>
@endsection
