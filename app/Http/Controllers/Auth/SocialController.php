<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    /**
     * Redirect to provider (Google/Facebook)
     */
    public function redirectToProvider($provider)
    {
        $providers = ['google', 'facebook'];
        if (!in_array($provider, $providers)) {
            abort(404, 'Invalid provider');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback from provider
     */
    public function handleProviderCallback($provider)
    {
        $providers = ['google', 'facebook'];
        if (!in_array($provider, $providers)) {
            abort(404, 'Invalid provider');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('register')->with('error', 'Authentication failed. Please try again.');
        }

        // Find or create user
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Existing user: Log in
            Auth::login($user);
            return redirect()->route('buyer.dashboard')->with('status', 'Welcome back!');
        } else {
            // New user: Create with social data
            $user = User::create([
                'name'        => $socialUser->getName(),
                'email'       => $socialUser->getEmail(),
                'password'    => Hash::make(Str::random(16)), // Random for social users
                'phone'       => null, // Optional: Ask in profile completion
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar'      => $socialUser->getAvatar(), // Facebook/Google provide this
                'role'        => 'buyer',
                'status'      => 'active',
            ]);

            Auth::login($user);
            return redirect()->route('buyer.dashboard')->with('status', 'Account created! Complete your profile.');
        }
    }
}