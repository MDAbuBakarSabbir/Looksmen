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
        if (!Schema::hasTable('whatsapp_messages')) {
            Schema::create('whatsapp_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('whatsapp_contact_id')->constrained('whatsapp_contacts')->onDelete('cascade');
                $table->string('message_id')->unique()->nullable();
                $table->text('body')->nullable();
                $table->string('type')->default('text'); // text, image, document, etc.
                $table->string('direction')->default('inbound'); // inbound, outbound
                $table->string('status')->default('received'); // received, sent, delivered, read
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
