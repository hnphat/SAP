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
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                         \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
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
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
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
                                    <a href="{{route('hd.list')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Hợp đồng</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('cancel.list')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Đề nghị hủy hợp đồng</p>
                                    </a>
                                </li>
                            @endif
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            <li class="nav-item">
                                <a href="{{route('kho.list')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Kho xe</p>
                                </a>
                            </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                    <li class="nav-item">
                                        <a href="{{route('pheduyet.list')}}" class="nav-link">
                                            <i class="fas fa-caret-right nav-icon"></i>
                                            <p>Phê duyệt HĐ</p>
                                        </a>
                                    </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <li class="nav-item">
                                    <a href="{{route('denghi.list')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Duyệt đề nghị HĐ</p>
                                    </a>
                                </li>
                            @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-car-side"></i>
                        <p>
                            <strong>QL XE DEMO</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
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
                             \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('tpdv'))
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
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') || \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))
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
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))
                        <!-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Duyệt cấp xăng</p>
                            </a>
                        </li> -->
                        @endif
                    </ul>
                </li>
                <!--
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            <strong>TÀI LIỆU</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-file-alt nav-icon"></i>
                                <p>Tài liệu chia sẽ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-file-upload nav-icon"></i>
                                <p>Upload tài liệu</p>
                            </a>
                        </li>
                    </ul>
                </li>
                -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>
                            <strong>HÀNH CHÍNH</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                               <li class="nav-item">
                                    <a href="{{route('capxang.denghi')}}" class="nav-link">
                                        <i class="fas fa-caret-right nav-icon"></i>
                                        <p>Đề nghị nhiên liệu</p>
                                    </a>
                                </li>
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                               \Illuminate\Support\Facades\Auth::user()->hasRole('lead'))                    
                            <li class="nav-item">
                                <a href="{{route('capxang.duyet')}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Duyệt cấp xăng</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('report') ||
                     \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
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
                            \Illuminate\Support\Facades\Auth::user()->hasRole('report'))
                            <li class="nav-item">
                                <a href="{{route("report")}}" class="nav-link">
                                    <i class="fas fa-caret-right nav-icon"></i>
                                    <p>Báo cáo ngày</p>
                                </a>
                            </li>
                        @endif
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('report'))
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
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            GÓP Ý
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
