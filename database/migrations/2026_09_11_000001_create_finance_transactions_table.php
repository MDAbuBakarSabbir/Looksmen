<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('entry_type', ['INCOME', 'EXPENSE'])->default('EXPENSE')->index();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('category', 100)->index();
            $table->string('payment_method', 100)->default('Cash');
            $table->dateTime('transaction_date')->index();
            $table->string('staff_name', 100)->index();
            $table->text('notes')->nullable();
            $table->string('receipt_image', 255)->nullable();
            $table->unsignedBigInteger('admin_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
