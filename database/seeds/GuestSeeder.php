<?php

use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('guest')->insert([
           [
               'id' => 1,
               'id_type_guest' => 1,
               'name' => 'KH Nguyễn A',
               'phone' => '0000 111 222',
               'address' => 'Long Xuyên, An Giang',
               'id_user_create' => 1
           ],
            [
                'id' => 2,
                'id_type_guest' => 1,
                'name' => 'KH Nguyễn B',
                'phone' => '0000 111 222',
                'address' => 'Long Xuyên, An Giang',
                'id_user_create' => 2
            ],
            [
                'id' => 3,
                'id_type_guest' => 1,
                'name' => 'KH Nguyễn C',
                'phone' => '0000 111 222',
                'address' => 'Long Xuyên, An Giang',
                'id_user_create' => 3
            ],
            [
                'id' => 4,
                'id_type_guest' => 2,
                'name' => 'KH Nguyễn D',
                'phone' => '0000 111 222',
                'address' => 'Long Xuyên, An Giang',
                'id_user_create' => 4
            ],
            [
                'id' => 5,
                'id_type_guest' => 2,
                'name' => 'KH Nguyễn E',
                'phone' => '0000 111 222',
                'address' => 'Long Xuyên, An Giang',
                'id_user_create' => 5
            ],
            [
                'id' => 6,
                'id_type_guest' => 2,
                'name' => 'KH Nguyễn F',
                'phone' => '0000 111 222',
                'address' => 'Long Xuyên, An Giang',
                'id_user_create' => 6
            ]
        ]);
    }
}
