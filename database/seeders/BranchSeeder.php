<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branch')->insert(
            array(
                
                ['branchName'=> 'CDO'],
                ['branchName' => 'MISAMIS ORIENTAL'],
                ['branchName'=> 'BUKIDNON'],
                ['branchName' => 'CARAGA'],
                ['branchName'=> 'BOHOL'],
                ['branchName'=> 'DAVAO'],
                ['branchName' => 'HEAD OFFICE'],
                
            )
        );
    }
}
