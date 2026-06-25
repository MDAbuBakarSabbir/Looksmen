<?php

namespace Database\Seeders;

use App\Models\Admins;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@user.com',
            'password' => Hash::make('12345678')
        ]);
        $this->call(AdminSeeder::class);
        $this->call(OrdersSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(ThanaSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(WebSettingsSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(CourierSeeder::class);
        $this->call(PaymentApiSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductimgSeeder::class);
        $this->call(ProductAttriSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(FraudCheck::class);

    }

}
