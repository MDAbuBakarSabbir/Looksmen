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
        if (!Schema::hasTable('instagram_messages')) {
            Schema::create('instagram_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('instagram_contact_id')->constrained('instagram_contacts')->onDelete('cascade');
                $table->string('message_id')->unique()->nullable();
                $table->text('body')->nullable();
                $table->string('type')->default('text'); // text, image, etc.
                $table->string('direction')->default('inbound'); // inbound, outbound
                $table->string('status')->default('received'); // received, sent, read
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_messages');
    }
};
