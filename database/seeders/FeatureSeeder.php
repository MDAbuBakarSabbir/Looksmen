<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feature =
            [
                [
                    "name"=> "affiliate",
                    "status"=> "1"
                ],
                [
                    "name"=> "courier_api",
                    "status"=> "1"
                ],
                [
                    "name"=> "payment_api",
                    "status"=> "1"
                ],
                [
                    "name"=> "coupon",
                    "status"=> "1"
                ],
                [
                    "name"=> "email_verification",
                    "status"=> "1"
                ],
                [
                    "name"=> "sms_verification",
                    "status"=> "1"
                ],
                [
                    "name"=> "social_login_api",
                    "status"=> "1"
                ],
                [
                    "name"=> "facebook_api",
                    "status"=> "1"
                ],
                [
                    "name"=> "fraud_check_api",
                    "status"=> "1"
                ],

            ];
        DB::table('feature_activations')->insert($feature);
    }
}
