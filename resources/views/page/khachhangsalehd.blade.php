@extends('admin.index')
@section('title')
    Quản lý saler
@endsection
@section('script_head')
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"> 
    <!-- <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"> -->
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý saler</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Quản lý saler</li>
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
                                    <strong>Quản lý saler</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                              <form>
                                <div class="card-body row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Chọn nhân viên</label>
                                                <select name="nhanVien" class="form-control"> 
                                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('cskh') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))
                                                    <option value="0">Tất cả</option>                                                               
                                                    @foreach($groupsale as $row)    
                                                        @if (!\Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))                                                    
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @elseif ($row['group'] == $groupid)
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @else
                                                        
                                                        @endif 
                                                    @endforeach   
                                                @else   
                                                    <option value="{{$iduser}}">{{$nameuser}}</option>
                                                @endif
                                                </select> <br/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Từ</label>
                                                <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Đến</label>
                                                <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <br/>
                                                <button id="xemReport" type="button" class="btn btn-info" class="form-control">Xem</button>
                                            </div>
                                        </div>
                                </div>
                              </form>
                              <hr/>
                              <div id="all">
       
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
                    <th>Giảm giá</th>
                    <th>Thành tiền</th>
                </tr>
                <tbody id="phuKienBan">
                </tbody>
            </table>
            <h6>
                <strong>Giá:</strong> <span id="tongPhuKienBan" class="text-bold"></span> <br/>
                <!-- <strong>Áp dụng giảm giá: </strong> <span id="magiamgia" class="text-bold text-pink"></span> <br/>
                <strong>Tổng cộng: </strong> <span id="tongPhuKienBanGiam" class="text-bold text-primary"></span> <br/> -->
            </h6>
            <hr>
            <h5 class="text-bold">PHỤ KIỆN KHUYẾN MÃI, QUÀ TẶNG</h5>
            <table class="table table-striped table-bordered">
                <tr class="bg-info">
                    <th>TT</th>
                    <th>Nội dung</th>
                    <th>Loại</th>
                </tr>
                <tbody id="phuKienKhuyenMai">
                </tbody>             
            </table>
            <!-- <h6><strong>TỔNG CỘNG:</strong> <span id="tongPhuKienKhuyenMai" class="text-bold text-primary"></span></h6> -->
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
       $(document).ready(function(){
            // $("#taiReport").click(function(){
            //     if(confirm('Xác nhận xuất dữ liệu excel')) {
            //         open("{{url('management/dichvu/exportexcel/')}}" 
            //     + "/" 
            //     + $("input[name=chonNgayOne]").val() 
            //     + "/den/" 
            //     + $("input[name=chonNgayTwo]").val()
            //     + "/loaibaocao/" 
            //     +  $("select[name=chonBaoCao]").val()
            //     + "/u/" 
            //     +  $("select[name=nhanVien]").val()
            //     ,'_blank');
            //     }
            // });


         $("#xemReport").click(function(){
            $.ajax({
                type: "post",
                url: "{{url('management/guest/loadbaocaokhachhang/')}}",
                dataType: "text",
                data: {
                    "_token": "{{csrf_token()}}",
                    "nhanVien": $("select[name=nhanVien]").val(),
                    "tu": $("input[name=chonNgayOne]").val(),
                    "den": $("input[name=chonNgayTwo").val()
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'info',
                        title: " Đã gửi yêu cầu! "
                    })
                    $("#all").html(response);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                }
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
                                "<td>"+ (response.phuKienBan[i].giamGia != 0 ? response.phuKienBan[i].giamGia + "%" : "") +"</td>" +
                                "<td>" + formatNumber(parseInt(response.phuKienBan[i].cost - (response.phuKienBan[i].cost*response.phuKienBan[i].giamGia/100))) + "</td>" +
                                "</tr>";                            
                        }
                    $("#phuKienBan").html(phuKienBan);
                    $('#tongPhuKienBan').text(response.tongPhuKienBan);
                    // $('#magiamgia').text(response.magiamgia);
                    // $('#tongPhuKienBanGiam').text(response.tongPhuKienBanGiam);
                    let phuKienKhuyenMai = "";
                    for(let i = 0; i < response.phuKienKhuyenMai.length; i++) {
                        let mode = "";
                        switch(response.phuKienKhuyenMai[i].mode) {
                            case "KEMTHEOXE": mode = "<strong class='text-secondary'>Kèm theo xe</strong>"; break;
                            case "GIABAN": mode = "<strong class='text-pink'>Tặng trên giá bán</strong>"; break;
                            case "CTKM": mode = "<strong class='text-info'>Chương trình khuyến mãi</strong>"; break;
                            case "TANGTHEM": mode = "<strong class='text-success'>Tặng thêm</strong>"; break;
                            default: mode = "";
                        }
                        mode = (mode != "") ? mode : (response.phuKienKhuyenMai[i].free_kem == true ? "<strong class='text-secondary'>Kèm theo xe</strong>" : "<strong class='text-success'>Tặng thêm</strong>");
                        phuKienKhuyenMai += "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + response.phuKienKhuyenMai[i].name + "</td>" +                                
                                // "<td>" + formatNumber(parseInt(response.phuKienKhuyenMai[i].cost)) + "</td>" +
                                "<td>" + mode + "</td>" +
                                "</tr>";                            
                        }
                    $("#phuKienKhuyenMai").html(phuKienKhuyenMai);
                    // $('#tongPhuKienKhuyenMai').text(response.tongPhuKienKhuyenMai);
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
       });
    </script>
@endsection
