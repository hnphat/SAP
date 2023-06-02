@extends('admin.index')
@section('title')
   Báo cáo hợp đồng
@endsection
@section('script_head')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Báo cáo hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Báo cáo hợp đồng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div>
            <form>
                <div class="card-body row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Chọn loại báo cáo</label>
                                <select name="baoCao" class="form-control">
                                    <option value="1">Tất cả</option>                           
                                    <option value="2">Đề nghị đợi duyệt (admin)</option>                                 
                                    <option value="3">Đề nghị mới tạo</option>  
                                    <option value="4">Hợp đồng ký</option>    
                                    <option value="5">Hợp đồng ký chờ</option>      
                                    <option value="6">Hợp đồng ký đại lý</option> 
                                    <option value="7">Hợp đồng huỷ</option>                               
                                    <option value="8">Hợp đồng đợi duyệt (trưởng phòng)</option> 
                                    <option value="9">Đã giao xe</option>                                                              
                                </select> <br/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Từ</label>
                                <input type="date" name="tu" value="<?php echo Date('Y-m-d');?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Đến</label>
                                <input type="date" name="den" value="<?php echo Date('Y-m-d');?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="submit" id="xemReport" class="form-control btn btn-info" value="XEM"/>
                                <br><br>
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <button type="button" id="exportExcel" class="form-control btn btn-primary export">Xuất dữ liệu</button>
                                @endif
                                <!-- <a href ="{{url('exportexcel/') }} " class="form-control btn btn-primary export"> Xuất dữ liệu </a> -->
                            </div>
                        </div>
                </div>
            </form>
            <div class="container-fluid">
                <div style="overflow:auto;">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>STT</th>
                            <th>Ngày tạo</th>
                            <th>Nguồn KH</th>
                            <th>Loại HĐ</th>
                            <th>Trạng thái</th>
                            <th>Sale</th>
                            <th>Khách hàng</th>
                            <th>Dòng xe</th>
                            <th>Màu</th>
                            <th>Thanh toán</th>
                            <th>Giá bán</th>
                            <th>Giá vốn</th>
                            <th>Cộng tiền mặt</th>
                            <th>Hỗ trợ HTV</th>

                            <th class="table-secondary">Tặng trước bạ</th>
                            <th class="table-secondary">Tặng Bảo hiểm</th>
                            <th class="table-secondary">Tặng Phụ kiện</th>
                            <th class="table-secondary">Tặng Công ĐK</th>
                            <th class="table-secondary">Tổng khuyến mãi</th>
                            <th class="table-primary">Bảo hiểm bán</th>
                            <th class="table-primary">Phụ kiện bán</th>
                            <th class="table-primary">Công đăng ký</th>
                            <!-- <th>Hoa hồng MG</th> -->
                            <th class="table-secondary">Phí vận chuyển</th>
                            <th class="table-success">Lợi nhuận xe</th>
                            <th class="table-success">Tỉ suất LN xe</th>
                            <th>Ngày xuất xe</th>
                            
                            <th>Ngày nhận nợ</th>
                            <th class="table-secondary">Phí lãi vay</th>
                            <th class="table-secondary">Phí lưu kho</th>
                            <th class="table-secondary">HH sale</th>
                            <th class="table-warning">Lãi gộp</th>
                            <th class="table-warning">Tỉ suất lãi gộp</th>

                            <th>Tác vụ</th>
                        </tr>
                        <tbody id="showBaoCao">
                        </tbody>                                         
                    </table>
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
          <h4 class="modal-title"><strong>BÁO CÁO HỢP ĐỒNG CHI TIẾT</strong></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <h6>Mã đề nghị: <span id="maDeNghi" class="text-bold text-primary"></span> 
          - Ngày tạo: <span id="ngayTao" class="text-bold"></span></h6>
          <h6>Số hợp đồng: <span id="soHopDong" class="text-bold text-pink"></span></h6>
          <h6>Nhân viên kinh doanh: <span id="tenNhanVien" class="text-bold"></span></h6>
          <hr>
          <h5 class="text-bold">THÔNG TIN KHÁCH HÀNG</h5>
          <h6>
          Họ và tên: <span id="tenKhachHang" class="text-bold"></span> 
          - Ngày sinh: <span id="ngaySinh" class="text-bold"></span> 
          - Số điện thoại: <span id="soDienThoai" class="text-bold"></span>
          - MST: <span id="maSoThue" class="text-bold"></span>
        </h6>
          <h6>CMND/CCCD: <span id="cmnd" class="text-bold"></span>
          - Ngày Cấp: <span id="ngayCap" class="text-bold"></span> 
          - Nơi cấp: <span id="noiCap" class="text-bold"></span></h6>
          <h6>Địa chỉ: <span id="diaChi" class="text-bold"></span></h6>
          <h6>Đại diện: <span id="daiDien" class="text-bold"></span> - Chức vụ: <span id="chucVu" class="text-bold"></span></h6>
          <hr>
          <h5 class="text-bold">THÔNG TIN XE BÁN</h5>
          <h6>Xe bán: <span id="tenXeBan" class="text-bold"></span>
          - Màu sắc: <span id="mauXeBan" class="text-bold"></span> 
          - Giá xe: <span id="giaXeBan" class="text-bold text-primary"></span> 
          - Tiền cọc: <span id="tienDatCoc" class="text-bold text-primary"></span></h6>
          <h6>Hình thức thanh toán: <span id="hinhThucThanhToan" class="text-bold"></span></h6>
          <h5 class="text-bold">CHI TIẾT XE</h5>
          <table class="table table-striped table-bordered">
              <tr class="bg-info">
                  <th>Tên xe</th>
                  <th>VIN</th>
                  <th>Số khung/số máy</th>
                  <th>Thông tin khác</th>
              </tr>
              <tbody>
                <tr >
                    <td><span id="tenXeBan2" class="text-bold"></span></td>
                    <td><span id="soKhung" class="text-bold text-pink"></span></td>
                    <td><span id="soMay" class="text-bold text-pink"></span></td>
                    <td><span id="chiTietXe" class="text-bold text-secondary"></span></td>
                </tr>
              </tbody>              
          </table>
          <hr>
          <h5 class="text-bold">CÁC LOẠI PHÍ</h5>
          <table class="table table-striped table-bordered">
              <tr class="bg-info">
                  <th>TT</th>
                  <th>Nội dung</th>
                  <th>Giá</th>
                  <th>Tặng</th>
              </tr>
              <tbody id="cacLoaiPhi">                
              </tbody>              
          </table>
          <h6><strong>TỔNG CỘNG:</strong> <span id="tongCongPhi" class="text-bold text-primary"></span><span id="chiPhiTang" class="text-bold"></span></h6>
          <hr>
          <h5 class="text-bold">PHỤ KIỆN BÁN</h5>
          <table class="table table-striped table-bordered">
              <tr class="bg-info">
                  <th>TT</th>
                  <th>Nội dung</th>
                  <th>Giá</th>
              </tr>
              <tbody id="phuKienBan">
              </tbody>
          </table>
          <h6><strong>TỔNG CỘNG:</strong> <span id="tongPhuKienBan" class="text-bold text-primary"></span></h6>
          <hr>
          <h5 class="text-bold">PHỤ KIỆN KHUYẾN MÃI, QUÀ TẶNG</h5>
          <table class="table table-striped table-bordered">
              <tr class="bg-info">
                  <th>TT</th>
                  <th>Nội dung</th>
                  <th>Giá</th>
                  <th>Loại</th>
              </tr>
              <tbody id="phuKienKhuyenMai">
              </tbody>             
          </table>
          <h6><strong>TỔNG CỘNG:</strong> <span id="tongPhuKienKhuyenMai" class="text-bold text-primary"></span></h6>
          <hr>
          <h4><strong>TỔNG GIÁ TRỊ HỢP ĐỒNG:</strong> <span id="tongGiaTriHopDong" class="text-bold text-primary"></span></h4>
          <hr>
          <h5 class="text-bold text-primary">Chưa bao gồm phí vận chuyển (nếu có): <span id="phiVanChuyen" class="text-bold text-warning"></span></h5>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
    <!----------------------->
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
            $("#exportExcel").click(function(){
                if(confirm('Xác nhận xuất dữ liệu excel')) {
                    open("{{url('management/baocaohopdong/exportexcel/')}}" 
                + "/" 
                + $("input[name=tu]").val() 
                + "/den/" 
                + $("input[name=den]").val()
                + "/loaibaocao/" 
                +  $("select[name=baoCao]").val()
                ,'_blank');
                }
            });


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
                            title: " Lỗi Server!"
                        })
                    }
                });
            });            
        });
        $(document).on('click','#xemChiTiet',function(){
            $.ajax({
                type: "post",
                url: "{{route('chitiethopdong.post')}}",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "idhopdong": $(this).data('idhopdong')
                },
                success: function(response) {
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })           
                    $('#maDeNghi').text(response.maDeNghi);
                    $('#ngayTao').text(response.ngayTao);
                    $('#soHopDong').text(response.soHopDong);
                    $('#tenNhanVien').text(response.tenNhanVien);
                    $('#tenKhachHang').text(response.tenKhachHang);
                    $('#ngaySinh').text(response.ngaySinh);
                    $('#soDienThoai').text(response.soDienThoai);
                    $('#maSoThue').text(response.maSoThue);
                    $('#cmnd').text(response.cmnd);
                    $('#ngayCap').text(response.ngayCap);
                    $('#noiCap').text(response.noiCap);
                    $('#diaChi').text(response.diaChi);
                    $('#daiDien').text(response.daiDien);
                    $('#chucVu').text(response.chucVu);
                    $('#tenXeBan').text(response.tenXeBan);

                    $('#tenXeBan2').text(response.tenXeBan);
                    $('#soKhung').text(response.soKhung);
                    $('#soMay').text(response.soMay);
                    $('#mauXeBan').text(response.mauXeBan);
                    $('#giaXeBan').text(response.giaXeBan);
                    $('#tienDatCoc').text(response.tienDatCoc);
                    $('#hinhThucThanhToan').text(response.hinhThucThanhToan);
                    $('#chiTietXe').text(response.chiTietXe);

                    let cacLoaiPhi = "";
                    let tangChiPhi = 0;
                    for(let i = 0; i < response.cacLoaiPhi.length; i++) {
                        cacLoaiPhi += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.cacLoaiPhi[i].name + "</td>" +                                
                                "<td>" + formatNumber(parseInt(response.cacLoaiPhi[i].cost)) + "</td>" +
                                "<td>" + (response.cacLoaiPhi[i].cost_tang == true ? "<strong class='text-success'>Có</strong>" : "Không") + "</td>" +
                                "</tr>"; 
                                if (response.cacLoaiPhi[i].cost_tang == true)  
                                    tangChiPhi += parseInt(response.cacLoaiPhi[i].cost);                         
                        }
                    $("#cacLoaiPhi").html(cacLoaiPhi);                   
                    let tongCongFinal = parseInt(response.tongCongPhi.replaceAll(',', '')) - parseInt(tangChiPhi.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replaceAll('.0', '').replaceAll(',', ''));
                    $('#tongCongPhi').text(tongCongFinal.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replaceAll('.0', ''));
                    $('#chiPhiTang').text(" (Đã trừ chi phí tặng: " + tangChiPhi.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replaceAll('.0', '') + ")");
                    let phuKienBan = "";
                    for(let i = 0; i < response.phuKienBan.length; i++) {
                        phuKienBan += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.phuKienBan[i].name + "</td>" +                                
                                "<td>" + formatNumber(parseInt(response.phuKienBan[i].cost)) + "</td>" +
                                "</tr>";                            
                        }
                    $("#phuKienBan").html(phuKienBan);
                    $('#tongPhuKienBan').text(response.tongPhuKienBan);
                    let phuKienKhuyenMai = "";
                    for(let i = 0; i < response.phuKienKhuyenMai.length; i++) {
                        phuKienKhuyenMai += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.phuKienKhuyenMai[i].name + "</td>" +                                
                                "<td>" + formatNumber(parseInt(response.phuKienKhuyenMai[i].cost)) + "</td>" +
                                "<td>" + (response.phuKienKhuyenMai[i].free_kem == false ? "<strong class='text-success'>Tặng thêm</strong>" : "Kèm theo xe") + "</td>" +
                                "</tr>";                            
                        }
                    $("#phuKienKhuyenMai").html(phuKienKhuyenMai);
                    $('#tongPhuKienKhuyenMai').text(response.tongPhuKienKhuyenMai);
                    $('#phiVanChuyen').text(formatNumber(response.phiVanChuyen));
                    $('#tongGiaTriHopDong').text(response.tongGiaTriHopDong);      
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                }
            }); 
        });
    </script>
@endsection
