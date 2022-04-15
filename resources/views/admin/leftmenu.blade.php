<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('trangchu')}}" class="brand-link">
        <img src="images/logo/logo.jpg" alt="Hyundai An Giang Logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light">Hyundai An Giang</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img class="img-circle elevation-2" src="upload/hoso/<?php 
                    try {
                        if (\Illuminate\Support\Facades\Auth::user()->userDetail->anh != null)
                            echo \Illuminate\Support\Facades\Auth::user()->userDetail->anh;
                        else 
                            echo "noavatar.jpg";
                    } catch (Throwable $ex) {
                        echo "noavatar.jpg";
                    }
                ?>" alt="Hình ảnh">
            </div>           
            <div class="info">
                <a href="{{route('changepass.list')}}" class="d-block">
                    <?php
                        try {
                            echo 'Tài khoản: '.\Illuminate\Support\Facades\Auth::user()->name . "<br/>(" . \Illuminate\Support\Facades\Auth::user()->userDetail->surname . ")";
                        } catch (Throwable $ex) {
                            echo \Illuminate\Support\Facades\Auth::user()->name;
                        }
                    ?>
                </a>
                <a href="{{route('out')}}" class="text-danger">Đăng xuất</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                <strong>QUẢN TRỊ</strong>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <li class="nav-item">
                            <a href="{{route('phong.panel')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Quản lý phòng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("user.list")}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Người dùng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('roles.list')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Phân quyền</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("hoso.list")}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Hồ sơ</p>
                            </a>
                        </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <li class="nav-item">
                                    <a href="{{route("typecar.list")}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Model Xe</p>
                                    </a>
                                </li>
                            @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <li class="nav-item">
                                    <a href="{{route('cong.list')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Loại công - phụ tùng</p>
                                    </a>
                                </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('sale') ||
                        \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p>
                                <strong>KINH DOANH</strong>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    @endif
                    <ul class="nav nav-treeview">
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('sale'))
                                <li class="nav-item">
                                    <a href="{{route('guest.list')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Khách hàng</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('hd.denghi')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Đề nghị t/h hợp đồng</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('hd.quanly.denghi')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Quản lý đề nghị</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('sale.kho')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Tồn kho</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('sale.xembanggiaxe')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Bảng giá xe</p>
                                    </a>
                                </li>
                                    <li class="nav-item">
                                    <a href="{{route('sale.xemthongbao')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Thông báo nội bộ</p>
                                    </a>
                                </li>
                                @endif
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                    <li class="nav-item">
                                        <a href="{{route('sale.banggiaxe')}}" class="nav-link">
                                            <i class="fas fa-caret-right nav-icon"></i>
                                            <p>QL Bảng giá xe</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('sale.thongbao')}}" class="nav-link">
                                            <i class="fas fa-caret-right nav-icon"></i>
                                            <p>QL Thông báo nội bộ</p>
                                        </a>
                                    </li>
                                @endif                            
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                <li class="nav-item">
                                    <a href="{{route('hd.quanly.pheduyet')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Phê duyệt đề nghị</p>
                                    </a>
                                </li>
                            @endif
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                                <li class="nav-item">
                                    <a href="{{route('hd.quanly.pheduyet.hopdong')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Phê duyệt hợp đồng</p>
                                    </a>
                                </li>
                            @endif
                    </ul>
                </li>
                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-car"></i>
                        <p>
                            <strong>KHO XE</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <li class="nav-item">
                            <a href="{{route('get.kho.v2')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Quản lý kho</p>
                            </a>
                        </li>                       
                        @endif
                        <li class="nav-item">
                            <a href="{{route('get.khohd.v2.report')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Báo cáo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('normal') ||
                    \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-car-side"></i>
                        <p>
                            <strong>QL XE DEMO</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <li class="nav-item">
                            <a href="{{route('laithu.list')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Quản lý xe</p>
                            </a>
                        </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('car'))
                        <li class="nav-item">
                            <a href="{{route('laithu.duyet')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Duyệt đăng ký</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('laithu.duyet.pay')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Duyệt trả</p>
                            </a>
                        </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') || 
                        \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))
                        <li class="nav-item">
                            <a href="{{route('laithu.tbp.duyet')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Duyệt đăng ký (TBP)</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('laithu.reg')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Đăng ký xe</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('laithu.pay')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Trả xe</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('status.list')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Tình trạng xe</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('ketoan') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            <strong>KẾ TOÁN</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                        <li class="nav-item">
                            <a href="{{route('ketoan')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Hợp đồng xe chi tiết</p>
                            </a>
                        </li>       
                        <li class="nav-item">
                            <a href="{{route('get.khohd.v2')}}" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Xe hợp đồng</p>
                            </a>
                        </li>     
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                            <li class="nav-item">
                                <a href="{{route('chungtu.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Chứng từ/mộc</p>
                                </a>
                            </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                            <li class="nav-item">
                                <a href="{{route('xemchungtu.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Xem Chứng từ/mộc</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('normal') ||
                    \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-person-booth"></i>
                        <p>
                            <strong>NHÂN SỰ</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('hanhchinh.hoso')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Thông tin nhân viên</p>
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a href="{{route('noiquy.xem')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Nội quy - quy chế</p>
                                </a>
                            </li> 
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('lead_chamcong'))
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('lead_chamcong'))
                            <li class="nav-item">
                                <a href="{{route('nhansu.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Quản lý chấm công</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('chotcong.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Quản lý chốt công</p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{route('tonghop.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Tổng hợp công</p>
                                </a>
                            </li>
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            <li class="nav-item">
                                <a href="{{route('quanlyphep.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Quản lý loại phép</p>
                                </a>
                            </li>
                            @endif
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('chamcong') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                            <li class="nav-item">
                                <a href="{{route('xinphep.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Quản lý xin phép</p>
                                </a>
                            </li>
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('chamcong') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            <li class="nav-item">
                                <a href="{{route('chitiet.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Chấm công chi tiết</p>
                                </a>
                            </li>
                            @endif
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('normal') ||
                    \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>
                            <strong>HÀNH CHÍNH</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                               <li class="nav-item">
                                    <a href="{{route('capxang.denghi')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Đề nghị nhiên liệu</p>
                                    </a>
                                </li>                                
                                <li class="nav-item">
                                    <a href="{{route('vpp.denghicongcu.panel')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Đề nghị công cụ</p>
                                    </a>
                                </li>
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('sale')||
                                    \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))    
                                <li class="nav-item">
                                    <a href="{{route('caphoa.panel')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Quản lý cấp hoa</p>
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{route('hanhchinh.xemthongbao')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Thông báo</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('hanhchinh.xembieumau')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Biểu mẫu</p>
                                    </a>
                                </li>                                                          
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))                    
                            <li class="nav-item">
                                <a href="{{route('capxang.duyet')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Duyệt nhiên liệu</p>
                                </a>
                            </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('drp') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))
                            <li class="nav-item">
                                    <a href="{{route('hanhchinh.bieumau.quanly')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Quản lý biểu mẫu</p>
                                    </a>
                             </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))  
                            <li class="nav-item">
                                <a href="{{route('vpp.nhapkho.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Quản lý nhập kho</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('vpp.quanlyxuatkho.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Quản lý xuất kho</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('vpp.baocaokho.panel')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Báo cáo kho</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('report') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('watch'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-binoculars"></i>
                        <p>
                            <strong>BÁO CÁO</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                        ((\Illuminate\Support\Facades\Auth::user()->hasRole('report') 
                        && \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))
                        && (\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') 
                            || \Illuminate\Support\Facades\Auth::user()->hasRole('tpdv') 
                            || \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') 
                            || \Illuminate\Support\Facades\Auth::user()->hasRole('cskh'))))
                            <li class="nav-item">
                                <a href="{{route("report")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Báo cáo ngày</p>
                                </a>
                            </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('boss') || 
                            \Illuminate\Support\Facades\Auth::user()->hasRole('watch') ||
                            ((\Illuminate\Support\Facades\Auth::user()->hasRole('report') && \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))) 
                            && (\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') 
                            || \Illuminate\Support\Facades\Auth::user()->hasRole('tpdv') 
                            || \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') 
                            || \Illuminate\Support\Facades\Auth::user()->hasRole('cskh')))
                            <li class="nav-item">
                                <a href="{{route("overview.list")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Báo cáo số liệu</p>
                                </a>
                            </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('report'))
                            <li class="nav-item">
                                <a href="{{route("overview.worklist")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Báo cáo công việc</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('work') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cannabis"></i>
                        <p>
                            <strong>CÔNG VIỆC</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('work') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                            <li class="nav-item">
                                <a href="{{route("worktohard")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Công việc tổng</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("complete.list")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Đã hoàn thành</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("working.list")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Đang thực hiện</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("work.get")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Nhận việc</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route("work.push")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Giao việc</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-mail-bulk"></i>
                        <p>
                            <strong>QUẢN LÝ MAIL</strong>
                        </p>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="{{route('nhatky.list')}}" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>
                            <strong>NHẬT KÝ</strong>
                        </p>
                    </a>
                </li>                
                @endif
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            GÓP Ý
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>                 -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
