@php
use App\Models\Client;

$uri = request()->route()->uri;
$root = request()->root();
$logged = auth('client')->check();

if($logged){
$user = auth('client')->user();
$client = Client::find($user->id);
}
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="uri" content="{{$uri}}">
    <meta name="root" content="{{$root}}">
    @if($logged)
    <meta name="client-id" content="{{$client->id}}">
    @endif

    @isset($deliveryValue)
    <meta name="deliveryValue" content="{{$deliveryValue}}">
    @endisset

    <title>{{config('app.name', 'Lanxi Delivery')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/cart.css', 'resources/js/app.js', 'resources/js/cart.js'])
</head>

<body>

    <div class="cardapio">

        @isset($categories)
        <div class="sidebar">
            <div class="header">
                <div class="logo">
                    Lanxi
                </div>
            </div>

            <ul class="menu">
                @foreach($categories as $k => $item)
                <li class="menu-item">
                    <a href="{{url('')}}#{{$item->slug}}" class="menu-link {{ $k == 0 ? '-active' : '' }}">
                        <div>
                            <i class="mdi mdi-{{$item->icon}}"></i>
                            {{$item->name}}
                        </div>

                        <i class="mdi mdi-arrow-right"></i>
                    </a>
                </li>
                @endforeach
            </ul>
            
        </div> <!-- .sidebar -->
        @endisset

        <div class="workspace {{$uri == 'login' ? 'start-0 w-100' : ''}}">

            <div class="header">
                <!--
                    <form action="#" method="get" class="form-search">
                        <input type="text" name="search" placeholder="O que você quer comer hoje?" class="input-search">
                        <button type="submit" class="button-search"><i class="mdi mdi-magnify"></i></button>
                    </form>            
                -->

                <div class="menu w-100 d-flex justify-content-end">
                    <a href="{{route('welcome')}}" class="home {{$uri == '/' ? '-active' : ''}}">
                        <i class="mdi mdi-home"></i>
                        Início
                    </a>

                    <a href="{{route('orders')}}" class="orders {{$uri == 'pedidos' || $uri == 'pedido' ? '-active' : ''}}">
                        <i class="mdi mdi-receipt"></i>
                        Pedidos
                    </a>

                    <!-- {{route('cart')}} -->
                    <a class="open-cart item-cart {{$uri == 'cart' || $uri == 'checkout' ? '-active' : ''}}">
                        <span class="total">0</span>
                        <i class="mdi mdi-cart"></i>
                        Carrinho
                    </a>

                    @if($logged)
                    <a class="logout" href="{{route('client.logout')}}">
                        <i class="mdi mdi-logout"></i>
                        Sair
                    </a>
                    @else
                    <a class="login" href="{{route('client.login')}}">
                        <i class="mdi mdi-login"></i>
                        Login
                    </a>
                    @endif
                </div>
            </div> <!-- .header -->

            @yield('content')

        </div> <!-- .workspace -->

    </div> <!-- .cardapio -->

    @include('components.cart.index')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>

</html>