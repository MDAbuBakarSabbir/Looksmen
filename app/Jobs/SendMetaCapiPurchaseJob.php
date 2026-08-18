<?php

namespace App\Jobs;

use App\Models\Orders;
use App\Services\MetaCapiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMetaCapiPurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $eventId;
    public $clientContext;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $eventId = null, array $clientContext = [])
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
        $this->clientContext = $clientContext;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $order = Orders::find($this->orderId);
            if ($order) {
                MetaCapiService::sendPurchaseEvent($order, $this->eventId, $this->clientContext);
            }
        } catch (\Exception $e) {
            Log::error("SendMetaCapiPurchaseJob Error for Order #{$this->orderId}: " . $e->getMessage());
        }
    }
}
