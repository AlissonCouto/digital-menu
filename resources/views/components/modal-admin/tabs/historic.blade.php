@isset($orders)
@if($orders->count() > 0)
<div class="row historic">
    <div class="col-12">
        <div class="historic-header">
            <div class="client">
                Histórico de {{$client->name}} ({{$orders->count() . ' Pedidos'}})
            </div> <!-- .client -->

            <div class="dates">
                <div class="item">
                    <strong>Primeiro pedido:</strong>
                    <span>{{$firstRequest->date->format('d/m/Y H:i:s')}}</span>
                </div> <!-- .item -->

                <div class="item">
                    <strong>Último pedido:</strong>
                    <span>{{$lastRequest->date->format('d/m/Y H:i:s')}}</span>
                </div> <!-- .item -->

                <div class="item">
                    <strong>Total gasto:</strong>
                    <span>R$ {{number_format($total, 2, ',', '.')}}</span>
                </div> <!-- .item -->

                <div class="item">
                    <strong>Cadastrado em:</strong>
                    <span>{{$client->created_at->format('d/m/Y H:i:s')}}</span>
                </div> <!-- .item -->
            </div> <!-- .dates -->
        </div> <!-- .historic-header -->


        <div class="historic-body">
            <table class="historic-table">

                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Nº Pedido</th>
                        <th>Valor</th>
                        <th>Origem</th>
                        <th>Entrega</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{$order->date->format('d/m/Y H:i:s')}}</td>
                        <td>{{$order->code}}</td>
                        <td>R$ {{number_format($order->total, 2, ',', '.')}}</td>
                        <td>{{$order->origin == 'attendant' ? 'Atendente' : 'Cliente'}}</td>
                        <td>

                            @php
                            $delivery_method = '';

                            switch($order->delivery_method){
                            case 'withdrawal':
                            $delivery_method = 'Retirada';
                            break;

                            case 'comeget':
                            $delivery_method = 'Consumir no local';
                            break;

                            default:
                            $delivery_method = 'Entrega';
                            }
                            @endphp

                            {{$delivery_method}}
                        </td>
                        <td>
                            <div class="status -{{$order->status}}">

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
                                <span>{{$status[$order->status]}}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div> <!-- .historic-body -->
    </div>
</div>
@endif
@else
<div class="row historic">
    <div class="col-12">
        <div class="historic-header">
            <div class="client">
                Selecione um cliente para visualizar os dados.
            </div> <!-- .client -->
        </div>
    </div>
</div>
@endisset