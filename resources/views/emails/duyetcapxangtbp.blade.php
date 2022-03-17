@component('mail::message')
# THÔNG BÁO DUYỆT CẤP NHIÊN LIỆU

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<p>
    Bạn nhận được 01 yêu cầu cấp nhiên liệu từ
    <br/>Nhân viên: <strong>{{ $nguoiYeuCau }}</strong><br/>
    Mã code: <strong>HAGI-CX-0{{ $code }}</strong><br/>
    Ngày đăng ký: <strong>{{ $ngayDangKy }}</strong><br/>
    Xe đăng ký: <strong>{{ $xeDangKy }}</strong><br/>
    Lý do cấp: <strong>{{ $lyDo }}</strong><br/>
    Nhiên liệu: <strong>{{ $nhienLieu }}</strong><br/>
    Số lít: <strong>{{ $soLit }}</strong><br/>
    Khách hàng: <strong>{{ $khach }}</strong><br/>
    Ghi chú: <strong>{{ $ghiChu }}</strong><br/>
</p>

<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">
        ĐĂNG NHẬP VÀ XỬ LÝ
    </a>
</p>

Bỏ qua email này nếu bạn đã xem và xử lý phê duyệt.
Thanks,<br>
{{ config('app.name') }}
@endcomponent