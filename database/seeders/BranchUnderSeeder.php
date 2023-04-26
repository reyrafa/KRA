<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchUnderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branchUnder')->insert(
            array(
                ['branchName'=> 'YACAPIN', 'branchID' =>1],
                ['branchName'=> 'CARMEN', 'branchID' => 1],
                ['branchName'=> 'AGORA', 'branchID' => 1],
                ['branchName'=> 'PUERTO', 'branchID' => 1],
                ['branchName'=> 'BULUA', 'branchID' => 1],
                ['branchName'=> 'COGON', 'branchID' => 1],
                ['branchName' => 'GINGOOG', 'branchID' => 2],
                ['branchName' => 'EL SALVADOR', 'branchID' => 2],
                ['branchName'=> 'TALAKAG', 'branchID' => 3],
                ['branchName'=> 'BAUNGON', 'branchID' => 3],
                ['branchName'=> 'MANOLO', 'branchID' => 3],
                ['branchName'=> 'AGLAYAN', 'branchID' => 3],
                ['branchName'=> 'VALENCIA', 'branchID' => 3],
                ['branchName'=> 'MARAMAG', 'branchID' => 3],
                ['branchName'=> 'DON CARLOS', 'branchID' => 3],
                ['branchName' => 'BUTUAN', 'branchID' => 4],
                ['branchName'=> 'UBAY', 'branchID' => 5],
                ['branchName'=> 'TAGBILIRAN', 'branchID' => 5],
                ['branchName'=> 'BALINGASAG', 'branchID' => 2],
                ['branchName'=> 'TUBIGON', 'branchID' =>5],
                ['branchName'=> 'TORIL', 'branchID' =>6],
                ['branchName'=> 'ILUSTRE', 'branchID' =>6],
                ['branchName'=> 'HEAD OFFICE', 'branchID' => 7]
            )
        );
    }
}
