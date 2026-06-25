<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courier =
            [
                [
                    "courier_name"=> "steadfast",
                ],
                [
                    "courier_name"=> "pathao",
                ],
                [
                    "courier_name"=> "paperfly",
                ],
                [
                    "courier_name"=> "redx",
                ],
                [
                    "courier_name"=> "ecourier",
                ],
                [
                    "courier_name"=> "cityfast",
                ],

            ];
        DB::table('courier_apis')->insert($courier);
    }
}
