<!DOCTYPE html>
<html>
<head>
    <title>PHIẾU CẤP XĂNG</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="row">
    <div class="col-md-8">
        <img src="{{asset('images/logo/tc.png')}}" style="width: 200px; height: auto;" alt="Logo">
        <h3 style="text-align: center;"><strong>PHIẾU ĐỀ NGHỊ CẤP NHIÊN LIỆU</strong></h3>
        <h5 style="text-align: right;">{{\HelpFunction::revertCreatedAt($car->created_at)}}</h5>
        <p><strong>Họ và tên:</strong> {{$car->user->userDetail->surname}}</p>
        <p><strong>Khách hàng:</strong> {{$car->khachHang}}</p>
        <p><strong>Số lít đề nghị:</strong> {{$car->fuel_num}} lít; <strong>Loại:</strong>
            @if($car->fuel_type == 'X')
                Xăng
            @else
                Dầu
            @endif
        </p>
        <p><strong>Loại xe:</strong> {{$car->fuel_car}}; <strong>Biển số:</strong> {{$car->fuel_frame}}</p>
        <p><strong>Lý do cấp:</strong> {{$car->fuel_lyDo}}</p>
        <p><strong>Ghi chú:</strong> {{$car->ghiChu}}</p>
        <!-- <p><strong>Số km (dự kiến)):</strong> {{$car->fuel_km}} km <strong></strong> ({{$car->duongDi}}).</p> -->
        <div class="col">
                <strong>NGƯỜI ĐỀ NGHỊ</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;<strong><?php if ($car->lead_id != null) echo "T. BỘ PHẬN (Đã duyệt)";?></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;<strong>HCNS (Đã duyệt)</strong><br/><br/><br/>
                {{$car->user->userDetail->surname}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <?php if ($car->lead_id != null) echo $tbp;?>
        </div>
    </div>
    <div class="col-md-4">
{{--        <h3 style="text-align: center;">QR CODE</h3>--}}
{{--        <h1 style="text-align: center;">--}}
{{--            {!! QrCode::size(200)->generate(url("/show/{$car->id}")); !!}--}}
{{--        </h1>--}}
    </div>
</div>
</body>
</html>
