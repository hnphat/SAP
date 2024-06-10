@extends('admin.index')
@section('title')
    Quản lý nhập kho
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
                        <h1 class="m-0"><strong>Quản lý nhập kho</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Quản lý nhập kho</li>
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
                                <strong>Quản lý nhập Kho</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="row">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addModal">THÊM PHIẾU NHẬP</button>
                            </div>
                            <div class="col-md-3">
                                <select name="chonPhieuNhap" class="form-control">
                                    <option value="">Loading....</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button id="xemPhieuNhap" type="button" class="btn btn-info btn-xs">Tải</button>
                            </div>                            
                            <div class="col-md-2">
                                <a target="_blank" href="{{route('vpp.nhomhang.panel')}}" class="btn btn-warning btn-xs">NHÓM HÀNG</a>
                            </div>
                            <div class="col-md-2">
                                <a target="_blank" href="{{route('vpp.danhmuc.panel')}}" class="btn btn-primary btn-xs">DANH MỤC HÀNG</a>
                            </div>
                        </div>
                        <hr>
                        <div id="phieuNhapKhoTop" style="display:none;">
                            <h5>NGÀY: <span id="ngayNhap" class="text-pink"></span></h5>
                            <h5>PHIẾU NHẬP KHO: 
                                <strong class="text-info" id="maPhieuNK"></strong> 
                                <button id="xoaPhieuNhap" class="btn btn-danger btn-sm">Xóa phiếu</button>
                                <button id="suaPhieuNhap" class="btn btn-secondary btn-sm">Cập nhật phiếu</button>
                            </h5>
                            <h5>NỘI DUNG: <i><span id="noiDungPN"></span></i></h5>
                            <hr>
                            <h5>DANH MỤC HÀNG HÓA <button id="themHangHoa" class="btn btn-success btn-sm" style="display:none;"><strong>Bổ sung</strong></button></h5>
                        </div>
                        <div class="row container">
                            <form id="showForm">                               
                                
                            </form>                            
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
                    <h4 class="modal-title">THÊM PHIẾU NHẬP</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <form id="addForm" autocomplete="off">
                        {{csrf_field()}}
                        <input type="hidden" name="idPN" value="">
                        <div class="form-group">
                            <label>Mã phiếu</label> 
                            <input type="text" readonly name="maPhieu" class="form-control" placeholder="Chưa có mã - Chọn tạo phiếu">
                        </div>     
                        <div class="form-group">
                            <label>Nội dung nhập kho</label> 
                            <input type="text" name="noiDungNhap" class="form-control" placeholder="Nội dung nhập kho">
                        </div>                        
                        <!-- <div class="form-group">                               
                            <button id="themHangHoa" type="button" class="btn btn-success btn-xs"><strong>+</strong></button>
                        </div>   
                        <div id="listHangHoa">
                        </div>        -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <!-- <button id="btnAdd" class="btn btn-primary" form="addForm">Lưu</button> -->
                    <button id="taoPhieu" class="btn btn-warning btn-xs">Tạo phiếu</button>
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
            // Load danh mục hàng hóa
            let danhmuc = ``;     
            let phieunhap = ``;   
            let showrow = ``;      
            let countrow = 1;
            let arr = 1;
            let order = new Map();
            $.ajax({
                    url: "{{url('management/vpp/quanlynhapkho/loaddanhmucall/')}}",
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
            // -----   
            // Load danh sách phiếu nhập
            function reload() {
                $.ajax({
                    url: "{{url('management/vpp/quanlynhapkho/loadphieunhap/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        $("select[name=chonPhieuNhap]").empty();  
                        response.data.forEach((x) => {
                            phieunhap += `<option value="${x.id}">PNK-0${x.id}; Ngày nhập ${x.ngay}-${x.thang}-${x.nam}</option>`;
                        });       
                        $("select[name=chonPhieuNhap]").append(phieunhap);      
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
                    url: "{{url('management/vpp/quanlynhapkho/loadphieunhapchitiet/')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                       "idPN": $("select[name=chonPhieuNhap]").val()
                    },
                    success: function(response) {    
                        arr = 1;
                        order.clear();
                        $('#themHangHoa').hide(); 
                        $("#phieuNhapKhoTop").show();
                        $("#maPhieuNK").text("PNK-0" + $("select[name=chonPhieuNhap]").val()); 
                        $("#noiDungPN").text(response.noiDung);   
                        $("#ngayNhap").text(response.ngayNhap);               
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
                                        <div class="col-md-4">
                                            <label>Hàng hóa</label> 
                                            <select name="rhangHoa${arr}" disabled class="form-control">
                                                ${danhmuc}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Số lượng</label> 
                                            <input type="number" disabled name="rsoLuong${arr}" value="${x.soLuong}" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Đơn giá</label> 
                                            <input type="number" disabled name="rdonGia${arr}"  value="${x.donGia}" class="form-control">
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
                        <input type="hidden" name="idPNUpdate" value="${$("select[name=chonPhieuNhap]").val()}"/>
                        <button id="updateNhapKho" type="button" disabled class="btn btn-success">LƯU</button>`;                       
                        $("#showForm").append(showrow);    
                        order.forEach((value,key)=>{
                            $("select[name=rhangHoa" + key + "]").val(value);
                        })    
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Không có phiếu nào để xem!"
                        })
                    }
                });
            }
            reload();
            // -----       
            // $("#themHangHoa").click(function(){
            //     var row = `<div class="form-group">
            //                     <div class="row">
            //                         <div class="col-md-4">
            //                             <label>Hàng hóa</label> 
            //                             <select name="hangHoa${countrow}" class="form-control">
            //                                 ${danhmuc}
            //                             </select>
            //                         </div>
            //                         <div class="col-md-3">
            //                             <label>Số lượng</label> 
            //                             <input type="number" name="soLuong${countrow}" value="0" class="form-control">
            //                         </div>
            //                         <div class="col-md-3">
            //                             <label>Đơn giá</label> 
            //                             <input type="number" name="donGia${countrow}"  value="0" class="form-control">
            //                         </div>
            //                     </div>
            //                 </div>`;
            //     $("#listHangHoa").append(row); 
            //     countrow++;
            // });

            $("#taoPhieu").click(function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/vpp/quanlynhapkho/taophieu/')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "noiDungNhap": $("input[name=noiDungNhap]").val()
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
                            phieunhap = ``;
                            reload();
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

            // $("#btnAdd").click(function(e){
            //     e.preventDefault();
            //     $.ajax({
            //         type:'POST',
            //         url: "{{ url('management/vpp/quanlynhapkho/post/')}}",
            //         dataType: "json",
            //         data: $("#addForm").serialize(),
            //         success: (response) => {                        
            //             Toast.fire({
            //                 icon: response.type,
            //                 title: response.message
            //             })
            //             if (response.code == 200) {
            //                 $("#listHangHoa").empty(); 
            //                 $("#taoPhieu").show();  
            //                 $("input[name=maPhieu]").val(''); 
            //                 $("input[name=noiDungNhap]").val('');
            //                 $("input[name=idPN]").val('');
            //                 $("#addModal").modal('hide');
            //                 phieunhap = ``;
            //                 reload();
            //             }
            //         },
            //         error: function(response){
            //             Toast.fire({
            //                 icon: 'info',
            //                 title: ' Không thể thêm hàng hóa'
            //             })
            //         }
            //     });
            // });

            $("#xemPhieuNhap").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/vpp/quanlynhapkho/loadphieunhapchitiet/')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                       "idPN": $("select[name=chonPhieuNhap]").val()
                    },
                    success: function(response) {    
                        arr = 1;
                        order.clear();
                        $('#themHangHoa').hide(); 
                        $("#phieuNhapKhoTop").show();
                        $("#maPhieuNK").text("PNK-0" + $("select[name=chonPhieuNhap]").val()); 
                        $("#noiDungPN").text(response.noiDung);   
                        $("#ngayNhap").text(response.ngayNhap);               
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
                                        <div class="col-md-4">
                                            <label>Hàng hóa</label> 
                                            <select name="rhangHoa${arr}" disabled class="form-control">
                                                ${danhmuc}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Số lượng</label> 
                                            <input type="number" disabled name="rsoLuong${arr}" value="${x.soLuong}" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Đơn giá</label> 
                                            <input type="number" disabled name="rdonGia${arr}"  value="${x.donGia}" class="form-control">
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
                        <input type="hidden" name="idPNUpdate" value="${$("select[name=chonPhieuNhap]").val()}"/>
                        <button id="updateNhapKho" type="button" disabled class="btn btn-success">LƯU</button>`;                       
                        $("#showForm").append(showrow);    
                        order.forEach((value,key)=>{
                            $("select[name=rhangHoa" + key + "]").val(value);
                        })    
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Không có phiếu nào để xem!"
                        })
                    }
                });
            });

            $(document).on("click","#deleteRow", function(){
                $("#row_sel_" + $(this).data('code')).remove();
            });

            $("#suaPhieuNhap").click(function(){
                $('#showForm select').prop('disabled', false);
                $('#showForm input').prop('disabled', false);
                $('#showForm button').prop('disabled', false);
                $('#themHangHoa').show();
            });

            $("#xoaPhieuNhap").click(function(){
                if (confirm('Bạn có chắc muốn xóa?\nLưu ý: Sẽ xóa tất cả hàng hóa có trong phiếu nhập đó. Thay đổi số liệu tồn kho!')) {                    
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/vpp/quanlynhapkho/delete/')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idPN": $("select[name=chonPhieuNhap]").val()
                        },
                        success: (response) => {                        
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code == 200) {
                                phieunhap = ``;
                                $("#phieuNhapKhoTop").hide();            
                                $("#showForm").empty();  
                                reload();
                            }
                        },
                        error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Không thể xóa phiếu này'
                            })
                        }
                    });
                }
            });

            $(document).on("click","#updateNhapKho", function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/vpp/quanlynhapkho/update/')}}",
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

            $("#themHangHoa").click(function(){
                order.set(arr, 0);                       
                temp = `<div class="form-group" id="row_sel_${arr}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Hàng hóa</label> 
                                <select name="rhangHoa${arr}" class="form-control">
                                    ${danhmuc}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Số lượng</label> 
                                <input type="number" name="rsoLuong${arr}" value="0" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Đơn giá</label> 
                                <input type="number" name="rdonGia${arr}"  value="0" class="form-control">
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
        });
    </script>
@endsection
