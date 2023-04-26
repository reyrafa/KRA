<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('position')->insert(
            array(
                ['positionDesc' => 'Bookeeper'],
                ['positionDesc' => 'Position2'],
                ['positionDesc' => 'Position3']
            )
        );
    }
}
