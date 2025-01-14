@php

$formspayment = [
'credit' => 'Crédito',
'debit' => 'Débito',
'cash' => 'Dinheiro',
'pix' => 'PIX'
];

switch($status){
case 'ready':
$labelButton = 'Avançar pedido';
$icon = 'sync';
break;

case 'closed':
$labelButton = 'Finalizar pedido';
$icon = 'check-circle';
break;

default:
$labelButton = 'Aceitar pedido';
$icon = 'arrow-right';
}

@endphp

<div class="item">
    <div class="header">
        <h3 class="code">#{{$order->code}}</h3>

        @if($status == 'inproduction' && $order->origin == 'client')
        <a data-status="rejected" href="{{route('orders.status.update', $order->id)}}" class="reject update-status">
            <i class="mdi mdi-close"></i> Rejeitar
        </a>
        @endif
    </div>

    <div class="body">
        <div class="col-one">
            <div class="client">
                <strong class="name">{{$order->client->name}}</strong>
                <div class="phone">{{$order->client->phone}}</div>
            </div>

            @php
            $address = '';

            switch($order->delivery_method){
            case 'withdrawal':
            $address = 'Retirar no estabelecimento';
            break;
            case 'comeget':
            $address = 'Consumir no local';
            break;
            default:
            $street = $order->street . ', ' ?? '';
            $number = $order->number . ' ' ?? '';
            $neighborhood = '- ' . $order->neighborhood . '. ' ?? '';

            $address = $street . $number . $neighborhood;
            }
            @endphp
            <div class="address">{{$address}}</div>
        </div>

        <div class="col-two">
            <div class="values">
                <div class="total">
                    <strong>Total: </strong>
                    <span>R$ {{number_format($order->total, 2, ',', '.')}}</span>
                </div>

                <div class="payment">
                    <strong>{{$formspayment[$order->payment]}}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="edit">
        <a href="{{route('order.edit', $order->id)}}">Editar Pedido</a>
    </div>

    <a href="{{route('orders.show', $order->id)}}" class="details">Detalhes do pedido</a>

    <div class="button">
        <a data-status="{{$status}}" href="{{route('orders.status.update', $order->id)}}" class="accept update-status">
            {{$labelButton}}
            <i class="mdi mdi-{{$icon}}"></i>
        </a>
    </div>
</div>