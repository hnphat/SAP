<!DOCTYPE html>
<html>
<head>
    <title>PHIẾU CẤP XĂNG</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<div class="w3-row">
    <div class="w3-twothird">
        <img src="{{asset('images/logo/hyundaiag.png')}}" style="width: 200px; height: auto;" alt="Logo">
        <h3 style="text-align: center;">PHIẾU ĐỀ NGHỊ CẤP XĂNG XE</h3>
        <h5 style="text-align: right;">{{\HelpFunction::revertCreatedAt($car->created_at)}}</h5>
        <p>Họ và tên: {{$car->user->userDetail->surname}}</p>
        <p>Khách hàng:</p>
        <p>Số lít đề nghị: {{$car->fuel_num}} lít; Loại:
            @if($car->fuel_type == 'X')
                Xăng
            @else
                Dầu
            @endif
        </p>
        <p>Loại xe: {{$car->xeLaiThu->name}}; Biển số: {{$car->xeLaiThu->number_car}}</p>
        <p>Lý do cấp: {{$car->fuel_lyDo}}</p>
        <p>Số km hiện tại: {{$car->km_current}} km; Số xăng: {{$car->fuel_current}} km.</p>
        <div class="w3-row">
            <div class="w3-third">
                <p style="text-align: center;">NGƯỜI ĐỀ NGHỊ</p>
                <br><br><br>
                <p style="text-align: center;"> {{$car->user->userDetail->surname}} </p>
            </div>
            <div class="w3-third"><p style="text-align: center;">T. BỘ PHẬN</p></div>
            <div class="w3-third"><p style="text-align: center;">HCNS</p></div>
        </div>
    </div>
    <div class="w3-third">
        <h3 style="text-align: center;">QR CODE</h3>
        <h1 style="text-align: center;">
            {!! QrCode::size(200)->generate(url("/show/{$car->id}")); !!}
        </h1>
    </div>
</div>
</body>
</html>
