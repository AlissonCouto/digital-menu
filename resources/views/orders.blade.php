@extends('master')

@section('content')

<div class="orders">

    <div class="container">

        <div class="infos">
            <div class="header-product">
                <div>
                    <a href="{{route('welcome')}}"><i class="mdi mdi-arrow-left"></i></a>
                    Meus Pedidos
                </div>
            </div>

            <div class="orders-list">

                <div class="list">

                    <div class="row">
                        @if($orders->count() > 0)
                        @foreach($orders as $order)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">

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

                                    <div class="items">
                                        <ul class="list-unstyled">
                                            @foreach($order->order_items()->get() as $orderItem)
                                            <li>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span>{{$orderItem->quantity}}x {{$orderItem->name}}</span>
                                                    <strong>R$ {{number_format($orderItem->total, 2, ',', '.')}}</strong>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div>
                                        <div class="price">R$ {{number_format($order->total, 2, ',', '.')}}</div>

                                        <div class="button">
                                            <a href="{{route('order', $order->id)}}" class="details">Detalhes</a>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                        @endforeach
                        @else

                        <div class="col-12">
                            <div class="item">

                                <div class="wrapper">

                                    <div class="header">
                                        <div>
                                            <div class="code">
                                                Nenhum pedido encontrado.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endif
                    </div>

                </div> <!-- .list -->

            </div> <!-- .orders-list -->

        </div>
    </div>

</div>

@endsection