@extends('admin.master')

@php

$statuses = [
'realized' => 'Realizado',
'inanalysis' => 'Em análise',
'inproduction' => 'Em Produção',
'ready' => 'Pronto',
'closed' => 'Concluído',
'rejected' => 'Rejeitado',
'canceled' => 'Cancelado'
];

$timezone = new \DateTimeZone('America/Sao_Paulo');

@endphp

@section('content')
<div class="toolbar d-flex align-items-center justify-content-end">
    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')
</div><!-- .toolbar -->

@if(session()->has('success'))
<div class="col-12 m-b-5">
    <div class="popup popup-{{ session('success')['success'] ? 'success' : 'danger' }}">
        {{ session('success')['message'] }}
    </div>
</div>
@endif

<div class="table-container">

    <div class="header-table">

        <div class="meta-infos">

            <h2 class="title">Pedidos <span id="entity-quantity">({{ $total }})</span></h2>

            <div class="filter">

                <form class="search-container" action="{{route('orders.consult')}}" method="post" id="search-entity" data-entity="orders">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Pesquisar" name="search" id="search">

                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <a href="{{route('order.new')}}" class="new-button">Novo</a>

            </div>

        </div> <!-- .meta-infos -->

        <div class="pagination">
            <div class="from">{{ $entity->firstItem() }}</div> -
            <div class="to">{{ $entity->lastItem() }}</div>
            <span>de</span>
            <div class="total">{{ $entity->total() }}</div>

            <div class="navigations">
                <a href="#" class="prev {{ $entity->onFirstPage() ? '-inactive' : '' }}" data-page="{{ $entity->currentPage() - 1 }}">
                    <i class="mdi mdi-chevron-left icon"></i>
                </a>

                <a href="#" class="next {{ $entity->hasMorePages() ? '' : '-inactive' }}" data-page="{{ $entity->currentPage() + 1 }}">
                    <i class="mdi mdi-chevron-right icon"></i>
                </a>
            </div>
        </div> <!-- pagination -->

    </div> <!-- .header-table -->

    <div class="header-table">

        <div class="meta-infos">

            <div class="filter">

                <div class="form-floating">
                    <select id="categories" class="form-control" name="status">
                        <option value="" selected>Todos...</option>

                        @foreach($statuses as $k => $v)
                        <option value="{{$k}}">{{$v}}</option>
                        @endforeach
                    </select>
                    <label for="categories">Status</label>
                </div>

            </div>

        </div>

    </div>

    <div class="body-table">

        <div class="orders-list">

            <div class="list">

                <div class="row">
                    @if($entity->count() > 0)
                    @foreach($entity as $order)
                    <div class="col-12 col-md-6 col-lg-4 mb-4">

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

                                <div class="edit">
                                    <a href="{{route('order.edit', $order->id)}}">Editar Pedido</a>
                                </div>

                                <div>
                                    <div class="price">R$ {{number_format($order->total, 2, ',', '.')}}</div>

                                    <div class="actions button d-flex align-items-center">
                                        <a href="{{route('orders.destroy', $order->id)}}" class="link-delete"><i class="mdi mdi-delete"></i></a>
                                        <a href="{{route('orders.show', $order->id)}}" class="details">Detalhes</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    @endforeach
                    @else

                    <table class="table responsive-table table-striped">
                        <tbody>

                            <tr>
                                <td colspan="6" class="text-center pt-5">
                                    <h1 class="h1">Nenhum pedido encontrado</h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @endif
                </div>

            </div> <!-- .list -->

        </div> <!-- .orders-list -->

    </div>

</div>

@endsection