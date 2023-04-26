<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('filestatus')->insert(
            array(
                ['fileStatusDesc' => 'created'],
                ['fileStatusDesc' => 'updated'],
            )
        );
    }
}
