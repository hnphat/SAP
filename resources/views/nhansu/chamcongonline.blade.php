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
                <form id="addForm" autocomplete="off">
                    {{csrf_field()}}
                <div>   
                    <!-- <button onclick="openPermissionSettings()" class="btn btn-info">Chọn lại quyền Camera</button> -->
                    <p id="notDevice" style="display: none;">Trạng thái thiết bị: <strong class="text-danger">Bạn chưa đăng ký <button id="regDevice" class="btn btn-success btn-sm">Đăng ký ngay</button></strong></p>     
                    <p id="hasDevice" style="display: none;">Trạng thái thiết bị: <strong class="text-success">Đã đăng ký</strong></p>
                    <p id="hasDeviceOther" style="display: none;">Trạng thái thiết bị: <strong class="text-danger">Thiết bị khác thiết bị đã đăng ký</strong></p>
                    <input type="hidden" name="statusDevice" id="statusDevice">
                    <input type="hidden" name="getNowTimer" id="getNowTimer">
                    <p id="viTriNot" style="display: none;">Trạng thái vị trí: <strong class="text-danger">Đang không ở Công ty</strong></p>
                    <p id="viTriHas" style="display: none;">Trạng thái vị trí: <strong class="text-success">Đang ở Công ty</strong></p>
                    <input type="hidden" name="statusPos" id="statusPos">
                    <p class="text-center"><strong style="font-size:39pt;" id="showTimeNow"></strong></p>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>CHỌN BUỔI</strong>
                            <select class="form-control" name="buoiChamCong">
                                <option value="1">Sáng</option>
                                <option value="2">Chiều</option>
                                <option value="3">Tối</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <strong>CHỌN LOẠI CHẤM CÔNG</strong>
                            <select class="form-control" name="loaiChamCong">
                                <option value="1">Chấm công vào</option>
                                <option value="2">Chấm công ra</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-center">
                        <p class="text-center" id="btnOpenCamera">
                            <button onclick="startCamera()" class="btn btn-info" type="button">Mở camera</button>
                        </p>           
                        <p class="text-center">
                          <video id="camera" autoplay playsinline style="width:250px;max-width:250px;"></video>
                        </p>             
                        <canvas id="canvas" width="400" height="300" style="display:none;"></canvas>
                        <!-- <img id="preview" width="320" style="margin-top: 10px;"> -->
                        <!-- <button onclick="captureImage()" class="btn btn-primary" type="button">Chụp thử ảnh</button> -->
                        <input type="hidden" id="imageCaptured" name="imageCaptured">
                        <br/>
                        <p class="text-center">
                            <button id="sendChamCong" type="button" class="btn btn-primary">CHẤM CÔNG</button>
                            <!-- <button id="sendChamCong" style="display: none;" type="button" class="btn btn-primary">CHẤM CÔNG</button> -->
                        </p>
                        <h2 id="thongBao"></h2>
                    </p>
                    <hr>
                    <h5>BẢNG GHI CHẤM CÔNG HÔM NAY</h5>
                    <div class="row">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Thời gian</th>
                                    <th>Buổi</th>
                                    <th>Loại chấm công</th>
                                </tr>
                            </thead>
                            <tbody id="showChamCongHistory">
                               
                            </tbody>
                        </table>
                    </div>
                </div>    
                </form>           
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <script>
    let stream = null;    
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1920 },  // FULL HD
                height: { ideal: 1080 },
                facingMode: { exact: "user" }   // ép dùng camera trước
            },
            audio: false
            });

            document.getElementById("camera").srcObject = stream;
            if (stream) {
                document.getElementById("sendChamCong").style.display = "inline-block";
            }
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
            alert("Vui lòng tìm mục Camera và đổi thành 'Allow (Cho phép)' trong phần cài đặt trang web!");
        }
    }

    function captureImage() {
        if (!stream) {
            alert("Chưa bật camera!");
            return;
        }
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        let ctx = canvas.getContext("2d");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        let dataUrl = canvas.toDataURL("image/jpeg", 0.65); // Base64
        lastImage = dataUrl;
        // document.getElementById("preview").src = dataUrl;
        return dataUrl;
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
    <!-- <script src="https://openfpcdn.io/fingerprintjs/v3"></script> -->
    <script src="https://openfpcdn.io/fingerprintjs/v3/umd.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        (function(){
            const el = document.getElementById('showTimeNow');
            const sttTimer = document.getElementById('getNowTimer');
            if (!el) return;

            const pad = (n) => n.toString().padStart(2, '0');

            function updateTime() {
                const now = new Date();
                const hh = pad(now.getHours());
                const mm = pad(now.getMinutes());
                el.textContent = hh + ':' + mm;
                sttTimer.value = hh + ':' + mm;
            }   

            updateTime(); // cập nhật ngay lập tức
            setInterval(updateTime, 1000); // cập nhật mỗi giây
        })();
        
        async function getDeviceId() {
            const fp = await FingerprintJS.load();
            const result = await fp.get();
            return result.visitorId;
        }

        async function kiemTraTrangThaiThietBi() {
            try {
                const device_id = await getDeviceId();
                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/kiemtratrangthaithietbi/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "device_id": device_id
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            if (response.result === 0) {
                                $("#notDevice").show();
                                $("#hasDevice").hide();
                                $("#hasDeviceOther").hide();
                                $("#statusDevice").val(0);
                            } else if (response.result === 1) {
                                $("#notDevice").hide();
                                $("#hasDevice").show();
                                $("#hasDeviceOther").hide();
                                $("#statusDevice").val(1);
                            } else if (response.result === 2) {
                                $("#notDevice").hide();
                                $("#hasDevice").hide();
                                $("#hasDeviceOther").show();
                                $("#statusDevice").val(2);
                            }
                        } else {
                            Toast.fire({ icon: 'error', title: "Lỗi kiểm tra!" });
                        }
                    },
                    error: function() {
                        Toast.fire({ icon: 'error', title: "Không thể kiểm tra!" });
                    }
                });

            } catch (e) {
                console.error(e);
                Toast.fire({ icon: 'error', title: "Fingerprint lỗi" });
            }
        }

        document.addEventListener("DOMContentLoaded", kiemTraTrangThaiThietBi);

        document.getElementById("regDevice").addEventListener("click", async function(){
            try {
                const device_id = await getDeviceId();
                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/dangkythietbi/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "device_id": device_id
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            Toast.fire({ 
                                icon: response.type, 
                                title: response.message 
                            });
                            kiemTraTrangThaiThietBi();
                        } else {
                            Toast.fire({ 
                                icon: 'error', 
                                title: response.message 
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({ icon: 'error', title: "Không thể đăng ký thiết bị!" });
                    }
                });

            } catch (e) {
                console.error(e);
                Toast.fire({ icon: 'error', title: "Fingerprint lỗi" });
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            function kiemTraTrangThaiViTri() {
                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/kiemtratrangthaivitri/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}"
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            if (response.result === 1) {
                                $("#viTriNot").hide();
                                $("#viTriHas").show();
                                $("#statusPos").val(1);
                            } else {
                                $("#viTriNot").show();
                                $("#viTriHas").hide();
                                $("#statusPos").val(0);
                            }
                        } else {
                            Toast.fire({ icon: 'error', title: "Lỗi kiểm tra!" });
                        }
                    },
                    error: function() {
                        Toast.fire({ icon: 'error', title: "Không thể kiểm tra!" });
                    }
                });
            }
            
            kiemTraTrangThaiViTri();

            function autoLoadHistory() {
                // Gọi AJAX load lịch sử chấm công hôm nay
                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/loadlichsu/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}"
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            $("#showChamCongHistory").html("");
                            let txt = ``;
                            let stt = 1;
                            let loai = 0;
                            let buoi = 0;
                            for (let index = 0; index < response.data.length; index++) {
                                const element = response.data[index];
                                loai = parseInt(element.loaichamcong);
                                buoi = parseInt(element.buoichamcong);
                                let loaiText = (loai === 1) ? "<strong class='text-primary'>Vào</strong>" : "<strong class='text-pink'>Ra</strong>";
                                let buoiText = (buoi === 1) ? "Sáng" : (buoi === 2) ? "Chiều" : "Tối";
                                txt += `<tr>
                                        <td>${stt}</td>
                                        <td><strong>${element.thoigianchamcong}</strong></td>
                                        <td><strong>${buoiText}</strong></td>
                                        <td>${loaiText}</td>
                                    </tr>`;
                                stt++;
                            }
                            $("#showChamCongHistory").html(txt);
                        } else {
                            Toast.fire({ icon: 'error', title: "Lỗi tải lịch sử chấm công!" });
                        }
                    },
                    error: function() {
                        Toast.fire({ icon: 'error', title: "Không thể tải lịch sử chấm công!" });
                    }
                });
            }
            autoLoadHistory();
            $("#sendChamCong").click(function(){
                // if (confirm("Xác nhận chấm công?\nVui lòng kiểm tra kỹ Buổi chấm công, Loại chấm công.\nKhông thể chấm lại nếu chọn sai thông tin") == false) {
                //     return;
                // }
                if (!stream) {
                    alert("Chưa bật camera!");
                    return;
                }
                let capturedImage = captureImage();
                $("#imageCaptured").val(capturedImage);
                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/chamcong/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        if (response.code === 200) {
                            // Toast.fire({ 
                            //     icon: 'success', 
                            //     title: "Đã ghi nhận chấm công!" 
                            // });
                            $("#camera").hide();
                            $("#btnOpenCamera").hide();
                            $("#thongBao").html("<span class='text-success'>Đã ghi nhận chấm công!</span>");
                            $("#sendChamCong").hide();
                            autoLoadHistory();
                        } else {
                            // Toast.fire({ 
                            //     icon: 'error', 
                            //     title: response.message 
                            // });
                            $("#camera").hide();
                            $("#btnOpenCamera").hide();
                            $("#thongBao").html("<span class='text-danger'>"+response.message+"</span>");
                            $("#sendChamCong").hide();
                        }
                    },
                    error: function() {
                        $("#camera").hide();
                        Toast.fire({ icon: 'error', title: "Không thể chấm công!" });
                    }
                });
            });
        });
    </script>
@endsection
