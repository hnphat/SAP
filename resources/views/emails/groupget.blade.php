@component('mail::message')
# THÔNG BÁO TIẾP NHẬN KHÁCH HÀNG MỚI

<h1>Xin chào: {{ $nhom }}</h1>
<h1>Trưởng nhóm: {{ $nguoiDuyet }}</h1>
<p>
    Nhóm của bạn nhận được khách hàng cần được chăm sóc từ Hệ thống
    <br/>Họ tên khách: <strong>{{ $hoTen }}</strong><br/>
    Số điện thoại: <strong>{{ substr($dienThoai,0,4) . "xxxxxxxx" }}</strong><br/>
    Yêu cầu từ khách hàng: <strong>{{ $yeuCau }}</strong><br/>
</p>
<i style="color: brown;">
<strong>Ghi chú: Vui lòng truy cập ứng dụng để gán khách này cho sale trong nhóm để tiến hành chăm sóc. Xin cảm ơn!</strong>
</i>

<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">ĐĂNG NHẬP NGAY</a>
</p>

Bỏ qua email này nếu bạn đã nhận được và xử lý xong.
Thanks,<br>
{{ config('app.name') }}
@endcomponent