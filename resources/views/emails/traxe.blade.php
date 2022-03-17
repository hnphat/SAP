@component('mail::message')
# THÔNG BÁO DUYỆT TRẢ XE DEMO

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<p>
    Bạn nhận được 01 yêu cầu trả xe demo từ
    <br/>Nhân viên: <strong>{{ $nguoiYeuCau }}</strong><br/>
    Ngày đi: <strong>{{ $ngayDi }}</strong><br/>
    Ngày trả: <strong>{{ $ngayTra }}</strong><br/>
    Xe đăng ký: <strong>{{ $xeDangKy }}</strong><br/>
    Km trả: <strong>{{ $km }}</strong><br/>
    Km xăng trả: <strong>{{ $kmXang }}</strong><br/>
    Trạng thái xe: <strong>{{ $status }}</strong><br/>
    Hồ sơ trả: <strong>{{ $hoSo }}</strong><br/>
</p>

<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">
        ĐĂNG NHẬP VÀ XỬ LÝ
    </a>
</p>

Bỏ qua email này nếu bạn đã xem và xử lý trả.
Thanks,<br>
{{ config('app.name') }}
@endcomponent