@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex align-items-center justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <a href="{{route('orders.index')}}" class="back-to-index"><i class="mdi mdi-arrow-left"></i></a>
            <h2 class="title">Detalhes do Pedido: #{{$order->code}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="orders-list">

            <div class="list">

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="item">
                            <div class="wrapper">

                                <div class="header">
                                    <div>
                                        <div class="code">
                                            Pedido #{{$order->code}}
                                        </div>

                                        <div class="client">
                                            <strong>Cliente: </strong> {{$order->client['name']}}
                                            - <strong>{{$order->client->phone}}</strong>
                                        </div>

                                        @php
                                            $timezone = new \DateTimeZone('America/Sao_Paulo');

                                            $date = $order->date;
                                            $date->setTimezone($timezone);
                                        @endphp

                                        <div class="date">
                                            Em {{$date->format('d/m/Y')}} às {{$date->format('H:i:s')}}
                                        </div>
                                    </div>

                                    <div class="status -{{$order->status}}">
                                        <span>{{$status[$order->status]}}</span>
                                    </div>
                                </div>


                                @php
                                $statuses = $orderStatuses->pluck('date', 'status')->toArray();
                                @endphp

                                <div class="statuses">
                                    <ul class="list-unstyled">
                                        <li class="step {{isset($statuses['realized']) ? '-active' : ''}}">{{isset($statuses['realized']) ? (new \DateTime($statuses['realized']))->format('H:i') : '00:00' }} - Pedido realizado</li>
                                        <li class="step {{isset($statuses['inanalysis']) ? '-active' : ''}}">{{isset($statuses['inanalysis']) ? (new \DateTime($statuses['inanalysis']))->format('H:i') : '00:00' }} - Pedido em análise</li>
                                        <li class="step {{isset($statuses['inproduction']) ? '-active' : ''}}">{{isset($statuses['inproduction']) ? (new \DateTime($statuses['inproduction']))->format('H:i') : '00:00' }} - Pedido em produção</li>
                                        <li class="step {{isset($statuses['delivery']) ? '-active' : ''}}">{{isset($statuses['delivery']) ? (new \DateTime($statuses['delivery']))->format('H:i') : '00:00' }} - Saiu para entrega</li>
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

                                @php
                                $payments = [
                                'pix' => [
                                'icon' => 'pix.png',
                                'name' => 'Pix'
                                ],
                                'cash' => [
                                'icon' => 'cash.png',
                                'name' => 'Dinheiro'
                                ],
                                'credit' => [
                                'icon' => 'credit-card.png',
                                'name' => 'Cartão de débito'
                                ],
                                'debit' => [
                                'icon' => 'credit-card.png',
                                'name' => 'Cartão de crédito'
                                ],
                                ];
                                @endphp

                                <div class="list-payments">
                                    <div class="item">
                                        <div class="name mb-3">Forma de pagamento</div>
                                        <div class="d-flex align-items-center">
                                            <div class="icon">
                                                <img src="{{asset('storage/images/' . $payments[$order->payment]['icon'])}}" alt="Pix">
                                            </div>
                                            <div class="name">{{$payments[$order->payment]['name']}}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="item">
                            <div class="wrapper">

                                <div>
                                    <div class="header">
                                        <div class="code">Items</div>
                                    </div>

                                    <div class="items">
                                        <ul class="list-unstyled">
                                            @foreach($order->order_items()->get() as $orderItem)
                                            <li>
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <span>{{$orderItem->quantity}}x {{$orderItem->name}}</span>
                                                    <strong>R$ {{number_format($orderItem->total, 2, ',', '.')}}</strong>
                                                </div>
                                            </li>

                                            @if($orderItem->category == 'pizzas')
                                            
                                            <li>

                                                <div class="additionals">
                                                    @if($orderItem->border)
                                                    <ul class="list-unstyled">
                                                        <li><strong>Borda: </strong>{{$orderItem->border->name}} {{isset($orderItem->border->price) ? ' - R$ ' . number_format($orderItem->border->price, 2, ',', '.') : ''}}</li>
                                                    </ul>
                                                    @endif

                                                    @if($orderItem->pasta)
                                                    <ul class="list-unstyled">
                                                        <li><strong>Massa: </strong>{{$orderItem->pasta->name}} {{isset($orderItem->pasta->price) ? ' - R$ ' . number_format($orderItem->pasta->price, 2, ',', '.') : ''}}</li>
                                                    </ul>
                                                    @endif
                                                </div>

                                            </li>

                                            <li>
                                                <div class="d-flex">

                                                    @if($orderItem->order_item_products()->count() > 0)
                                                    <div class="ingredients-removeds">

                                                        @foreach($orderItem->order_item_products()->get() as $k => $item_product)
                                                        @if($item_product->product()->count() > 0)

                                                        @if($item_product->ingredients_removed()->count() > 0)
                                                        @if($k == 0)
                                                        <h2 class="title">Ingredientes removidos</h2>
                                                        @endif

                                                        <div class="my-4">
                                                            <strong>
                                                                {{$item_product->product()->first()->name}}
                                                            </strong>
                                                        </div>

                                                        <ul class="list-unstyled">
                                                            @foreach($item_product->ingredients_removed()->get() as $removed)
                                                            <li><i class="mdi mdi-close-circle"></i>{{$removed->ingredient()->first()->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                    @if($orderItem->order_item_products()->count() > 0)
                                                    <div class="additionals">
                                                        @foreach($orderItem->order_item_products()->get() as $k => $item_product)
                                                        @if($item_product->product()->count() > 0)

                                                        @if($item_product->additional_ingredients()->count() > 0)

                                                        @if($k == 0)
                                                        <h2 class="title">Adicionais</h2>
                                                        @endif

                                                        <div class="my-4">
                                                            <strong>
                                                                {{$item_product->product()->first()->name}}
                                                            </strong>
                                                        </div>

                                                        <ul class="list-unstyled">
                                                            @foreach($item_product->additional_ingredients()->get() as $additional)
                                                            <li><i class="mdi mdi-check-circle"></i>{{$additional->ingredient()->first()->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                </div>
                                            </li>
                                            @endif

                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="values">

                                    <div class="subtotal">
                                        <strong>Subtotal:</strong>
                                        <span>R$ {{number_format($order->subtotal, 2, ',', '.')}}</span>
                                    </div>

                                    <div class="shipping">
                                        <strong>Entrega:</strong>
                                        <span>R$ {{number_format($order->shipping, 2, ',', '.')}}</span>
                                    </div>

                                    <div class="shipping">
                                        <strong>Descontos:</strong>
                                        <span>R$ {{number_format($order->discount, 2, ',', '.')}}</span>
                                    </div>

                                    <div class="total">
                                        <strong>Total:</strong>
                                        <span>R$ {{number_format($order->total, 2, ',', '.')}}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- .list -->

        </div> <!-- .orders-list -->
    </div>

</div>

@endsection