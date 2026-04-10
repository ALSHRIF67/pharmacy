<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStockCache
{
    public $sale;


    public function handle(SaleCompleted $event): void
    {
        // Logic to update stock cache or trigger reporting
        // For now, we logging for audit
        \Log::info("Sale completed: " . $event->sale->id . " Total: " . $event->sale->total_price);
    }
}
