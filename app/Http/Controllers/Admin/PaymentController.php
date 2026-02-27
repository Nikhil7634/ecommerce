<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\SellerEarning;
use App\Models\SellerWithdrawal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    /**
     * Display all payment transactions
     */
    public function transactions(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $status = $request->get('status', 'all');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $search = $request->get('search', '');

            $query = $this->buildTransactionsQuery($type, $status, $startDate, $endDate, $search);
            
            $transactions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

            // Get statistics
            $statistics = $this->getTransactionStatistics($startDate, $endDate);

            // Get payment methods distribution
            $paymentMethods = $this->getPaymentMethodsDistribution($startDate, $endDate);

            return view('admin.payments.transactions', compact(
                'transactions',
                'statistics',
                'paymentMethods',
                'type',
                'status',
                'startDate',
                'endDate',
                'search'
            ));
            
        } catch (\Exception $e) {
            Log::error('Transactions page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading transactions: ' . $e->getMessage());
        }
    }

    /**
     * Display all withdrawal requests
     */
    public function withdrawals(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $search = $request->get('search', '');

            $query = SellerWithdrawal::with(['seller', 'processor']);

            // Apply status filter
            if ($status != 'all') {
                $query->where('status', $status);
            }

            // Apply date filter
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

            // Apply search filter
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('withdrawal_number', 'like', "%{$search}%")
                      ->orWhereHas('seller', function($sq) use ($search) {
                          $sq->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('business_name', 'like', "%{$search}%");
                      });
                });
            }

            $withdrawals = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

            // Get statistics
            $statistics = $this->getWithdrawalStatistics($startDate, $endDate);

            // Get status counts for filter badges
            $statusCounts = $this->getWithdrawalStatusCounts();

            return view('admin.payments.withdrawals', compact(
                'withdrawals',
                'statistics',
                'statusCounts',
                'status',
                'startDate',
                'endDate',
                'search'
            ));

        } catch (\Exception $e) {
            Log::error('Withdrawals page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading withdrawals: ' . $e->getMessage());
        }
    }

    /**
     * Show withdrawal details
     */
    public function showWithdrawal($id)
    {
        try {
            $withdrawal = SellerWithdrawal::with(['seller', 'processor'])
                ->findOrFail($id);

            return view('admin.payments.withdrawal-details', compact('withdrawal'));

        } catch (\Exception $e) {
            Log::error('Show withdrawal error: ' . $e->getMessage());
            return redirect()->route('admin.payments.withdrawals')
                ->with('error', 'Withdrawal not found.');
        }
    }

    public function approveWithdrawal(Request $request, $id)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:500',
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->back()
                    ->with('error', 'You must be logged in to perform this action.');
            }

            $withdrawal = SellerWithdrawal::findOrFail($id);

            if ($withdrawal->status != 'pending' && $withdrawal->status != 'processing') {
                return redirect()->back()
                    ->with('error', 'Only pending withdrawals can be approved.');
            }

            DB::beginTransaction();

            // Get the authenticated user ID
            $adminId = Auth::id();

            $withdrawal->update([
                'status' => 'completed',
                'processed_at' => now(),
                'completed_at' => now(),
                'processed_by' => $adminId,
                'admin_notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.payments.withdrawals.show', $withdrawal->id)
                ->with('success', 'Withdrawal approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve withdrawal error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error approving withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Reject withdrawal request
     */
    public function rejectWithdrawal(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string|min:10|max:500',
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->back()
                    ->with('error', 'You must be logged in to perform this action.');
            }

            $withdrawal = SellerWithdrawal::findOrFail($id);

            if ($withdrawal->status != 'pending') {
                return redirect()->back()
                    ->with('error', 'Only pending withdrawals can be rejected.');
            }

            DB::beginTransaction();

            // Get the authenticated user ID
            $adminId = Auth::id();

            $withdrawal->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'processed_by' => $adminId,
                'admin_notes' => $request->reason,
            ]);

            DB::commit();

            return redirect()->route('admin.payments.withdrawals.show', $withdrawal->id)
                ->with('success', 'Withdrawal rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject withdrawal error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error rejecting withdrawal: ' . $e->getMessage());
        }
    }
 

    /**
     * Export withdrawals to CSV
     */
    public function exportWithdrawals(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $search = $request->get('search', '');

            $query = SellerWithdrawal::with('seller');

            if ($status != 'all') {
                $query->where('status', $status);
            }

            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('withdrawal_number', 'like', "%{$search}%")
                      ->orWhereHas('seller', function($sq) use ($search) {
                          $sq->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $withdrawals = $query->orderBy('created_at', 'desc')->get();

            $filename = 'withdrawals-' . $startDate . '-to-' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($withdrawals) {
                $handle = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($handle, [
                    'Withdrawal #',
                    'Seller Name',
                    'Seller Email',
                    'Amount',
                    'Fee',
                    'Net Amount',
                    'Payment Method',
                    'Status',
                    'Requested Date',
                    'Processed Date',
                    'Completed Date',
                    'Notes'
                ]);

                // Data
                foreach ($withdrawals as $w) {
                    fputcsv($handle, [
                        $w->withdrawal_number,
                        $w->seller->name ?? 'N/A',
                        $w->seller->email ?? 'N/A',
                        $w->amount,
                        $w->fee,
                        $w->net_amount,
                        ucfirst($w->payment_method),
                        ucfirst($w->status),
                        $w->created_at->format('Y-m-d H:i:s'),
                        $w->processed_at ? Carbon::parse($w->processed_at)->format('Y-m-d H:i:s') : '',
                        $w->completed_at ? Carbon::parse($w->completed_at)->format('Y-m-d H:i:s') : '',
                        $w->notes ?? '',
                    ]);
                }

                fclose($handle);
            };

            return Response::stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export withdrawals error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting withdrawals: ' . $e->getMessage());
        }
    }

    /**
     * Export transactions to CSV
     */
    public function exportTransactions(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $status = $request->get('status', 'all');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

            $query = $this->buildTransactionsQuery($type, $status, $startDate, $endDate, '');
            $transactions = $query->orderBy('created_at', 'desc')->get();

            $filename = 'transactions-' . $startDate . '-to-' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($transactions) {
                $handle = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($handle, [
                    'Transaction ID',
                    'Type',
                    'Description',
                    'User',
                    'Amount',
                    'Payment Method',
                    'Status',
                    'Date'
                ]);

                // Data
                foreach ($transactions as $transaction) {
                    $userName = 'N/A';
                    if ($transaction->transaction_type == 'order' && $transaction->user) {
                        $userName = $transaction->user->name;
                    } elseif ($transaction->transaction_type == 'withdrawal' && $transaction->seller) {
                        $userName = $transaction->seller->name . ' (Seller)';
                    }

                    fputcsv($handle, [
                        $transaction->transaction_id,
                        ucfirst($transaction->transaction_type),
                        $transaction->description,
                        $userName,
                        '₹' . number_format($transaction->amount, 2),
                        ucfirst($transaction->payment_method ?? 'N/A'),
                        ucfirst($transaction->status),
                        $transaction->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return Response::stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export transactions error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting transactions: ' . $e->getMessage());
        }
    }

    /**
     * Build transactions query based on filters
     */
    private function buildTransactionsQuery($type, $status, $startDate, $endDate, $search)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($type == 'orders') {
            $query = Order::query()
                ->with(['user'])
                ->select(
                    'id',
                    'order_number as transaction_id',
                    'user_id',
                    'total_amount as amount',
                    'payment_method',
                    'payment_status as status',
                    'created_at',
                    DB::raw("'order' as transaction_type"),
                    DB::raw("CONCAT('Order #', order_number) as description")
                );
        } elseif ($type == 'withdrawals') {
            $query = SellerWithdrawal::query()
                ->with(['seller'])
                ->select(
                    'id',
                    'withdrawal_number as transaction_id',
                    'seller_id as user_id',
                    'amount',
                    'payment_method',
                    'status',
                    'created_at',
                    DB::raw("'withdrawal' as transaction_type"),
                    DB::raw("CONCAT('Withdrawal #', withdrawal_number) as description")
                );
        } else {
            // Combine both using UNION
            $orders = Order::query()
                ->select(
                    'id',
                    'order_number as transaction_id',
                    'user_id',
                    'total_amount as amount',
                    'payment_method',
                    'payment_status as status',
                    'created_at',
                    DB::raw("'order' as transaction_type"),
                    DB::raw("CONCAT('Order #', order_number) as description")
                );

            $withdrawals = SellerWithdrawal::query()
                ->select(
                    'id',
                    'withdrawal_number as transaction_id',
                    'seller_id as user_id',
                    'amount',
                    'payment_method',
                    'status',
                    'created_at',
                    DB::raw("'withdrawal' as transaction_type"),
                    DB::raw("CONCAT('Withdrawal #', withdrawal_number) as description")
                );

            $query = $orders->union($withdrawals);
        }

        // Apply date filter
        $query->whereBetween('created_at', [$start, $end]);

        // Apply status filter
        if ($status != 'all') {
            $query->where('status', $status);
        }

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Get transaction statistics
     */
    private function getTransactionStatistics($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        try {
            // Order statistics
            $orderStats = [
                'total' => Order::whereBetween('created_at', [$start, $end])->count() ?: 0,
                'amount' => Order::whereBetween('created_at', [$start, $end])->sum('total_amount') ?: 0,
                'successful' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'paid')
                    ->count() ?: 0,
                'successful_amount' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'paid')
                    ->sum('total_amount') ?: 0,
                'pending' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'pending')
                    ->count() ?: 0,
                'pending_amount' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'pending')
                    ->sum('total_amount') ?: 0,
                'failed' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'failed')
                    ->count() ?: 0,
                'failed_amount' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'failed')
                    ->sum('total_amount') ?: 0,
            ];

            // Withdrawal statistics
            $withdrawalStats = [
                'total' => SellerWithdrawal::whereBetween('created_at', [$start, $end])->count() ?: 0,
                'amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])->sum('amount') ?: 0,
                'completed' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->count() ?: 0,
                'completed_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->sum('amount') ?: 0,
                'pending' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['pending', 'processing'])
                    ->count() ?: 0,
                'pending_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['pending', 'processing'])
                    ->sum('amount') ?: 0,
                'rejected' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'rejected')
                    ->count() ?: 0,
                'rejected_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'rejected')
                    ->sum('amount') ?: 0,
            ];

            return [
                'orders' => $orderStats,
                'withdrawals' => $withdrawalStats,
                'total_transactions' => $orderStats['total'] + $withdrawalStats['total'],
                'total_amount' => $orderStats['amount'] + $withdrawalStats['amount'],
            ];
            
        } catch (\Exception $e) {
            Log::error('Transaction statistics error: ' . $e->getMessage());
            return [
                'orders' => ['total' => 0, 'amount' => 0, 'successful' => 0, 'successful_amount' => 0, 'pending' => 0, 'pending_amount' => 0, 'failed' => 0, 'failed_amount' => 0],
                'withdrawals' => ['total' => 0, 'amount' => 0, 'completed' => 0, 'completed_amount' => 0, 'pending' => 0, 'pending_amount' => 0, 'rejected' => 0, 'rejected_amount' => 0],
                'total_transactions' => 0,
                'total_amount' => 0,
            ];
        }
    }

    /**
     * Get withdrawal statistics
     */
    private function getWithdrawalStatistics($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        try {
            $stats = [
                'total_withdrawals' => SellerWithdrawal::whereBetween('created_at', [$start, $end])->count() ?: 0,
                'total_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])->sum('amount') ?: 0,
                'pending_count' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['pending', 'processing'])
                    ->count() ?: 0,
                'pending_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['pending', 'processing'])
                    ->sum('amount') ?: 0,
                'completed_count' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->count() ?: 0,
                'completed_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->sum('amount') ?: 0,
                'rejected_count' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'rejected')
                    ->count() ?: 0,
                'rejected_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'rejected')
                    ->sum('amount') ?: 0,
                'cancelled_count' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'cancelled')
                    ->count() ?: 0,
                'cancelled_amount' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'cancelled')
                    ->sum('amount') ?: 0,
            ];

            return $stats;

        } catch (\Exception $e) {
            Log::error('Withdrawal statistics error: ' . $e->getMessage());
            return [
                'total_withdrawals' => 0,
                'total_amount' => 0,
                'pending_count' => 0,
                'pending_amount' => 0,
                'completed_count' => 0,
                'completed_amount' => 0,
                'rejected_count' => 0,
                'rejected_amount' => 0,
                'cancelled_count' => 0,
                'cancelled_amount' => 0,
            ];
        }
    }

    /**
     * Get withdrawal status counts for filter badges
     */
    private function getWithdrawalStatusCounts()
    {
        try {
            return [
                'all' => SellerWithdrawal::count() ?: 0,
                'pending' => SellerWithdrawal::where('status', 'pending')->count() ?: 0,
                'processing' => SellerWithdrawal::where('status', 'processing')->count() ?: 0,
                'completed' => SellerWithdrawal::where('status', 'completed')->count() ?: 0,
                'rejected' => SellerWithdrawal::where('status', 'rejected')->count() ?: 0,
                'cancelled' => SellerWithdrawal::where('status', 'cancelled')->count() ?: 0,
            ];
        } catch (\Exception $e) {
            Log::error('Withdrawal status counts error: ' . $e->getMessage());
            return [
                'all' => 0,
                'pending' => 0,
                'processing' => 0,
                'completed' => 0,
                'rejected' => 0,
                'cancelled' => 0,
            ];
        }
    }

    /**
     * Get payment methods distribution
     */
    private function getPaymentMethodsDistribution($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        try {
            $methods = Order::whereBetween('created_at', [$start, $end])
                ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('payment_method')
                ->get();

            return $methods;
            
        } catch (\Exception $e) {
            Log::error('Payment methods distribution error: ' . $e->getMessage());
            return collect([]);
        }
    }
}