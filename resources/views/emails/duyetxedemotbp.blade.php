@component('mail::message')
# THÔNG BÁO DUYỆT XE DEMO

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<p>
    Bạn nhận được 01 yêu cầu sử dụng xe demo từ
    <br/>Nhân viên: <strong>{{ $nguoiYeuCau }}</strong><br/>
    Ngày đăng ký: <strong>{{ $ngayDangKy }}</strong><br/>
    Xe đăng ký: <strong>{{ $xeDangKy }}</strong><br/>
    Lý do sử dụng: <strong>{{ $lyDo }}</strong><br/>
    Số km hiện tại: <strong>{{ $km }}</strong><br/>
    Số km xăng hiện tại: <strong>{{ $kmXang }}</strong><br/>
    Tình trạng xe: <strong>{{ $status }}</strong><br/>
    Thời gian bắt đầu sử dụng: <strong>{{ $batDau }}</strong><br/>
    Thời gian về dự kiến: <strong>{{ $ketThuc }}</strong><br/> 
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