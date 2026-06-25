<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentapi =
            [
                [
                    "paymentapi_name"=> "bkash",
                ],
                [
                    "paymentapi_name"=> "nagad",
                ],
                [
                    "paymentapi_name"=> "rocket",
                ],
                [
                    "paymentapi_name"=> "sslcommerz",
                ],

            ];
        DB::table('payment_apis')->insert($paymentapi);
    }
}
