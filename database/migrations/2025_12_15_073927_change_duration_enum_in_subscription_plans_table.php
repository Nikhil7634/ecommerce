<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->enum('duration', ['1_month','3_months','6_months','1_year','2_years','3_years','4_years'])
                ->default('1_month')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->enum('duration', ['monthly','yearly'])->default('monthly')->change();
        });
    }
};
