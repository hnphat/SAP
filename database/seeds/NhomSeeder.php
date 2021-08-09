<?php

use Illuminate\Database\Seeder;

class NhomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('nhom')->insert([
           [
               'id' => 1,
               'name' => 'TVBH'
           ],
            [
                'id' => 2,
                'name' => 'CVDV'
            ],
            [
                'id' => 3,
                'name' => 'CSKH'
            ],
            [
                'id' => 4,
                'name' => 'MKT'
            ]
        ]);
    }
}
