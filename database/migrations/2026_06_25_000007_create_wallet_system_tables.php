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
        // 1. Add wallet_balance to users table if it doesn't exist
        if (!Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('wallet_balance', 15, 2)->default(0.00)->after('email');
            });
        }

        // 2. Create wallet_transactions table
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('wallet'); // bkash, manual_deposit, points_conversion, admin_adjustment
            $table->string('type'); // credit, debit
            $table->string('status')->default('pending'); // pending, approved, failed
            $table->text('payment_details')->nullable(); // transaction IDs, deposit slips, notes
            $table->timestamps();
        });

        // 3. Register wallet_system in feature_activations if not already present
        $exists = DB::table('feature_activations')->where('name', 'wallet_system')->exists();
        if (!$exists) {
            DB::table('feature_activations')->insert([
                'name' => 'wallet_system',
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
        Schema::dropIfExists('wallet_transactions');

        if (Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('wallet_balance');
            });
        }

        DB::table('feature_activations')->where('name', 'wallet_system')->delete();
    }
};
