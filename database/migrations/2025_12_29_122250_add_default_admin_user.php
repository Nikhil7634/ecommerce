<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // Check if we have any admin user
        $adminExists = User::where('role', 'admin')->exists();
        
        if (!$adminExists) {
            // Create default admin user
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'), // Change this password
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            
            // Alternatively, update the first user to admin
            // $firstUser = User::first();
            // if ($firstUser) {
            //     $firstUser->update(['role' => 'admin']);
            // }
        }
    }

    public function down(): void
    {
        // Optional: Remove the default admin if needed
        User::where('email', 'admin@example.com')->delete();
    }
};