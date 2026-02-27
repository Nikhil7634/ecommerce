<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\SellerEarning;

class FixDeliveredOrderEarnings extends Command
{
    protected $signature = 'earnings:fix-delivered';
    protected $description = 'Fix earnings for delivered orders that are still pending';

    public function handle()
    {
        $this->info('Fixing earnings for delivered orders...');
        
        // Get all delivered orders
        $orders = Order::where('status', 'delivered')->get();
        
        $fixed = 0;
        
        foreach ($orders as $order) {
            $earnings = SellerEarning::where('order_id', $order->id)
                ->where('status', 'pending')
                ->get();
            
            foreach ($earnings as $earning) {
                $earning->update([
                    'status' => 'available',
                    'available_at' => now(),
                ]);
                $fixed++;
                
                $this->line("Fixed earning #{$earning->id} for order #{$order->order_number}");
            }
        }
        
        $this->info("Fixed {$fixed} earnings records.");
    }
}