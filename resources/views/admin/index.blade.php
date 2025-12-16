<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title')
    </title>
    <base href="{{asset('')}}" />
    <link rel="shortcut icon" href="images/logo/hyundai-fav.png"/>
    <link >
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        .notification {
            color: white;
            text-decoration: none;
            padding: 15px 26px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

        .notification:hover {
            background: gray;
        }

        .notification .badge {
            position: absolute;
            top: 0;
            right: -5px;
            padding: 2px 5px;
            border-radius: 50%;
            background: red;
            color: white;
        }       
        /* Bông tuyết */
        .snowflake {
            color: #fff;
            font-size: 1em;
            font-family: Arial, sans-serif;
            text-shadow: 0 0 5px #000;
        }

        .snowflake,
        .snowflake .inner {
        animation-iteration-count: infinite;
        animation-play-state: running;
        }
        @keyframes snowflakes-fall {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(110vh);
        }
        }
        @keyframes snowflakes-shake {
        0%,
        100% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(80px);
        }
        }
        .snowflake {
        position: fixed;
        top: -10%;
        z-index: 9999;
        -webkit-user-select: none;
        user-select: none;
        cursor: default;
        animation-name: snowflakes-shake;
        animation-duration: 3s;
        animation-timing-function: ease-in-out;
        }
        .snowflake .inner {
        animation-duration: 10s;
        animation-name: snowflakes-fall;
        animation-timing-function: linear;
        }
        .snowflake:nth-of-type(0) {
        left: 1%;
        animation-delay: 0s;
        }
        .snowflake:nth-of-type(0) .inner {
        animation-delay: 0s;
        }
        .snowflake:first-of-type {
        left: 10%;
        animation-delay: 1s;
        }
        .snowflake:first-of-type .inner,
        .snowflake:nth-of-type(8) .inner {
        animation-delay: 1s;
        }
        .snowflake:nth-of-type(2) {
        left: 20%;
        animation-delay: 0.5s;
        }
        .snowflake:nth-of-type(2) .inner,
        .snowflake:nth-of-type(6) .inner {
        animation-delay: 6s;
        }
        .snowflake:nth-of-type(3) {
        left: 30%;
        animation-delay: 2s;
        }
        .snowflake:nth-of-type(11) .inner,
        .snowflake:nth-of-type(3) .inner {
        animation-delay: 4s;
        }
        .snowflake:nth-of-type(4) {
        left: 40%;
        animation-delay: 2s;
        }
        .snowflake:nth-of-type(10) .inner,
        .snowflake:nth-of-type(4) .inner {
        animation-delay: 2s;
        }
        .snowflake:nth-of-type(5) {
        left: 50%;
        animation-delay: 3s;
        }
        .snowflake:nth-of-type(5) .inner {
        animation-delay: 8s;
        }
        .snowflake:nth-of-type(6) {
        left: 60%;
        animation-delay: 2s;
        }
        .snowflake:nth-of-type(7) {
        left: 70%;
        animation-delay: 1s;
        }
        .snowflake:nth-of-type(7) .inner {
        animation-delay: 2.5s;
        }
        .snowflake:nth-of-type(8) {
        left: 80%;
        animation-delay: 0s;
        }
        .snowflake:nth-of-type(9) {
        left: 90%;
        animation-delay: 1.5s;
        }
        .snowflake:nth-of-type(9) .inner {
        animation-delay: 3s;
        }
        .snowflake:nth-of-type(10) {
        left: 25%;
        animation-delay: 0s;
        }
        .snowflake:nth-of-type(11) {
        left: 65%;
        animation-delay: 2.5s;
        }

        /* Noel chạy ngang */
        .santa {
            position: fixed;
            bottom: -50px;
            right: -500px;
        }
        .santa img {
            width: 500px;
            height: auto;
        }
    </style>
    @yield('script_head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    @include('admin.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.leftmenu')

    <!-- Content Wrapper. Contains page content -->
    @yield('content')
    <!-- End Content Wrapper -->

    <!-- Control Sidebar -->
    @include('admin.sidebar')
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include('admin.footer')
</div>
@yield('script')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
<!-- <script src="script/tet.js"></script> -->
<script type="text/javascript">
$(document).ready(function() {
        var windowWidth = $(document).width();
        var santa = $(".santa");
        santa_right_pos = windowWidth + santa.width();
        santa.right = santa_right_pos;
        function movesanta(){
            santa.animate({right : windowWidth +  santa.width()},12000, function(){
                santa.css("right","-500px");
                setTimeout(function(){
                    movesanta();
                },3000);
            });
        }
        // setTimeout(() => {      
        //     movesanta();
        // }, 30000);
        let isIndexPage = document.getElementById('isIndexPage');
       
        // Xử lý âm thanh
        const ALLSound = {
            jinglebell: "{{asset('upload/song/jinglebell.mp3')}}",               
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
                    s.currentTime = 0;
                    s.play();
                }catch(e){}
            }
        }

        if(isIndexPage){
            playSound('jinglebell');
            movesanta();
        }
});
</script>
</body>
</html>
