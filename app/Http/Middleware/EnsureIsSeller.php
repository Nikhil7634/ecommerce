<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsSeller
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'seller') {
            // You can customize where to send non-sellers
            return redirect()->route('home')->with('error', 'Access denied. Sellers only.');
        }

        return $next($request);
    }
}