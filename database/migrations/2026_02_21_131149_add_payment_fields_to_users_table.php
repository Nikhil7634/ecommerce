<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Bank details
            $table->string('account_holder_name')->nullable()->after('gst_no');
            $table->string('account_number')->nullable()->after('account_holder_name');
            $table->string('bank_name')->nullable()->after('account_number');
            $table->string('ifsc_code')->nullable()->after('bank_name');
            $table->string('account_type')->default('savings')->after('ifsc_code');
            
            // UPI details
            $table->string('upi_id')->nullable()->after('account_type');
            $table->string('upi_qr')->nullable()->after('upi_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_holder_name',
                'account_number',
                'bank_name',
                'ifsc_code',
                'account_type',
                'upi_id',
                'upi_qr',
            ]);
        });
    }
};