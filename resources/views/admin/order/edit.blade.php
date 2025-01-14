@extends('admin.master')

@section('head')
<meta name="deliveryValue" content="{{$deliveryValue}}">
@endsection

@section('stylesheets')
@vite(['resources/css/orders.css'])
@endsection

@section('content')

@if($errors->any())

@dd($errors->all())

@endif

<input id="initial-cart" type="hidden" value="{{json_encode($cart)}}">

<div class="app-container create-orders edit-orders">
    <div class="tools">
        <h2 class="title">Edição do pedido #{{$order->code}}</h2>
        <div style="font-size: 1.5rem;">
            <strong>Debug do carrinho</strong>
            <pre id="debug-cart"></pre>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="wrapper">

                <div class="delivery-client">
                    <div class="client">
                        <div class="field-whatsapp">
                            {{$order->client->phone}}
                        </div>

                        <div class="field-name">
                            {{$order->client->name}}
                        </div>
                    </div>

                    <div class="errorclient"></div>

                    <div class="delivery-options">
                        <label type="button" class="item {{$order->delivery_method == 'delivery' ? '-checked' : ''}}" for="delivery">
                            <input type="radio" name="delivery-method" class="d-none" id="delivery" value="delivery" class="type-method" {{$order->delivery_method == 'delivery' ? 'checked' : ''}}>
                            <i class="mdi mdi-motorbike"></i>
                            Entrega
                        </label>

                        <label type="button" class="item {{$order->delivery_method == 'withdrawal' ? '-checked' : ''}}" for="withdrawal">
                            <input type="radio" name="delivery-method" class="d-none" id="withdrawal" value="withdrawal" class="type-method" {{$order->delivery_method == 'withdrawal' ? 'checked' : ''}}>
                            <i class="mdi mdi-walk"></i>
                            Retirada
                        </label>

                        <label type="button" class="item {{$order->delivery_method == 'comeget' ? '-checked' : ''}}" for="comeget">
                            <input type="radio" name="delivery-method" class="d-none" id="comeget" value="comeget" class="type-method" {{$order->delivery_method == 'comeget' ? 'checked' : ''}}>
                            <i class="mdi mdi-storefront"></i>
                            Balcão
                        </label>
                    </div> <!-- .delivery-options -->
                </div> <!--.delivery-client -->

                <div id="list-clients" class="list-clients"></div>

                <div class="resume-address">
                    <div>
                        <span class="address"></span>
                    </div>

                    <a href="#" id="new-address" class="new-address">Outro endereço</a>
                </div> <!-- .resume-address -->

                <div class="items-container">
                    <div class="header">
                        <div>
                            <i class="mdi mdi-cart-plus"></i>
                            Adicionar Item
                        </div>

                        <form id="form-product" class="form-product" action="{{route('search.products.list')}}" method="get">
                            <input type="text" id="product" name="product" placeholder="Nome do item" autocomplete="off">

                            <div class="errorflavor"></div>
                        </form>
                    </div> <!-- .header -->
                </div> <!-- .items-container -->

                <div class="position-relative">
                    <div id="list-products" class="list-products"></div>
                </div>

                <div id="customizable-items">
                    @include('components.products.customizable-items.empty')
                </div>

                <div class="observations">
                    <div class="to-deliveryman">
                        <textarea name="comments_deliveryman" id="comments_deliveryman" rows="5" placeholder="Observações para o Entregador">{{$order->comments_deliveryman}}</textarea>
                    </div>

                    <div class="to-order" id="to-order">
                        <textarea name="comments" id="comments" rows="5" placeholder="Observações do Pedido">{{$order->comments}}</textarea>
                    </div>
                </div> <!-- .observations -->

                <div class="button">
                    <a href="{{route('dashboard')}}">Cancelar Pedido</a>
                </div>

            </div> <!-- .wrapper -->
        </div> <!--- Coluna do pedido -->

        <div class="col-12 col-md-4">
            <div class="wrapper">
                <div class="order-resume">
                    <div class="header">
                        Itens do pedido
                    </div>

                    <div class="table">
                        <table class="table-items">

                            <!--
<thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qtd</th>
                                    <th>Preço</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($order->order_items as $k => $item)
                                <tr class="item" data-item-id="{{$k}}">
                                    <td> <a data-id="{{$k}}" id="remove-item" class="remove-item"><i class="mdi mdi-delete"></i></a> {{$item->name}}</td>
                                    <td class="text-center"><input data-id="{{$k}}" id="update-quantity" min="1" class="update-quantity" type="number" value="{{$item->quantity}}" /></td>
                                    <td>{{number_format($item->unitary_value, 2, ',', '.')}}</td>
                                    <td>{{number_format($item->total, 2, ',', '.')}}</td>
                                </tr>
                                @endforeach
                            </tbody>                        
-->

                        </table>
                        <div class="details">
                            <div class="delivery">
                                <strong>Taxa de entrega</strong>
                                <span>R$ <input type="number" name="delivery-value" id="delivery-value" class="discounts" min="0" value="{{$order->shipping}}"></span>
                            </div> <!-- .delivery -->

                            <div class="discount">
                                <strong>
                                    Desconto
                                    <select name="discount_type" class="discount_type" id="discount_type">
                                        <option value="value">R$</option>
                                        <option value="percent">%</option>
                                    </select>
                                </strong>
                                <span><input type="number" name="discounts" id="discounts" class="discounts" min="0" value="{{$order->discount ?? 0}}"></span>
                            </div> <!-- .discount -->
                        </div> <!-- .details -->

                        <div id="total-order" class="total">
                            Total do Pedido: R$ {{number_format($order->total, 2, ',', '.')}}
                        </div> <!-- .total -->

                        <div class="form-payments">
                            <label type="button" class="item {{$order->payment == 'pix' ? '-checked' : ''}}" for="cash">
                                <input type="radio" name="payment-form" class="d-none" id="cash" value="cash" class="type-method" {{$order->payment == 'pix' ? 'checked' : ''}}>
                                <i class="mdi mdi-cash"></i>
                                Pix
                            </label>

                            <label type="button" class="item {{$order->payment == 'cash' ? '-checked' : ''}}" for="cash">
                                <input type="radio" name="payment-form" class="d-none" id="cash" value="cash" class="type-method" {{$order->payment == 'cash' ? 'checked' : ''}}>
                                <i class="mdi mdi-cash"></i>
                                Dinheiro
                            </label>

                            <label type="button" class="item {{$order->payment == 'credit' ? '-checked' : ''}}" for="credit-card">
                                <input type="radio" name="payment-form" class="d-none" id="credit-card" value="credit-card" class="type-method" {{$order->payment == 'credit' ? 'checked' : ''}}>
                                <i class="mdi mdi-credit-card"></i>
                                Cartão <br> Crédito
                            </label>

                            <label type="button" class="item {{$order->payment == 'debit' ? '-checked' : ''}}" for="debit-card">
                                <input type="radio" name="payment-form" class="d-none" id="debit-card" value="debit-card" class="debit-card" {{$order->payment == 'debit' ? 'checked' : ''}}>
                                <i class="mdi mdi-credit-card"></i>
                                Cartão <br> Débito
                            </label>
                        </div> <!-- .form-payments -->

                        <div class="clear-button"></div>

                        <form action="{{route('order.update', $order->id)}}" method="post" id="adminOrderCreate">
                            @csrf()
                            @method('PUT')

                            <div id="order-fields"></div>

                            <div class="save-button">
                                <button>
                                    <i class="mdi mdi-check-circle"></i>
                                    Editar Pedido
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!--- Item do pedido -->
    </div>
</div>

<div id="modal-address" class="modal modal-address" tabindex="-1">
    <div class="overlayer">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dados de endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs tabs-resume-orders" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="client-tab" data-bs-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Cliente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="address-tab" data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="true">Endereço</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="historic-tab" data-bs-toggle="tab" href="#historic" role="tab" aria-controls="historic" aria-selected="false">Histórico</a>
                        </li>
                    </ul>
                    <div class="tab-content tabs-data-clients" id="myTabContent">
                        <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
                            @include('components.modal-admin.tabs.client', ['client' => $client])
                        </div>
                        <div class="tab-pane fade show active" id="address" role="tabpanel" aria-labelledby="address-tab">
                            @include('components.modal-admin.tabs.address', ['client' => $client, 'addresses' => $addresses])
                        </div>
                        <div class="tab-pane fade" id="historic" role="tabpanel" aria-labelledby="historic-tab">
                            @include('components.modal-admin.tabs.historic', ['client' => $client, 'orders' => $orders, 'firstRequest' => $firstRequest, 'lastRequest' => $lastRequest, 'total' => $total])
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>

@include('components.modal-admin.modal-address-delete')
@include('components.modal-admin.modal-new-flavor')

@endsection

@section('scripts')
@vite(['resources/js/edit-orders.js'])
@endsection