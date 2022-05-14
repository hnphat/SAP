@extends('admin.index')
@section('title')
    Chi tiết vấn đề
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Chi tiết vấn đề</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Tra cứu cuộc họp - Chi tiết vấn đề</li>
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
                                    <strong>Chi tiết vấn đề</strong>
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
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
       
        // show data
        $(document).ready(function() {   
            $('#summernote').summernote({
                height: 150
            });
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
                                txt += `<span>${x.surname} <button id="deleteMem" data-idnoidung="${x.id_noidung}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
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
                    url: "{{url('management/cuochop/tracuuhop/loadchitietvande/')}}",
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
                        if (response.code == 200) {
                            $("#memShow").empty();
                            let txt = ``;
                            if (response.code == 200) {
                                let arr = response.data;
                                arr.forEach(function(x){
                                    txt += `<span>${x.surname} <button id="deleteMem" data-idnoidung="${x.id_noidung}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
                                });
                                $("#memShow").append(txt);
                            }
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

            $("#btnAddGopY").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/postgopy/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addFormGopY").serialize(),
                    success: function(response) {
                        $("#addFormGopY")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })   
                        load();  
                        $("#addModalGopY").modal('hide');
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });
            
            $("#btnAddSuaGopY").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/suagopy/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addFormSuaGopY").serialize(),
                    success: function(response) {
                        $("#addFormSuaGopY")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })   
                        load();  
                        $("#addModalSuaGopY").modal('hide');
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#capNhatBtn").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/capnhatketluan/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#capNhatForm").serialize(),
                    success: function(response) {
                        $("#capNhatForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })   
                        load();  
                        $("#capNhatModal").modal('hide');
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
                                txt += `<span>${x.surname} <button id="deleteMem" data-idnoidung="${x.id_noidung}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
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

        // delete member
        $(document).on('click','#deleteMem', function(e){
                e.preventDefault();
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
                if(confirm("Bạn có chắc muốn xoá thành viên này?")) {
                    $.ajax({
                        url: "{{url('management/cuochop/chitiethop/deletemem/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idnoidung": $(this).data('idnoidung'),
                            "iduser": $(this).data('iduser')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code == 200) {
                                $("#memShow").empty();
                                let txt = ``;
                                if (response.code == 200) {
                                    let arr = response.data;
                                    arr.forEach(function(x){
                                        txt += `<span>${x.surname} <button id="deleteMem" data-idnoidung="${x.id_noidung}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
                                    });
                                    $("#memShow").append(txt);
                                }
                            }    
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

            // Thêm góp ý
            $(document).on('click','#gopYBtn', function(){                   
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadgopy/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "idnoidung": $(this).data('idnoidung')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        
                        if (response.code == 200) {
                            $("input[name=idND]").val(response.data[0].id_noidung);
                            $("select[name=thanhVienGopY]").empty();
                            let txt = ``;
                            let arr = response.data;
                            arr.forEach(function(x){
                                txt += `<option value="${x.id_user}">${x.surname}</option>`;
                            });
                            $("select[name=thanhVienGopY]").html(txt);
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
            
            
            // delete data
        $(document).on('click','#xoaGopY', function(){
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
                    url: "{{url('management/cuochop/chitiethop/xoagopy/')}}",
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


            // load nội dung góp ý
            $(document).on('click','#editGopY', function(){                   
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadsuagopy/')}}",
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
                        $("input[name=eidGY]").val(response.data.id);
                        $("input[name=enoiDungGopY]").val(response.data.gopY);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }); 

            // load nội dung cập nhật
            $(document).on('click','#capNhatLoad', function(){                   
                $.ajax({
                    url: "{{url('management/cuochop/chitiethop/loadcapnhat/')}}",
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
                        $("input[name=idCapNhat]").val(response.data.id);
                        $("select[name=trangThai]").val(response.data.status);
                        $('#summernote').summernote('code', response.data.ketLuan);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }); 
    </script>
@endsection
