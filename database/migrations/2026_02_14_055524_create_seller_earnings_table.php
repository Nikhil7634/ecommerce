<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seller_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->string('type')->default('sale'); // sale, refund, adjustment
            $table->string('status')->default('pending'); // pending, available, withdrawn
            $table->text('description')->nullable();
            $table->timestamp('available_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
            
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('set null');
            
            $table->index(['seller_id', 'status']);
            $table->index(['seller_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_earnings');
    }
};