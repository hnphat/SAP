@component('mail::message')
# THÔNG BÁO GIAO VIỆC

<h1>Xin chào: {{ $nguoiNhan }}</h1>
<p>
    Bạn nhận được 01 công việc được giao từ <strong>{{ $nguoiGiao }}</strong><br/>
    <strong style="color: brown;">Deadline:</strong> Từ <strong>{{ $ngayBatDau }}</strong>
    đến <strong>{{ $ngayKetThuc }}</strong><br/>
    Yêu cầu công việc: <strong>{{ $yeuCau }}</strong><br/>    
</p>
<h5>
    HƯỚNG DẪN:<br/>
    <span style="color:brown;">
    1. ĐĂNG NHẬP <br/>
    2. VÀO MỤC CÔNG VIỆC <br/>
    3. VÀO MỤC NHẬN VIỆC<br/>
    4. XỬ LÝ VIỆC ĐƯỢC GIAO</span>
</h5>
<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">
        ĐĂNG NHẬP VÀ XỬ LÝ CÔNG VIỆC
    </a>
</p>

Bỏ qua email này nếu bạn đã xem và xử lý công việc được giao.
Thanks,<br>
{{ config('app.name') }}
@endcomponent