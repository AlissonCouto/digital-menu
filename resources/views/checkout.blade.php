@php

$userLogged = auth('client')->check();

$systemaddress = [
[
'id' => 'withdrawal',
'name' => 'Retirar no estabelecimento',
'street' => 'AV.dourados',
'number' => 356,
'neighborhood' => 'Centro',
'city' => 'Naviraí',
'uf' => 'MS'
],
[
'id' => 'comeget',
'name' => 'Consumir no local',
'street' => 'AV.dourados',
'number' => 356,
'neighborhood' => 'Centro',
'city' => 'Naviraí',
'uf' => 'MS'
]
];

$payments = [
[
'id' => 1,
'icon' => 'pix.png',
'name' => 'Pix'
],
[
'id' => 2,
'icon' => 'cash.png',
'name' => 'Dinheiro'
],
[
'id' => 3,
'icon' => 'credit-card.png',
'name' => 'Cartão'
]
];

// Dados do usuário
$whatsapp = '';
$name = '';

if($userLogged){

$user = auth('client')->user();

$whatsapp = $user->phone;
$name = $user->name;

$clientaddress = $user->addresses()->get();
}

@endphp

@extends('master')

@section('content')

@if($errors->any())
@dd($errors->all());
@endif

<div class="checkout">

    <form id="clientOrderCreate" class="container" method="post" action="{{route('client.order.create')}}">

        @csrf()

        <div class="infos">
            <div class="header-product">
                <div>
                    <a href="{{route('cart')}}"><i class="mdi mdi-arrow-left"></i></a>
                    Finalizar compra
                </div>
            </div>

            <div class="form-checkout">

                @if(!$userLogged)
                <div class="mt-5 card-call-login">
                    <h2 class="title mb-5">Já tem uma conta?</h2>
                    <a href="{{route('client.login')}}">Faça login</a>
                </div>
                @endif

                <h2 class="title mb-5">Registre-se</h2>

                <h2 class="title">Dados pessoais</h2>

                <div class="form-floating">
                    <input id="whatsapp" type="text" name="phone" class="form-control phoneMask" value="{{$whatsapp}}">
                    <label for="whatsapp">Seu Whatsapp</label>
                </div>
                <span class="field-error mb-3"></span>

                <div class="form-floating mb-3">
                    <input id="name" type="text" name="name" class="form-control" value="{{$name}}">
                    <label for="name">Seu nome</label>
                </div>
                <span class="field-error mb-3"></span>

                <div class="form-floating mb-3">
                    <input id="password" type="password" name="password" class="form-control">
                    <label for="password">Sua senha</label>
                </div>
                <span class="field-error mb-3"></span>

                <div class="adresses">

                    @if($userLogged)
                    <h2 class="title">Dados de endereço</h2>
                    <a type="button" class="add-address" href="{{route('address')}}">Novo endereço</a>
                    @else

                    <div class="form-checkout w-100">

                        <h2 class="title">Informe seu endereço</h2>

                        <div class="row">
                            <div class="col-12 col-md-8">
                                <div class="form-floating">
                                    <input id="street" type="text" name="street" class="form-control">
                                    <label for="street">Rua</label>
                                </div>
                                <span class="field-error mb-3"></span>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-floating">
                                    <input id="number" type="text" name="number" class="form-control">
                                    <label for="number">Número</label>
                                </div>
                                <span class="field-error mb-3"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input id="neighborhood" type="text" name="neighborhood" class="form-control">
                                    <label for="neighborhood">Bairro</label>
                                </div>
                                <span class="field-error mb-3"></span>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input id="reference" type="text" name="reference" class="form-control">
                                    <label for="reference">Ponto de Referência</label>
                                </div>
                                <span class="field-error mb-3"></span>
                            </div>
                        </div>

                    </div>

                    @endif
                </div>

                <div class="list-adresses">

                    @if($userLogged)
                    @foreach($clientaddress as $k => $row)
                    <div class="item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="name">{{$row->description}}</div>
                            <input type="radio" name="address" value="{{$row->id}}" {{ $k == 0 ? 'checked' : '' }}>
                        </div>
                        <div class="address">
                            <div>{{$row->street}}, {{$row->number}}</div>
                            <div>{{$row->neighborhood}} - {{$row->city()->first()->nome}}/{{$row->city()->first()->uf}}</div>

                            <a href="#" class="delete">Excluir</a>

                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if(!$userLogged)
                    <div class="item mb-5">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="name">Usar o endereço acima</div>
                            <input type="radio" name="address" value="delivery" checked>
                        </div>
                    </div>
                    @endif

                    @foreach($systemaddress as $row)
                    <div class="item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="name">{{$row['name']}}</div>
                            <input type="radio" name="address" value="{{$row['id']}}">
                        </div>
                        <div class="address">
                            <div>{{$row['street']}}, {{$row['number']}}</div>
                            <div>{{$row['neighborhood']}} - {{$row['city']}}/{{$row['uf']}}</div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="payments">
                    <h2 class="title">Formas de pagamento</h2>
                </div>

                <div class="list-payments">

                    @foreach($payments as $k => $row)
                    <div class="item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <img src="{{asset('storage/images/' . $row['icon'])}}" alt="{{$row['name']}}">
                                </div>
                                <div class="name">{{$row['name']}}</div>
                            </div>

                            <input type="radio" name="payments" value="{{$row['id']}}" {{ $k == 0 ? 'checked' : '' }}>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="coupon my-5">
                    <h2 class="title">Você tem um cupom?</h2>

                    <div class="form-floating mb-3">
                        <input id="coupon" type="text" name="coupon" class="form-control">
                        <label for="coupon">Digite seu cupom</label>
                    </div>
                    <div class="message"></div>
                </div>

                <div class="observations">
                    <div class="title">
                        Observações
                    </div>

                    <div class="text">

                        <textarea name="descriptions" id="observations" rows="10"></textarea>
                        <div class="caracteres">0/200</div>

                    </div>
                </div>

            </div>

        </div>

        <div class="values">
            <div class="subtotal">
                <strong>Subtotal</strong>
                <span class="value">R$ 0,00</span>
            </div>

            <div class="shipping">
                <strong>Entrega:</strong>
                <span class="value">R$ 0,00</span>
            </div>

            <div class="shipping discount">
                <strong>Desconto:</strong>
                <span class="value">R$ 0,00</span>
            </div>

            <div class="total">
                <strong>Total:</strong>
                <span class="value">R$ 0,00</span>
            </div>
        </div>

        <div class="button">
            <button class="add-cart" type="submit">
                <span>Avançar</span>
            </button>
        </div>
    </form>

</div>

@endsection