<?php

use Illuminate\Database\Seeder;

class TaiLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tai_lieu')->insert([
           [
               'id' => 1,
               'id_user' => 1,
               'ngayTao' => '01/08/2021',
               'tieuDe' => 'Quy trình kinh doanh',
               'moTa' => 'Quy trình kinh doanh',
               'noiDung' => 'Nội dung quy trình kinh doanh',
               'duongDan' => '#'
           ],
            [
                'id' => 2,
                'id_user' => 1,
                'ngayTao' => '02/08/2021',
                'tieuDe' => 'Quy trình dịch vụ',
                'moTa' => 'Quy trình dịch vụ',
                'noiDung' => 'Nội dung quy trình dịch vụ',
                'duongDan' => '#'
            ],
            [
                'id' => 3,
                'id_user' => 2,
                'ngayTao' => '03/08/2021',
                'tieuDe' => 'Quy trình kế toán',
                'moTa' => 'Quy trình kế toán',
                'noiDung' => 'Nội dung quy trình kế toán',
                'duongDan' => '#'
            ]
        ]);
    }
}
