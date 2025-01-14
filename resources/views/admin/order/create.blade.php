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

<div class="app-container create-orders">
    <div class="tools">
        <h2 class="title">Registro de pedidos</h2>

        <!--
        <div style="font-size: 1.5rem;">
            <strong>Debug do carrinho</strong>
            <pre id="debug-cart"></pre>
        </div>        
    -->

    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="wrapper">

                <div class="delivery-client">
                    <div class="delivery-options">
                        <label type="button" class="item -checked" for="delivery">
                            <input type="radio" name="delivery-method" class="d-none" id="delivery" value="delivery" class="type-method" checked>
                            <i class="mdi mdi-motorbike"></i>
                            Entrega
                        </label>

                        <label type="button" class="item" for="withdrawal">
                            <input type="radio" name="delivery-method" class="d-none" id="withdrawal" value="withdrawal" class="type-method">
                            <i class="mdi mdi-walk"></i>
                            Retirada
                        </label>

                        <label type="button" class="item" for="comeget">
                            <input type="radio" name="delivery-method" class="d-none" id="comeget" value="comeget" class="type-method">
                            <i class="mdi mdi-storefront"></i>
                            Balcão
                        </label>
                    </div> <!-- .delivery-options -->

                    <form id="searchClient" method="get" action="{{route('client.search')}}">
                        <div class="client">
                            <div class="whatsapp">
                                <i class="mdi mdi-user"></i>
                                <input type="text" placeholder="Whatsapp" id="whatsapp">
                            </div>

                            <div class="name">
                                <input type="text" placeholder="Nome do cliente" id="name" autocomplete="off">
                            </div>
                        </div>

                        <div class="errorclient"></div>
                    </form>
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
                        <textarea name="comments_deliveryman" id="comments_deliveryman" rows="5" placeholder="Observações para o Entregador"></textarea>
                    </div>

                    <div class="to-order" id="to-order">
                        <textarea name="comments" id="comments" rows="5" placeholder="Observações do Pedido"></textarea>
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
                        <table class="table-items"></table>
                        <div class="details">
                            <div class="delivery">
                                <strong>Taxa de entrega</strong>
                                <span>R$ <input type="number" name="delivery-value" id="delivery-value" class="discounts" min="0" value="{{$deliveryValue}}"></span>
                            </div> <!-- .delivery -->

                            <div class="discount">
                                <strong>
                                    Desconto
                                    <select name="discount_type" class="discount_type" id="discount_type">
                                        <option value="value">R$</option>
                                        <option value="percent">%</option>
                                    </select>
                                </strong>
                                <span><input type="number" name="discounts" id="discounts" class="discounts" min="0" value="0"></span>
                            </div> <!-- .discount -->
                        </div> <!-- .details -->

                        <div id="total-order" class="total">
                            Total do Pedido: R$ 0,00
                        </div> <!-- .total -->

                        <div class="form-payments">
                            <label type="button" class="item -checked" for="cash">
                                <input type="radio" name="payment-form" class="d-none" id="cash" value="cash" class="type-method" checked>
                                <i class="mdi mdi-cash"></i>
                                Dinheiro
                            </label>

                            <label type="button" class="item" for="credit-card">
                                <input type="radio" name="payment-form" class="d-none" id="credit-card" value="credit-card" class="type-method">
                                <i class="mdi mdi-credit-card"></i>
                                Cartão <br> Crédito
                            </label>

                            <label type="button" class="item" for="debit-card">
                                <input type="radio" name="payment-form" class="d-none" id="debit-card" value="debit-card" class="debit-card">
                                <i class="mdi mdi-credit-card"></i>
                                Cartão <br> Débito
                            </label>
                        </div> <!-- .form-payments -->

                        <div class="clear-button"></div>

                        <form action="{{route('admin.order.create')}}" method="post" id="adminOrderCreate">
                            @csrf()

                            <div id="order-fields"></div>

                            <div class="save-button">
                                <button>
                                    <i class="mdi mdi-check-circle"></i>
                                    Salvar Pedido
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!--- Item do pedido -->
    </div>
</div>

@include('components.modal-admin.modal-address')
@include('components.modal-admin.modal-address-delete')
@include('components.modal-admin.modal-new-flavor')

@endsection

@section('scripts')
@vite(['resources/js/orders.js'])
@endsection