<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generalSettings =
        [
            [
                "name"=> "web_name",
                "value"=> "LOOKSMEN",
                "status"=> "1"
            ],
            [
                "name"=> "web_description",
                "value"=> "looksmen",
                "status"=> "1"
            ],
            [
                "name"=> "web_tags",
                "value"=> "looksmen",
                "status"=> "1"
            ],
            [
                "name"=> "web_favicon",
                "value"=> "looksmen.png",
                "status"=> "1"
            ],
            [
                "name"=> "web_logo",
                "value"=> "logo.png",
                "status"=> "1"
            ],
            [
                "name"=> "footer_logo",
                "value"=> "footer_logo.png",
                "status"=> "1"
            ],
            [
                "name"=> "contact_address",
                "value"=> "Dhaka,Bangladesh",
                "status"=> "1"
            ],
            [
                "name"=> "contact_phone",
                "value"=> "+8801568482005",
                "status"=> "1"
            ],
            [
                "name"=> "contact_email",
                "value"=> "infolooksmen.com",
                "status"=> "1"
            ],
            [
                "name"=> "meta_title",
                "value"=> "looksmen",
                "status"=> "1"
            ],
            [
                "name"=> "meta_description",
                "value"=> "looksmen",
                "status"=> "1"
            ],
            [
                "name"=> "meta_keyword",
                "value"=> "looksmen",
                "status"=> "1"
            ],
            [
                "name"=> "maintainance",
                "value"=> "Deactivated",
                "status"=> "0"
            ],
            [
                "name"=> "fraud_check_api_key",
                "value"=> "bdc_1g4lK6Uve1zbgmqpJbp7PsBe4ND4OfSMgIiDnsUvJNyNwOcpNHRf0lfDJ3zU",
                "status"=> "1"
            ],
            [
                "name"=> "fraud_check_api_url",
                "value"=> "https://api.bdcourier.com/courier-check",
                "status"=> "1"
            ],
        ];


        DB::table('general_web_settings')->insert($generalSettings);
    }
}
