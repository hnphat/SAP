<?php

use Illuminate\Database\Seeder;

class TypeGuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('type_guest')->insert([
            [
                'id' => 1,
                'name' => 'Cá nhân'
            ],
            [
                'id' => 2,
                'name' => 'Doanh nghiệp'
            ]
        ]);
    }
}
