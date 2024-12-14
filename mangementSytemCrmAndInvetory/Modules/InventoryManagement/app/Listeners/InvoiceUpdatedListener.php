<?php

namespace Modules\InventoryManagement\Listeners;

use Modules\InventoryManagement\app\Events\InvoiceUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InventoryManagement\Events\InvoiceUpdated;

class InvoiceUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvoiceUpdated $event): void{
        $invoice = $event->invoice;
        if (!$invoice || !$invoice->products()) return;
        echo 1234;
        foreach ($invoice->products() as $product){
        }

    }
}
