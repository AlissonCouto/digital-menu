<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Events\OrderNotificationAdminEvent;
use Illuminate\Support\Facades\Log;

class OrderNotificationAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $companyId;
    private $order;

    /**
     * Create a new job instance.
     */
    public function __construct($companyId, $order)
    {
        $this->companyId = $companyId;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $html = '';
        $status = 'inproduction';
        $order = $this->order;
        $html = view('admin.components.order')->with(compact('order', 'status'))->render();
        OrderNotificationAdminEvent::dispatch($this->companyId, $html);
    }
}
