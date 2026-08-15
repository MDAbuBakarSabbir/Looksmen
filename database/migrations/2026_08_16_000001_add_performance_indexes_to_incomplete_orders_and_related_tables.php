<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Incomplete Orders table performance indexes
        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table) {
                if (! $this->hasIndex('incomplete_orders', 'incomplete_orders_phone_index')) {
                    $table->index('phone');
                }
                if (! $this->hasIndex('incomplete_orders', 'incomplete_orders_status_index')) {
                    $table->index('status');
                }
            });
        }

        // 2. Orders table performance indexes
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! $this->hasIndex('orders', 'orders_phone_index')) {
                    $table->index('phone');
                }
                if (! $this->hasIndex('orders', 'orders_delivery_status_index')) {
                    $table->index('delivery_status');
                }
                if (! $this->hasIndex('orders', 'orders_payment_status_index')) {
                    $table->index('payment_status');
                }
            });
        }

        // 3. Coupons table performance indexes
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (! $this->hasIndex('coupons', 'coupons_status_index')) {
                    $table->index('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table) {
                if ($this->hasIndex('incomplete_orders', 'incomplete_orders_phone_index')) {
                    $table->dropIndex(['phone']);
                }
                if ($this->hasIndex('incomplete_orders', 'incomplete_orders_status_index')) {
                    $table->dropIndex(['status']);
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if ($this->hasIndex('orders', 'orders_phone_index')) {
                    $table->dropIndex(['phone']);
                }
                if ($this->hasIndex('orders', 'orders_delivery_status_index')) {
                    $table->dropIndex(['delivery_status']);
                }
                if ($this->hasIndex('orders', 'orders_payment_status_index')) {
                    $table->dropIndex(['payment_status']);
                }
            });
        }

        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if ($this->hasIndex('coupons', 'coupons_status_index')) {
                    $table->dropIndex(['status']);
                }
            });
        }
    }

    /**
     * Helper to safely check if index exists.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $doctrineSchemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineIndexes = $doctrineSchemaManager->listTableIndexes($table);

            return array_key_exists($indexName, $doctrineIndexes);
        } catch (Throwable $e) {
            // Fallback for newer Laravel versions or drivers without DBAL
            try {
                $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);

                return ! empty($indexes);
            } catch (Throwable $t) {
                return false;
            }
        }
    }
};
