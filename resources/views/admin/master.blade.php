@php

$menu = [
[
'icon' => 'mdi-home',
'name' => 'Dashboard',
'url' => route('dashboard')
],
[
'icon' => 'mdi-receipt',
'name' => 'Pedidos',
'url' => route('orders.index')
],
[
'icon' => 'mdi-chart-bar',
'name' => 'Relatórios',
'url' => route('graphics.index')
],
[
'icon' => 'mdi-account-group',
'name' => 'Clientes',
'url' => route('clients.index')
],
[
'icon' => 'mdi-hamburger',
'name' => 'Produtos',
'url' => route('products.index')
],
[
'icon' => 'mdi-clipboard-list',
'name' => 'Ingredientes',
'url' => route('ingredients.index')
],
[
'icon' => 'mdi-pasta',
'name' => 'Massas',
'url' => route('pastas.index')
],
[
'icon' => 'mdi-food-croissant',
'name' => 'Bordas',
'url' => route('borders.index')
],
[
'icon' => 'mdi-ruler',
'name' => 'Tamanhos',
'url' => route('sizes.index')
],
[
'icon' => 'mdi-view-list',
'name' => 'Categorias',
'url' => route('categories.index')
],
[
'icon' => 'mdi-cash-marker',
'name' => 'Taxas E',
'url' => route('fees.index')
],
[
'icon' => 'mdi-moped',
'name' => 'Entregadores',
'url' => route('deliverydrivers.index')
],
[
'icon' => 'mdi-account-switch',
'name' => 'Funcionários',
'url' => route('employees.index')
],
[
'icon' => 'mdi-ticket-percent',
'name' => 'Cupons',
'url' => route('coupons.index')
]
];

$uri = request()->route()->uri;
$fullUrl = request()->fullurl();

if(auth()->check()){
$user = auth()->user();
$company = $user->company()->first();
}

@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="uri" content="{{$uri}}">

    @if(auth()->check())
    <meta name="company-id" content="{{$company->id}}">
    @endif

    <title>{{config('app.name', 'Lanxi Delivery')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Styles -->
    @if($uri != 'admin/login')
    @vite(['resources/css/admin.css', 'resources/css/charts.css', 'resources/js/admin.js', 'resources/js/charts.js'])
    @else
    @vite(['resources/css/login.css'])
    @endif

    @yield('head')

    @yield('stylesheets')
</head>

<body>

    <div class="app">

        @if($uri != 'admin/login')
        <div class="sidebar">
            <div class="header">
                <div class="logo">
                    LX
                </div>
            </div>

            <div class="menu">
                @foreach($menu as $k => $item)
                <div class="menu-item {{ $fullUrl == $item['url'] ? '-active' : '' }}">
                    <a href="{{$item['url']}}" class="menu-link">
                        <i class="mdi {{$item['icon']}} icon"></i>
                        <span class="name">{{$item['name']}}</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div> <!-- .sidebar -->

        <div class="workspace">

            @yield('content')

        </div> <!-- .workspace -->
        @else
        @yield('content')
        @endif

        <div id="modal-delete" class="modal modal-delete" tabindex="-1">
            <div class="overlayer">

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tem certeza que deseja apagar?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="buttons">
                                <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="mdi mdi-arrow-left"></i>
                                    Não
                                </button>

                                <button type="button" data-url="" class="delete h-100" data-bs-dismiss="modal">
                                    <i class="mdi mdi-delete"></i>
                                    Sim
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div> <!-- .cardapio -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    @yield('scripts')
</body>

</html>