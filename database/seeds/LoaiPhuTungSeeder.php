<?php

use Illuminate\Database\Seeder;

class LoaiPhuTungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('loai_phu_tung')->insert([
            [
                'id' => 1,
                'name' => 'Dầu'
            ],
            [
                'id' => 2,
                'name' => 'Nhớt'
            ],
            [
                'id' => 3,
                'name' => 'Bố thắng'
            ]
        ]);
    }
}
