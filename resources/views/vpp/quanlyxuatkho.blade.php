@extends('admin.index')
@section('title')
    Quản lý xuất kho
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý xuất kho</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Quản lý xuất kho</li>
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
                                <strong>Quản lý xuất kho</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="row">                           
                            <div class="col-md-4">
                                <select name="chonPhieu" class="form-control">
                                    <option value="">Loading....</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button id="xemPhieu" type="button" class="btn btn-info btn-xs">Tải</button>
                            </div>  
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="phieuTop" style="display:none;">
                                    <h5>NGÀY: <span id="ngayYeuCau" class="text-pink"></span></h5>
                                    <h5>NGƯỜI YÊU CẦU: <span id="nguoiYeuCau" class="text-blue"></span></h5>
                                    <h5>MÃ PHIẾU: 
                                        <strong class="text-info" id="maPhieu"></strong>                                 
                                    </h5>
                                    <h5>NỘI DUNG YÊU CẦU (XUẤT KHO): <i><span id="noiDung"></span></i></h5>
                                    <h5>TRẠNG THÁI: <span id="trangThai"></span></h5>
                                    <h5>TRẠNG THÁI NHẬN: <span id="trangThaiNhan"></span></h5>
                                    <hr/>
                                    <h5>HÀNG HÓA YÊU CẦU <button id="themHangHoa" class="btn btn-success btn-sm" style="display:none;"><strong>Bổ sung</strong></button></h5>
                                </div>
                            </div>                            
                        </div>
                        <div class="row container">
                            <div id="showForm">                               
                                
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
    <!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
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
            // Load danh mục hàng hóa  
            let danhmuc = ``;     
            let phieuxuat = ``;   
            let showrow = ``;   
            let arr = 1;
            let order = new Map();          
            // -----   
            $.ajax({
                    url: "{{url('management/requestvpp/denghicongcu/loaddanhmuc/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        response.data.forEach((x) => {
                            danhmuc += `<option value="${x.id}">${x.tenSanPham}</option>`;
                        });                     
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
            });

            // Load danh sách phiếu nhập
            function reload() {
                $.ajax({
                    url: "{{url('management/vpp/quanlyxuatkho/loadphieu/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        $("select[name=chonPhieu]").empty();  
                        response.data.forEach((x) => {
                            let stt = (x.duyet == 1) ? "Đã duyệt" : "Chưa duyệt";
                            phieuxuat += `<option value="${x.id}">PXK-0${x.id}; 
                            Ngày yêu cầu ${x.ngay}-${x.thang}-${x.nam} (${stt})</option>`;
                        });       
                        $("select[name=chonPhieu]").append(phieuxuat);      
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }           
            reload();
            // -----             
            function autoLoad() {
                $.ajax({
                    url: "{{url('management/vpp/quanlyxuatkho/loadphieuchitiet/')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                       "idPX": $("select[name=chonPhieu]").val()
                    },
                    success: function(response) {    
                        arr = 1;
                        order.clear();
                        $('#themHangHoa').hide(); 
                        $("#phieuTop").show();
                        $("#doiChieu").show();
                        $("#maPhieu").text("PXK-0" + $("select[name=chonPhieu]").val()); 
                        $("#noiDung").text(response.noiDung);   
                        $("#ngayYeuCau").text(response.ngayXuat);   
                        $("#nguoiYeuCau").text(response.user);    
                        $("#trangThai").html((response.status == 1) ? "<strong class='text-success'>Đã duyệt</strong>&nbsp;&nbsp;&nbsp;<button id='huyDuyet' class='btn btn-danger btn-sm'>Hoàn trạng</button>" : "<strong class='text-danger'>Chưa duyệt</strong>");        
                        $("#trangThaiNhan").html((response.statusNhan == 1) ? "<strong class='text-success'>Đã nhận</strong>" : "<strong class='text-danger'>Chưa nhận</strong>");           
                        $("#showForm").empty();  
                        showrow = ``;                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                      
                        response.data.forEach((x) => {  
                            order.set(arr, x.id);                       
                            showrow += `<div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">                                           
                                            <select name="rhangHoa${arr}" disabled class="form-control">
                                                ${danhmuc}
                                            </select>
                                        </div>
                                        <div class="col-md-6">                                           
                                            <input type="number" disabled name="rsoLuong${arr}" value="${x.soLuong}" class="form-control">
                                        </div>   
                                    </div>
                                </div>                                                                                              
                                `;
                            arr++;
                        });  
                        if (response.status == 0)
                            showrow += `<button id="duyetPhieu" class="btn btn-success">DUYỆT PHIẾU</button>`;
                        $("#showForm").append(showrow);    
                        order.forEach((value,key)=>{
                            $("select[name=rhangHoa" + key + "]").val(value);
                        })    
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Không có yêu cầu nào để xem!"
                        })
                        $("#showForm").empty();
                        reload();
                        $("#phieuTop").hide();
                        $("#doiChieu").hide();
                        phieuxuat = ``;
                    }
                });
            }
            $("#xemPhieu").click(function(e){
                e.preventDefault();
                autoLoad();
            });   

            $(document).on("click","#duyetPhieu", function(){
                if (confirm('Xác nhận phê duyệt yêu cầu công cụ')) {
                    $.ajax({
                        url: "{{url('management/vpp/quanlyxuatkho/duyetphieu/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "phieu": $("select[name=chonPhieu]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("select[name=chonPhieu]").empty();
                            phieunhap = ``;
                            setTimeout(autoLoad,3000);
                            setTimeout(reload,3000);
                        },
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể duyệt!"
                            })
                        }
                    });
                }
            });  

            $(document).on("click","#huyDuyet", function(){
                if (confirm('Xác nhận hoàn trạng (hủy bỏ phê duyệt) phiếu này!')) {
                    $.ajax({
                        url: "{{url('management/vpp/quanlyxuatkho/huyduyetphieu/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "phieu": $("select[name=chonPhieu]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("select[name=chonPhieu]").empty();
                            phieunhap = ``;
                            setTimeout(autoLoad,3000);
                            phieunhap = ``;
                            setTimeout(reload,3000);
                        },
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể duyệt!"
                            })
                        }
                    });
                }
            });

            // -- event realtime
            let es = new EventSource("{{route('vpp.refresh')}}");
            es.onmessage = function(e) {               
                let fullData = JSON.parse(e.data);
                // console.log(fullData);
                if (fullData.flag == true) {                   
                    autoLoad();                
                } 
            }
            // -- event realtime       
        });
    </script>
@endsection
