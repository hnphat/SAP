<?php

use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            [
                'name' => 'system',
                'description' => 'TK hệ thống, full quyền'
            ],
            [
                'name' => 'adminsale',
                'description' => 'Model xe, Kho Xe, Phê duyệt hđ, Duyệt đề nghị hđ'
            ],
            [
                'name' => 'tpkd',
                'description' => 'Phê duyệt hợp đồng, báo cáo ngày'
            ],
            [
                'name' => 'tpdv',
                'description' => 'Chức năng tpdv'
            ],
            [
                'name' => 'xuong',
                'description' => 'Chức năng xưởng'
            ],
            [
                'name' => 'sale',
                'description' => 'Hợp đồng, Đề nghị hủy hợp đồng'
            ],
            [
                'name' => 'boss',
                'description' => 'Xem tất cả báo cáo'
            ],
            [
                'name' => 'mkt',
                'description' => 'Duyệt đăng ký, trả xe'
            ],
            [
                'name' => 'ketoan',
                'description' => 'Chức năng kế toán'
            ],
            [
                'name' => 'cskh',
                'description' => 'Chức năng CSKH'
            ],
            [
                'name' => 'drp',
                'description' => 'Chức năng DRP'
            ],
            [
                'name' => 'hcns',
                'description' => 'Duyệt đề nghị cấp xăng'
            ],
            [
                'name' => 'it',
                'description' => 'Chức năng IT'
            ],
            [
                'name' => 'normal',
                'description' => 'Đề nghị sử dụng/trả xe'
            ],
            [
                'name' => 'lead',
                'description' => 'Phê duyệt đề nghị cấp xăng'
            ],
            [
                'name' => 'report',
                'description' => 'Được phép báo cáo ngày, xem báo cáo công việc'
            ],
            [
                'name' => 'watch',
                'description' => 'Được phép xem báo cáo ngày/tháng của các phòng ban'
            ],
            [
                'name' => 'work',
                'description' => 'Quản lý Công việc'
            ],
            [
                'name' => 'chamcong',
                'description' => 'Được chấm công'
            ],
            [
                'name' => 'lead_chamcong',
                'description' => 'Quản lý phép, duyệt phép,...'
            ],
            [
                'name' => 'nv_baohiem',
                'description' => 'Quản lý bảo hiểm'
            ],
            [
                'name' => 'nv_phukien',
                'description' => 'Quản lý phụ kiện'
            ],
            [
                'name' => 'to_phu_kien',
                'description' => 'Lắp đặt phụ kiện'
            ],
            [
                'name' => 'quanlyhop',
                'description' => 'Quản lý tất cả cuộc họp'
            ],
            [
                'name' => 'hop',
                'description' => 'Được tham gia họp và tạo cuộc họp'
            ],
            [
                'name' => 'covan',
                'description' => 'Quản lý đánh giá khách hàng'
            ],
            [
                'name' => 'qlcovan',
                'description' => 'Quản lý tất cả đánh giá'
            ],
            [
                'name' => 'baocaohopdong',
                'description' => 'Xem báo cáo hợp đồng'
            ],
            [
                'name' => 'baocaophukienbaohiem',
                'description' => 'Xem báo cáo phụ kiện, bảo hiểm'
            ],
            [
                'name' => 'truongnhomsale',
                'description' => 'Quyền trưởng nhóm kinh doanh'
            ],
            [
                'name' => 'quantri',
                'description' => 'Sử dụng module quản trị'
            ],
        ]);

        DB::table('role_user')->insert([
           'role_id' => 1,
           'user_id' => 1
        ]);
    }
}
