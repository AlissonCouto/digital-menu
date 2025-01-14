@php

use App\Models\PizzaSize;

$sizes = PizzaSize::get();

$products = $row->products();
@endphp

@foreach($sizes as $size)
<a href="{{route('pizzas', $size->slug)}}" class="item">
    <div class="img">
        <img src="{{ asset('storage/products/pizza.jpg') }}" alt="{{ $size->name }}">
    </div>

    <div class="meta-infos">
        <div class="title-price">
            <h3 class="title">{{$size->name}}</h3>
        </div>

        <div class="ingredients">Pizza com até 2 sabores</div>
    </div>
</a>
@endforeach