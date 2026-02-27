<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add order_id if not exists
            if (!Schema::hasColumn('reviews', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            }
            
            // Add seller_reply if not exists
            if (!Schema::hasColumn('reviews', 'seller_reply')) {
                $table->text('seller_reply')->nullable()->after('review');
            }
            
            // Add replied_at if not exists
            if (!Schema::hasColumn('reviews', 'replied_at')) {
                $table->timestamp('replied_at')->nullable()->after('seller_reply');
            }
            
            // Add images if not exists
            if (!Schema::hasColumn('reviews', 'images')) {
                $table->json('images')->nullable()->after('replied_at');
            }
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'seller_reply', 'replied_at', 'images']);
        });
    }
};