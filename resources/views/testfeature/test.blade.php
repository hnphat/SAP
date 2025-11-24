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
    <link rel="stylesheet" href="{{asset('ai/style/face-detection.css')}}">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="{{asset('ai/js/face-api.min.js')}}"></script>
    <script src="{{asset('ai/js/webcam-easy.min.js')}}"></script>
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
                    <!-- <div>   
                        <input type="hidden" name="getNowTimer" id="getNowTimer">
                        <p id="viTriNot" style="display: none;">Trạng thái vị trí: <strong class="text-danger">Đang không ở Công ty</strong></p>
                        <p id="viTriHas" style="display: none;">Trạng thái vị trí: <strong class="text-success">Đang ở Công ty</strong></p>
                        <input type="hidden" name="statusPos" id="statusPos">
                        <p class="text-center"><strong style="font-size:39pt;" id="showTimeNow"></strong></p>
                        <p class="text-center">
                            <p class="text-center" id="btnOpenCamera">
                                <button onclick="startCamera()" class="btn btn-info" type="button">Mở camera</button>
                            </p>           
                            <p class="text-center">
                            <video id="camera" autoplay playsinline style="width:250px;max-width:250px;"></video>
                            </p>          
                            <canvas id="canvas" width="400" height="300" style="display:none;"></canvas>
                            <input type="hidden" id="imageCaptured" name="imageCaptured">
                            <br/>
                            <p class="text-center">
                                <button id="sendChamCong" type="button" class="btn btn-primary">CHẤM CÔNG</button>
                            </p>
                            <h2 id="thongBao" class="text-center"></h2>
                            <p id="AiVoice" class="text-center" style="display: none;"><img width="150" src="{{asset('images/voiceai.gif')}}?v={{ time() }}" alt="voice ai"></p>
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
                    </div>     -->
                    <!-- Phiên bản mới -->
                    <div>
                        <div class="row">
                            <div class="col-12 col-md-4 col-xl-3 align-top">
                                <div class="row mb-3">
                                        <div class="col-md-10 col-6 form-control">
                                            <label class="form-switch">
                                            <input type="checkbox" id="webcam-switch">
                                            <i></i> Mở Camera </label>  
                                            <button id="cameraFlip" class="btn d-none"></button>     
                                        </div>                 
                                </div>
                            </div>
                            <div class="col-12 col-md-8 col-xl-9 align-top" id="webcam-container">
                                <div class="loading d-none">
                                    Tải mô hình
                                        <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                        </div>
                                </div>
                                
                                <div id="video-container">
                                        <video id="webcam" autoplay muted playsinline></video>
                                </div>  
                                <div id="errorMsg" class="col-12 alert-danger d-none">
                                Mở camera không thành công<br>
                                Vui lòng cho phép camera trên thiết bị. <br>
                                </div>
                            </div>
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
    const ALLSound = {
        s1: "{{asset('upload/datavoice/1.mp3')}}",   
        s2: "{{asset('upload/datavoice/2.mp3')}}", 
        s3: "{{asset('upload/datavoice/3.mp3')}}", 
        s4: "{{asset('upload/datavoice/4.mp3')}}", 
        s5: "{{asset('upload/datavoice/5.mp3')}}", 
        s6: "{{asset('upload/datavoice/6.mp3')}}", 
        s7: "{{asset('upload/datavoice/7.mp3')}}", 
        s8: "{{asset('upload/datavoice/8.mp3')}}", 
        s9: "{{asset('upload/datavoice/9.mp3')}}", 
        s10: "{{asset('upload/datavoice/10.mp3')}}", 
        s11: "{{asset('upload/datavoice/11.mp3')}}", 
        s12: "{{asset('upload/datavoice/12.mp3')}}", 
        s13: "{{asset('upload/datavoice/13.mp3')}}", 
        s14: "{{asset('upload/datavoice/14.mp3')}}", 
        s15: "{{asset('upload/datavoice/15.mp3')}}", 
        s16: "{{asset('upload/datavoice/16.mp3')}}", 
        s17: "{{asset('upload/datavoice/17.mp3')}}", 
        s18: "{{asset('upload/datavoice/18.mp3')}}", 
        s19: "{{asset('upload/datavoice/19.mp3')}}", 
        s20: "{{asset('upload/datavoice/20.mp3')}}", 
        s21: "{{asset('upload/datavoice/21.mp3')}}", 
        s22: "{{asset('upload/datavoice/22.mp3')}}", 
        s23: "{{asset('upload/datavoice/23.mp3')}}", 
        s24: "{{asset('upload/datavoice/24.mp3')}}", 
        s25: "{{asset('upload/datavoice/25.mp3')}}", 
        s26: "{{asset('upload/datavoice/26.mp3')}}", 
        s27: "{{asset('upload/datavoice/27.mp3')}}", 
        s28: "{{asset('upload/datavoice/28.mp3')}}", 
        s29: "{{asset('upload/datavoice/29.mp3')}}", 
        s30: "{{asset('upload/datavoice/30.mp3')}}", 
        s31: "{{asset('upload/datavoice/31.mp3')}}", 
        s32: "{{asset('upload/datavoice/32.mp3')}}", 
        s33: "{{asset('upload/datavoice/33.mp3')}}", 
        s34: "{{asset('upload/datavoice/34.mp3')}}", 
        s35: "{{asset('upload/datavoice/35.mp3')}}", 
        s36: "{{asset('upload/datavoice/36.mp3')}}", 
        s37: "{{asset('upload/datavoice/37.mp3')}}", 
        s38: "{{asset('upload/datavoice/38.mp3')}}", 
        s39: "{{asset('upload/datavoice/39.mp3')}}", 
    };   
    const sounds = {};

    function initSounds(){
        for (const [k,url] of Object.entries(ALLSound)){
            if (url){
                const a = new Audio(url);
                a.preload = "auto";
                sounds[k] = a;
            }
        }
    }

    initSounds();
    function playSound(name){
        const s = sounds[name];
        if (s){ 
            try{
                // Hiển thị biểu tượng AI khi phát
                $('#AiVoice').show();
                // reset và phát
                s.currentTime = 0;
                s.play();
                // Khi âm thanh kết thúc => ẩn biểu tượng AI
                s.onended = function(){
                    $('#AiVoice').hide();
                };
            }catch(e){}
        }
    }

    function playSoundWithRandom(listNames){
        if (!listNames || listNames.length === 0) return;
        let idx = Math.floor(Math.random() * listNames.length);
        let name = listNames[idx];
        const s = sounds[name];
        if (s){ 
            try{
                $('#AiVoice').show();
                s.currentTime = 0;
                s.play();
                s.onended = function(){
                    $('#AiVoice').hide();
                };
            }catch(e){}
        }
    }
    
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
            const videoEl = document.getElementById("camera");
            videoEl.srcObject = stream;
            // Hiển thị video theo hướng gương (người dùng mong muốn): lật ngang
            videoEl.style.transform = 'scaleX(-1)';
            if (stream) {
                playSound("s21");
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
            // cũng áp dụng lật ngang cho fallback
            document.getElementById("camera").style.transform = 'scaleX(-1)';
            }
            catch(e2) {
            alert("Điện thoại không cho phép mở camera trước: " + e2);
            }
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

        // Vì video đang được hiển thị đã lật ngang bằng CSS (scaleX(-1)),
        // khi vẽ lên canvas cần lật ngang để ảnh xuất ra khớp với hiển thị.
        ctx.save();
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        ctx.restore();

        let dataUrl = canvas.toDataURL("image/jpeg", 0.65); // Base64
        lastImage = dataUrl;
        return dataUrl;
    }

    // Other function
        const webcamElement = document.getElementById('webcam');
        const webcam = new Webcam(webcamElement, 'user');
        // const modelPath = './ai/models';
        const modelPath = "{{asset('ai/models')}}";
        let currentStream;
        let displaySize;
        let convas;
        let faceDetection;
        $("#webcam-switch").change(function () {
        if(this.checked){
            webcam.start()
                .then(result =>{
                    cameraStarted();
                    webcamElement.style.transform = "";
                    console.log("webcam started");
                })
                .catch(err => {
                    displayError();
                });
            setTimeout(() => {
                $(".loading").removeClass('d-none');
                Promise.all([
                faceapi.nets.tinyFaceDetector.load(modelPath),
                faceapi.nets.faceLandmark68TinyNet.load(modelPath),
                faceapi.nets.faceExpressionNet.load(modelPath),
                faceapi.nets.ageGenderNet.load(modelPath)
                ]).then(function(){
                createCanvas();
                startDetection();
                });
            }, 5000);
        }
        else {        
            cameraStopped();
            webcam.stop();
            console.log("webcam stopped");
            clearInterval(faceDetection);
            if(typeof canvas !== "undefined"){
                setTimeout(function() {
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
                }, 1000);
            }
        }        
        });

        $('#cameraFlip').click(function() {
            webcam.flip();
            webcam.start()
            .then(result =>{ 
            webcamElement.style.transform = "";
            });
        });

        $("#webcam").bind("loadedmetadata", function () {
        displaySize = { width:this.scrollWidth, height: this.scrollHeight }
        });

        function createCanvas(){
        if( document.getElementsByTagName("canvas").length == 0 )
        {
            canvas = faceapi.createCanvasFromMedia(webcamElement)
            document.getElementById('webcam-container').append(canvas)
            faceapi.matchDimensions(canvas, displaySize)
        }
        }

        function toggleContrl(id, show){
        if(show){
            $("#"+id).prop('disabled', false);
            $("#"+id).parent().removeClass('disabled');
        }else{
            $("#"+id).prop('checked', false).change();
            $("#"+id).prop('disabled', true);
            $("#"+id).parent().addClass('disabled');
        }
        }

        function startDetection(){
        faceDetection = setInterval(async () => {
            const detections = await faceapi.detectAllFaces(webcamElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks(true).withFaceExpressions().withAgeAndGender()
            const resizedDetections = faceapi.resizeResults(detections, displaySize)
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
            // Nhận diện đường biên
            faceapi.draw.drawDetections(canvas, resizedDetections)
            // nhận diện tuổi và giới tính
            resizedDetections.forEach(result => {
            const { age, gender, genderProbability } = result
            new faceapi.draw.DrawTextField(
                [
                `${faceapi.round(age, 0)} years`,
                `${gender} (${faceapi.round(genderProbability)})`
                ],
                result.detection.box.bottomRight
            ).draw(canvas)
            })

            if(!$(".loading").hasClass('d-none')){
            $(".loading").addClass('d-none')
            }
        }, 300)
        }

        function cameraStarted(){
        $("#errorMsg").addClass("d-none");
        if( webcam.webcamList.length > 1){
            $("#cameraFlip").removeClass('d-none');
        }
        }

        function cameraStopped(){
        $("#errorMsg").addClass("d-none");
        $("#cameraFlip").addClass('d-none');
        }

        function displayError(err = ''){
        if(err!=''){
            $("#errorMsg").html(err);
        }
        $("#errorMsg").removeClass("d-none");
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
            $("#sendChamCong").off('click').on('click', function(e){
                e.preventDefault();
                if (!stream) {
                    alert("Chưa bật camera!");
                    return;
                }
                var $btn = $(this);
                // nếu đang gửi thì thoát
                if ($btn.data('sending')) return;

                // khóa nút và đánh dấu đang gửi
                $btn.data('sending', true).prop('disabled', true).addClass('disabled');

                let capturedImage = captureImage();
                $("#imageCaptured").val(capturedImage);

                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/chamcong/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        if (response.code === 200) {
                            $("#camera").hide();
                            $("#btnOpenCamera").hide();
                            $("#thongBao").html("<span class='text-success'>"+response.message+"</span>");
                            $("#sendChamCong").hide();
                            // $("#AiVoice").show();
                            autoLoadHistory();
                            playSoundWithRandom(["s7","s5","s8","s10","s12","s13","s15","s16","s18","s19","s20","s23","s24","s25","s26","s27","s28","s29","s30","s31","s32","s33","s34","s35","s36","s37","s38","s39"]);
                        } else {
                            $("#camera").hide();
                            $("#btnOpenCamera").hide();
                            $("#thongBao").html("<span class='text-danger'>"+response.message+"</span>");
                            $("#sendChamCong").hide();
                            // $("#AiVoice").show();
                            if (response.key && response.key !== "random") {
                                playSound(response.key);
                            } 
                        }
                    },
                    error: function() {
                        $("#camera").hide();
                        Toast.fire({ icon: 'error', title: "Không thể chấm công!" });
                    },
                    complete: function() {
                        // mở lại nút sau khi request hoàn tất (nếu vẫn hiển thị)
                        $btn.data('sending', false).prop('disabled', false).removeClass('disabled');
                    }
                });
            });
        });
    </script>
@endsection
