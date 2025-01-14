@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes do Cupons: {{$coupon->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{$coupon->name}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <textarea disabled name="description" id="description">{{$coupon->description}}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">

                            <div class="form-floating">
                                <select disabled id="discount_type" class="form-control" name="discount_type">
                                    <option value="value" {{$coupon->discount_type == 'value' ? 'selected' : ''}}>Valor R$</option>
                                    <option value="percent" {{$coupon->discount_type == 'percent' ? 'selected' : ''}}>Percentual %</option>
                                </select>
                                <label for="discount_type">Tipo de desconto</label>
                            </div>

                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="value" id="value" class="form-control priceMask" value="{{number_format($coupon->value, 2, ',', '.')}}">
                                <label for="value">Valor</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">

                            <div class="form-floating">
                                <select disabled id="validity_type" class="form-control" name="validity_type">
                                    <option value="usage_limit" {{$coupon->validity_type == 'usage_limit' ? 'selected' : ''}}>Limite de uso</option>
                                    <option value="deadline" {{$coupon->validity_type == 'deadline' ? 'selected' : ''}}>Data Limite</option>
                                </select>
                                <label for="validity_type">Tipo de validade</label>
                            </div>

                        </div>

                        @if($coupon->validity_type == 'deadline')
                        <div id="fields-deadline" class="col-12">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-5">
                                    <div class="form-floating">
                                        <input disabled type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{$coupon->expiration_date}}">
                                        <label for="expiration_date">Data Limite</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-5">
                                    <div class="form-floating">
                                        <input disabled type="time" name="expiry_time" id="expiry_time" class="form-control" value="{{$coupon->expiry_time}}">
                                        <label for="expiry_time">Hora Limite</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($coupon->validity_type == 'usage_limit')
                        <div id="fields-usage-limit">
                            <div class="col-12 mb-5">
                                <div class="form-floating">
                                    <input disabled type="number" name="usage_limit" id="usage_limit" class="form-control " value="{{$coupon->usage_limit}}">
                                    <label for="usage_limit">Limite de uso</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{ $coupon->active == 1 ? 'Sim' : 'Não' }}">
                                <label for="name">Ativo?</label>
                            </div>
                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('coupons.index') }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection