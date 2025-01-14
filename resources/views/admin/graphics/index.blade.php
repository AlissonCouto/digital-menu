@extends('admin.master')

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

<div class="table-container charts">

    <div class="header-table">

        <div class="meta-infos">

            <h2 class="title">Relatórios</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="body-table">

        <div class="container-fluid per-year">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <h2 class="h2">Histórico</h2>
                        <div class="form-floating">
                            <select name="history" class="form-control">
                                <option>Faturamento</option>
                                <option>Novos Pedidos</option>
                                <option>Pedidos cancelados</option>
                                <option>Visitas</option>
                                <option>Ticket médio</option>
                                <option>Clientes cadastrados</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="years">

                        <canvas id="history" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                    </div> <!-- .years -->
                </div>
            </div>
        </div> <!-- .history -->

        <div class="container-fluid">

            <div class="row lines">

                <div class="col-12 per-month">
                    <div class="d-flex align-items-center">
                        <h2 class="h2">Resultados do mês</h2>
                        <div class="fields">
                            <input type="month">
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Faturamento
                            </h2>
                        </div>

                        <div class="invoicing">

                            <canvas id="invoicing" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Ticket Médio
                            </h2>
                        </div>

                        <div class="average-ticket">

                            <canvas id="average-ticket" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>
            </div>

            <div class="row lines">

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Pedidos completos
                            </h2>
                        </div>

                        <div class="completed-orders">

                            <canvas id="completed-orders" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Pedidos por hora
                            </h2>
                        </div>

                        <div class="orders-per-hour">

                            <canvas id="orders-per-hour" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>
            </div>

            <div class="row lines">

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Pedidos por forma de pagamento
                            </h2>
                        </div>

                        <div class="orders-by-payment-method">

                            <canvas id="orders-by-payment-method" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Pedidos por dia da semana
                            </h2>
                        </div>

                        <div class="orders-by-day-of-the-week">

                            <canvas id="orders-by-day-of-the-week" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>
            </div>

            <div class="row lines">

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Pedidos por novos clientes
                            </h2>
                        </div>

                        <div class="orders-from-new-customers">

                            <canvas id="orders-from-new-customers" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="item">
                        <div class="header">
                            <h2 class="title">
                                Pedidos por forma de entrega
                            </h2>
                        </div>

                        <div class="orders-by-delivery-method">

                            <canvas id="orders-by-delivery-method" style="max-width: 100%; max-height: 300px;"></canvas> <!-- ID precisa ser o mesmo -->

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- .months -->

    </div>

</div>

@endsection