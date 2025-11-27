<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === 1. USERS TABLE ===
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Social Auth
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable()->unique();

            // Role & Status
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');

            // Basic Info
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('avatar', 255)->nullable();

            // === LOCATION FIELDS (for sellers & buyers) ===
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip', 20)->nullable();

            // === SELLER-SPECIFIC ===
            $table->string('business_name')->nullable(); // e.g., "John's Electronics"
            $table->string('gst_no')->nullable();
            $table->timestamp('seller_verified_at')->nullable(); // approval timestamp

            $table->timestamps();
        });

        // === 2. SELLER DOCUMENTS (separate table - scalable) ===
        Schema::create('seller_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // e.g., 'business_license', 'id_card'
            $table->string('file_path');     // storage path: seller_documents/abc123.pdf
            $table->string('original_name')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });

        // === 3. PASSWORD RESET TOKENS ===
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // === 4. SESSIONS ===
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_documents');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};