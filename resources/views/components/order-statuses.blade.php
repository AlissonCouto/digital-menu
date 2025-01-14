@php
$statuses = $orderStatuses->pluck('date', 'status')->toArray();
@endphp

<div class="statuses">
    <ul class="list-unstyled">
        <li class="step {{isset($statuses['realized']) ? '-active' : ''}}">{{isset($statuses['realized']) ? (new \DateTime($statuses['realized']))->format('H:i') : '00:00' }} - Pedido realizado</li>
        <li class="step {{isset($statuses['inanalysis']) ? '-active' : ''}}">{{isset($statuses['inanalysis']) ? (new \DateTime($statuses['inanalysis']))->format('H:i') : '00:00' }} - Pedido em análise</li>
        <li class="step {{isset($statuses['inproduction']) ? '-active' : ''}}">{{isset($statuses['inproduction']) ? (new \DateTime($statuses['inproduction']))->format('H:i') : '00:00' }} - Pedido em produção</li>
        <li class="step {{isset($statuses['ready']) ? '-active' : ''}}">{{isset($statuses['ready']) ? (new \DateTime($statuses['ready']))->format('H:i') : '00:00' }} - Saiu para entrega</li>
    </ul>
</div>

<div class="list-adresses">
    <div class="item">
        <div class="d-flex align-items-center justify-content-between">
            <div class="name">Endereço de entrega</div>
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
        }

        @endphp

        @if($order->delivery_method == 'delivery')
        <div class="address">
            <div>{{$order->street}}, {{$order->number}}</div>
            <div>{{$order->neighborhood}} - {{$order->city}}/{{$order->uf}}</div>
        </div>
        @else
        <div class="address">
            <div>{{$address}}</div>
        </div>
        @endif
    </div>
</div>