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
</body>
</html>
