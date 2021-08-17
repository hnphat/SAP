<?php

use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            [
                'name' => 'system'
            ],
            [
                'name' => 'adminsale'
            ],
            [
                'name' => 'admindv'
            ],
            [
                'name' => 'tpkd'
            ],
            [
                'name' => 'tpdv'
            ],
            [
                'name' => 'sale'
            ],
            [
                'name' => 'boss'
            ],
            [
                'name' => 'mkt'
            ],
            [
                'name' => 'ketoan'
            ],
            [
                'name' => 'cskh'
            ],
            [
                'name' => 'drp'
            ],
            [
                'name' => 'hcns'
            ],
            [
                'name' => 'normal'
            ]
        ]);

        DB::table('role_user')->insert([
           'role_id' => 1,
           'user_id' => 2
        ]);
    }
}
