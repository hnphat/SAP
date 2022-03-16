@component('mail::message')
# THÔNG BÁO XIN PHÉP

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<p>
    Bạn nhận được 01 yêu cầu xin phép từ
    <br/>Nhân viên: <strong>{{ $nhanVien }}</strong><br/>
    Ngày xin: <strong>{{ $ngayXin }}</strong><br/>
    Buổi: <strong>{{ $buoi }}</strong><br/>
    Loại phép: <strong>{{ $loaiPhep }}</strong><br/>
    Lý do: <strong>{{ $lyDo }}</strong>
</p>
<i style="color: red;">
Lưu ý: Phép chỉ có thể duyệt sau ngày xin tối thiểu 02 ngày. Sau ngày đó sẽ không thể phê duyệt phép.
</i>

<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">ĐĂNG NHẬP VÀ PHÊ DUYỆT</a>
</p>

<!-- @component('mail::table')
| Laravel       | Table         | Example  |
| ------------- |:-------------:| --------:|
| Col 2 is      | Centered      | $10      |
| Col 3 is      | Right-Aligned | $20      |
@endcomponent -->

Bỏ qua email này nếu bạn đã xem và xử lý phép.
Thanks,<br>
{{ config('app.name') }}
@endcomponent