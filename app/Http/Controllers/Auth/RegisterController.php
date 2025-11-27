<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SellerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function registerSeller(Request $request)
    {
        Log::info('=== SELLER REGISTRATION STARTED ===');

        // Validate the request
        $validatedData = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'business_name'  => 'required|string|max:255',
            'gst_no'         => 'required|string|min:15|max:15',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|unique:users,email',
            'state'          => 'required|string',
            'city'           => 'required|string|max:100',
            'zip'            => 'required|string|size:6',
            'address'        => 'required|string',
            'attachment'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'password'       => 'required|min:6|confirmed',
            'terms'          => 'required',
        ], [
            'gst_no.min' => 'GST number must be exactly 15 characters.',
            'gst_no.max' => 'GST number must be exactly 15 characters.',
            'zip.size' => 'ZIP code must be exactly 6 characters.',
            'attachment.required' => 'Please upload a document.',
            'terms.required' => 'You must agree to the terms and conditions.',
        ]);

        Log::info('Validation passed');

        try {
            DB::beginTransaction();
            Log::info('Database transaction started');

            // Create user - Use 'inactive' status for new sellers
            $userData = [
                'name'              => $request->first_name . ' ' . $request->last_name,
                'email'             => $request->email,
                'password'          => $request->password,
                'phone'             => $request->phone,
                'address'           => $request->address,
                'country'           => 'India',
                'state'             => $request->state,
                'city'              => $request->city,
                'zip'               => $request->zip,
                'business_name'     => $request->business_name,
                'gst_no'            => $request->gst_no,
                'role'              => 'seller',
                'status'            => 'inactive', // Use 'inactive' instead of 'pending'
                'email_verified_at' => now(),
            ];

            Log::info('Attempting to create user with data:', $userData);

            $user = User::create($userData);
            Log::info('User created successfully. User ID: ' . $user->id);

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('seller_documents', $filename, 'public');

                SellerDocument::create([
                    'user_id'       => $user->id,
                    'document_type' => 'gst_proof',
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);

                Log::info('Document uploaded successfully: ' . $path);
            }

            DB::commit();
            Log::info('Database transaction committed');

            // Login the user
            Auth::login($user);
            Log::info('User logged in successfully');

            // Redirect based on status
            if ($user->status === 'active') {
                Log::info('Redirecting to seller dashboard - Account is active');
                return redirect()->route('seller.dashboard')
                    ->with('success', 'Welcome ' . $user->business_name . '! Your seller account is active.');
            } else {
                Log::info('Redirecting to pending page - Account is inactive/under review');
                return redirect()->route('seller.pending')
                    ->with('success', 'Thank you for registering! Your seller account is under review and will be activated soon.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Seller Registration Error: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }
}