<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Events\OrderStatusUpdatedEvent;

class OrderStatusUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $clientId;
    private $htmlStatus;
    private $status;
    private $order;

    /**
     * Create a new job instance.
     */
    public function __construct($clientId, $htmlStatus, $status, $order)
    {
        $this->clientId = $clientId;
        $this->htmlStatus = $htmlStatus;
        $this->status = $status;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        switch ($this->status) {
            case 'inproduction':
                $notification = [
                    'title' => 'Seu pedido está em produção!',
                    'body' => "Estamos preparando seu pedido com todo cuidado. Em breve estará pronto para entrega!",
                    'icon' => "/images/notification/inproduction-icon.png",
                    'tag' => "order-update"
                ];
                break;
            case 'ready':
                $notification = [
                    'title' => 'Seu pedido está pronto!',
                    'body' => "Seu pedido está pronto e a caminho. Fique atento, a entrega será feita em breve.",
                    'icon' => "/images/notification/ready-icon.png",
                    'tag' => "order-update"
                ];
                break;
                case 'closed':
                $notification = [
                    'title' => 'Pedido Atualizado',
                    'body' => "Seu pedido está pronto para entrega!",
                    'icon' => "/images/pizza-icon.png",
                    'tag' => "order-update"
                ];
                break;
            case 'rejected':
                $notification = [
                    'title' => 'Pedido rejeitado.',
                    'body' => "Infelizmente, seu pedido foi rejeitado. Por favor, entre em contato conosco para mais informações.",
                    'icon' => "/images/notification/rejected-icon.png",
                    'tag' => "order-update"
                ];
                break;
            case 'canceled':
                $notification = [
                    'title' => 'Pedido cancelado.',
                    'body' => "Seu pedido foi cancelado. Se houver dúvidas ou problemas, fale conosco para entender o motivo.",
                    'icon' => "/images/notification/rejected-icon.png",
                    'tag' => "order-update"
                ];
                break;
            default:
                $notification = null;
        }

        $status = [
            'realized' => 'Realizado',
            'inanalysis' => 'Em análise',
            'inproduction' => 'Em Produção',
            'ready' => 'Pronto',
            'closed' => 'Concluído',
            'rejected' => 'Rejeitado',
            'canceled' => 'Cancelado'
        ];

        $tagStatus = '
        <div class="status -' . $this->status . '">
            <span>' . $status[$this->status] . '</span>
        </div>
    ';

        OrderStatusUpdatedEvent::dispatch($this->clientId, $this->htmlStatus, $notification, $tagStatus, $this->order);
    }
}
