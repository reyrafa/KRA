<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = array("AGAINST CLOTHING ALLOWANCE", "AGAINST CONSOLIDATED BONUS", "AGAINST PBB", "AGRICULTURAL LOAN", "BRAND NEW CAR", "CHECK REDISCOUNTING LOAN", "INSTANT LOAN", 
        "LOAN AGAINST TIME DEPOSIT", "MICRO, SMALL, MEDIUM ENTERPRISE LOAN", "MOTORCYCLE", "PEI", "PENSION LOAN", "PETTY CASH LOAN", "PROVIDENTIAL LOAN", "SALARY LOAN", "SALARY LOAN PLUS", "SURPLUS CAR");

        for($i = 0; $i<17; $i++){
            $randomString = bin2hex(random_bytes(5));

            while(DB::table('products')->where('productCode', $randomString)->exists()){
                $randomString = bin2hex(random_bytes(5));
            };

            $data =['productCode' => $randomString, 'productDescription' => $product[$i], 'createdBy' => '55555', 'created_at' => now()];
            DB::table('products')->insert($data);
        }
       
    }
}
