<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function transactions()
    {
        $transactions = \App\Models\Transaction::latest()->paginate(20);
        return view('admin.payments.transactions', compact('transactions'));
    }

    public function withdrawals()
    {
        $withdrawals = Withdrawal::with('seller')->latest()->paginate(20);
        return view('admin.payments.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal(Withdrawal $withdrawal)
    {
        $withdrawal->update(['status' => 'paid']);
        return back()->with('success', 'Withdrawal approved and marked as paid.');
    }
}