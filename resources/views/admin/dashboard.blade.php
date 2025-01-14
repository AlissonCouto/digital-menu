@extends('admin.master')

@section('content')
<div class="toolbar d-flex align-items-center justify-content-end">
    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')
</div><!-- .toolbar -->

<div class="header">
    <form action="#" method="get" class="form-search">
        <input type="text" name="search" placeholder="Busque por Cliente OU Código do pedido" class="input-search">
        <button type="submit" class="button-search"><i class="mdi mdi-magnify"></i></button>
    </form>

    <div class="menu">
        <a href="{{route('order.new')}}" class="new-order">
            <i class="mdi mdi-cart-plus"></i>
            Novo Pedido
        </a>
    </div>
</div> <!-- .header -->

<div class="app-container orders" id="order-lanes">
    @include('admin.components.order-lanes', ['inanalysis' => $inanalysis, 'inproduction' => $inproduction, 'delivery' => $delivery])
</div> <!-- .orders -->
@endsection