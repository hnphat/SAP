<?php

use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'guest',
                'email' => 'guest@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 0
            ],
            [
                'id' => 2,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin'),
                'active' => 1
            ],
            [
                'id' => 3,
                'name' => 'ceo',
                'email' => 'ceo@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 4,
                'name' => 'adminsale',
                'email' => 'adminsale@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 5,
                'name' => 'admindv',
                'email' => 'admindv@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 6,
                'name' => 'tpkd',
                'email' => 'tpkd@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 7,
                'name' => 'tpdv',
                'email' => 'tpdv@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 8,
                'name' => 'tvbh',
                'email' => 'tvbh@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 9,
                'name' => 'cvdv',
                'email' => 'cvdv@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ],
            [
                'id' => 10,
                'name' => 'other',
                'email' => 'other@gmail.com',
                'password' => bcrypt('123456'),
                'active' => 1
            ]
        ]);
    }
}
