<?php

use Illuminate\Database\Seeder;

class QuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('quyen')->insert([
           [
               'id' => 1,
               'name' => "Guest"
           ],
            [
                'id' => 2,
                'name' => "System"
            ],
            [
                'id' => 3,
                'name' => "CEO"
            ],
            [
                'id' => 4,
                'name' => "AdminSale"
            ],
            [
                'id' => 5,
                'name' => "AdminService"
            ],
            [
                'id' => 6,
                'name' => "TPKD"
            ],
            [
                'id' => 7,
                'name' => "TPDV"
            ],
            [
                'id' => 8,
                'name' => "TVBH"
            ],
            [
                'id' => 9,
                'name' => "CVDV"
            ],
            [
                'id' => 10,
                'name' => "OTHER"
            ]
        ]);
    }
}
