<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $district = [
            [
                "name"=>"Bagerhat",
                "delivery_charge"=>"130"
            ],
            [
                "name"=>"Bandarban",
                "delivery_charge"=>"130"
            ],
            [
                "name"=>"Barguna",
                "delivery_charge"=>"130"
            ],
            [
                "name"=>"Barishal",
                "delivery_charge"=>"130"
            ],
           [
               "name"=> "Bhola",
               "delivery_charge"=>"130"
           ],
            [
                "name"=>"Bogura",
                "delivery_charge"=>"130"
            ],
           [
               "name"=> "Brahmanbaria",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Chandpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Chapai Nawabganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Chattogram",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Chuadanga",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Cumilla",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Cox\"s Bazar",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Dhaka",
               "delivery_charge"=>"70"
           ],
           [
               "name"=> "Dhaka Sub",
               "delivery_charge"=>"100"
           ],
           [
               "name"=> "Dinajpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Faridpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Feni",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Gaibandha",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Gazipur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Gopalganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Habiganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Jashore",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Jhalokati",
               "delivery_charge"=>"130"
           ],
          [
              "name"=>  "Jhenaidah",
              "delivery_charge"=>"130"
          ],
            [
                "name"=>"Joypurhat",
                "delivery_charge"=>"130"
            ],
           [
               "name"=> "Jamalpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Khagrachhari",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Khulna",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Kishoreganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Kurigram",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Kushtia",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Lakshmipur",
               "delivery_charge"=>"130"
           ],
          [
              "name"=>  "Lalmonirhat",
              "delivery_charge"=>"130"
          ],
           [
               "name"=> "Madaripur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Magura",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Manikganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Meherpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Moulvibazar",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Munshiganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Mymensingh",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Naogaon",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Narail",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Narayanganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Narsingdi",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Natore",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Netrokona",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Nilphamari",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Noakhali",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Pabna",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Panchagarh",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Patuakhali",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Pirojpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Rajbari",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Rajshahi",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Rangamati",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Rangpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Satkhira",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Shariatpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Sherpur",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Sirajganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Sunamganj",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Sylhet",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Tangail",
               "delivery_charge"=>"130"
           ],
           [
               "name"=> "Thakurgaon",
               "delivery_charge"=>"130"
           ]
        ];
        DB::table('districts')->insert($district);
    }
}
