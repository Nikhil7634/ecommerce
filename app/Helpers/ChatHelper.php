<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ChatHelper
{
    public static function getUserName()
    {
        return Auth::check() ? (Auth::user()->name ?? 'User') : 'Guest';
    }
    
    public static function getUser()
    {
        return Auth::user();
    }
    
    public static function isLoggedIn()
    {
        return Auth::check();
    }
}