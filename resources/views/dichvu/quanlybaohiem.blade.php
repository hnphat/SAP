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
                                    <option value="5">Huỷ</option>                                                           -->
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
                           Mới tạo: <span class="bg-secondary"> &nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
                           Đang thực hiện: <span class="bg-success"> &nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
                           Hoàn tất: <span class="bg-info"> &nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
                           Huỷ: <span class="bg-danger"> &nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
                        </div>
                </div>
            </form>
            <div class="row">               
               <div class="col-md-4">
                <div style="overflow:auto;">
                        <table id="tableMain" class="table table-bordered">
                                <tr class="bg-primary">
                                    <th>Báo giá</th>        
                                    <th>Biển số</th>
                                    <th>Số khung</th>
                                    <th>Ngày vào</th>
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
                                        <option value="1">Báo giá bảo hiểm</option>
                                    </select>                              
                                </div>        
                            </div>                                    
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">TIẾN ĐỘ</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Thời gian vào</label><br/>                                      
                                    <input id="gioVao" type="time" name="gioVao" class="form-control">                                                                        
                                </div>        
                            </div>  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ngày vào</label><br/>                                      
                                    <input id="ngayVao" type="date" name="ngayVao" class="form-control">                                                                        
                                </div>        
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Thời gian ra</label><br/>                                      
                                    <input id="gioRa" type="time" name="gioRa" class="form-control">                                                                        
                                </div>        
                            </div>  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ngày ra</label><br/>                                      
                                    <input id="ngayRa" type="date" name="ngayRa" class="form-control">                                                                        
                                </div>        
                            </div> 
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">TÌM THÔNG TIN</h4>
                        <div class="row">                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Loại báo giá</label>
                                    <select id="isPKD" name="isPKD" class="form-control">
                                        <option value="1">Báo giá kinh doanh</option>
                                        <option value="0">Báo giá khai thác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                        <label>Tìm nhanh khách hàng</label><br/>                                      
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
                                                @if($r->hasRole('sale'))
                                                    <option value="{{$r->id}}">{{$r->userDetail->surname}}</option>
                                                @endif
                                            @endforeach
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
                        <h5>Tổng báo giá: <strong class="text-primary" id="tongBaoGia">8,000,000</strong></h5>
                        <!-- <h5>Chiết khấu: <strong class="text-primary" id="chietKhau">800,000</strong></h5> -->
                        <!-- <h4>Tổng cần thanh toán: <strong class="text-primary" id="tongThanhToan">7,200,000</strong></h4> -->
                    </div>
               </div>
            </div>
        </div>
        <!-- /.content -->
    </div>    
    <!--  MEDAL -->
   <!-- The Modal -->
  <div class="modal fade" id="showModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><strong>THÊM HẠNG MỤC</strong></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">         
                <form id="addForm">
                    @csrf
                    <input type="hidden" name="bgid"/>
                    <div class="row">
                        <div class="form-group">
                                <label>Mặt hàng: <span class="text-primary" id="s_noiDung"></span></label> ----
                                <label>Đơn vị tính: <span class="text-primary" id="s_dvt"></span></label> ----
                                <label>Giá đề nghị: <span class="text-primary" id="s_gia"></span></label> ----
                                <label>Công KTV (nếu có): <span class="text-primary" id="s_congktv"></span></label>
                        </div>   
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hangHoa">Mã hàng</label>
                                <input placeholder="Nhập nội dung gợi ý" list="hangHoas" name="hangHoa" id="hangHoa" class="form-control">
                                <datalist id="hangHoas">
                                    @foreach($bhpk as $bh)
                                        <option value="{{$bh->ma}}">{{$bh->noiDung}}</option>
                                    @endforeach                                    
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tên hàng</label>
                                <input type="text" name="tenHang" disabled class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Số lượng</label>
                                <input type="number" name="soLuong" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">                            
                            <div class="form-group">
                                <label>Đơn giá</label>
                                <input id="donGia" type="number" name="donGia" value="0" class="form-control">
                                <span id="showDonGias"></span>
                            </div>                            
                        </div>
                        <div class="col-md-2">       
                            <div class="form-group">
                                <label>Thành tiền</label>
                                <input disabled id="thanhTien" type="number" name="thanhTien" value="0" class="form-control">
                                <span id="showThanhTien"></span>
                            </div>   
                        </div>     
                        <div class="col-md-1">       
                            <div class="form-group">
                                <label>Tặng</label>
                                <select name="tang" class="form-control">                                    
                                    <option value="0" selected>Không</option>
                                    <option value="1">Có</option>
                                </select>
                            </div>
                        </div>                                            
                    </div>                
                    <div class="container row">
                        <button id="saveBtn" type="button" class="btn btn-success">LƯU</button>
                    </div>
                </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

  <!-- Sửa  -->
  <div class="modal fade" id="inModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><strong>IN</strong></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">         
                <form id="inForm">
                    @csrf    
                    <div class="form-group">
                        <label>Chọn nội dung cần in</label>
                        <select name="noiDungIn" class="form-control">
                            <option value="1">Báo giá sửa chửa</option>
                            <option value="2">Lệnh sửa chửa</option>
                            <option value="3">Yêu cầu xuất vật tư</option>
                            <option value="4">Quyết toán sửa chữa</option>
                        </select>
                    </div>
                    <div class="container row">
                        <button id="inLoad" type="button" class="btn btn-primary">TẢI FILE IN</button>
                    </div>
                </form>
        </div>        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>        
      </div>
    </div>
  </div>

   <!-- IN -->
   <div class="modal fade" id="editModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><strong>Cập nhật KTV</strong></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">         
                <form id="editForm">
                    @csrf   
                    <input type="hidden" name="editID"> 
                    <input type="hidden" name="editIDHM"> 
                    <div class="form-group">
                        <label>Công việc: <strong id="congViec" class="text-success"></strong></label>
                    </div>
                    <div class="form-group">
                        <label for="chonKTV">Chọn KTV</label>
                        <select name="chonKTV" id="chonKTV" class="form-control">
                            @foreach($user as $u)
                                @if($u->hasRole('to_phu_kien'))
                                    <option value="{{$u->id}}">{{$u->userDetail->surname}}</option>
                                @endif
                            @endforeach                            
                        </select>
                        <br/>
                        <button id="btnAddKTV" class="btn btn-info btn-sm">THÊM</button>
                        <br/><br/>
                        <p id="showKTVChon">
                            <!-- <span>[ĐÃ THÊM] Nguyễn Hoàng Mới</span> <span class="badge badge-danger">Xóa</span> -->
                        </p>
                    </div>                    
                    <!-- <div class="container row">
                        <button id="updateHangMuc" type="button" class="btn btn-primary">CẬP NHẬT</button>
                    </div> -->
                </form>
        </div>        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>        
      </div>
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

        // show data
        $(document).ready(function() {
            function refreshHangMuc() {
                $.ajax({
                    type: "post",
                    url: "{{route('refreshhangmuc')}}",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": $("#eid").val()
                    },
                    success: function(response) {    
                        $("#chiTietHangMuc").html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tải danh mục!"
                        })                       
                    }
                });
            }
            function startUp() {
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
                //--------------
                $("#tongBaoGia").text("");
                $("#chietKhau").text("");
                $("#tongThanhToan").text("");
                $("#eid").val('');
            }

            function butChonseMain(process,done,cancel) {    
                if (process == 0) {
                    $("#add").show();  
                    $("#edit").show();
                    $("#done").hide();
                    $("#process").show();
                    $("#delete").show();  
                    $("#cancel").hide();
                    $("#in").hide();
                    $("#save").hide();
                    $("#unsave").hide();
                } else if(process == 1 && done == 0 && cancel == 0){
                    $("#add").show();  
                    $("#edit").show();
                    $("#done").show();
                    $("#cancel").show();
                    $("#delete").hide();
                    $("#process").hide();
                    $("#in").show();
                    $("#save").hide();
                    $("#unsave").hide();
                } else if(process == 1 && done == 1 && cancel == 0) {
                    $("#add").show(); 
                    $("#cancel").show();
                    $("#done").hide();
                    $("#process").hide();
                    $("#in").show();
                    $("#save").hide();
                    $("#unsave").hide();
                } else {
                    $("#add").show();  
                    $("#edit").hide();
                    $("#done").hide();
                    $("#process").hide();
                    $("#delete").hide();  
                    $("#cancel").hide();
                    $("#in").hide();
                    $("#save").hide();
                    $("#unsave").hide();
                }   
            }

            function disAfterSave() {
                $("#add").show();  
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
                $("#btnAdd").hide(); 
            }
            startUp();

            $("#add").click(function(){
                startUp();
                $(this).hide();
                $("#save").show();
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
                //--------------
                //--------------
                $("#tongBaoGia").text("");
                $("#chietKhau").text("");
                $("#tongThanhToan").text("");
            });

            $("#delete").click(function(){
                if(confirm("Bạn có chắc muốn xoá báo giá này?")) {
                    $.ajax({
                            type: "post",
                            url: "{{route('deletebaogia')}}",
                            dataType: "json",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "eid": $("#eid").val(),                              
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                }) 
                                if (response.code == 200) {
                                   setTimeout(() => {
                                    startUp();
                                    reloadData(); 
                                    butChonseMain(response.data.inProcess,response.data.isDone,response.data.isCancel);                                   
                                   }, 2000);
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'warning',
                                    title: " Không thể xoá! Báo giá đang có hạng mục, vui lòng xoá hạng mục trước!"
                                })                       
                            }
                        });  
                }
            });

            $("#notsave").click(function(){
                $(this).hide();
                if ($('#eid').val()) {
                  $("#save").hide();
                  disAfterSave();
                } else {
                  startUp();
                }
            });

            $("#edit").click(function(){
                $(this).hide();
                $("#showChiTietHangMuc").show();
                $("#btnAdd").show();
                $("#save").show();
                $("#delete").hide();
                $("#notsave").show();
                $("#add").hide();
                $("#process").hide();
                $("#done").hide();
                $("#cancel").hide();
                $("#in").hide();
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
            });

            $("#save").click(function(){
                if ($('#eid').val()) {
                    if (!$("#ngayVao").val() || !$("#gioVao").val())
                        alert("Bạn chưa nhập thời gian xe vào")
                    else if (!$("#ngayRa").val() || !$("#gioRa").val())
                        alert("Bạn chưa nhập thời gian xe hoàn tất")
                    else if (!$("#hoTen").val() || !$("#dienThoai").val())
                        alert("Bạn chưa nhập họ tên hoặc số điện thoại khách hàng")
                    else if (!$("#soKhung").val() || !$("#bienSo").val())
                        alert("Bạn chưa nhập biển số hoặc số khung xe")
                    else if (!$("#yeuCau").val())
                        alert("Bạn chưa nhập yêu cầu của khách hàng");
                    else if ($("input[name=dienThoai]").val().match(/\d/g).length !== 10) {
                        alert("Số điện thoại không đúng định dạng 10 số");                    
                    } else {
                        $.ajax({
                            type: "post",
                            url: "{{route('editbaogia')}}",
                            dataType: "json",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "eid": $("#eid").val(),
                                "isPKD": $("#isPKD").val(),
                                "isBaoHiem": $("#isBaoHiem").val(),
                                "hopDong": $("#hopDong").val(),
                                "nhanVien": $("#nhanVien").val(),
                                "saler": $("#saler").val(),
                                "gioVao": $("#gioVao").val(),
                                "ngayVao": $("#ngayVao").val(),
                                "gioRa": $("#gioRa").val(),
                                "ngayRa": $("#ngayRa").val(),
                                "hoTen": $("#hoTen").val(),
                                "dienThoai": $("#dienThoai").val(),
                                "mst": $("#mst").val(),
                                "diaChi": $("#diaChi").val(),
                                "bienSo": $("#bienSo").val(),
                                "soKhung": $("#soKhung").val(),
                                "soMay": $("#soMay").val(),
                                "thongTinXe": $("#thongTinXe").val(),
                                "taiXe": $("#taiXe").val(),
                                "dienThoaiTaiXe": $("#dienThoaiTaiXe").val(),
                                "yeuCau": $("#yeuCau").val()
                            },
                            success: function(response) {
                                $("#save").hide();
                                $("#notsave").hide();
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                }) 
                                if (response.code == 200) {
                                    setTimeout(() => {
                                        $("#btnAdd").show();
                                        $("#showChiTietHangMuc").show();
                                        disAfterSave();
                                        $("#save").hide();
                                        $("#notsave").hide();
                                        $("#soBaoGia").val(response.soBG);
                                        $("#isBaoHiem").val(response.isBaoHiem);
                                        $("#eid").val(response.idBG);
                                        reloadData();
                                        butChonseMain(response.data.inProcess,response.data.isDone,response.data.isCancel);                                      
                                    }, 2000);
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'warning',
                                    title: " Không tìm thấy!"
                                })                       
                            }
                        });                     
                }
                } else {
                    if (!$("#ngayVao").val() || !$("#gioVao").val())
                    alert("Bạn chưa nhập thời gian xe vào")
                    else if (!$("#ngayRa").val() || !$("#gioRa").val())
                            alert("Bạn chưa nhập thời gian xe hoàn tất")
                    else if (!$("#hoTen").val() || !$("#dienThoai").val())
                            alert("Bạn chưa nhập họ tên hoặc số điện thoại khách hàng")
                    else if (!$("#soKhung").val() || !$("#bienSo").val())
                            alert("Bạn chưa nhập biển số hoặc số khung xe")
                    else if (!$("#yeuCau").val())
                            alert("Bạn chưa nhập yêu cầu của khách hàng");
                    else if ($("input[name=dienThoai]").val().match(/\d/g).length !== 10) {
                            alert("Số điện thoại không đúng định dạng 10 số");                    
                    } else {
                            $.ajax({
                                type: "post",
                                url: "{{route('postbaogia')}}",
                                dataType: "json",
                                data: {
                                    "_token": "{{csrf_token()}}",
                                    "isPKD": $("#isPKD").val(),
                                    "isBaoHiem": $("#isBaoHiem").val(),
                                    "hopDong": $("#hopDong").val(),
                                    "nhanVien": $("#nhanVien").val(),
                                    "saler": $("#saler").val(),
                                    "gioVao": $("#gioVao").val(),
                                    "ngayVao": $("#ngayVao").val(),
                                    "gioRa": $("#gioRa").val(),
                                    "ngayRa": $("#ngayRa").val(),
                                    "hoTen": $("#hoTen").val(),
                                    "dienThoai": $("#dienThoai").val(),
                                    "mst": $("#mst").val(),
                                    "diaChi": $("#diaChi").val(),
                                    "bienSo": $("#bienSo").val(),
                                    "soKhung": $("#soKhung").val(),
                                    "soMay": $("#soMay").val(),
                                    "thongTinXe": $("#thongTinXe").val(),
                                    "taiXe": $("#taiXe").val(),
                                    "dienThoaiTaiXe": $("#dienThoaiTaiXe").val(),
                                    "yeuCau": $("#yeuCau").val()
                                },
                                success: function(response) {
                                    $("#save").hide();
                                    $("#notsave").hide();                                   
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    }) 
                                    if (response.code == 200) {
                                        setTimeout(() => {
                                            $("#btnAdd").show();
                                            $("#showChiTietHangMuc").show();
                                            disAfterSave();
                                            $("#save").hide();
                                            $("#notsave").hide();
                                            $("#soBaoGia").val(response.soBG);
                                            $("#isBaoHiem").val(response.isBaoHiem);
                                            $("#eid").val(response.idBG);  
                                            reloadData();   
                                            butChonseMain(response.data.inProcess,response.data.isDone,response.data.isCancel);                                                                             
                                        }, 2000);
                                    }
                                },
                                error: function() {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: " Không tìm thấy!"
                                    })                       
                                }
                            });                     
                    }
                }
            });
            function reloadData() {
                $.ajax({
                    type: "post",
                    url: "{{route('timkiembaogia')}}",
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
                       // startUp();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi!"
                        })
                    }
                });
            }
            $("#xemReport").click(function(e){
                e.preventDefault();
                reloadData();
            });  
            
            $("#process").click(function(){
                if (confirm("Xác nhận thực hiện báo giá?")) {
                    $.ajax({
                        type: "post",
                        url: "{{route('thuchienbaogia')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "eid": $("#eid").val()
                        },
                        success: function(response) {
                            $("#process").hide();
                            $("#delete").hide();
                            $("#edit").hide();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            }) 
                            if (response.code == 200) {
                                setTimeout(() => {
                                    reloadData();   
                                    $("#delete").hide();
                                    butChonseMain(response.data.inProcess,response.data.isDone,response.data.isCancel);                                   
                                }, 2000);
                            }
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Không tìm thấy!"
                            })                       
                        }
                    });
                }
            });    

            $("#saveBtn").click(function(e){
                $("input[name=bgid]").val($("#eid").val());
                $.ajax({
                    type: "post",
                    url: "{{route('luuhangmuc')}}",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {      
                        $("#addForm")[0].reset();               
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#showModal").modal('hide');
                        refreshHangMuc();
                        onloadTongCong();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể lưu!"
                        })                       
                    }
                });
            });

            $("#cancel").click(function(){                
                if (confirm("Xác nhận huỷ báo giá này?")) {
                    let lyDo = prompt("Lý do huỷ báo giá");
                    if (lyDo) {
                        $.ajax({
                            type: "post",
                            url: "{{route('huybaogia')}}",
                            dataType: "json",
                            data: {
                                "_token": "{{csrf_token()}}",
                                "eid": $("#eid").val(),
                                "lyDo": lyDo
                            },
                            success: function(response) {
                                $("#cancel").hide();
                                $("#delete").hide();
                                $("#edit").hide();
                                $("#process").hide();
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                }) 
                                if (response.code == 200) {
                                    setTimeout(() => {
                                        reloadData();  
                                        $("#delete").hide();
                                        butChonseMain(response.data.inProcess,response.data.isDone,response.data.isCancel);                                      
                                    }, 2000);
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'warning',
                                    title: " Không tìm thấy!"
                                })                       
                            }
                        });
                    } else {
                        Toast.fire({
                            icon: "info",
                            title: "Bạn chưa nhập lý do huỷ!"
                        }) 
                    }                   
                }
            });

            $("#done").click(function(){
                if (confirm("Xác nhận hoàn tất báo giá này?")) {
                    $.ajax({
                        type: "post",
                        url: "{{route('donebaogia')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "eid": $("#eid").val()
                        },
                        success: function(response) {
                            $("#done").hide();
                            $("#delete").hide();
                            $("#edit").hide();
                            $("#process").hide();
                            $("#cancel").hide();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            }) 
                            if (response.code == 200) {
                                setTimeout(() => {
                                    reloadData();  
                                    $("#delete").hide();
                                    butChonseMain(response.data.inProcess,response.data.isDone,response.data.isCancel);                                      
                                }, 2000);
                            }
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Không tìm thấy!"
                            })                       
                        }
                    });
                }
            });
            
            $('#isPKD').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                if (valueSelected == 1) {
                    $('#timHopDong').attr('placeholder','Nhập số hợp đồng');
                    $('#saler').prop('disabled',false);
                } else {
                    $('#timHopDong').attr('placeholder','Nhập số điện thoại');
                    $('#saler').prop('disabled',true);
                }                    
            });

            function onloadHangMuc() {
                $.ajax({
                    type: "post",
                    url: "{{route('loadhangmuc')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "boPhan": $("select[name=boPhan]").val(),     
                        "hangMuc": $("select[name=hangMuc]").val(),                              
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#hangMucChiTiet").empty();
                        let dataVal = response.data;
                        for(let i = 0; i < dataVal.length; i++) {
                            $("#hangMucChiTiet").append("<option value='"+dataVal[i].id+"'>"+dataVal[i].noiDung+"</option>");
                        }      
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hạng mục nào!"
                        })                       
                    }
                });  
            }

            $("#updateHangMuc").click(function(){
                $.ajax({
                    type: "post",
                    url: "{{route('postedithangmuc')}}",
                    dataType: "json",
                    data: $("#editForm").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })   
                        $("#editModal").modal('hide');
                        refreshHangMuc();  
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể cập nhật hạng mục!"
                        })                       
                    }
                });  
            });

            function onloadTongCong() {
                $.ajax({
                    type: "post",
                    url: "{{route('loadtongcong')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": $("#eid").val()                             
                    },
                    success: function(response) {
                        $("#tongBaoGia").text(formatNumber(parseInt(response.tongBaoGia)));
                        $("#chietKhau").text(formatNumber(parseInt(response.chietKhau)));
                        $("#tongThanhToan").text(formatNumber(parseInt(response.thanhToan)));            
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tính tổng báo giá!"
                        })                       
                    }
                });  
            }

            $('#hangMuc').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                $.ajax({
                    type: "post",
                    url: "{{route('loadhangmuc')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "boPhan": $("select[name=boPhan]").val(),     
                        "hangMuc": valueSelected,                              
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#hangMucChiTiet").empty();
                        let dataVal = response.data;
                        for(let i = 0; i < dataVal.length; i++) {
                            $("#hangMucChiTiet").append("<option value='"+dataVal[i].id+"'>"+dataVal[i].noiDung+"</option>");
                        }      
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hạng mục nào!"
                        })                       
                    }
                });  
            });

            $('#boPhan').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                $.ajax({
                    type: "post",
                    url: "{{route('loadhangmuc')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "boPhan": valueSelected,     
                        "hangMuc":  $("select[name=hangMuc]").val(),                              
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#hangMucChiTiet").empty();
                        let dataVal = response.data;
                        for(let i = 0; i < dataVal.length; i++) {
                            $("#hangMucChiTiet").append("<option value='"+dataVal[i].id+"'>"+dataVal[i].noiDung+"</option>");
                        }                           
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hạng mục nào!"
                        })                       
                    }
                });  
            });

            function getChiTietHangMuc(val) {
                $.ajax({
                    type: "post",
                    url: "{{route('loadbhpk')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": valueSelected                           
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#s_noiDung").text(response.data.noiDung);
                        $("#s_dvt").text(response.data.dvt);              
                        $("#s_gia").text(formatNumber(parseInt(response.data.donGia)));              
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hạng mục nào!"
                        })                       
                    }
                }); 
            }

            $('#hangMucChiTiet').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                $.ajax({
                    type: "post",
                    url: "{{route('loadbhpk')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": valueSelected                           
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#s_noiDung").text(response.data.noiDung);
                        $("#s_dvt").text(response.data.dvt);              
                        $("#s_gia").text(formatNumber(parseInt(response.data.donGia)));              
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hạng mục nào!"
                        })                       
                    }
                });  
            });

            $('#donGia').keyup(function(){
                var cos = $(this).val();
                var cos_soluong = $("input[name=soLuong]").val();
                $('#showDonGias').text("(" + DOCSO.doc(cos) + ")");
                $('input[name=thanhTien]').val(cos * cos_soluong);
                $('#showThanhTien').text("(" + DOCSO.doc(cos * cos_soluong) + ")");
            });
            $('#chietKhaun').keyup(function(){
                var cos = $(this).val();
                $('#showChietKhaus').text("(" + DOCSO.doc(cos) + ")");
            });

            $("#inLoad").click(function(){    
                switch(parseInt($("select[name=noiDungIn]").val())){
                    case 1: {
                        if($("#process").is(":visible")){
                            Toast.fire({
                                icon: 'warning',
                                title: "Báo giá chưa thực hiện không thể in!"
                            })
                        } else {
                           open("{{url('management/dichvu/inbaogia/')}}/" + $("#eid").val(),"_blank"); 
                        }
                    } break;
                    case 2: {
                        if($("#process").is(":visible")){
                            Toast.fire({
                                icon: 'warning',
                                title: "Báo giá chưa thực hiện không thể in!"
                            })
                        } else {
                           open("{{url('management/dichvu/inlenhsuachua/')}}/" + $("#eid").val(),"_blank"); 
                        }
                    } break;
                    case 3: {
                        if($("#process").is(":visible")){
                            Toast.fire({
                                icon: 'warning',
                                title: "Báo giá chưa thực hiện không thể in!"
                            })
                        } else {
                           open("{{url('management/dichvu/inyeucaucapvattu/')}}/" + $("#eid").val(),"_blank"); 
                        }                        
                    } break;
                    case 4: {
                        if($("#done").is(":visible")){
                            Toast.fire({
                                icon: 'warning',
                                title: "Báo giá chưa hoàn tất không thể in!"
                            })
                        } else {
                           open("{{url('management/dichvu/inquyettoan/')}}/" + $("#eid").val(),"_blank"); 
                        }                        
                    } break;
                }
            });

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
        });


        $(document).on('click','#tes',function(){     
            function onloadTongCongIn() {
                $.ajax({
                    type: "post",
                    url: "{{route('loadtongcong')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": $("#eid").val()                             
                    },
                    success: function(response) {
                        $("#tongBaoGia").text(formatNumber(parseInt(response.tongBaoGia)));
                        $("#chietKhau").text(formatNumber(parseInt(response.chietKhau)));
                        $("#tongThanhToan").text(formatNumber(parseInt(response.thanhToan)));            
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tính tổng báo giá!"
                        })                       
                    }
                });  
            }

            function refreshHangMucIn(idBG) {
                $.ajax({
                    type: "post",
                    url: "{{route('refreshhangmuc')}}",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": idBG
                    },
                    success: function(response) {    
                        $("#chiTietHangMuc").html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tải danh mục!"
                        })                       
                    }
                });
            }
            function startUpIn() {
                $("#showChiTietHangMuc").show();
                $("#btnAdd").hide();
                $("#chiTietHangMuc").text('');
                $("#add").show();
                $("#save").hide();
                $("#edit").hide();
                $("#delete").hide();
                $("#notsave").hide();
                $("#process").hide();
                $("#done").hide();
                $("#cancel").hide();
                $("#in").hide();
                $("#soBaoGia").prop('disabled', true);
                $("#gioVao").prop('disabled', true);
                $("#gioRa").prop('disabled', true);
                $("#ngayRa").prop('disabled', true);
                $("#ngayVao").prop('disabled', true);
                $("#soBaoGia").val('');
                $("#lyDoHuy").val('');
                $("#ngayRa").val('');
                $("#ngayVao").val('');
                $("#gioVao").val('');
                $("#gioRa").val('');

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
                //--------------
                $("#tongBaoGia").text("");
                $("#chietKhau").text("");
                $("#tongThanhToan").text("");
                $("#eid").val('');
            }

            function butChonse(process,done,cancel) {    
                if (process == 0) {
                    $("#add").show();  
                    $("#edit").show();
                    $("#process").show();
                    $("#done").hide();
                    $("#delete").show();  
                    $("#cancel").hide();
                    $("#in").hide();
                    $("#save").hide();
                    $("#unsave").hide();
                } else if(process == 1 && done == 0 && cancel == 0){
                    $("#add").show();  
                    $("#edit").show();
                    $("#cancel").show();
                    $("#done").show();
                    $("#process").hide();
                    $("#in").show();
                    $("#save").hide();
                    $("#unsave").hide();
                } else if(process == 1 && done == 1&& cancel == 0) {
                    $("#add").show(); 
                    $("#done").hide();
                    $("#cancel").show();
                    $("#process").hide();
                    $("#in").show();
                    $("#save").hide();
                    $("#unsave").hide();
                }    
            }

        //    $("table tr").removeClass("border border-primary");
        //    $(this).attr("class","border border-primary");
           $.ajax({
                type: "post",
                url: "{{route('loadbaogia')}}",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "eid": $(this).data('id')
                },
                success: function(response) {  
                    Toast.fire({
                        icon: 'info',
                        title: " Đã gửi yêu cầu tải hạng mục! "
                    })                    
                    startUpIn();                                  
                    $("#eid").val(response.data.id);
                    $("#isPKD").val(response.data.isPKD);
                    $("#soBaoGia").val(response.soBG);
                    $("#isBaoHiem").val(response.data.isBaoHiem);
                    $("#gioVao").val(response.data.thoiGianVao);
                    $("#gioRa").val(response.data.thoiGianHoanThanh);
                    $("#ngayVao").val(response.data.ngayVao);
                    $("#ngayRa").val(response.data.ngayHoanThanh);
                    $("#hopDong").val(response.data.hopDongKD);
                    $("#nhanVien").val(response.data.nvKD);
                    $("#saler").val(response.data.saler);
                    $("#hoTen").val(response.data.hoTen);
                    $("#dienThoai").val(response.data.dienThoai);
                    $("#mst").val(response.data.mst);
                    $("#diaChi").val(response.data.diaChi);
                    $("#taiXe").val(response.data.taiXe);
                    $("#dienThoaiTaiXe").val(response.data.dienThoaiTaiXe);
                    $("#thongTinXe").val(response.data.thongTinXe);
                    $("#bienSo").val(response.data.bienSo);
                    $("#soKhung").val(response.data.soKhung);
                    $("#soMay").val(response.data.soMay);
                    $("#yeuCau").val(response.data.yeuCau);
                    if (response.data.lyDoHuy != null)
                        $("#lyDoHuy").val(response.data.lyDoHuy);
                    else
                        $("#lyDoHuy").val('');
                    butChonse(response.data.inProcess,response.data.isDone,response.data.isCancel);
                    refreshHangMucIn(response.data.id);
                    onloadTongCongIn();
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                    startUpIn();
                }
            });
        });

        $(document).on('click','#delHangMuc', function(){
            function refreshHangMucTwo(idBG) {
                    $.ajax({
                        type: "post",
                        url: "{{route('refreshhangmuc')}}",
                        dataType: "text",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "eid": idBG
                        },
                        success: function(response) {    
                            $("#chiTietHangMuc").html(response);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Không thể tải danh mục!"
                            })                       
                        }
                    });
            }

            if($("#edit").is(":visible")){
                alert("Bạn phải chọn chỉnh sửa báo giá trước khi thực hiện thao tác này!")
            } else {
                if(confirm("Bạn có chắc muốn xoá?")) {
                    let idBG = $(this).data('bgid');
                    let idHM = $(this).data('hm');                
                    $.ajax({
                        type: "post",
                        url: "{{route('delhangmuc')}}",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "eid": idBG,
                            "ehm": idHM
                        },
                        success: function(response) {    
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            }) 
                            refreshHangMucTwo(idBG);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Không thể tải hạng mục!"
                            })                       
                        }
                    });
                } 
            }
        })

        $(document).on('click','#editHangMuc', function(){
            if($("#edit").is(":visible")){
                alert("Bạn phải chọn chỉnh sửa báo giá trước khi thực hiện thao tác này!")
            } else {
                $.ajax({
                    type: "post",
                    url: "{{route('getedithangmuc')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": $(this).data('bgid'),
                        "ehm": $(this).data('hm')
                    },
                    success: function(response) {    
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        if (response.code != 500) {
                            $("#editModal").modal('show');
                            $("#congViec").text(response.congViec);
                            $("input[name=editID]").val(response.data.id_baogia);
                            $("input[name=editIDHM]").val(response.data.id_baohiem_phukien);
                            let l_ktv = response.ktv;
                            let txt_ktv = ``;
                            for(let i = 0; i < l_ktv.length; i++)
                                txt_ktv += `<span>[ĐÃ THÊM] ${l_ktv[i].surname}</span> <span style="cursor: pointer;" id="xoaKTV" data-bgid="${l_ktv[i].id_baogia}" data-hm="${l_ktv[i].id_bhpk}" data-idktv="${l_ktv[i].id}" class="badge badge-danger">Xóa</span><br/>`;
                            $("#showKTVChon").html(txt_ktv);
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tải hạng mục!"
                        })        
                        $("#showKTVChon").html("<span></span>");               
                    }
                });   
            }              
        })

       
        $(document).on('click','#xoaKTV', function(){
            function delKTVrefresh(idBG) {
                    $.ajax({
                        type: "post",
                        url: "{{route('refreshhangmuc')}}",
                        dataType: "text",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "eid": idBG
                        },
                        success: function(response) {    
                            $("#chiTietHangMuc").html(response);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Không thể tải danh mục!"
                            })                       
                        }
                    });
            }

            if (confirm('Xác nhận xóa?')) {
                let idbg = $(this).data('bgid');
                $.ajax({
                    type: "post",
                    url: "{{route('xoaktv')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('idktv'),
                        "eid": $(this).data('bgid'),
                        "ehm": $(this).data('hm')
                    },
                    success: function(response) {    
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })                    
                        let l_ktv = response.ktv;
                        let txt_ktv = ``;
                        for(let i = 0; i < l_ktv.length; i++)
                            txt_ktv += `<span>[ĐÃ THÊM] ${l_ktv[i].surname}</span> <span style="cursor: pointer;" id="xoaKTV" data-idktv="${l_ktv[i].id}" class="badge badge-danger">Xóa</span><br/>`;
                        $("#showKTVChon").html(txt_ktv);
                        delKTVrefresh(idbg);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không thể tải hạng mục!"
                        })        
                        $("#showKTVChon").html("<span></span>");               
                    }
                });    
            }                   
        })

        $('#hangHoa').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                $.ajax({
                    type: "post",
                    url: "{{route('loadhanghoa')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "ma": $("input[name=hangHoa]").val(),                         
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })      
                        if (response.code == 200) {    
                            $("input[name=tenHang]").val(response.data.noiDung);     
                            $("#s_noiDung").text(response.data.noiDung);
                            $("#s_dvt").text(response.data.dvt);
                            $("#s_gia").text(formatNumber(parseInt(response.data.donGia)));
                            $("#s_congktv").text(formatNumber(parseInt(response.data.congKTV)));
                        }
                        else {
                            $("input[name=tenHang]").val("");    
                            $("#s_noiDung").text("");
                            $("#s_dvt").text("");
                            $("#s_gia").text("");
                            $("#s_congktv").text("");
                        }                            
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hàng hóa yêu cầu!"
                        })                       
                    }
                });  
            }
        });   
        
        $(document).on('click','#btnAddKTV', function(e){
            e.preventDefault();
            function addKTVrefresh(idBG) {
                    $.ajax({
                        type: "post",
                        url: "{{route('refreshhangmuc')}}",
                        dataType: "text",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "eid": idBG
                        },
                        success: function(response) {    
                            $("#chiTietHangMuc").html(response);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: " Không thể tải danh mục!"
                            })                       
                        }
                    });
            }
            let idbg = $("input[name=editID]").val();
            $.ajax({
                type: "post",
                url: "{{route('postktv')}}",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "idbg": $("input[name=editID]").val(),
                    "idhh": $("input[name=editIDHM]").val(),
                    "work": $("select[name=chonKTV]").val(),
                },
                success: function(response) {    
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })                   
                    let l_ktv = response.ktv;
                    let txt_ktv = ``;
                    for(let i = 0; i < l_ktv.length; i++)
                        txt_ktv += `<span>[ĐÃ THÊM] ${l_ktv[i].surname}</span> <span style="cursor: pointer;" id="xoaKTV" data-idktv="${l_ktv[i].id}" class="badge badge-danger">Xóa</span><br/>`;
                    $("#showKTVChon").html(txt_ktv);
                    addKTVrefresh(idbg);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Không thể thêm kỹ thuật viên!"
                    })                       
                }
            });             
        })
    </script>
@endsection
