@extends('admin.index')
@section('title')
   Chấm công online
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Chấm công Online</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Chấm công Online</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="container">      
                    <video id="camera" autoplay playsinline style="width:100%;max-width:200px;"></video>
                    <button onclick="startCamera()">Start camera</button>
                    <button onclick="openPermissionSettings()">Chọn lại quyền Camera</button>
                    <p>Trạng thái: <strong class="text-danger">Bạn chưa đăng ký thiết bị <button id="regDevice" class="btn btn-success btn-sm">Đăng ký ngay</button></strong></p>     
                    <p>Trạng thái: <strong class="text-success">Thiết bị đã đăng ký</strong></p>
                    <p>Trạng thái: <strong class="text-danger">Thiết bị lạ khác với thiết bị đã đăng ký trước đó</strong></p>
                    <input type="hidden" name="getStt" id="getStt">
                    <input type="hidden" name="getNowTimer" id="getNowTimer">
                    <p>Thời gian hiện tại: <strong style="font-size:25pt;">08:00</strong></p>
                    <p>Trạng thái vị trí: <strong class="text-danger">Đang không ở Công ty</strong></p>
                    <p>Trạng thái vị trí: <strong class="text-success">Đang ở Công ty</strong></p>
                    <p>Điều kiện chấm công: <strong class="text-success">Có thể chấm công</strong></p>
                    <p>Điều kiện chấm công: <strong class="text-danger">Không thể chấm công</strong></p>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>CHỌN BUỔI</strong>
                            <select class="form-control" name="buoi">
                                <option value="1">Sáng</option>
                                <option value="2">Chiều</option>
                                <option value="3">Tối</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <strong>CHỌN LOẠI CHẤM CÔNG</strong>
                            <select class="form-control" name="buoi">
                                <option value="1">Chấm công vào</option>
                                <option value="2">Chấm công ra</option>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <p class="text-center">
                        <button class="btn btn-primary">CHẤM CÔNG</button>
                    </p>
                    <h6 class="text-info">Ghi nhận chấm công: <strong>12:00 ngày 18/11/2025</strong></h6>
                    <p>
                        <strong>ĐÃ CHẤM CÔNG</strong><br>
                        - Buổi sáng: <br>
                        + Vào: Chưa có <br>
                        + Ra: 16:00 <br>
                        - Buổi chiều: <br>
                        + Vào: 12:00 <br>
                        + Ra: Chưa có <br>
                        - Buổi tối: <br>
                        + Vào: Chưa có <br>
                        + Ra: Chưa có <br>
                    </p>
                </div>               
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <script>
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: { exact: "user" }   // ép dùng camera trước
            },
            audio: false
            });

            document.getElementById("camera").srcObject = stream;
        } 
        catch (err) {
            // fallback nếu máy không hỗ trợ exact
            console.warn("Không dùng được exact:user, chuyển sang ideal:user", err);

            try {
            const fallback = await navigator.mediaDevices.getUserMedia({
                video: {
                facingMode: { ideal: "user" }  // ưu tiên camera trước
                },
                audio: false
            });
            document.getElementById("camera").srcObject = fallback;
            }
            catch(e2) {
            alert("Điện thoại không cho phép mở camera trước: " + e2);
            }
        }
    }
    function openPermissionSettings() {
        // Chỉ Chrome hỗ trợ API này
        if (navigator.permissions && navigator.permissions.query) {
            alert("Vui lòng tìm mục Camera và đổi thành 'Allow (Cho phép)'.\nTrình duyệt sẽ tự hiện phần cài đặt.");
        }

        // Mở trang cấu hình site của trình duyệt
        window.location.href = "chrome://settings/content/siteDetails?site=" + encodeURIComponent(location.origin);
    }
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
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

        $(document).ready(function(){
           
               
        });
    </script>
@endsection
