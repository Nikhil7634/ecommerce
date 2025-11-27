<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlashDealController extends Controller
{
    public function index()
    {
        return view('flash-deals');
    }
}
