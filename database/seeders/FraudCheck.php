<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FraudCheck extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checkers =
            [
                [
                    "name"=> "bdcourier",
                    "api_key"=>'bdc_1g4lK6Uve1zbgmqpJbp7PsBe4ND4OfSMgIiDnsUvJNyNwOcpNHRf0lfDJ3zU',
                    "base_url"=>'https://bdcourier.com/api/courier-check',
                ],

            ];
        DB::table('fraud_checks')->insert($checkers);
    }
}

