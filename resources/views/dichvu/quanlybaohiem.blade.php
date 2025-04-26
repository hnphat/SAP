@extends('admin.index')
@section('title')
   Quản lý bảo hiểm
@endsection
@section('script_head')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        #tableMain tr:hover{
            background-color: gray;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý bảo hiểm</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Quản lý bảo hiểm</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div>
            <form autocomplete="off">
                <div class="container row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Chọn loại báo giá</label>
                                <select name="baoCao" class="form-control">
                                    <option value="1">Tất cả</option>                           
                                    <option value="2">Mới tạo</option>                                 
                                    <option value="3">Đang thực hiện</option>  
                                    <option value="4">Hoàn tất</option>    
                                    <option value="5">Huỷ</option>                                                       -->
                                </select> <br/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Từ</label>
                                <input type="date" name="tu" value="<?php echo Date('Y-m-d');?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Đến</label>
                                <input type="date" name="den" value="<?php echo Date('Y-m-d');?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <input type="submit" id="xemReport" class="form-control btn btn-info" value="TÌM"/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                           Mới tạo: <span class="bg-secondary"> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="badge badge-secondary" id="badge1"></span><br/>
                           Đang thực hiện: <span class="bg-success"> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="badge badge-success" id="badge2"></span><br/>
                           Hoàn tất: <span class="bg-info"> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="badge badge-info" id="badge3"></span><br/>
                           Huỷ: <span class="bg-danger"> &nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="badge badge-danger" id="badge5"></span><br/>
                        </div>
                </div>
            </form>
            <div class="row container">
                <h5>
                    Tổng: <strong class="text-success" id="tongDoanhThu"></strong> 
                    Kinh doanh: <strong class="text-primary" id="tongKinhDoanh"></strong> (Thực thu: <strong class="text-primary" id="tongKinhDoanhs"></strong>) 
                    Khai thác: <strong class="text-info" id="tongKhaiThac"></strong> (Thực thu: <strong class="text-info" id="tongKhaiThacs"></strong>)
                </h5>
            </div>
            <div class="row">               
               <div class="col-md-4">
                <div style="overflow:auto;height: 800px;">
                        <table id="tableMain" class="table table-bordered">
                                <tr class="bg-primary" style="position: sticky; top:0;left:0;">
                                    <th>Loại</th>
                                    <th>Báo giá</th>        
                                    <th>Biển số</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày vào</th>
                                    <th>Doanh thu</th>
                                </tr>
                                <tbody style="font-size: 10pt;" id="showDataFind">
                                                                                          
                                </tbody>
                        </table>
                    </div>
               </div>
               <div class="col-md-8">
                    <div class="container">
                        <button id="add" class="btn btn-success btn-sm">Thêm mới</button>
                        <button id="save" class="btn btn-info btn-sm">Lưu</button>
                        <button id="edit" class="btn btn-primary btn-sm">Sửa</button>
                        <button id="delete" class="btn btn-danger btn-sm">Xoá</button>
                        <button id="notsave" class="btn btn-secondary btn-sm">Không Lưu</button>
                        <button id="process" class="btn btn-primary btn-sm">Thực hiện</button>
                        <button id="done" class="btn btn-warning btn-sm">Hoàn tất</button>    
                        <button id="cancel" class="btn btn-danger btn-sm">Huỷ BG</button>    
                        <button id="in" class="btn btn-secondary" data-toggle='modal' data-target='#inModal'><span class="fas fa-print"></span> IN</button>
                        <br/>
                        <hr>                         
                        <h4 class="text-bold text-info">TÌM THÔNG TIN</h4>
                        <div class="row">                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Chọn</label>
                                    <select id="isPKD" name="isPKD" class="form-control">
                                        <option value="1">Theo số hợp đồng</option>
                                        <option value="0">Theo số điện thoại</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label>&nbsp;</label><br/>                                      
                                        <input id="timHopDong" placeholder="Nhập số hợp đồng" type="text" name="timHopDong" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group" style="display:none;">
                                        <label>Hợp đồng số</label><br/>                                      
                                        <input disabled id="hopDong" type="text" name="hopDong" class="form-control">                                                                        
                                </div>                           
                                <div class="form-group" style="display:none;">
                                        <label>NV Kinh doanh</label><br/>                                      
                                        <input disabled id="nhanVien" type="text" name="nhanVien" class="form-control">                                                                        
                                </div>
                                <div class="form-group">
                                        <label>NV Kinh doanh</label><br/>   
                                        <select disabled name="saler" id="saler" class="form-control">
                                            <option value="0">Không có</option>
                                            @foreach($user as $r)
                                                @if($r->hasRole('sale') && $r->active)
                                                    <option value="{{$r->id}}">{{$r->userDetail->surname}}</option>
                                                @endif
                                            @endforeach
                                        </select>                                   
                                </div>
                            </div>
                        </div>
                        <hr>               
                        <div class="row">   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Số báo giá</label><br/>                                      
                                    <input id="soBaoGia" type="text" name="soBaoGia" class="form-control">                                                                        
                                    <input type="hidden" name="eid" id="eid">
                                </div>        
                            </div>     
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lý do huỷ (nếu có)</label><br/>                                      
                                    <input disabled id="lyDoHuy" type="text" name="lyDoHuy" class="form-control">                                                                        
                                </div>        
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hình thức</label><br/>        
                                    <select                                    
                                    disabled id="isBaoHiem" name="isBaoHiem" class="form-control">
                                    <option value="1" selected>Báo giá bảo hiểm</option>
                                    </select>                              
                                </div>        
                            </div>                                    
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">THÔNG TIN KHÁCH HÀNG</h4>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Họ tên</label><br/>                                      
                                    <input id="hoTen" type="text" name="hoTen" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Điện thoại</label><br/>                                      
                                    <input id="dienThoai" type="number" name="dienThoai" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>MST</label><br/>                                      
                                    <input id="mst" type="text" name="mst" class="form-control">                                                                        
                                </div>                                
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Địa chỉ</label><br/>                                      
                                    <input id="diaChi" type="text" name="diaChi" class="form-control">                                                                        
                                </div>        
                            </div>                                                
                        </div>
                        <div class="row">   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Người liên hệ</label><br/>                                      
                                    <input id="taiXe" type="text" name="taiXe" class="form-control">                                                                        
                                </div>        
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Điện thoại</label><br/>                                      
                                    <input id="dienThoaiTaiXe" type="number" name="dienThoaiTaiXe" class="form-control">                                                                        
                                </div>        
                            </div>                                           
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">THÔNG TIN XE</h4>
                        <div class="row"> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Biển số</label><br/>                                      
                                    <input id="bienSo" type="text" name="bienSo" class="form-control">                                                                        
                                </div>        
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Số khung</label><br/>                                      
                                    <input id="soKhung" type="text" name="soKhung" class="form-control">                                                                        
                                </div>        
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Số máy</label><br/>                                      
                                    <input id="soMay" type="text" name="soMay" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Chi tiết xe</label><br/>                                      
                                    <input id="thongTinXe" placeholder="VD: Hyundai Accent 1.6AT; Màu đỏ;" type="text" name="chiTietXe" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">YÊU CẦU KHÁCH HÀNG</h4>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">    
                                    <input id="yeuCau" placeholder="Nhập yêu cầu KH" type="text" name="yeuCau" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <h6 class="text-bold text-info">TIỀN ĐẶT CỌC (Nếu có)</h6>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">    
                                    <input id="tienCoc" placeholder="Nhập tiền đặt cọc" type="number" min="0" value="0" name="tienCoc" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">CHI TIẾT HẠNG MỤC</h4>
                        <div id="showChiTietHangMuc">
                            <button id="btnAdd" class="btn btn-success" data-toggle='modal' data-target='#showModal'><span class="fas fa-plus-circle"></span></button>
                            <div class="row">
                                <div style="overflow:auto;">
                                    <table class="table table-striped table-bordered" style="font-size:11pt;">
                                        <tr class="bg-primary">                                   
                                            <th>Mã</th>
                                            <th>Nội dung</th>
                                            <th>Đvt</th>
                                            <th>SL</th>
                                            <th>Đơn giá</th>
                                            <th>Chiết khấu (%)</th>
                                            <th>Thành tiền</th>
                                            <th>Tặng</th>
                                            <th>KTV</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                        <tbody id="chiTietHangMuc">
                                            
                                        </tbody>
                                    </table>     
                                </div>                                                  
                            </div>
                        </div>                        
                        <hr>
                        <h5>Tổng báo giá: <strong class="text-primary" id="tongBaoGia"></strong></h5>
                    </div>
               </div>
            </div>
        </div>
        <!-- /.content -->
    </div>    
    <!--  MEDAL -->": 
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();

        // Hàm khởi tạo nút ban đầu
        function startUp() {
            $("#loadData").hide();
            $("#showChiTietHangMuc").hide();
            $("#btnAdd").hide();
            $("#chiTietHangMuc").text('');
            $("#add").show();
            $("#save").hide();
            $("#delete").hide();
            $("#edit").hide();
            $("#notsave").hide();
            $("#process").hide();
            $("#done").hide();
            $("#cancel").hide();
            $("#in").hide();
            $("#saler").prop('disabled', true);
            $("#soBaoGia").prop('disabled', true);
            $("#gioVao").prop('disabled', true);
            $("#gioRa").prop('disabled', true);
            $("#ngayRa").prop('disabled', true);
            $("#ngayVao").prop('disabled', true);
            $("#soBaoGia").val('');
            $("#ngayRa").val('');
            $("#ngayVao").val('');
            $("#gioVao").val('');
            $("#gioRa").val('');

            $("#isPKD").prop('disabled', true);
            $("#timHopDong").prop('disabled', true);
            $("#hopDong").prop('disabled', true);
            $("#nhanVien").prop('disabled', true);
            $("#hoTen").prop('disabled', true);
            $("#dienThoai").prop('disabled', true);
            $("#mst").prop('disabled', true);
            $("#diaChi").prop('disabled', true);
            $("#taiXe").prop('disabled', true);
            $("#dienThoaiTaiXe").prop('disabled', true);
            $("#bienSo").prop('disabled', true);
            $("#soKhung").prop('disabled', true);
            $("#soMay").prop('disabled', true);
            $("#thongTinXe").prop('disabled', true);
            $("#yeuCau").prop('disabled', true);
            $("#tienCoc").prop('disabled', true);
            //----------xoa
            $("#timHopDong").val('');
            $("#hopDong").val('');
            $("#nhanVien").val('');
            $("#hoTen").val('');
            $("#dienThoai").val('');
            $("#mst").val('');
            $("#diaChi").val('');
            $("#taiXe").val('');
            $("#dienThoaiTaiXe").val('');
            $("#bienSo").val('');
            $("#soKhung").val('');
            $("#soMay").val('');
            $("#thongTinXe").val('');
            $("#yeuCau").val('');
            $("#tienCoc").val(0);
            //--------------
            $("#tongBaoGia").text("");
            $("#eid").val('');
        }
        // Hàm khởi tạo nút không lưu
        function disAfterSave() {
            $("#add").show();  
            $("#loadData").hide();
            $("#edit").show();
            $("#delete").show();
            $("#process").show();
            $("#cancel").hide();
            $("#soBaoGia").prop('disabled', true);
            $("#gioVao").prop('disabled', true);
            $("#gioRa").prop('disabled', true);
            $("#ngayRa").prop('disabled', true);
            $("#ngayVao").prop('disabled', true);

            $("#isPKD").prop('disabled', true);
            $("#timHopDong").prop('disabled', true);
            $("#hopDong").prop('disabled', true);
            $("#nhanVien").prop('disabled', true);
            $("#saler").prop('disabled', true);
            $("#hoTen").prop('disabled', true);
            $("#dienThoai").prop('disabled', true);
            $("#mst").prop('disabled', true);
            $("#diaChi").prop('disabled', true);
            $("#taiXe").prop('disabled', true);
            $("#dienThoaiTaiXe").prop('disabled', true);
            $("#bienSo").prop('disabled', true);
            $("#soKhung").prop('disabled', true);
            $("#soMay").prop('disabled', true);
            $("#thongTinXe").prop('disabled', true);
            $("#yeuCau").prop('disabled', true);
            $("#tienCoc").prop('disabled', true);
            $("#btnAdd").hide(); 
        }
        startUp();
        $(document).ready(function() {
            // Tải lại dữ liệu sau khi thao tác            
            function reloadData() {
                $.ajax({
                    type: "post",
                    url: "{{route('timkiembaogiabaohiem')}}",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "baoCao": $("select[name=baoCao]").val(),
                        "tu": $("input[name=tu]").val(),
                        "den": $("input[name=den]").val(),
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: " Đã gửi yêu cầu! "
                        }) 
                        $("#showDataFind").html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi!"
                        })
                    }
                });
            }

            // Bộ chọn tìm theo số hợp đồng hay số điện thoại
            $('#isPKD').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                if (valueSelected == 1) {
                    $('#timHopDong').attr('placeholder','Nhập số hợp đồng');
                    $('#saler').prop('disabled',false);
                } else {
                    $('#timHopDong').attr('placeholder','Nhập số điện thoại');
                    $('#saler').prop('disabled',false);
                }                    
            });

            // Tìm kiếm theo số hợp đồng hoặc số điện thoại
            $("#timHopDong").keyup(function(e){
                let isPKD = $("#isPKD").val();
                let findVal = $(this).val();
                if(e.keyCode == 13) {
                    switch(parseInt(isPKD)) {
                        case 1: {
                            $.ajax({
                                type: "post",
                                url: "{{route('timhopdong')}}",
                                dataType: "json",
                                data: {
                                    "_token": "{{csrf_token()}}",
                                    "findVal": findVal
                                },
                                success: function(response) {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    }) 
                                    if (response.code == 200) { 
                                        $('#hopDong').val(response.hopDong);
                                        $('#nhanVien').val(response.nhanVien);
                                        $('#hoTen').val(response.hoTen);
                                        $('#dienThoai').val(response.dienThoai);
                                        $('#mst').val(response.mst);
                                        $('#diaChi').val(response.diaChi);
                                        $('#bienSo').val(response.bienSo);
                                        $('#soKhung').val(response.soKhung);
                                        $('#soMay').val(response.soMay);
                                        $('#thongTinXe').val(response.thongTinXe);
                                    } else {
                                        $('#hopDong').val("");
                                        $('#nhanVien').val("");
                                        $('#hoTen').val("");
                                        $('#dienThoai').val("");
                                        $('#mst').val("");
                                        $('#diaChi').val("");
                                        $('#bienSo').val("");
                                        $('#soKhung').val("");
                                        $('#soMay').val("");
                                        $('#thongTinXe').val("");
                                    }
                                },
                                error: function() {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: " Không tìm thấy!"
                                    })
                                    $('#hopDong').val("");
                                    $('#nhanVien').val("");
                                    $('#hoTen').val("");
                                    $('#dienThoai').val("");
                                    $('#mst').val("");
                                    $('#diaChi').val("");
                                    $('#bienSo').val("");
                                    $('#soKhung').val("");
                                    $('#soMay').val("");
                                    $('#thongTinXe').val("");
                                }
                            }); 
                        } break;
                        case 0: {
                            $.ajax({
                                type: "post",
                                url: "{{route('timkhachhang')}}",
                                dataType: "json",
                                data: {
                                    "_token": "{{csrf_token()}}",
                                    "findVal": findVal
                                },
                                success: function(response) {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    }) 
                                    if (response.code == 200) {                                        
                                        $('#hoTen').val(response.hoTen);
                                        $('#dienThoai').val(response.dienThoai);
                                        $('#mst').val(response.mst);
                                        $('#diaChi').val(response.diaChi);
                                        $('#bienSo').val(response.bienSo);
                                        $('#soKhung').val(response.soKhung);
                                        $('#soMay').val(response.soMay);
                                        $('#thongTinXe').val(response.thongTinXe);
                                        $('#bienSo').val(response.bienSo);
                                        $('#taiXe').val(response.taiXe);
                                        $('#dienThoaiTaiXe').val(response.dienThoaiTaiXe);
                                    } else {                                       
                                        $('#hoTen').val("");
                                        $('#dienThoai').val("");
                                        $('#mst').val("");
                                        $('#diaChi').val("");
                                        $('#bienSo').val("");
                                        $('#soKhung').val("");
                                        $('#soMay').val("");
                                        $('#thongTinXe').val("");
                                        $('#bienSo').val("");
                                        $('#taiXe').val("");
                                        $('#dienThoaiTaiXe').val("");
                                    }                              
                                },
                                error: function() {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: " Không tìm thấy!"
                                    })
                                    $('#hoTen').val("");
                                    $('#dienThoai').val("");
                                    $('#mst').val("");
                                    $('#diaChi').val("");
                                    $('#bienSo').val("");
                                    $('#soKhung').val("");
                                    $('#soMay').val("");
                                    $('#thongTinXe').val("");
                                    $('#bienSo').val("");
                                    $('#taiXe').val("");
                                    $('#dienThoaiTaiXe').val("");
                                }
                            }); 
                        } break;
                    }
                }
            });

            // Nút thêm mới
            $("#add").click(function(){
                startUp();
                $(this).hide();
                $("#save").show();
                $("#loadData").hide();
                $("#delete").hide();
                $("#notsave").show();
                $("#soBaoGia").val('');
                $("#ngayRa").prop('disabled', false);
                $("#ngayVao").prop('disabled', false);
                $("#gioVao").prop('disabled', false);
                $("#gioRa").prop('disabled', false);
                $("#isPKD").prop('disabled', false);
                $("#timHopDong").prop('disabled', false);
                $("#saler").prop('disabled', false);
                $("#hoTen").prop('disabled', false);
                $("#dienThoai").prop('disabled', false);
                $("#mst").prop('disabled', false);
                $("#diaChi").prop('disabled', false);
                $("#taiXe").prop('disabled', false);
                $("#dienThoaiTaiXe").prop('disabled', false);
                $("#bienSo").prop('disabled', false);
                $("#soKhung").prop('disabled', false);
                $("#soMay").prop('disabled', false);
                $("#thongTinXe").prop('disabled', false);
                $("#yeuCau").prop('disabled', false);
                $("#tienCoc").prop('disabled', false);
                $("#eid").val('');
                $("#soBaoGia").val('');
                $("#ngayRa").val('');
                $("#ngayVao").val('');
                $("#gioVao").val('');
                $("#gioRa").val('');
                //----------xoa
                $("#timHopDong").val('');
                $("#hopDong").val('');
                $("#nhanVien").val('');
                $("#saler").val(0);
                $("#hoTen").val('');
                $("#dienThoai").val('');
                $("#mst").val('');
                $("#diaChi").val('');
                $("#taiXe").val('');
                $("#dienThoaiTaiXe").val('');
                $("#bienSo").val('');
                $("#soKhung").val('');
                $("#soMay").val('');
                $("#thongTinXe").val('');
                $("#yeuCau").val('');
                $("#tienCoc").val(0);
                //--------------
                //--------------
                $("#tongBaoGia").text("");
            });

            // Nút không lưu
            $("#notsave").click(function(){
                $(this).hide();
                if ($('#eid').val()) {
                  $("#save").hide();
                  disAfterSave();
                } else {
                  startUp();
                }
            });

            // Tìm kiếm
            $("#xemReport").click(function(e){
                e.preventDefault();
                reloadData();
                // counterBadge();
                // counterDoanhThu();
            });  
        });
    </script>
@endsection
