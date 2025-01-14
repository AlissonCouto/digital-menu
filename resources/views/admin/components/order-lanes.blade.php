@php

$formspayment = [
'credit' => 'Crédito',
'debit' => 'Débito',
'cash' => 'Dinheiro',
'pix' => 'PIX'
];

@endphp

<div class="row">
    <div class="col-12 col-md-4">
        <div class="wrapper inanalysis" id="inanalysis">
            <div class="header">
                <h2 class="title"><i class="mdi mdi-cart-plus"></i>Novos pedidos</h2>
                <span class="total">{{$inanalysis->count()}}</span>
            </div>

            <div class="listing" id="listing">
                @foreach($inanalysis as $order)
                @include('admin.components.order', ['order' => $order, 'status' => 'inproduction'])
                @endforeach
            </div>
        </div>
    </div> <!-- Novos pedidos -->

    <div class="col-12 col-md-4">
        <div class="wrapper inprogess">
            <div class="header">
                <h2 class="title"><i class="mdi mdi-progress-clock"></i>Em produção</h2>
                <span class="total">{{$inproduction->count()}}</span>
            </div>

            <div class="listing ">
                @foreach($inproduction as $order)
                @include('admin.components.order', ['order' => $order, 'status' => 'ready'])
                @endforeach
            </div>
        </div>
    </div> <!-- Em produção -->

    <div class="col-12 col-md-4">
        <div class="wrapper closed">
            <div class="header">
                <h2 class="title"><i class="mdi mdi-check-circle"></i>Pedidos prontos</h2>
                <span class="total">{{$delivery->count()}}</span>
            </div>

            <div class="listing ">
                @foreach($delivery as $order)
                @include('admin.components.order', ['order' => $order, 'status' => 'closed'])
                @endforeach
            </div>
        </div>
    </div> <!-- Pedidos prontos -->
</div>