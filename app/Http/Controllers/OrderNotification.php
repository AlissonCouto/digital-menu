<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Client;
use App\Models\Company;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderNotification extends Controller
{

    private $status;

    public function __construct()
    {
        $this->status = [
            'realized' => 'Realizado',
            'inanalysis' => 'Em análise',
            'inproduction' => 'Em Produção',
            'ready' => 'Pronto',
            'closed' => 'Concluído',
            'rejected' => 'Rejeitado',
            'canceled' => 'Cancelado'
        ];
    }

    public function forAttendant(Request $request)
    {

        //$user = Auth::guard('web')->user();
        $user = Auth::user();
        $company = $user->company()->first();

        $orders = $company->orders()
            ->join('order_statuses', 'orders.id', '=', 'order_statuses.order_id')
            ->where('order_statuses.status', '=', 'inanalysis')
            ->where('order_statuses.notified', '=', false)
            ->select('orders.*')
            ->get();

        //dd($company->orders()->join('order_statuses', 'orders.id', '=', 'order_statuses.order_id')->where('order_statuses.status', '=', 'inanalysis')->get());

        if ($orders->isNotEmpty()) {
            $orderIds = $orders->pluck('id');

            DB::table('order_statuses')
                ->whereIn('order_id', $orderIds)
                ->where('status', '=', 'inanalysis')
                ->update(['notified' => true]);

            $html = [];
            foreach ($orders as $order) {
                $newStatus = $order->status == 'inanalysis' ? 'inproduction' : $order->status;
                $html[] = view('admin.components.order')->with(['order' => $order, 'status' => $newStatus])->render();
            }

            $data = json_encode(['success' => true, 'message' => 'Pedidos retornados com sucesso.', 'data' => implode('', $html)]);
        } else {
            $data = json_encode(['success' => false, 'message' => 'Nenhum pedido encontrado.', 'data' => []]);
        } // Se tem pedidos

        return $data;
    } // attendant()

    public function forClient(Request $request, Client $client)
    {

        $order = $client->orders()
            ->join('order_statuses', 'orders.id', '=', 'order_statuses.order_id')
            ->whereNotIn('order_statuses.status', ['realized', 'inanalysis', 'closed'])
            ->where('order_statuses.notified', '=', false)
            ->select('orders.*')
            ->orderby('id', 'DESC')
            ->first();

        if (is_null($order)) {
            $data = json_encode(['success' => false, 'message' => 'Nenhum pedido encontrado.', 'data' => [], 'notification' => []]);
        } else {
            $orderId = $order->id;

            DB::table('order_statuses')
                ->where('order_id', $orderId)
                ->where('order_statuses.status', '!=', 'inanalysis')
                ->where('order_statuses.status', '!=', 'realized')
                ->update(['notified' => true]);

            switch ($order->status) {
                case 'inproduction':
                    $notification = [
                        'title' => 'Seu pedido está em produção!',
                        'body' => "Estamos preparando seu pedido com todo cuidado. Em breve estará pronto para entrega!",
                        'icon' => asset('storage/images/notification/inproduction-icon.png'),
                        'tag' => "order-update"
                    ];
                    break;
                case 'ready':
                    $notification = [
                        'title' => 'Seu pedido está pronto!',
                        'body' => "Seu pedido está pronto e a caminho. Fique atento, a entrega será feita em breve.",
                        'icon' => asset('storage/images/notification/ready-icon.png'),
                        'tag' => "order-update"
                    ];
                    break;
                    /*case 'closed':
                                $notification = [
                                    'title' => 'Pedido Atualizado',
                                    'body' => "Seu pedido está pronto para entrega!",
                                    'icon' => "/images/pizza-icon.png",
                                    'tag' => "order-update"
                                ];
                                break;*/
                case 'rejected':
                    $notification = [
                        'title' => 'Pedido rejeitado.',
                        'body' => "Infelizmente, seu pedido foi rejeitado. Por favor, entre em contato conosco para mais informações.",
                        'icon' => asset('storage/images/notification/rejected-icon.png'),
                        'tag' => "order-update"
                    ];
                    break;
                case 'canceled':
                    $notification = [
                        'title' => 'Pedido cancelado.',
                        'body' => "Seu pedido foi cancelado. Se houver dúvidas ou problemas, fale conosco para entender o motivo.",
                        'icon' => asset('storage/images/notification/canceled-icon.png'),
                        'tag' => "order-update"
                    ];
                    break;
                default:
                    $notification = null;
            }

            $tagStatus = '
                <div class="status -' . $order->status . '">
                    <span>' . $this->status[$order->status] . '</span>
                </div>
            ';

            $message = 'Pedidos retornados com sucesso.';
            $success = true;
            $htmlStatus = [];
            $orderStatuses = $order->statuses()->get();
            $htmlStatus = view('components.order-statuses')->with(['order' => $order, 'status' => $order->status, 'orderStatuses' => $orderStatuses])->render();

            $data = json_encode(['success' => $success, 'message' => $message, 'htmlStatus' => $htmlStatus, 'tagStatus' => $tagStatus, 'orderId' => $order->id, 'notification' => $notification]);
        }

        return $data;
    } // forClient()
}
