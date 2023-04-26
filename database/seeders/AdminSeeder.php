<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'companyID' => '55555',
            'password' => Hash::make('oicict2020'),
            'scopeID' => '1',
            'statusID' => '1',
            'created_at' => now(),
            'updated_at' => now()

        ]);
    }
}
