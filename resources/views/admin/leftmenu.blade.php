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
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?php
                        try {
                            echo \Illuminate\Support\Facades\Auth::user()->userDetail->surname;
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
                                <i class="fas fa-user-alt nav-icon"></i>
                                <p>Người dùng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('roles.list')}}" class="nav-link">
                                <i class="fas fa-drafting-compass nav-icon"></i>
                                <p>Phân quyền</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("hoso.list")}}" class="nav-link">
                                <i class="far fa-newspaper nav-icon"></i>
                                <p>Hồ sơ</p>
                            </a>
                        </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                <li class="nav-item">
                                    <a href="{{route("typecar.list")}}" class="nav-link">
                                        <i class="fas fa-car nav-icon"></i>
                                        <p>Model Xe</p>
                                    </a>
                                </li>
                            @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <li class="nav-item">
                                    <a href="{{route('cong.list')}}" class="nav-link">
                                        <i class="fas fa-diagnoses nav-icon"></i>
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
                                        <i class="fas fa-people-arrows nav-icon"></i>
                                        <p>Khách hàng</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('hd.list')}}" class="nav-link">
                                        <i class="fas fa-allergies nav-icon"></i>
                                        <p>Hợp đồng</p>
                                    </a>
                                </li>
                            @endif
                            @if (\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                 \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                            <li class="nav-item">
                                <a href="{{route('kho.list')}}" class="nav-link">
                                    <i class="fas fa-columns nav-icon"></i>
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
                                            <i class="fab fa-accusoft nav-icon"></i>
                                            <p>Phê duyệt hợp đồng</p>
                                        </a>
                                    </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                <li class="nav-item">
                                    <a href="{{route('denghi.list')}}" class="nav-link">
                                        <i class="fab fa-asymmetrik nav-icon"></i>
                                        <p>Đề nghị hợp đồng</p>
                                    </a>
                                </li>
                            @endif
                    </ul>
                </li>
                <!--
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-hammer"></i>
                        <p>
                            <strong>DỊCH VỤ</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-people-arrows nav-icon"></i>
                                <p>Khách hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-hand-holding-usd nav-icon"></i>
                                <p>Báo giá thực hiện</p>
                            </a>
                        </li>
                            @endif
                    </ul>
                </li>
                -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-car-side"></i>
                        <p>
                            <strong>QUẢN LÝ LÁI THỬ</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-hand-point-right nav-icon"></i>
                                <p>Duyệt lái thử</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-hdd nav-icon"></i>
                                <p>Tình trạng xe</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-hand-holding nav-icon"></i>
                                <p>Đăng ký sử dụng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-hand-paper nav-icon"></i>
                                <p>Trả xe</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fab fa-creative-commons-remix nav-icon"></i>
                                <p>Đề nghị cấp xăng</p>
                            </a>
                        </li>
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
                <!--
                <li class="nav-item">
                    @if (\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                         \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            <strong>BÁO CÁO</strong>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if (\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                             \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                        <li class="nav-item">
                                <a href="{{route("package.list")}}" class="nav-link">
                                    <i class="fas fa-business-time nav-icon"></i>
                                    <p>Bảo hiểm - Phụ kiện</p>
                                </a>
                            </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tồn kho xe</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Khách hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kinh doanh</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dịch vụ</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                -->
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
