<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scope')->insert(
            array(
            ['scopeDesc' => 'admin'],
            ['scopeDesc' => 'branch'],
            ['scopeDesc' => 'head office']
        ));
    }
}
