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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., site_name, admin_email
            $table->text('value')->nullable(); // stores the value (string, number, path)
            $table->timestamps();
        });

        // Optional: Seed default values
        $this->seedDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    private function seedDefaultSettings()
    {
        $defaults = [
            'site_name' => 'My Ecommerce',
            'admin_email' => 'admin@example.com',
            'commission_rate' => '10.00',
            'contact_number' => '+91 98765 43210',
            'copyright_text' => '© 2025 My Ecommerce. All rights reserved.',
            'social_facebook' => 'https://facebook.com/yourpage',
            'social_instagram' => 'https://instagram.com/yourpage',
            'social_twitter' => 'https://twitter.com/yourpage',
            'social_linkedin' => 'https://linkedin.com/company/yourcompany',
            'site_logo' => null, // will be filled when uploaded
            'favicon' => null,
        ];

        foreach ($defaults as $key => $value) {
            \DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};