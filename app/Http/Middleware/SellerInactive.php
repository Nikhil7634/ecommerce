<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerInactive
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Only apply to sellers
        if ($user->role === 'seller') {

            // If seller is ACTIVE and trying to access /pending → redirect to dashboard
            if ($user->status === 'active' && $request->routeIs('seller.pending')) {
                return redirect()->route('seller.dashboard');
            }

            // If seller is INACTIVE and trying to access anything except /pending → force to pending
            if ($user->status === 'inactive' && ! $request->routeIs('seller.pending')) {
                return redirect()->route('seller.pending');
            }
        }

        return $next($request);
    }
}