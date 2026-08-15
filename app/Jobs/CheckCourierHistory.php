<?php

namespace App\Jobs;

use App\Models\Orders;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckCourierHistory implements ShouldQueue
{
    use Dispatchable, \Illuminate\Queue\InteractsWithQueue, \Illuminate\Queue\SerializesModels, Queueable;

    public $orderId;

    public $userEmail;

    public $customerName;

    public $grandTotal;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $userEmail = null, $customerName = null, $grandTotal = null)
    {
        $this->orderId = $orderId;
        $this->userEmail = $userEmail;
        $this->customerName = $customerName;
        $this->grandTotal = $grandTotal;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Orders::find($this->orderId);
        if ($order) {
            try {
                $order->getCourierHistoryData();
            } catch (\Exception $e) {
                Log::error('Job courier history check error: '.$e->getMessage());
            }

            try {
                if ($this->userEmail) {
                    send_template_mail($this->userEmail, 'order_confirmation_mail', [
                        'customer_name' => $this->customerName,
                        'order_id' => $this->orderId,
                        'order_total' => $this->grandTotal,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Job Order Confirmation Mail Trigger Error: '.$e->getMessage());
            }
        }
    }
}
