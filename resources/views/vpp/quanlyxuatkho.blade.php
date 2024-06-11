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
                            <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">
                                <strong>Duyệt đề nghị dụng cụ</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-2-tab" data-toggle="pill" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                                <strong>Duyệt đề nghị công cụ</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-3-tab" data-toggle="pill" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">
                                <strong>Lịch sử xuất/nhập</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                            <div class="row">                           
                                <div class="col-md-8">
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
                        <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tab-2-tab">
                            <div class="row">
                                <div class="col-md-8">
                                    <select name="chonPhieuCongCu" class="form-control">
                                        <option value="">Loading....</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button id="xemPhieuCongCu" type="button" class="btn btn-info btn-xs">Tải</button>
                                </div>  
                            </div>
                            <hr>
                            <div id="phieuTopCongCu" style="display:none;">
                                <h5>NGÀY: <span id="ngayYeuCauCongCu" class="text-pink"></span></h5>
                                <h5>NGƯỜI YÊU CẦU: <span id="nguoiYeuCauCongCu" class="text-blue"></span></h5>
                                <h5>PHIẾU YÊU CẦU CÔNG CỤ: 
                                    <strong class="text-info" id="maPhieuCongCu"></strong>                                     
                                </h5>
                                <h5>NỘI DUNG: <i><span id="noiDungCongCu"></span></i></h5>                            
                                <h5>TRẠNG THÁI: <span id="trangThaiCongCu"></span></h5>
                                <h5>TRẠNG THÁI NHẬN: <span id="trangThaiNhanCongCu"></span></h5>
                                <hr>
                            </div>
                            <div class="row container">
                                <form id="showFormCongCu">                               
                                    
                                </form>                            
                            </div>
                            <hr>   
                            <h3>Trạng thái công cụ</h3>
                            <table id="dataTableDuyetTra" class="display" style="width:100%">
                                <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Đang sử dụng</th>
                                        <th>Sử dụng từ</th>
                                        <th>Trạng thái</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>       
                        <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tab-3-tab">
                            <h5>Lịch sử xuất/nhập</h5>
                            <table id="dataTable" class="display" style="width:100%">
                                <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>ID</th>
                                        <th>Thời gian</th>
                                        <th>Tài khoản</th>
                                        <th>Nội dung</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                            </table>
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
            // Lịch sử
            let table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // processing: true,
                // serverSide: true,
                ajax: "{{ url('management/vpp/quanlyxuatkho/loadnhatky') }}",
                order: [[0, 'desc']],
                columns: [
                    { "data": "id"},
                    { "data": "ngay" },
                    { "data": "name" },
                    { "data": "noiDung" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "";
                        }
                    }
                ]
            });
            let tableDuyetTra = $('#dataTableDuyetTra').DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // processing: true,
                // serverSide: true,
                ajax: "{{ url('management/requestvpp/denghicongcu/congcu/dangsudung') }}",
                order: [[0, 'desc']],
                columns: [
                    { "data": "stt"},
                    { "data": "name" },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            if (row.deNghiTra != 0 && row.duyetTra != 0)
                                return `<strong class="text-warning">Không</strong>`;
                            else 
                                return row.noiDung;
                        } 
                    },
                    { "data": "ngay" },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                            if (row.deNghiTra == 0)
                                return `<strong class="text-info">Đang sử dụng</strong>`;
                            else if (row.duyetTra != 0)
                                return `<strong class="text-warning">Đã trả công cụ</strong><br/> <i class="text-secondary">(${row.ngayTra})</i>`;
                            else
                                return `<strong class="text-secondary">Yêu cầu trả</strong>`;
                        } },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.deNghiTra == 0)
                                return ``;
                            else if (row.duyetTra != 0)
                                return ``;
                            else
                                return `<button id="duyetTra" data-id=${row.idPhieuXuat} class="btn btn-warning btn-sm">Duyệt trả</button>
                            &nbsp;<button id="tuChoi" data-id=${row.idPhieuXuat} class="btn btn-danger btn-sm">Từ chối</button>`;
                        }
                    }
                ]
            });
            // -------------------------------
            // Load danh mục hàng hóa  
            let danhmuc = ``;     
            let danhmuccongcu = ``;     
            let phieuxuat = ``; 
            let phieuxuatcongcu = ``; 
            let showrow = ``;   
            let arr = 1;
            let arrCongCu = 1;
            let order = new Map();        
            let orderCongCu = new Map();    
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
                            danhmuc += `<option value="${x.id}">${x.tenSanPham} (${x.donViTinh})</option>`;
                        });                     
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
            });
            $.ajax({
                    url: "{{url('management/requestvpp/denghicongcu/loaddanhmuccongcu/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        response.data.forEach((x) => {
                            danhmuccongcu += `<option value="${x.id}">${x.tenSanPham} (${x.donViTinh})</option>`;
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
                        phieuxuat = ``;
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
                $.ajax({
                    url: "{{url('management/vpp/quanlyxuatkho/loadphieucongcu/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        $("select[name=chonPhieuCongCu]").empty();  
                        phieuxuatcongcu = ``;
                        response.data.forEach((x) => {
                            let stt = (x.duyet == 1) ? "Đã duyệt" : "Chưa duyệt";
                            phieuxuatcongcu += `<option value="${x.id}">ĐNCC-0${x.id}; 
                            Ngày yêu cầu ${x.ngay}-${x.thang}-${x.nam} (${stt})</option>`;
                        });       
                        $("select[name=chonPhieuCongCu]").append(phieuxuatcongcu);      
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
            function autoLoadCongCu() {
                $.ajax({
                    url: "{{url('management/vpp/quanlyxuatkho/loadphieuchitietcongcu/')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                       "idPX": $("select[name=chonPhieuCongCu]").val()
                    },
                    success: function(response) {    
                        arrCongCu = 1;
                        orderCongCu.clear();
                        $('#themHangHoaCongCu').hide(); 
                        $("#phieuTopCongCu").show();
                        $("#doiChieuCongCu").show();
                        $("#maPhieuCongCu").text("ĐNCC-0" + $("select[name=chonPhieuCongCu]").val()); 
                        $("#noiDungCongCu").text(response.noiDung);   
                        $("#ngayYeuCauCongCu").text(response.ngayXuat);   
                        $("#nguoiYeuCauCongCu").text(response.user);    
                        $("#trangThaiCongCu").html((response.status == 1) ? "<strong class='text-success'>Đã duyệt</strong>&nbsp;&nbsp;&nbsp;<button id='huyDuyetCongCu' class='btn btn-danger btn-sm'>Hoàn trạng</button>" : "<strong class='text-danger'>Chưa duyệt</strong>");        
                        $("#trangThaiNhanCongCu").html((response.statusNhan == 1) ? "<strong class='text-success'>Đã nhận</strong>" : "<strong class='text-danger'>Chưa nhận</strong>");           
                        $("#showFormCongCu").empty();  
                        showrow = ``;                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                      
                        response.data.forEach((x) => {  
                            orderCongCu.set(arrCongCu, x.id);                       
                            showrow += `<div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">                                           
                                            <select name="rhangHoaCongCu${arrCongCu}" disabled class="form-control">
                                                ${danhmuccongcu}
                                            </select>
                                        </div>
                                        <div class="col-md-6">                                           
                                            <input type="number" disabled name="rsoLuongCongCu${arrCongCu}" value="${x.soLuong}" class="form-control">
                                        </div>   
                                    </div>
                                </div>                                                                                              
                                `;
                            arrCongCu++;
                        });  
                        if (response.status == 0)
                            showrow += `<button id="duyetPhieuCongCu" form="showFormCongCu" class="btn btn-success">DUYỆT PHIẾU</button>`;
                        $("#showFormCongCu").append(showrow);    
                        orderCongCu.forEach((value,key)=>{
                            $("select[name=rhangHoaCongCu" + key + "]").val(value);
                        })    
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Không có yêu cầu nào để xem!"
                        })
                        $("#showFormCongCu").empty();
                        reload();
                        $("#phieuTopCongCu").hide();
                        $("#doiChieuCongCu").hide();
                        phieuxuatcongcu = ``;
                    }
                });
            }
            $("#xemPhieu").click(function(e){
                e.preventDefault();
                autoLoad();
            });   
            $("#xemPhieuCongCu").click(function(e){
                e.preventDefault();
                autoLoadCongCu();
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
                            phieuxuat = ``;
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

            $(document).on("click","#duyetPhieuCongCu", function(e){
                e.preventDefault();
                if (confirm('Xác nhận phê duyệt yêu cầu công cụ?')) {
                    $.ajax({
                        url: "{{url('management/vpp/quanlyxuatkho/duyetphieucongcu/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "phieu": $("select[name=chonPhieuCongCu]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("select[name=chonPhieuCongCu]").empty();
                            phieuxuatcongcu = ``;
                            setTimeout(autoLoadCongCu,3000);
                            setTimeout(reload,3000);
                            tableDuyetTra.ajax.reload();
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
                            phieuxuat = ``;
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

            $(document).on("click","#huyDuyetCongCu", function(){
                if (confirm('Xác nhận hoàn trạng (hủy bỏ phê duyệt) phiếu này!')) {
                    $.ajax({
                        url: "{{url('management/vpp/quanlyxuatkho/huyduyetphieucongcu/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "phieu": $("select[name=chonPhieuCongCu]").val()
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("select[name=chonPhieuCongCu]").empty();
                            phieuxuatcongcu = ``;
                            setTimeout(autoLoadCongCu,3000);
                            setTimeout(reload,3000);
                            tableDuyetTra.ajax.reload();
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

            $(document).on("click","#duyetTra", function(){
                if (confirm("Xác nhận duyệt trả công cụ?\nLưu ý: Kiểm tra kỹ chất lượng, số lượng công cụ\nSau khi duyệt công cụ sẽ quay về kho!\nTra cứu qua lịch sử xuất/nhập")) {
                    let idPhieuXuat = $(this).data('id');
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/duyettracongcu/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": idPhieuXuat
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })    
                            tableDuyetTra.ajax.reload();                
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể trả công cụ!'
                            })
                        }
                    }); 
                }
            });

            $(document).on("click","#tuChoi", function(){
                if (confirm("Xác nhận từ chối phê duyệt yêu cầu trả công cụ?")) {
                    let idPhieuXuat = $(this).data('id');
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/tuchoi/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": idPhieuXuat
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })    
                            tableDuyetTra.ajax.reload();                
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể trả công cụ!'
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
