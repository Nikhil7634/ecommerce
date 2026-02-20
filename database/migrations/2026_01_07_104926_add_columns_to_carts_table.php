<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Add variant columns
            $table->string('variant_color')->nullable()->after('product_id');
            $table->string('variant_size')->nullable()->after('variant_color');
            
            // Add price columns
            $table->decimal('price', 10, 2)->nullable()->after('variant_size');
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            
            // You might also want to add variant_id if you plan to use it later
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn([
                'variant_color',
                'variant_size',
                'price',
                'original_price',
                'variant_id'
            ]);
        });
    }
};