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
        Schema::table('support_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('support_tickets', 'subject')) {
                $table->string('subject')->nullable()->after('ticket_id');
            }
            if (!Schema::hasColumn('support_tickets', 'status')) {
                $table->string('status')->default('open')->after('details');
            }
            if (!Schema::hasColumn('support_tickets', 'priority')) {
                $table->string('priority')->default('medium')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['subject', 'status', 'priority']);
        });
    }
};
