@extends('master')

@section('content')

    <div class="page-cart">

        <div class="container">

            <div class="infos">
                <div class="header-product">
                    <div>
                        <a href="{{route('welcome')}}"><i class="mdi mdi-arrow-left"></i></a>    
                        Carrinho
                    </div>

                    <button type="button"class="clear clear-cart"><i class="mdi mdi-delete"></i> Limpar</button>
                </div>

                <div class="cart-items"></div>

                <a href="{{route('welcome')}}" class="continue">Continuar comprando</a>
            </div>

            <div class="button">
                <a type="button" class="add-cart" href="{{route('checkout')}}">
                    <span>Avançar</span>
                    <span class="value">R$ 0,00</span>
                </a>
            </div>
        </div>

    </div>

    @include('components.comments')

@endsection