<?php

use Illuminate\Database\Seeder;

class LoaiCongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('loai_cong')->insert([
           [
               'id' => 1,
               'name' => 'Công Đồng'
           ],
            [
                'id' => 2,
                'name' => 'Công Sơn'
            ],
            [
                'id' => 3,
                'name' => 'Công SCC'
            ]
        ]);
    }
}
