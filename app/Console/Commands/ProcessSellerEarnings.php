<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SellerEarning;
use Carbon\Carbon;

class ProcessSellerEarnings extends Command
{
    protected $signature = 'earnings:process';
    protected $description = 'Process seller earnings from completed orders';

    public function handle()
    {
        $this->info('Processing seller earnings...');
        
        // Get orders that are delivered/completed and not yet processed
        $orders = Order::whereIn('status', ['delivered', 'completed'])
            ->whereDoesntHave('earnings')
            ->get();
        
        $count = 0;
        $commissionRate = 10; // Get from settings
        
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                // Calculate earnings
                $amount = $item->total_price;
                $commission = ($amount * $commissionRate) / 100;
                $netAmount = $amount - $commission;
                
                // Create earning record
                SellerEarning::create([
                    'seller_id' => $item->seller_id,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'amount' => $amount,
                    'commission' => $commission,
                    'net_amount' => $netAmount,
                    'type' => 'sale',
                    'status' => 'available',
                    'description' => 'Earnings from order #' . $order->order_number,
                    'available_at' => now(),
                ]);
                
                $count++;
            }
        }
        
        $this->info("Processed {$count} earnings records.");
    }
}