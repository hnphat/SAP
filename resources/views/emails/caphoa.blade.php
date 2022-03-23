@component('mail::message')
# THÔNG BÁO DUYỆT YÊU CẦU CẤP HOA

<h1>Xin chào: {{ $nguoiDuyet }}</h1>
<p>
    Bạn nhận được 01 yêu cầu cấp hoa từ
    <br/>Nhân viên: <strong>{{ $nguoiYeuCau }}</strong><br/>
    Khách hàng: <strong>{{ $khachHang }}</strong><br/>
    Dòng xe: <strong>{{ $dongXe }}</strong><br/>
    Biển số/số khung: <strong>{{ $num }}</strong><br/>
    Giờ giao xe: <strong>{{ $gioGiaoXe }}</strong><br/>
    Ngày giao xe: <strong>{{ $ngayGiaoXe }}</strong><br/>
    Ghi chú: <strong>{{ $ghiChu }}</strong><br/>
</p>

<p style="text-align: center;">
    <a style="background-color: #4CAF50;border: none;color: white;padding: 5px 10px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="https://otophucanh.com" target="_blank">
        ĐĂNG NHẬP VÀ XỬ LÝ
    </a>
</p>

Bỏ qua email này nếu bạn đã xem và xử lý.
Thanks,<br>
{{ config('app.name') }}
@endcomponent