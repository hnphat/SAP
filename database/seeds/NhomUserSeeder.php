<?php

use Illuminate\Database\Seeder;

class NhomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('nhom_user')->insert([
           [
               'id' => 1,
               'id_nhom' => 1,
               'id_user' => 7
           ],
            [
                'id' => 2,
                'id_nhom' => 2,
                'id_user' => 8
            ]
        ]);
    }
}
