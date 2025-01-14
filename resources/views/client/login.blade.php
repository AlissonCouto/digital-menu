@extends('master')

@section('content')

<div class="w-100 checkout">

    <div class="container">

        <div class="infos">
            <div class="header-product">
                <div>
                    <a href="{{route('checkout')}}"><i class="mdi mdi-arrow-left"></i></a>
                    Faça login
                </div>
            </div>

            <form class="form-checkout mt-5" action="{{route('client.login.store')}}" method="post">
                @csrf()

                <div class="form-floating mt-5 mb-3">
                    <input id="whatsapp" type="text" name="phone" class="form-control phoneMask" required>
                    <label for="whatsapp">Seu Whatsapp</label>
                </div>

                <div class="form-floating mb-3">
                    <input id="password" type="password" name="password" class="form-control" required>
                    <label for="password">Sua senha</label>
                </div>

                <div class="adresses">
                    <button type="submit" class="d-block w-100 add-address">Entrar</button>
                </div>



            </form>

        </div>

    </div>

</div>

</div>

@endsection