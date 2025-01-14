@extends('master')

@section('content')

<div class="order page-order">

    <div class="container">

        <div class="infos">
            <div class="header-product">
                <div>
                    <a href="{{route('orders')}}"><i class="mdi mdi-arrow-left"></i></a>
                    Detalhes do Pedidos
                </div>
            </div>

            <div class="orders-list">

                <div class="list">

                    <div class="row">
                        <div class="col-12 col-md-6 mb-5">
                            <div class="item">
                                <div class="wrapper">

                                    <div class="header">
                                        <div>
                                            <div class="code">
                                                Pedido #{{$order->code}}
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

                                        <div id="status-{{$order->id}}">
                                            <div class="status -{{$order->status}}">
                                                <span>{{$status[$order->status]}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="order-statuses">
                                        @include('components.order-statuses', ['order' => $order, 'orderStatuses' => $orderStatuses])
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
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span>{{$orderItem->quantity}}x {{$orderItem->name}}</span>
                                                        <strong>R$ {{number_format($orderItem->total, 2, ',', '.')}}</strong>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="d-flex">

                                                        @if($orderItem->order_item_products()->count() > 0)
                                                        <div class="ingredients-removeds">
                                                            <h2 class="title">Ingredientes removidos</h2>
                                                            @foreach($orderItem->order_item_products()->get() as $item_product)
                                                            @if($item_product->product()->count() > 0)
                                                            <div class="my-4">
                                                                <strong>
                                                                    {{$item_product->product()->first()->name}}
                                                                </strong>
                                                            </div>

                                                            @if($item_product->ingredients_removed()->count() > 0)
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
                                                            <h2 class="title">Adicionais</h2>
                                                            @foreach($orderItem->order_item_products()->get() as $item_product)
                                                            @if($item_product->product()->count() > 0)
                                                            <div class="my-4">
                                                                <strong>
                                                                    {{$item_product->product()->first()->name}}
                                                                </strong>
                                                            </div>

                                                            @if($item_product->additional_ingredients()->count() > 0)
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

</div>

@endsection