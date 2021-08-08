<?php

use Illuminate\Database\Seeder;

class TypeCarDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('type_car_detail')->insert([
            [
                'id' => 1,
                'id_type_car' => 1,
                'name' => 'Grand i10'
            ],
            [
                'id' => 2,
                'id_type_car' => 1,
                'name' => 'Santafe'
            ],
            [
                'id' => 3,
                'id_type_car' => 1,
                'name' => 'Elantra'
            ],
            [
                'id' => 4,
                'id_type_car' => 1,
                'name' => 'Accent'
            ],
            [
                'id' => 5,
                'id_type_car' => 1,
                'name' => 'Tucson'
            ],
            [
                'id' => 6,
                'id_type_car' => 2,
                'name' => 'Vios'
            ],
            [
                'id' => 7,
                'id_type_car' => 3,
                'name' => 'Transit'
            ]
        ]);
    }
}
