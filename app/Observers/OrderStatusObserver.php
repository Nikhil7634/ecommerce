<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\SellerEarning;
use Illuminate\Support\Facades\Log;

class OrderStatusObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Check if status was changed to 'delivered'
        if ($order->isDirty('status') && $order->status == 'delivered') {
            $this->makeEarningsAvailable($order);
        }
        
        // Check if status was changed from 'delivered' to something else
        if ($order->isDirty('status') && $order->getOriginal('status') == 'delivered' && $order->status != 'delivered') {
            $this->revertEarningsToPending($order);
        }
    }
    
    /**
     * Make earnings available when order is delivered
     */
    private function makeEarningsAvailable($order)
    {
        $earnings = SellerEarning::where('order_id', $order->id)->get();
        $updated = 0;
        
        foreach ($earnings as $earning) {
            if ($earning->status == 'pending') {
                $earning->update([
                    'status' => 'available',
                    'available_at' => now(),
                ]);
                $updated++;
                
                Log::info("Earning #{$earning->id} for order #{$order->order_number} is now available");
            }
        }
        
        if ($updated > 0) {
            Log::info("Made {$updated} earnings available for order #{$order->order_number}");
        }
    }
    
    /**
     * Revert earnings to pending if order is no longer delivered
     */
    private function revertEarningsToPending($order)
    {
        $earnings = SellerEarning::where('order_id', $order->id)
            ->where('status', 'available')
            ->get();
        
        $updated = 0;
        
        foreach ($earnings as $earning) {
            $earning->update([
                'status' => 'pending',
                'available_at' => now()->addDays(7),
            ]);
            $updated++;
            
            Log::info("Earning #{$earning->id} for order #{$order->order_number} reverted to pending");
        }
        
        if ($updated > 0) {
            Log::info("Reverted {$updated} earnings to pending for order #{$order->order_number}");
        }
    }
}