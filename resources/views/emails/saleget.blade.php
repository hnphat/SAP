@component('mail::message')
# THÔNG BÁO TIẾP NHẬN KHÁCH HÀNG MỚI

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<h1>Nhóm kinh doanh: {{ $nhom }}</h1>
<p>
    Bạn nhận được khách hàng cần được chăm sóc từ trưởng nhóm
    <br/>Họ tên khách: <strong>{{ $hoTen }}</strong><br/>
    Số điện thoại: <strong>{{ $dienThoai }}</strong><br/>
    Yêu cầu từ khách hàng: <strong>{{ $yeuCau }}</strong><br/>
</p>
<i style="color: brown;">
<strong>Ghi chú: Vui lòng truy cập ứng dụng để tiến hành chăm sóc khách hàng và cập nhật các quá trình đúng quy định. Xin cảm ơn!</strong>
</i>

<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">ĐĂNG NHẬP NGAY</a>
</p>

Bỏ qua email này nếu bạn đã nhận được và xử lý xong.
Thanks,<br>
{{ config('app.name') }}
@endcomponent