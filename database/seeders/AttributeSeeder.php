<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Size',
                'status' => '1',
            ],
            [
                'name' => 'Storage',
                'status' => '1',
            ],
            [
                'name' => 'RAM',
                'status' => '1',
            ],
        ];
        DB::table('attributes')->insert($attributes);
    }
}
