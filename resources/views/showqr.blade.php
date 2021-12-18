<!DOCTYPE html>
<html>
<head>
    <title>Thông tin mượn xe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <h2>THÔNG TIN SỬ DỤNG XE</h2>
    <div style="font-size: 16pt;">
        <p><strong>Họ và tên: </strong> {{$car->user->userDetail->surname}}</p>
        <p>    <strong>Xe sử dụng: </strong> {{$car->xeLaiThu->name}}; {{$car->xeLaiThu->number_car}}; {{$car->xeLaiThu->mau}};  </p>
        <p> <strong>Lý do sử dụng: </strong> {{$car->lyDo}}</p>
        <p> <strong>Thời gian sử dụng: </strong> <strong><span style="color: #7d1038; font-size: 150%;">{{$car->time_go}}</span></strong> <strong><span style="color: #7d1038; font-size: 150%;">{{\HelpFunction::revertDate($car->date_go)}}</span> </strong></p>
        <p> <strong>Số km hiện tại: </strong> {{$car->km_current}} </p>
        <p> <strong>Số km xăng hiện tại: </strong> {{$car->fuel_current}} </p>
        <p> <strong>Tình trạng xe: </strong> {{$car->car_status}} </p>
        <p> <strong>Phê duyệt: </strong> <span style="color: green;"><strong>ĐÃ DUYỆT</strong></span></p>
    </div>
</body>
</html>
