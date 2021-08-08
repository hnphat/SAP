<?php

use Illuminate\Database\Seeder;

class PhuTungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('phu_tung')->insert([
           [
               'id' => 1,
               'id_dv' => 1,
               'name' => 'Dầu máy xe',
               'cost' => 100000,
               'id_loai_phu_tung' => 1
           ],
            [
                'id' => 2,
                'id_dv' => 2,
                'name' => 'Nhớt xe',
                'cost' => 200000,
                'id_loai_phu_tung' => 2
            ],
            [
                'id' => 3,
                'id_dv' => 1,
                'name' => 'Thắng xe Santafe',
                'cost' => 300000,
                'id_loai_phu_tung' => 3
            ],
            [
                'id' => 4,
                'id_dv' => 2,
                'name' => 'Nhớt santafe',
                'cost' => 450000,
                'id_loai_phu_tung' => 2
            ]
        ]);
    }
}
