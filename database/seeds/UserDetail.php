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
                'surname' => 'Nguyễn Văn A',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 2,
                'surname' => 'Nguyễn Văn Admin',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 3,
                'surname' => 'Nguyễn Văn AD Sale',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 4,
                'surname' => 'Nguyễn Văn AD Dv',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 5,
                'surname' => 'Nguyễn Văn TPDV',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 6,
                'surname' => 'Nguyễn Văn TPKD',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 7,
                'surname' => 'Nguyễn Văn TVBH',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 8,
                'surname' => 'Nguyễn Văn CVDV',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ],
            [
                'id_user' => 9,
                'surname' => 'Nguyễn Văn Other',
                'birthday' => '01/01/2000',
                'address' => 'Long Xuyên, An Giang',
                'phone' => '0000 111 222'
            ]

        ]);
    }
}
