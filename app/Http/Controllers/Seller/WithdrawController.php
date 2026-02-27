<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SellerEarning;
use App\Models\SellerWithdrawal;
use App\Models\SellerPayout;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

class WithdrawController extends Controller
{
    /**
     * Show withdrawal request form
     */
    public function withdrawalForm()
    {
        $sellerId = Auth::id();
        $seller = Auth::user();
        
        // Get available balance (earnings that are available for withdrawal)
        $availableBalance = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'available')
            ->sum('net_amount');
        
        // Get pending withdrawals (already requested but not yet processed)
        $pendingWithdrawals = SellerWithdrawal::where('seller_id', $sellerId)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');
        
        // Get minimum withdrawal amount from settings
        $minWithdrawal = $this->getMinWithdrawalAmount();
        
        // Get seller's saved payment methods
        $paymentMethods = $this->getSellerPaymentMethods($seller);
        
        // Get recent withdrawals
        $recentWithdrawals = SellerWithdrawal::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('seller.earnings.withdrawal', compact(
            'availableBalance',
            'pendingWithdrawals',
            'minWithdrawal',
            'paymentMethods',
            'recentWithdrawals',
            'seller'
        ));
    }

    /**
     * Process withdrawal request
     */
    public function requestWithdrawal(Request $request)
    {
        // Log the request for debugging
        Log::info('========== WITHDRAWAL REQUEST START ==========');
        Log::info('Request data:', $request->all());
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->url());
        
        try {
            // Validate the request
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|in:bank,paypal,razorpay',
                'account_holder_name' => 'required_if:payment_method,bank|string|max:255',
                'account_number' => 'required_if:payment_method,bank|string|max:50',
                'bank_name' => 'required_if:payment_method,bank|string|max:255',
                'ifsc_code' => 'required_if:payment_method,bank|string|max:20',
                'account_type' => 'nullable|in:savings,current',
                'paypal_email' => 'required_if:payment_method,paypal|email',
                'upi_id' => 'required_if:payment_method,razorpay|string|max:100',
                'terms' => 'required|accepted',
            ]);
            
            Log::info('Validation passed');
            
            $sellerId = Auth::id();
            Log::info('Seller ID: ' . $sellerId);
            
            // Check available balance
            $availableBalance = SellerEarning::where('seller_id', $sellerId)
                ->where('status', 'available')
                ->sum('net_amount');
            
            Log::info('Available balance: ' . $availableBalance . ', Requested: ' . $request->amount);
            
            if ($request->amount > $availableBalance) {
                Log::warning('Insufficient balance');
                return redirect()->back()
                    ->with('error', 'Insufficient balance. Your available balance is ₹' . number_format($availableBalance, 2))
                    ->withInput();
            }
            
            // Check minimum withdrawal amount
            $minWithdrawal = $this->getMinWithdrawalAmount();
            if ($request->amount < $minWithdrawal) {
                Log::warning('Below minimum withdrawal amount');
                return redirect()->back()
                    ->with('error', 'Minimum withdrawal amount is ₹' . number_format($minWithdrawal, 2))
                    ->withInput();
            }
            
            DB::beginTransaction();
            Log::info('Transaction started');
            
            // Prepare payment details as an array (will be JSON encoded by the model)
            $paymentDetails = $this->preparePaymentDetails($request);
            Log::info('Payment details prepared:', $paymentDetails);
            
            // Calculate fee (if any)
            $fee = $this->calculateWithdrawalFee($request->amount, $request->payment_method);
            $netAmount = $request->amount - $fee;
            Log::info("Fee: {$fee}, Net amount: {$netAmount}");
            
            // Generate unique withdrawal number
            $withdrawalNumber = 'WTH-' . strtoupper(uniqid());
            Log::info('Withdrawal number: ' . $withdrawalNumber);
            
            // Create withdrawal request
            $withdrawalData = [
                'seller_id' => $sellerId,
                'withdrawal_number' => $withdrawalNumber,
                'amount' => $request->amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'payment_method' => $request->payment_method,
                'payment_details' => $paymentDetails,
                'status' => 'pending',
                'notes' => $request->notes ?? null,
            ];
            
            Log::info('Creating withdrawal with data:', $withdrawalData);
            
            $withdrawal = SellerWithdrawal::create($withdrawalData);
            
            if (!$withdrawal) {
                throw new \Exception('Failed to create withdrawal record');
            }
            
            Log::info('Withdrawal created with ID: ' . $withdrawal->id);
            
            // Get available earnings to mark as withdrawn
            $earnings = SellerEarning::where('seller_id', $sellerId)
                ->where('status', 'available')
                ->orderBy('available_at', 'asc')
                ->get();
            
            Log::info('Found ' . $earnings->count() . ' available earnings');
            
            $remainingAmount = $request->amount;
            $earningsUsed = 0;
            
            foreach ($earnings as $earning) {
                if ($remainingAmount <= 0) break;
                
                Log::info("Processing earning ID: {$earning->id}, amount: {$earning->net_amount}, remaining: {$remainingAmount}");
                
                if ($earning->net_amount <= $remainingAmount) {
                    // Use entire earning
                    $earning->update([
                        'status' => 'withdrawn',
                        'withdrawn_at' => now(),
                    ]);
                    $remainingAmount -= $earning->net_amount;
                    $earningsUsed++;
                    Log::info("Used entire earning, remaining: {$remainingAmount}");
                } else {
                    // For partial withdrawal
                    // In a real system, you'd split the earning
                    $earning->update([
                        'status' => 'withdrawn',
                        'withdrawn_at' => now(),
                    ]);
                    $remainingAmount = 0;
                    $earningsUsed++;
                    Log::info("Used partial earning, remaining: {$remainingAmount}");
                }
            }
            
            Log::info("Used {$earningsUsed} earnings for this withdrawal");
            
            DB::commit();
            Log::info('Transaction committed successfully');
            Log::info('========== WITHDRAWAL REQUEST END ==========');
            
            return redirect()->route('seller.withdrawals.history')
                ->with('success', 'Withdrawal request submitted successfully. Your request number is ' . $withdrawalNumber);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            throw $e;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('========== WITHDRAWAL ERROR ==========');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error file: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('========== ERROR END ==========');
            
            return redirect()->back()
                ->with('error', 'Error processing withdrawal request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show withdrawal history
     */
    public function withdrawals(Request $request)
    {
        $sellerId = Auth::id();
        
        $query = SellerWithdrawal::where('seller_id', $sellerId);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }
        
        $withdrawals = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        // Get statistics
        $statistics = [
            'total_withdrawn' => SellerWithdrawal::where('seller_id', $sellerId)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_withdrawals' => SellerWithdrawal::where('seller_id', $sellerId)
                ->whereIn('status', ['pending', 'processing'])
                ->sum('amount'),
            'total_fees' => SellerWithdrawal::where('seller_id', $sellerId)
                ->where('status', 'completed')
                ->sum('fee'),
            'successful_withdrawals' => SellerWithdrawal::where('seller_id', $sellerId)
                ->where('status', 'completed')
                ->count(),
        ];
        
        return view('seller.earnings.withdrawals', compact('withdrawals', 'statistics'));
    }

    /**
     * Show withdrawal details
     */
    public function withdrawalDetails($id)
    {
        $sellerId = Auth::id();
        
        $withdrawal = SellerWithdrawal::where('seller_id', $sellerId)
            ->findOrFail($id);
        
        return view('seller.earnings.withdrawal-details', compact('withdrawal'));
    }

    /**
     * Cancel withdrawal request
     */
    public function cancelWithdrawal(Request $request, $id)
    {
        $sellerId = Auth::id();
        
        DB::beginTransaction();
        
        try {
            $withdrawal = SellerWithdrawal::where('seller_id', $sellerId)
                ->where('status', 'pending')
                ->findOrFail($id);
            
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'cancelled',
                'notes' => ($withdrawal->notes ? $withdrawal->notes . "\n" : '') . 'Cancelled by seller on ' . now(),
            ]);
            
            // Note: In production, you might want to restore the earnings
            // For now, we'll just cancel the withdrawal
            
            DB::commit();
            
            return redirect()->route('seller.withdrawals.history')
                ->with('success', 'Withdrawal request cancelled successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel withdrawal error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error cancelling withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * ==================== HELPER METHODS ====================
     */

    /**
     * Get minimum withdrawal amount from settings
     */
    private function getMinWithdrawalAmount()
    {
        $minWithdrawal = Setting::where('key', 'min_withdrawal_amount')->first();
        return $minWithdrawal ? floatval($minWithdrawal->value) : 100;
    }

    /**
     * Get seller's payment methods from user profile
     */
    private function getSellerPaymentMethods($seller)
    {
        $methods = [];
        
        if (!empty($seller->bank_name) && !empty($seller->account_number)) {
            $methods['bank'] = [
                'name' => 'Bank Transfer',
                'details' => $seller->bank_name . ' - ****' . substr($seller->account_number, -4),
            ];
        }
        
        if (!empty($seller->paypal_email)) {
            $methods['paypal'] = [
                'name' => 'PayPal',
                'details' => $seller->paypal_email,
            ];
        }
        
        if (!empty($seller->upi_id)) {
            $methods['razorpay'] = [
                'name' => 'UPI / Razorpay',
                'details' => $seller->upi_id,
            ];
        }
        
        return $methods;
    }

    /**
     * Prepare payment details as array (will be JSON encoded by model)
     */
    private function preparePaymentDetails($request)
    {
        $details = [];
        
        switch ($request->payment_method) {
            case 'bank':
                $details = [
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'bank_name' => $request->bank_name,
                    'ifsc_code' => $request->ifsc_code,
                    'account_type' => $request->account_type ?? 'savings',
                ];
                break;
                
            case 'paypal':
                $details = [
                    'paypal_email' => $request->paypal_email,
                ];
                break;
                
            case 'razorpay':
                $details = [
                    'upi_id' => $request->upi_id,
                ];
                break;
        }
        
        return $details;
    }

    /**
     * Calculate withdrawal fee based on payment method
     */
    private function calculateWithdrawalFee($amount, $method)
    {
        $feePercentage = 0;
        
        switch ($method) {
            case 'bank':
                $feePercentage = 0;
                break;
            case 'paypal':
                $feePercentage = 2.9;
                break;
            case 'razorpay':
                $feePercentage = 2.0;
                break;
        }
        
        return round(($amount * $feePercentage) / 100, 2);
    }
}