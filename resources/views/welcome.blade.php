@extends('master')

@section('content')
<div class="products-container">

    @if($categories)
    @foreach($categories as $row)
    <div class="categories-list" id="{{$row->slug}}">

        <div class="header">
            <h2 class="title">{{$row->name}}</h2>
        </div>

        <div class="list">

            @if($row->slug == 'pizzas')
            @include('components.pizzas.list', ['category' => $row])
            @else
            @include('components.products.list', ['category' => $row])
            @endif
        </div> <!-- .list -->

    </div> <!-- .categories-list -->
    @endforeach
    @endif

</div> <!-- .products-container -->
@endsection