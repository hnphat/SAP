<?php

use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sale')->insert([
            [
                'id' => 1,
                'id_guest' => 1,
                'id_car_sale' => 1,
                'id_user_create' => 8,
                'date_sale' => '01/08/2021'
            ],
            [
                'id' => 2,
                'id_guest' => 2,
                'id_car_sale' => 2,
                'id_user_create' => 6,
                'date_sale' => '08/08/2021'
            ],
        ]);
    }
}
