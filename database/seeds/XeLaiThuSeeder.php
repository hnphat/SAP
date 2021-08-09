<?php

use Illuminate\Database\Seeder;

class XeLaiThuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('xe_lai_thu')->insert([
           [
               'id' => 1,
               'name' => 'Accent',
               'number_car' => '67A-11222',
               'mau' => 'Trắng',
               'id_user_use' => 1
           ],
            [
                'id' => 2,
                'name' => 'Santafe',
                'number_car' => '67A-11333',
                'mau' => 'Trắng',
                'id_user_use' => 1
            ],
            [
                'id' => 3,
                'name' => 'Kona',
                'number_car' => '67A-11555',
                'mau' => 'Trắng',
                'id_user_use' => 1
            ]
        ]);
    }
}
