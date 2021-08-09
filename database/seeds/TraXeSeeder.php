<?php

use Illuminate\Database\Seeder;

class TraXeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tra_xe')->insert([
            [
                'id' => 1,
                'id_user_pay' => 7,
                'id_xe_lai_thu' => 1,
                'km_current' => 16266,
                'fuel_current' => 12,
                'car_status' => 'tốt',
                'date_return' => '18h00 10/08/2021',
                'allow' => false
            ],
            [
                'id' => 2,
                'id_user_pay' => 8,
                'id_xe_lai_thu' => 2,
                'km_current' => 1266,
                'fuel_current' => 25,
                'car_status' => 'tốt',
                'date_return' => '18h00 10/08/2021',
                'allow' => false
            ],
            [
                'id' => 3,
                'id_user_pay' => 4,
                'id_xe_lai_thu' => 2,
                'km_current' => 1266,
                'fuel_current' => 25,
                'car_status' => 'tốt',
                'date_return' => '18h00 10/08/2021',
                'allow' => false
            ]
        ]);
    }
}
