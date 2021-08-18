<?php

use Illuminate\Database\Seeder;

class UserDetail extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users_detail')->insert([
            [
                'id_user' => 1,
                'surname' => 'Nguyễn Văn Admin',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ]
        ]);
    }
}
