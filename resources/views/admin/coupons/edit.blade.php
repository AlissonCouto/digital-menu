@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Editar Cupom: {{$coupon->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            @if(session()->has('success'))
            <div class="col-12 m-b-5">
                <div class="alert alert-{{ session('success')['success'] ? 'success' : 'danger' }}">
                    {{ session('success')['message'] }}
                </div>
            </div>
            @endif

            <div class="col-12">
                <form class="form-default" action="{{route('coupons.update', $coupon->id)}}" method="post" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')
                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name') ?? $coupon->name}}">
                                <label for="name">Nome</label>
                            </div>
                            @error('name')
                            <span class="field-error mt-2 mb-3">{{$errors->first('name')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <textarea name="description" id="description">{{old('description') ?? $coupon->description}}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                            @error('description')
                            <span class="field-error mt-2 mb-3">{{$errors->first('description')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">

                            <div class="form-floating">
                                <select id="discount_type" class="form-control @error('discount_type') is-invalid @enderror" name="discount_type">
                                    @php
                                    $currentDiscountType = old('discount_type') ?? $coupon->discount_type;
                                    @endphp
                                    <option value="value" {{$currentDiscountType == 'value' ? 'selected' : ''}}>Valor R$</option>
                                    <option value="percent" {{$currentDiscountType == 'percent' ? 'selected' : ''}}>Percentual %</option>
                                </select>
                                <label for="discount_type">Tipo de desconto</label>
                            </div>
                            @error('discount_type')
                            <span class="field-error mt-2 mb-3">{{$errors->first('discount_type')}}</span>
                            @enderror

                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="text" name="value" id="value" class="form-control priceMask @error('value') is-invalid @enderror" value="{{number_format(old('value') ?? $coupon->value, 2, ',', '.')}}">
                                <label for="value">Valor</label>
                            </div>
                            @error('value')
                            <span class="field-error mt-2 mb-3">{{$errors->first('value')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">

                            <div class="form-floating">
                                <select id="validity_type" class="form-control @error('validity_type') is-invalid @enderror" name="validity_type">
                                    @php
                                    $currentValidityType = old('validity_type') ?? $coupon->validity_type;
                                    @endphp
                                    <option value="usage_limit" {{$currentValidityType == 'usage_limit' ? 'selected' : ''}}>Limite de uso</option>
                                    <option value="deadline" {{$currentValidityType == 'deadline' ? 'selected' : ''}}>Data Limite</option>
                                </select>
                                <label for="validity_type">Tipo de validade</label>
                            </div>
                            @error('validity_type')
                            <span class="field-error mt-2 mb-3">{{$errors->first('validity_type')}}</span>
                            @enderror

                        </div>

                        <div id="fields-deadline" class="col-12 {{ $currentValidityType == 'usage_limit' ? 'd-none' : '' }}">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-5">
                                    <div class="form-floating">
                                        <input type="date" name="expiration_date" id="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror" value="{{old('expiration_date') ?? $coupon->expiration_date}}">
                                        <label for="expiration_date">Data Limite</label>
                                    </div>
                                    @error('expiration_date')
                                    <span class="field-error mt-2 mb-3">{{$errors->first('expiration_date')}}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6 mb-5">
                                    <div class="form-floating">
                                        <input type="time" name="expiry_time" id="expiry_time" class="form-control @error('expiry_time') is-invalid @enderror" value="{{old('expiry_time') ?? $coupon->expiry_time}}">
                                        <label for="expiry_time">Hora Limite</label>
                                    </div>
                                    @error('expiry_time')
                                    <span class="field-error mt-2 mb-3">{{$errors->first('expiry_time')}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="fields-usage-limit" class="{{ $currentValidityType == 'deadline' ? 'd-none' : '' }}">
                            <div class="col-12 mb-5">
                                <div class="form-floating">
                                    <input type="number" name="usage_limit" id="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" value="{{old('usage_limit') ?? $coupon->usage_limit}}">
                                    <label for="usage_limit">Limite de uso</label>
                                </div>
                                @error('usage_limit')
                                <span class="field-error mt-2 mb-3">{{$errors->first('usage_limit')}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mb-5">

                            <div class="form-floating">
                                @php
                                $currentActive = old('active') ?? $coupon->active;
                                @endphp
                                <select id="active" class="form-control @error('active') is-invalid @enderror" name="active">
                                    <option value="1" {{ $currentActive == 1 ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ $currentActive == 0 ? 'selected' : '' }}>Não</option>
                                </select>
                                <label for="active">Ativo?</label>
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('coupons.index') }}" class="c-button btn-default">Voltar</a>
                                <button type="submit" class="c-button btn-save">Salvar</button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

@endsection