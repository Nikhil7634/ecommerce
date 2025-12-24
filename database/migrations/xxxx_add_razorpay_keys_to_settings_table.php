<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add Razorpay keys (test keys by default)
        DB::table('settings')->updateOrInsert(
            ['key' => 'razorpay_key'],
            ['value' => 'rzp_test_YourKeyHere', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'razorpay_secret'],
            ['value' => 'YourSecretHere', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['razorpay_key', 'razorpay_secret'])->delete();
    }
};