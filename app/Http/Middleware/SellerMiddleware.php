<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'seller') {
            if (Auth::user()->status === 'active') {
                return $next($request);
            } else {
                return redirect()->route('seller.pending')
                    ->with('error', 'Your seller account is under review. Please wait for approval.');
            }
        }

        return redirect()->route('home')->with('error', 'Access denied. Seller account required.');
    }
}