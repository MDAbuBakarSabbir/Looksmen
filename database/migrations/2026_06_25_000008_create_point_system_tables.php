<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add points to users table if it doesn't exist
        if (!Schema::hasColumn('users', 'points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('points')->default(0)->after('wallet_balance');
            });
        }

        // 2. Add points to products table if it doesn't exist
        if (!Schema::hasColumn('products', 'points')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('points')->default(0);
            });
        }

        // 3. Create point_transactions table
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('points');
            $table->string('type'); // earn, convert, refund, admin_adjustment
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('details')->nullable();
            $table->timestamps();
        });

        // 4. Register point_system in feature_activations if not already present
        $exists = DB::table('feature_activations')->where('name', 'point_system')->exists();
        if (!$exists) {
            DB::table('feature_activations')->insert([
                'name' => 'point_system',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 5. Seed default points settings in general_web_settings
        $existsConv = DB::table('general_web_settings')->where('name', 'point_conversion_rate')->exists();
        if (!$existsConv) {
            DB::table('general_web_settings')->insert([
                'name' => 'point_conversion_rate',
                'value' => '100', // 100 points = 1 BDT
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $existsEarn = DB::table('general_web_settings')->where('name', 'points_per_taka')->exists();
        if (!$existsEarn) {
            DB::table('general_web_settings')->insert([
                'name' => 'points_per_taka',
                'value' => '0.1', // 1 BDT spent = 0.1 points earned
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');

        if (Schema::hasColumn('users', 'points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('points');
            });
        }

        if (Schema::hasColumn('products', 'points')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('points');
            });
        }

        DB::table('feature_activations')->where('name', 'point_system')->delete();
        DB::table('general_web_settings')->whereIn('name', ['point_conversion_rate', 'points_per_taka'])->delete();
    }
};
