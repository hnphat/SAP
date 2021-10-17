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
                'name' => 'xuong'
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
                'name' => 'it'
            ],
            [
                'name' => 'normal'
            ],
            [
                'name' => 'sub1'
            ],
            [
                'name' => 'sub2'
            ],
            [
                'name' => 'lead_sub1'
            ],
            [
                'name' => 'lead_sub2'
            ],
            [
                'name' => 'lead'
            ],
            [
                'name' => 'report'
            ],
            [
                'name' => 'watch'
            ]
        ]);

        DB::table('role_user')->insert([
           'role_id' => 1,
           'user_id' => 1
        ]);
    }
}
