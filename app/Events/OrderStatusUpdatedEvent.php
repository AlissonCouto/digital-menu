<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $clientId;
    private $htmlStatus;
    private $notification;
    private $tagStatus;
    private $orderId;

    /**
     * Create a new event instance.
     */
    public function __construct($clientId, $htmlStatus, $notification, $tagStatus, $orderId)
    {
        $this->clientId = $clientId;
        $this->htmlStatus = $htmlStatus;
        $this->notification = $notification;
        $this->tagStatus = $tagStatus;
        $this->orderId = $orderId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('channel-order-status-updated.' . $this->clientId);
    } // broadcastOn()

    public function broadcastAs()
    {
        return 'order.status.updated';
    } // broadcastAs()

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'htmlStatus' => $this->htmlStatus,
            'notification' => $this->notification,
            'tagStatus' => $this->tagStatus,
            'orderId' => $this->orderId
        ];
    }
}
