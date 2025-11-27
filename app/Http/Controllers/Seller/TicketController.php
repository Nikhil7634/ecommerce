<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        $tickets = $seller->tickets()->latest()->get();
        return view('seller.tickets', compact('seller', 'tickets'));
    }

    public function create()
    {
        $seller = Auth::user();
        return view('seller.ticket-create', compact('seller'));
    }
}