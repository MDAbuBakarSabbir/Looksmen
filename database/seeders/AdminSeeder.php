<?php

namespace Database\Seeders;

use App\Models\Admins;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin =
            [
                [
                    "name" => "Master Admin",
                    'email' => 'admin@admin.com',
                    'number' => '01614694415',
                    'password' => Hash::make('12345678'),
                ],
            ];
        DB::table('admins')->insert($admin);
    }
}
