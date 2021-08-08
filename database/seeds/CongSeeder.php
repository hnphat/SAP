<?php

use Illuminate\Database\Seeder;

class CongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('cong')->insert([
           [
               'id' => 1,
               'id_dv' => 1,
               'name' => 'Phục hồi cảng trước',
               'cost' => 250000,
               'id_loai_cong' => 1
           ],
            [
                'id' => 2,
                'id_dv' => 1,
                'name' => 'Phục hồi cảng sau',
                'cost' => 200000,
                'id_loai_cong' => 1
            ],
            [
                'id' => 3,
                'id_dv' => 1,
                'name' => 'Khôi phục mâm dưới',
                'cost' => 150000,
                'id_loai_cong' => 1
            ],
            [
                'id' => 4,
                'id_dv' => 1,
                'name' => 'Sơn cửa',
                'cost' => 400000,
                'id_loai_cong' => 2
            ],
            [
                'id' => 5,
                'id_dv' => 1,
                'name' => 'Sơn cốp',
                'cost' => 300000,
                'id_loai_cong' => 2
            ],
            [
                'id' => 6,
                'id_dv' => 1,
                'name' => 'Thay bố thắng',
                'cost' => 100000,
                'id_loai_cong' => 3
            ],
            [
                'id' => 7,
                'id_dv' => 2,
                'name' => 'Móp mâm sau',
                'cost' => 300000,
                'id_loai_cong' => 1
            ],
            [
                'id' => 8,
                'id_dv' => 2,
                'name' => 'Sửa kính trước',
                'cost' => 150000,
                'id_loai_cong' => 1
            ],
            [
                'id' => 9,
                'id_dv' => 2,
                'name' => 'Sơn thân xe',
                'cost' => 1200000,
                'id_loai_cong' => 2
            ],
            [
                'id' => 10,
                'id_dv' => 2,
                'name' => 'Thay lông đền',
                'cost' => 600000,
                'id_loai_cong' => 3
            ]
        ]);
    }
}
