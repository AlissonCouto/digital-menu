@if($entity->count() > 0)

@php

$status = [
'realized' => 'Realizado',
'inanalysis' => 'Em análise',
'inproduction' => 'Em Produção',
'ready' => 'Pronto',
'closed' => 'Concluído',
'rejected' => 'Rejeitado',
'canceled' => 'Cancelado'
];

@endphp

@foreach($entity as $order)
<div class="col-12 col-md-6 col-lg-4 mb-4">

    <div class="item">

        <div class="wrapper">

            <div class="header">
                <div>
                    <div class="code">
                        Pedido #{{$order->code}}
                    </div>

                    <div class="client"><strong>Cliente: </strong> {{$order->client['name']}}</div>

                    <div class="date">
                        Em {{$order->date->format('d/m/Y')}} às {{$order->date->format('H:i:s')}}
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