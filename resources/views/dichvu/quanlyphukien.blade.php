@extends('admin.index')
@section('title')
   Quản lý phụ kiện
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
                        <h1 class="m-0"><strong>Quản lý phụ kiện</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Quản lý phụ kiện</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div>
            <form>
                <div class="container row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Chọn loại báo giá</label>
                                <select name="baoCao" class="form-control">
                                    <option value="1">Tất cả</option>                           
                                    <option value="2">Mới tạo</option>                                 
                                    <option value="3">Đang thực hiện</option>  
                                    <option value="4">Hoàn tất</option>    
                                    <option value="5">Huỷ</option>                                                          
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
                                <tbody style="font-size: 10pt;">
                                    <tr id="tes">
                                        <td>BG01-202204</td>
                                        <td>67A-21322</td>
                                        <td>RUOSQKOMSS0223232</td>
                                        <td>16/01/2021</td>
                                    </tr>
                                    <tr id="tes">
                                        <td>BG01-202204</td>
                                        <td>67A-21322</td>
                                        <td>RUOSQKOMSS0223232</td>
                                        <td>16/01/2021</td>
                                    </tr>                                    
                                </tbody>
                        </table>
                    </div>
               </div>
               <div class="col-md-8">
                    <div class="container">
                        <button class="btn btn-success btn-sm">Thêm mới</button>
                        <button class="btn btn-info btn-sm">Lưu</button>
                        <button class="btn btn-secondary btn-sm">Không Lưu</button>
                        <button class="btn btn-primary btn-sm">Thực hiện</button>
                        <button class="btn btn-warning btn-sm">Hoàn tất</button>    
                        <button class="btn btn-danger btn-sm">Huỷ BG</button>    
                        <button id="in" class="btn btn-secondary"><span class="fas fa-print"></span> IN</button>
                        <hr>                
                        <div class="row">   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Số báo giá</label><br/>                                      
                                    <input type="text" name="soBaoGia" class="form-control">                                                                        
                                </div>        
                            </div>        
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Thời gian xe vào</label><br/>                                      
                                    <input type="text" name="ngayVao" class="form-control">                                                                        
                                </div>        
                            </div>     
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Thời gian xe ra</label><br/>                                      
                                    <input type="text" name="ngayRa" class="form-control">                                                                        
                                </div>        
                            </div>                                                
                        </div>
                        <div class="row">                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Loại báo giá</label>
                                    <select name="isPKD" class="form-control">
                                        <option value="1">Báo giá kinh doanh (sale)</option>
                                        <option value="0">Báo giá khai thác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                        <label><span class="text-danger">(*)</span>Nhập mã HD hoặc sđt KH</label><br/>                                      
                                        <input placeholder="Nhập số hợp đồng vd: 233" type="text" name="timHopDong" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                        <label>Hợp đồng số</label><br/>                                      
                                        <input type="text" name="hopDong" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                        <label>NV Kinh doanh</label><br/>                                      
                                        <input type="text" name="nhanVien" class="form-control">                                                                        
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">THÔNG TIN KHÁCH HÀNG</h4>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Họ tên</label><br/>                                      
                                    <input type="text" name="hoTen" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Điện thoại</label><br/>                                      
                                    <input type="text" name="dienThoai" class="form-control">                                                                        
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>MST</label><br/>                                      
                                    <input type="text" name="mst" class="form-control">                                                                        
                                </div>                                
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Địa chỉ</label><br/>                                      
                                    <input type="text" name="diaChi" class="form-control">                                                                        
                                </div>        
                            </div>                                                
                        </div>
                        <div class="row">   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Người liên hệ</label><br/>                                      
                                    <input type="text" name="taiXe" class="form-control">                                                                        
                                </div>        
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Điện thoại</label><br/>                                      
                                    <input type="text" name="dienThoaiTaiXe" class="form-control">                                                                        
                                </div>        
                            </div>                                           
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">THÔNG TIN XE</h4>
                        <div class="row"> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Biển số</label><br/>                                      
                                    <input type="text" name="bienSo" class="form-control">                                                                        
                                </div>        
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Số khung</label><br/>                                      
                                    <input type="text" name="soKhung" class="form-control">                                                                        
                                </div>        
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Số máy</label><br/>                                      
                                    <input type="text" name="soMay" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Chi tiết xe</label><br/>                                      
                                    <input placeholder="VD: Hyundai; Màu đỏ; Năm SX: 2022;" type="text" name="chiTietXe" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">YÊU CẦU KHÁCH HÀNG</h4>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">    
                                    <input placeholder="Nhập yêu cầu KH" type="text" name="yeuCau" class="form-control">                                                                        
                                </div>        
                            </div>   
                        </div>
                        <hr>
                        <h4 class="text-bold text-info">CHI TIẾT HẠNG MỤC</h4>
                        <button class="btn btn-success" data-toggle='modal' data-target='#showModal'><span class="fas fa-plus-circle"></span></button>
                        <div class="row">
                            <div style="overflow:auto;">
                                <table class="table table-striped table-bordered" style="font-size:11pt;">
                                    <tr class="bg-primary">
                                        <th>Bộ phận</th>  
                                        <th>Loại</th>                                    
                                        <th>Mã</th>
                                        <th>Nội dung</th>
                                        <th>Đơn vị tính</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Chiết khấu</th>
                                        <th>Thành tiền</th>
                                        <th>Tặng</th>
                                        <th>Thực hiện</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    <tbody>
                                        <tr>
                                            <td>Phụ kiện</td>  
                                            <td>CONG</td>                                    
                                            <td>ABC</td>
                                            <td>Lắp cản trước 5 chỗ</td>
                                            <td>Cái</td>
                                            <td>1</td>
                                            <td>375,500</td>
                                            <td>0</td>
                                            <td>375,500</td>
                                            <td>Không</td>
                                            <td>Nguyễn Văn Hoàng Phi Hợp</td>
                                            <td>
                                                <button class="btn btn-success btn-xs">Sửa</button>
                                                <button class="btn btn-danger btn-xs">Xoá</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phụ kiện</td>  
                                            <td>CONG</td>                                    
                                            <td>ABC</td>
                                            <td>Lắp cản trước 5 chỗ</td>
                                            <td>Cái</td>
                                            <td>1</td>
                                            <td>375,500</td>
                                            <td>0</td>
                                            <td>375,500</td>
                                            <td>Không</td>
                                            <td>Nguyễn Văn Hoàng Phi Hợp</td>
                                            <td>
                                                <button class="btn btn-success btn-xs">Sửa</button>
                                                <button class="btn btn-danger btn-xs">Xoá</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>     
                            </div>                                                  
                        </div>
                        <hr>
                        <h5>Tổng báo giá: <strong class="text-primary">8,000,000</strong></h5>
                        <h5>Chiết khấu: <strong class="text-primary">800,000</strong></h5>
                        <h4>Tổng cần thanh toán: <strong class="text-primary">7,200,000</strong> (Đã bao gồm VAT)</h4>
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
                <!-- <th>Bộ phận</th>  
                <th>Loại</th>                                    
                <th>Mã</th>
                <th>Nội dung</th>
                <th>Đơn vị tính</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Chiết khấu</th>
                <th>Thành tiền</th>
                <th>Tặng</th>
                <th>Thực hiện</th>
                <th>Tác vụ</th> -->
                <form id="addForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chọn bộ phận</label>
                                <select name="boPhan" class="form-control">
                                    <option value="1">Bảo hiểm</option>
                                    <option value="0">Phụ kiện</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Số lượng</label>
                                <input type="number" name="soLuong" value="0" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tặng</label>
                                <select name="tang" class="form-control">
                                    <option value="0">Không</option>
                                    <option value="1">Có</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chọn loại hạng mục</label>
                                <select name="hangMuc" class="form-control">
                                    <option value="1">Công</option>
                                    <option value="0">Phụ tùng</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Đơn giá</label>
                                <input type="number" name="donGia" value="0" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Chọn kỹ thuật viên thực hiện</label>
                                <select name="kyThuatVien" class="form-control">
                                    <option value="2">Không có</option>
                                    <option value="1">Nguyễn Văn Hoàng Phi Hợp</option>
                                    <option value="0">Nguyễn Tuấn Thanh</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chọn hạng mục chi tiết</label>
                                <select name="hangMucChiTiet" class="form-control">
                                    <option value="1">TNDS 5 chỗ</option>
                                    <option value="0">Dán film phủ gầm Accent</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Chiết khấu (nếu có)</label>
                                <input type="number" name="chietKhau" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đơn vị tính: <span class="text-primary">Cái</span></label><br/>
                                <label>Giá đã nhập: <span class="text-primary">567,000</span> (VAT)</label>
                            </div>                            
                        </div>
                    </div>
                    <div class="container row">
                        <button class="btn btn-success">LƯU</button>
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
            $("#xemReport").click(function(e){
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: "{{route('baocaohopdong.post')}}",
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
                        $("#showBaoCao").html(response);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Lỗi!"
                        })
                    }
                });
            });            
        });
        $(document).on('click','#tes',function(){
            $("table tr").removeClass("bg-secondary");
           $(this).attr("class","bg-secondary");
        });
    </script>
@endsection
