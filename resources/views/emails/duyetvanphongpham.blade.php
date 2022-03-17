@component('mail::message')
# THÔNG BÁO DUYỆT CẤP CÔNG CỤ DỤNG CỤ

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<p>
    Bạn nhận được 01 yêu cầu cấp công cụ dụng cụ từ
    <br/>Nhân viên: <strong>{{ $nguoiYeuCau }}</strong><br/>
    Mã phiếu: <strong>PXK-0{{ $maPhieu }}</strong><br/>
    Nội dung yêu cầu: <strong>{{ $noiDung }}</strong><br/>
</p>
<h5>
    HƯỚNG DẪN:<br/>
    <span style="color:brown;">
    1. ĐĂNG NHẬP <br/>
    2. VÀO MỤC HÀNH CHÍNH <br/>
    3. VÀO MỤC QUẢN LÝ XUẤT KHO<br/>
    4. CHỌN MÃ PHIẾU: PXK-0{{ $maPhieu }}<br/>
    5. BẤM TẢI<br/>
    6. XỬ LÝ YÊU CẦU<br/>
    </span>
</h5>
<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">
        ĐĂNG NHẬP VÀ XỬ LÝ
    </a>
</p>

Bỏ qua email này nếu bạn đã xem và xử lý phê duyệt.
Thanks,<br>
{{ config('app.name') }}
@endcomponent