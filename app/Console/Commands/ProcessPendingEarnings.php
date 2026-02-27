<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SellerEarning;
use Carbon\Carbon;

class ProcessPendingEarnings extends Command
{
    protected $signature = 'earnings:process-pending';
    protected $description = 'Process pending earnings that have passed their available date';

    public function handle()
    {
        $this->info('Processing pending earnings...');
        
        $earnings = SellerEarning::where('status', 'pending')
            ->where('available_at', '<=', Carbon::now())
            ->get();
        
        $processed = 0;
        
        foreach ($earnings as $earning) {
            $earning->update([
                'status' => 'available',
            ]);
            $processed++;
            
            $this->line("Earning #{$earning->id} is now available");
        }
        
        $this->info("Processed {$processed} earnings.");
    }
}