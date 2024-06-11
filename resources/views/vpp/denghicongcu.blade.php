@extends('admin.index')
@section('title')
    Đề nghị công cụ
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
                        <h1 class="m-0"><strong>Đề nghị công cụ/dụng cụ</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Đề nghị công cụ</li>
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
                                <strong>Đề nghị dụng cụ</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-2-tab" data-toggle="pill" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                                <strong>Đề nghị công cụ</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addModal">THÊM YÊU CẦU</button>
                                </div>
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
                            <div id="phieuTop" style="display:none;">
                                <h5>NGÀY: <span id="ngayYeuCau" class="text-pink"></span></h5>
                                <h5>PHIẾU YÊU CẦU DỤNG CỤ: 
                                    <strong class="text-info" id="maPhieu"></strong> 
                                    <button id="xoaPhieu" class="btn btn-danger btn-sm">Xóa yêu cầu</button>
                                    <button id="suaPhieu" class="btn btn-secondary btn-sm">Cập nhật yêu cầu</button>
                                </h5>
                                <h5>NỘI DUNG: <i><span id="noiDung"></span></i></h5>                            
                                <h5>TRẠNG THÁI: <span id="trangThai"></span></h5>
                                <h5>TRẠNG THÁI NHẬN: <span id="trangThaiNhan"></span></h5>
                                <hr>
                                <h5>DỤNG CỤ YÊU CẦU <button id="themHangHoa" class="btn btn-success btn-sm" style="display:none;"><strong>Bổ sung</strong></button></h5>
                            </div>
                            <div class="row container">
                                <form id="showForm">                               
                                    
                                </form>                            
                            </div>
                        </div>   
                        <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tab-2-tab">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addModalCongCu">THÊM YÊU CẦU</button>
                                </div>
                                <div class="col-md-4">
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
                                <h5>PHIẾU YÊU CẦU CÔNG CỤ: 
                                    <strong class="text-info" id="maPhieuCongCu"></strong> 
                                    <button id="xoaPhieuCongCu" class="btn btn-danger btn-sm">Xóa yêu cầu</button>
                                    <button id="suaPhieuCongCu" class="btn btn-secondary btn-sm">Cập nhật yêu cầu</button>
                                </h5>
                                <h5>NỘI DUNG: <i><span id="noiDungCongCu"></span></i></h5>                            
                                <h5>TRẠNG THÁI: <span id="trangThaiCongCu"></span></h5>
                                <h5>TRẠNG THÁI NHẬN: <span id="trangThaiNhanCongCu"></span></h5>
                                <hr>
                                <h5>DỤNG CỤ YÊU CẦU <button id="themHangHoaCongCu" class="btn btn-success btn-sm" style="display:none;"><strong>Bổ sung</strong></button></h5>
                            </div>
                            <div class="row container">
                                <form id="showFormCongCu">                               
                                    
                                </form>                            
                            </div>
                            <hr>   
                            <h3>Thống kê công cụ đang sử dụng</h3>
                            <table id="dataTable" class="display" style="width:100%">
                                <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Đang sử dụng</th>
                                        <th>Thời gian sử dụng/đề nghị</th>
                                        <th>Tác vụ</th>
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
    <!--  MEDAL -->
    <div>
    <!-- Medal Add -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THÊM YÊU CẦU DỤNG CỤ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form id="addForm" autocomplete="off">
                        {{csrf_field()}}
                        <input type="hidden" name="idPX" value="">                       
                        <div class="form-group">
                            <label>Lý do yêu cầu dụng cụ:</label> 
                            <input type="text" name="noiDung" class="form-control" placeholder="Nội dung yêu cầu dụng cụ">
                        </div>                         
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="taoPhieu" class="btn btn-warning btn-xs">Tạo phiếu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
       <!-- Medal Add -->
    <div class="modal fade" id="addModalCongCu">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">THÊM YÊU CẦU CÔNG CỤ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form id="addFormCongCu" autocomplete="off">
                        {{csrf_field()}}
                        <input type="hidden" name="idPXCongCu" value="">                       
                        <div class="form-group">
                            <label>Lý do yêu cầu công cụ:</label> 
                            <input type="text" name="noiDungCongCuF" class="form-control" placeholder="Nội dung yêu cầu công cụ">
                        </div>                         
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="taoPhieuCongCu" class="btn btn-warning btn-xs">Tạo phiếu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    </div>
    <!----------------------->
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
            // ----------------------------
            let table = $('#dataTable').DataTable({
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
                                return `<strong class="text-warning">Đã trả công cụ</strong><br/><i class="text-secondary">(${row.ngayTra})`;
                            else 
                                return row.noiDung;
                        } 
                    },
                    { "data": "ngay" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.deNghiTra == 0)
                                return `<button id="traCongCu" data-id=${row.idPhieuXuat} class="btn btn-info btn-sm">Đề nghị trả công cụ</button>`;
                            else if (row.duyetTra == 1)
                                return ``;
                            else
                                return `<strong class="text-primary">Đã gửi đề nghị trả</strong>`;
                        }
                    }
                ]
            });
            // ----------------------------
            // Load danh mục hàng hóa
            let danhmuc = ``;     
            let congcu = ``;
            let phieuxuat = ``;   
            let phieuxuatcongcu = ``;   
            let showrow = ``;      
            let countrow = 1;
            let arr = 1;
            let arrCongCu = 1;
            let order = new Map();
            let orderCongCu = new Map();
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
                            congcu += `<option value="${x.id}">${x.tenSanPham} (${x.donViTinh})</option>`;
                        });                     
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
            });
            // -----   
            // Load danh sách phiếu nhập
            function reload() {
                $.ajax({
                    url: "{{url('management/requestvpp/denghicongcu/loadphieu/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        $("select[name=chonPhieu]").empty();  
                        response.data.forEach((x) => {
                            let stt = (x.duyet == 1 ? "Đã duyệt" : "Chưa duyệt");
                            phieuxuat += `<option value="${x.id}">PXK-0${x.id}; Ngày yêu cầu ${x.ngay}-${x.thang}-${x.nam} (${stt})</option>`;
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
                    url: "{{url('management/requestvpp/denghicongcu/loadphieucongcu/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        $("select[name=chonPhieuCongCu]").empty();  
                        response.data.forEach((x) => {
                            let stt = (x.duyet == 1 ? "Đã duyệt" : "Chưa duyệt");
                            phieuxuatcongcu += `<option value="${x.id}">ĐNCC-0${x.id}; Ngày yêu cầu ${x.ngay}-${x.thang}-${x.nam} (${stt})</option>`;
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
            function autoloadPhieu() {
                $.ajax({
                    url: "{{url('management/requestvpp/denghicongcu/loadphieuchitiet/')}}",
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
                        $("#maPhieu").text("PXK-0" + $("select[name=chonPhieu]").val()); 
                        $("#noiDung").text(response.noiDung);   
                        $("#ngayYeuCau").text(response.ngayXuat);    
                        $("#trangThai").html((response.status == 1) ? "<strong class='text-success'>Đã duyệt</strong>" : "<strong class='text-danger'>Chưa duyệt</strong>");           
                        $("#trangThaiNhan").html((response.statusNhan == 1) ? "<strong class='text-success'>Đã nhận</strong>" : "<strong class='text-danger'>Chưa nhận</strong>&nbsp;<button id='nhanHang' class='btn btn-success btn-sm'>Xác nhận</button>");           
                        $("#showForm").empty();  
                        showrow = ``;                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        showrow += `{{csrf_field()}}`;
                        response.data.forEach((x) => {  
                            order.set(arr, x.id);                       
                            showrow += `<div class="form-group" id="row_sel_${arr}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Công cụ/dụng cụ</label> 
                                            <select name="rhangHoa${arr}" disabled class="form-control">
                                                ${danhmuc}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Số lượng</label> 
                                            <input type="number" disabled name="rsoLuong${arr}" value="${x.soLuong}" class="form-control">
                                        </div>                                       
                                        <div class="col-md-2">      
                                            <br/>                                
                                            <button id="deleteRow" disabled type="button" class="btn btn-danger btn-sm" data-code="${arr}">Xóa</button>
                                        </div>
                                    </div>
                                </div>                                                                                              
                                `;
                            arr++;
                        });                        
                        showrow += `
                        <input type="hidden" name="idPXUpdate" value="${$("select[name=chonPhieu]").val()}"/>
                        <button id="updateXuatKho" type="button" disabled class="btn btn-success">LƯU</button>`;                       
                        $("#showForm").append(showrow);    
                        order.forEach((value,key)=>{
                            $("select[name=rhangHoa" + key + "]").val(value);
                        })    
                        if (response.status == 1) {
                            $('#showForm button').hide();
                            $('#themHangHoa').hide();
                            $("#suaPhieu").hide();
                            $("#xoaPhieu").hide();
                            if (response.statusNhan == 1) {
                                $('#nhanHang').hide();
                            } else {
                                $('#nhanHang').show();
                            }
                        } else {
                            $('#showForm button').show();
                            $("#suaPhieu").show();
                            $("#xoaPhieu").show();   
                            $('#nhanHang').hide();                         
                        }

                       
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Không có yêu cầu nào để xem!"
                        })
                    }
                });
            }
            function autoloadCongCu() {
                $.ajax({
                    url: "{{url('management/requestvpp/denghicongcu/loadphieuchitietcongcu/')}}",
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
                        $("#maPhieuCongCu").text("ĐNCC-0" + $("select[name=chonPhieuCongCu]").val()); 
                        $("#noiDungCongCu").text(response.noiDung);   
                        $("#ngayYeuCauCongCu").text(response.ngayXuat);    
                        $("#trangThaiCongCu").html((response.status == 1) ? "<strong class='text-success'>Đã duyệt</strong>" : "<strong class='text-danger'>Chưa duyệt</strong>");           
                        $("#trangThaiNhanCongCu").html((response.statusNhan == 1) ? "<strong class='text-success'>Đã nhận</strong>" : "<strong class='text-danger'>Chưa nhận</strong>&nbsp;<button id='nhanHang' class='btn btn-success btn-sm'>Xác nhận</button>");           
                        $("#showFormCongCu").empty();  
                        showrow = ``;                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        showrow += `{{csrf_field()}}`;
                        response.data.forEach((x) => {  
                            orderCongCu.set(arrCongCu, x.id);                       
                            showrow += `<div class="form-group" id="row_sel_congcu_${arrCongCu}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Công cụ/dụng cụ</label> 
                                            <select name="rhangHoaCongCu${arrCongCu}" disabled class="form-control">
                                                ${congcu}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Số lượng</label> 
                                            <input type="number" disabled name="rsoLuongCongCu${arrCongCu}" value="${x.soLuong}" class="form-control">
                                        </div>                                       
                                        <div class="col-md-2">      
                                            <br/>                                
                                            <button id="deleteRowCongCu" disabled type="button" class="btn btn-danger btn-sm" data-code="${arrCongCu}">Xóa</button>
                                        </div>
                                    </div>
                                </div>                                                                                              
                                `;
                            arrCongCu++;
                        });                        
                        showrow += `
                        <input type="hidden" name="idPXUpdateCongCu" value="${$("select[name=chonPhieuCongCu]").val()}"/>
                        <button id="updateXuatKhoCongCu" type="button" disabled class="btn btn-success">LƯU</button>`;                       
                        $("#showFormCongCu").append(showrow);    
                        orderCongCu.forEach((value,key)=>{
                            $("select[name=rhangHoaCongCu" + key + "]").val(value);
                        })    
                        if (response.status == 1) {
                            $('#showFormCongCu button').hide();
                            $('#themHangHoaCongCu').hide();
                            $("#suaPhieuCongCu").hide();
                            $("#xoaPhieuCongCu").hide();
                            if (response.statusNhan == 1) {
                                $('#nhanHangCongCu').hide();
                            } else {
                                $('#nhanHangCongCu').show();
                            }
                        } else {
                            $('#showFormCongCu button').show();
                            $("#suaPhieuCongCu").show();
                            $("#xoaPhieuCongCu").show();   
                            $('#nhanHangCongCu').hide();                         
                        }

                       
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Không có yêu cầu nào để xem!"
                        })
                    }
                });
            }
            reload();
            // -----             

            $("#taoPhieu").click(function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/requestvpp/denghicongcu/taophieu/')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "noiDung": $("input[name=noiDung]").val()
                    },
                    beforeSend: function () {
                       $("#taoPhieu").attr('disabled', true).html("Đang xử lý vui lòng đợi....");
                    },                  
                    success: (response) => {                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })  
                        if (response.code == 200) {
                            $("input[name=idPN]").val(response.idPN);   
                            $("input[name=maPhieu]").val(response.maPhieu);   
                            $("input[name=maPhieu]").val(''); 
                            $("input[name=noiDungNhap]").val('');
                            $("input[name=idPN]").val('');
                            $("#addModal").modal('hide');
                            phieuxuat = ``;
                            reload();
                            $("#taoPhieu").attr('disabled', false).html("Tạo phiếu");
                            setTimeout(autoloadPhieu, 1000);
                        }                            
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Không thể tạo phiếu'
                        })
                    }
                });
            });    
            
            $("#taoPhieuCongCu").click(function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/requestvpp/denghicongcu/taophieucongcu/')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "noiDungCongCu": $("input[name=noiDungCongCuF]").val()
                    },
                    beforeSend: function () {
                       $("#taoPhieuCongCu").attr('disabled', true).html("Đang xử lý vui lòng đợi....");
                    },                  
                    success: (response) => {                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })  
                        if (response.code == 200) {
                            $("input[name=idPNCongCu]").val(response.idPN);   
                            $("input[name=maPhieuCongCu]").val(response.maPhieu);   
                            $("input[name=maPhieuCongCu]").val(''); 
                            $("input[name=noiDungNhapCongCu]").val('');
                            $("input[name=idPNCongCu]").val('');
                            $("#addModalCongCu").modal('hide');
                            phieuxuatcongcu = ``;
                            reload();
                            $("#taoPhieuCongCu").attr('disabled', false).html("Tạo phiếu");
                            setTimeout(autoloadCongCu, 1000);
                        }                            
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Không thể tạo phiếu'
                        })
                    }
                });
            });    

            $("#xemPhieu").click(function(e){
                e.preventDefault();
                autoloadPhieu();
            });

            $("#xemPhieuCongCu").click(function(e){
                e.preventDefault();
                autoloadCongCu();
            });

            $(document).on("click","#deleteRow", function(){
                $("#row_sel_" + $(this).data('code')).remove();
            });
            $(document).on("click","#deleteRowCongCu", function(){
                $("#row_sel_congcu_" + $(this).data('code')).remove();
            });

            $("#suaPhieu").click(function(){
                $('#showForm select').prop('disabled', false);
                $('#showForm input').prop('disabled', false);
                $('#showForm button').prop('disabled', false);
                $('#themHangHoa').show();
            });

            $("#suaPhieuCongCu").click(function(){
                $('#showFormCongCu select').prop('disabled', false);
                $('#showFormCongCu input').prop('disabled', false);
                $('#showFormCongCu button').prop('disabled', false);
                $('#themHangHoaCongCu').show();
            });

            $("#xoaPhieu").click(function(){
                if (confirm('Bạn có chắc muốn xóa?\nLưu ý: Sẽ xóa tất cả hàng hóa có trong yêu cầu đó.\nCác phiếu đã duyệt không thể xóa!')) {                    
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/delete/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idPX": $("select[name=chonPhieu]").val()
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code == 200) {
                                phieuxuat = ``;
                                $("#phieuTop").hide();            
                                $("#showForm").empty();  
                                reload();
                            }
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể xóa yêu cầu này'
                            })
                        }
                    });
                }
            });

            $("#xoaPhieuCongCu").click(function(){
                if (confirm('Bạn có chắc muốn xóa?\nLưu ý: Sẽ xóa tất cả hàng hóa có trong yêu cầu đó.\nCác phiếu đã duyệt không thể xóa!')) {                    
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/deletecongcu/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idPX": $("select[name=chonPhieuCongCu]").val()
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code == 200) {
                                phieuxuatcongcu = ``;
                                $("#phieuTopCongCu").hide();            
                                $("#showFormCongCu").empty();  
                                reload();
                            }
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể xóa yêu cầu này'
                            })
                        }
                    });
                }
            });


            $(document).on("click","#updateXuatKho", function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/requestvpp/denghicongcu/update/')}}",
                    dataType: "json",
                    data: $("#showForm").serialize(),
                    success: (response) => {                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })    
                        $('#showForm select').prop('disabled', true);
                        $('#showForm input').prop('disabled', true);
                        $('#showForm button').prop('disabled', true);
                        $('#themHangHoa').hide();                       
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Không thể cập nhật phiếu nhập kho'
                        })
                    }
                });                
            });

            $(document).on("click","#updateXuatKhoCongCu", function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/requestvpp/denghicongcu/updatecongcu/')}}",
                    dataType: "json",
                    data: $("#showFormCongCu").serialize(),
                    success: (response) => {                        
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })    
                        $('#showFormCongCu select').prop('disabled', true);
                        $('#showFormCongCu input').prop('disabled', true);
                        $('#showFormCongCu button').prop('disabled', true);
                        $('#themHangHoaCongCu').hide();                       
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Không thể cập nhật phiếu'
                        })
                    }
                });                
            });

            $("#themHangHoa").click(function(){
                order.set(arr, 0);                       
                temp = `<div class="form-group" id="row_sel_${arr}">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Công cụ/dụng cụ</label> 
                                <select name="rhangHoa${arr}" class="form-control">
                                    ${danhmuc}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Số lượng</label> 
                                <input type="number" name="rsoLuong${arr}" value="0" class="form-control">
                            </div>
                            <div class="col-md-2">      
                                <br/>                                
                                <button id="deleteRow" type="button" class="btn btn-danger btn-sm" data-code="${arr}">Xóa</button>
                            </div>
                        </div>
                    </div>                                                                                              
                    `;
                arr++;
                $("#showForm").prepend(temp);    
            });

            $("#themHangHoaCongCu").click(function(){
                order.set(arrCongCu, 0);                       
                temp = `<div class="form-group" id="row_sel_congcu_${arrCongCu}">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Công cụ/dụng cụ</label> 
                                <select name="rhangHoaCongCu${arrCongCu}" class="form-control">
                                    ${congcu}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Số lượng</label> 
                                <input type="number" name="rsoLuongCongCu${arrCongCu}" value="0" class="form-control">
                            </div>
                            <div class="col-md-2">      
                                <br/>                                
                                <button id="deleteRowCongCu" type="button" class="btn btn-danger btn-sm" data-code="${arrCongCu}">Xóa</button>
                            </div>
                        </div>
                    </div>                                                                                              
                    `;
                arrCongCu++;
                $("#showFormCongCu").prepend(temp);    
            });

            $(document).on("click","#nhanHang", function(e){
                e.preventDefault();
                if (confirm('Xác nhận đã nhận đầy đủ công cụ/dụng cụ?')) {
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/nhanhang/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idPX": $("select[name=chonPhieu]").val()
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })    
                            $('#trangThaiNhan').html("<strong class='text-success'>Đã nhận</strong>");     
                            $('#nhanHang').hide();                    
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể nhận hàng lúc này!'
                            })
                        }
                    }); 
                }               
            });

            $(document).on("click","#nhanHangCongCu", function(e){
                e.preventDefault();
                if (confirm('Xác nhận đã nhận đầy đủ công cụ/dụng cụ?')) {
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/nhanhangcongcu/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idPX": $("select[name=chonPhieuCongCu]").val()
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })    
                            $('#trangThaiNhanCongCu').html("<strong class='text-success'>Đã nhận</strong>");     
                            $('#nhanHangCongCu').hide();                    
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể nhận hàng lúc này!'
                            })
                        }
                    }); 
                }               
            });


            $(document).on("click","#traCongCu", function(){
                if (confirm("Xác nhận đề nghị trả công cụ?")) {
                    let idPhieuXuat = $(this).data('id');
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/requestvpp/denghicongcu/tracongcu/')}}",
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
                            table.ajax.reload();                
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
            
        });
    </script>
@endsection
