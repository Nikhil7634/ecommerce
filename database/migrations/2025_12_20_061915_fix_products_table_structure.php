<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->unique()->after('stock');
            }

            if (!Schema::hasColumn('products', 'base_price')) {
                $table->decimal('base_price', 10, 2)->after('description');
            }

            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('base_price');
            }

            // Add other columns only if missing
            if (!Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('sku');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'base_price', 'sale_price', 'weight']);
        });
    }
};