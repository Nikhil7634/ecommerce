<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use App\Models\SellerEarning;
use App\Models\SellerWithdrawal;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class EarningController extends Controller
{
    protected $commissionRate;
    protected $commissionType;
    
    public function __construct()
    {
        // Fetch commission settings from database
        $this->commissionRate = $this->getCommissionRate();
        $this->commissionType = $this->getCommissionType();
    }

    /**
     * Display seller earnings dashboard
     */
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        
        // Get date range from request or default to current year
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        // Get earnings statistics
        $statistics = $this->getEarningsStatistics($sellerId);
        
        // Get monthly earnings for chart
        $monthlyEarnings = $this->getMonthlyEarnings($sellerId, $year);
        
        // Get recent earnings
        $recentEarnings = SellerEarning::where('seller_id', $sellerId)
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get pending withdrawals
        $pendingWithdrawals = SellerWithdrawal::where('seller_id', $sellerId)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');
        
        // Get available balance
        $availableBalance = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'available')
            ->sum('net_amount');
        
        // Get lifetime earnings
        $lifetimeEarnings = SellerEarning::where('seller_id', $sellerId)
            ->sum('net_amount');
        
        // Get total withdrawn
        $totalWithdrawn = SellerWithdrawal::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Get withdrawal history
        $withdrawals = SellerWithdrawal::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get earnings by product
        $topProducts = OrderItem::where('seller_id', $sellerId)
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get();
        
        return view('seller.earnings.index', compact(
            'statistics',
            'monthlyEarnings',
            'year',
            'month',
            'recentEarnings',
            'pendingWithdrawals',
            'availableBalance',
            'lifetimeEarnings',
            'totalWithdrawn',
            'withdrawals',
            'topProducts'
        ));
    }

    /**
     * Show detailed earnings report
     */
    public function report(Request $request)
    {
        $sellerId = Auth::id();
        
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }
        
        // Get earnings in date range
        $earnings = SellerEarning::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get summary
        $summary = [
            'total_sales' => $earnings->sum('amount'),
            'total_commission' => $earnings->sum('commission'),
            'total_net' => $earnings->sum('net_amount'),
            'order_count' => $earnings->pluck('order_id')->unique()->count(),
            'item_count' => $earnings->count(),
        ];
        
        // Get daily breakdown
        $dailyBreakdown = SellerEarning::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_sales'),
                DB::raw('SUM(commission) as total_commission'),
                DB::raw('SUM(net_amount) as total_net'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('seller.earnings.report', compact(
            'earnings',
            'summary',
            'dailyBreakdown',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show withdrawal request form
     */
    public function withdrawalForm()
    {
        $sellerId = Auth::id();
        $seller = Auth::user();
        
        // Get available balance
        $availableBalance = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'available')
            ->sum('net_amount');
        
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
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bank,paypal,razorpay',
            'account_holder_name' => 'required_if:payment_method,bank|string|max:255',
            'account_number' => 'required_if:payment_method,bank|string|max:50',
            'bank_name' => 'required_if:payment_method,bank|string|max:255',
            'ifsc_code' => 'required_if:payment_method,bank|string|max:20',
            'paypal_email' => 'required_if:payment_method,paypal|email',
            'upi_id' => 'required_if:payment_method,razorpay|string|max:100',
        ]);
        
        $sellerId = Auth::id();
        
        // Check available balance
        $availableBalance = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'available')
            ->sum('net_amount');
        
        if ($request->amount > $availableBalance) {
            return redirect()->back()
                ->with('error', 'Insufficient balance. Your available balance is ₹' . number_format($availableBalance, 2))
                ->withInput();
        }
        
        // Check minimum withdrawal amount
        $minWithdrawal = $this->getMinWithdrawalAmount();
        if ($request->amount < $minWithdrawal) {
            return redirect()->back()
                ->with('error', 'Minimum withdrawal amount is ₹' . number_format($minWithdrawal, 2))
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Prepare payment details based on method
            $paymentDetails = $this->preparePaymentDetails($request);
            
            // Calculate fee (if any)
            $fee = $this->calculateWithdrawalFee($request->amount, $request->payment_method);
            $netAmount = $request->amount - $fee;
            
            // Create withdrawal request
            $withdrawal = SellerWithdrawal::create([
                'seller_id' => $sellerId,
                'withdrawal_number' => 'WTH-' . strtoupper(uniqid()),
                'amount' => $request->amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'payment_method' => $request->payment_method,
                'payment_details' => json_encode($paymentDetails),
                'status' => 'pending',
                'notes' => $request->notes ?? null,
            ]);
            
            // Mark earnings as withdrawn
            $earnings = SellerEarning::where('seller_id', $sellerId)
                ->where('status', 'available')
                ->orderBy('available_at', 'asc')
                ->take($this->getEarningsToCover($request->amount))
                ->get();
            
            $remainingAmount = $request->amount;
            
            foreach ($earnings as $earning) {
                if ($remainingAmount <= 0) break;
                
                if ($earning->net_amount <= $remainingAmount) {
                    // Use entire earning
                    $earning->update([
                        'status' => 'withdrawn',
                        'withdrawn_at' => now(),
                    ]);
                    $remainingAmount -= $earning->net_amount;
                } else {
                    // Split earning - only withdraw part of it
                    $this->splitEarning($earning, $remainingAmount, $withdrawal->id);
                    $remainingAmount = 0;
                }
            }
            
            DB::commit();
            
            return redirect()->route('seller.earnings.withdrawals')
                ->with('success', 'Withdrawal request submitted successfully. Your request number is ' . $withdrawal->withdrawal_number);
            
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function cancelWithdrawal($id)
    {
        $sellerId = Auth::id();
        
        $withdrawal = SellerWithdrawal::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'cancelled',
                'notes' => ($withdrawal->notes ? $withdrawal->notes . "\n" : '') . 'Cancelled by seller on ' . now(),
            ]);
            
            // Return earnings to available status
            // This would require tracking which earnings were allocated to this withdrawal
            
            DB::commit();
            
            return redirect()->route('seller.earnings.withdrawals')
                ->with('success', 'Withdrawal request cancelled successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error cancelling withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Export earnings report
     */
    public function export(Request $request)
    {
        $sellerId = Auth::id();
        
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }
        
        $earnings = SellerEarning::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Generate CSV
        $filename = 'earnings-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // Add headers
        fputcsv($handle, [
            'Date',
            'Order Number',
            'Product',
            'Quantity',
            'Sale Amount',
            'Commission',
            'Net Amount',
            'Status'
        ]);
        
        // Add data
        foreach ($earnings as $earning) {
            fputcsv($handle, [
                $earning->created_at->format('Y-m-d H:i:s'),
                $earning->order ? $earning->order->order_number : 'N/A',
                $earning->orderItem && $earning->orderItem->product ? $earning->orderItem->product->name : 'N/A',
                $earning->orderItem ? $earning->orderItem->quantity : 1,
                $earning->amount,
                $earning->commission,
                $earning->net_amount,
                $earning->status,
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * ==================== HELPER METHODS ====================
     */

    /**
     * Get commission rate from settings
     */
    private function getCommissionRate()
    {
        // Try to get from settings table
        $commissionRate = Setting::where('key', 'commission_rate')->first();
        
        if ($commissionRate && $commissionRate->value) {
            return floatval($commissionRate->value);
        }
        
        // Default commission rate (10%)
        return 10;
    }

    /**
     * Get commission type from settings
     */
    private function getCommissionType()
    {
        // Types: 'percentage' or 'fixed'
        $commissionType = Setting::where('key', 'commission_type')->first();
        
        if ($commissionType && $commissionType->value) {
            return $commissionType->value;
        }
        
        // Default type
        return 'percentage';
    }

    /**
     * Get minimum withdrawal amount
     */
    private function getMinWithdrawalAmount()
    {
        $minWithdrawal = Setting::where('key', 'min_withdrawal_amount')->first();
        
        if ($minWithdrawal && $minWithdrawal->value) {
            return floatval($minWithdrawal->value);
        }
        
        // Default minimum (₹100)
        return 100;
    }

    /**
     * Get earnings statistics
     */
    private function getEarningsStatistics($sellerId)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        
        return [
            // Today
            'today_sales' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $today)
                ->sum('amount'),
            'today_net' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $today)
                ->sum('net_amount'),
            'today_orders' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $today)
                ->distinct('order_id')
                ->count('order_id'),
            
            // Yesterday
            'yesterday_sales' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $yesterday)
                ->sum('amount'),
            
            // This Week
            'week_sales' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisWeek)
                ->sum('amount'),
            'week_net' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisWeek)
                ->sum('net_amount'),
            
            // This Month
            'month_sales' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount'),
            'month_net' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisMonth)
                ->sum('net_amount'),
            'month_orders' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisMonth)
                ->distinct('order_id')
                ->count('order_id'),
            
            // Last Month
            'last_month_sales' => SellerEarning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$lastMonth, $thisMonth->subDay()])
                ->sum('amount'),
            
            // This Year
            'year_sales' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisYear)
                ->sum('amount'),
            'year_net' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisYear)
                ->sum('net_amount'),
            
            // Totals
            'total_commission' => SellerEarning::where('seller_id', $sellerId)
                ->sum('commission'),
            
            'available_earnings' => SellerEarning::where('seller_id', $sellerId)
                ->where('status', 'available')
                ->sum('net_amount'),
            
            'pending_earnings' => SellerEarning::where('seller_id', $sellerId)
                ->where('status', 'pending')
                ->sum('net_amount'),
        ];
    }

    /**
     * Get monthly earnings for chart
     */
    private function getMonthlyEarnings($sellerId, $year)
    {
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            $sales = SellerEarning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
            
            $net = SellerEarning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('net_amount');
            
            $monthlyData[] = [
                'month' => $startDate->format('M'),
                'sales' => $sales,
                'net' => $net,
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Get seller's payment methods
     */
    private function getSellerPaymentMethods($seller)
    {
        $methods = [];
        
        // Check if seller has bank details saved
        if ($seller->bank_name && $seller->account_number) {
            $methods['bank'] = [
                'name' => 'Bank Transfer',
                'details' => $seller->bank_name . ' - ****' . substr($seller->account_number, -4),
            ];
        }
        
        // Add PayPal if seller has PayPal email
        if ($seller->paypal_email) {
            $methods['paypal'] = [
                'name' => 'PayPal',
                'details' => $seller->paypal_email,
            ];
        }
        
        // Add Razorpay if seller has UPI ID
        if ($seller->upi_id) {
            $methods['razorpay'] = [
                'name' => 'UPI / Razorpay',
                'details' => $seller->upi_id,
            ];
        }
        
        return $methods;
    }

    /**
     * Prepare payment details from request
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
     * Calculate withdrawal fee
     */
    private function calculateWithdrawalFee($amount, $method)
    {
        $feePercentage = 0;
        
        switch ($method) {
            case 'bank':
                $feePercentage = 0; // No fee for bank transfers
                break;
            case 'paypal':
                $feePercentage = 2.9; // PayPal fee
                break;
            case 'razorpay':
                $feePercentage = 2.0; // Razorpay fee
                break;
        }
        
        return round(($amount * $feePercentage) / 100, 2);
    }

    /**
     * Get number of earnings to cover withdrawal amount
     */
    private function getEarningsToCover($amount)
    {
        return ceil($amount / 100); // Assuming average earning of ₹100
    }

    /**
     * Split an earning record for partial withdrawal
     */
    private function splitEarning($earning, $amountToWithdraw, $withdrawalId)
    {
        // Create a new earning record for the withdrawn portion
        SellerEarning::create([
            'seller_id' => $earning->seller_id,
            'order_id' => $earning->order_id,
            'order_item_id' => $earning->order_item_id,
            'amount' => $amountToWithdraw,
            'commission' => ($amountToWithdraw / $earning->amount) * $earning->commission,
            'net_amount' => $amountToWithdraw - (($amountToWithdraw / $earning->amount) * $earning->commission),
            'type' => 'withdrawal',
            'status' => 'withdrawn',
            'description' => 'Partial withdrawal from earning #' . $earning->id,
            'available_at' => $earning->available_at,
            'withdrawn_at' => now(),
        ]);
        
        // Update original earning with remaining amount
        $remainingAmount = $earning->amount - $amountToWithdraw;
        $remainingCommission = $earning->commission - (($amountToWithdraw / $earning->amount) * $earning->commission);
        $remainingNet = $earning->net_amount - ($amountToWithdraw - (($amountToWithdraw / $earning->amount) * $earning->commission));
        
        $earning->update([
            'amount' => $remainingAmount,
            'commission' => $remainingCommission,
            'net_amount' => $remainingNet,
        ]);
    }

    /**
     * Calculate commission for an order item
     * This method is used by CartController when creating earnings
     */
    public function calculateCommission($orderItem)
    {
        $product = Product::find($orderItem->product_id);
        
        if (!$product) {
            return [
                'commission' => 0,
                'net_amount' => $orderItem->total_price
            ];
        }
        
        $sellingPrice = $orderItem->unit_price;
        $quantity = $orderItem->quantity;
        $totalPrice = $orderItem->total_price;
        
        // If product has base_price (seller's actual price before commission)
        if ($product->base_price > 0 && $product->base_price != $sellingPrice) {
            // Commission is the difference between selling price and base price
            $unitCommission = $sellingPrice - $product->base_price;
            $commission = $unitCommission * $quantity;
            $netAmount = $product->base_price * $quantity;
        } else {
            // Calculate based on commission percentage
            $commission = ($totalPrice * $this->commissionRate) / 100;
            $netAmount = $totalPrice - $commission;
        }
        
        return [
            'commission' => round($commission, 2),
            'net_amount' => round($netAmount, 2)
        ];
    }
}