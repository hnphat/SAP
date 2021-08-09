<?php

use Illuminate\Database\Seeder;

class QuyenXemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('quyen_xem')->insert([
           [
               'id' => 1,
               'id_tai_lieu' => 1,
               'id_nhom' => 1
           ],
            [
                'id' => 2,
                'id_tai_lieu' => 2,
                'id_nhom' => 2
            ]
        ]);
    }
}
