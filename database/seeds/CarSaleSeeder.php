<?php

use Illuminate\Database\Seeder;

class CarSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('car_sale')->insert([
            [
                'id' => 1,
                'id_type_car_detail' => 1,
                'year' => '2000',
                'vin' => 'JOKS2009713121',
                'frame' => 'GHK2321K',
                'color' => 'Xanh',
                'gear' => 'AT',
                'machine' => '1.2',
                'seat' => '5',
                'fuel' => 'Xăng',
                'exist' => true,
                'order' => false,
                'cost' => 99000000,
                'id_user_create' => 1
            ],
            [
                'id' => 2,
                'id_type_car_detail' => 2,
                'year' => '2000',
                'vin' => 'JOKS2009713121',
                'frame' => 'GHK2321K',
                'color' => 'Đỏ',
                'gear' => 'MT',
                'machine' => '2.2',
                'seat' => '7',
                'fuel' => 'Dầu',
                'exist' => true,
                'order' => false,
                'cost' => 199000000,
                'id_user_create' => 1
            ]
        ]);
    }
}
