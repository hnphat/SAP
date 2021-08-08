<?php

use Illuminate\Database\Seeder;

class TypeCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('type_car')->insert([
            [
                'id' => 1,
                'name' => 'Hyundai'
            ],
            [
                'id' => 2,
                'name' => 'Toyota'
            ],
            [
                'id' => 3,
                'name' => 'Ford'
            ]
        ]);
    }
}
