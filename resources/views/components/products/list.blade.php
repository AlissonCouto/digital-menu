@php
$products = $row->products();
@endphp

@if($products->count() != 0)
@foreach($products->get() as $prod)
<a href="{{route('product', $prod->slug)}}" class="item">
    <div class="img">

        <img src="{{ asset('storage/products/crops/' . $prod->img) }}" alt="{{ $prod->name }}">
        <input type="hidden" id="img" value="{{ asset('storage/products/crops/' . $prod->img) }}">
    </div>

    <div class="meta-infos">
        <div class="title-price">
            <h3 class="title">{{$prod->name}}</h3>
            <div class="price">R$ {{number_format($prod->price, 2, ',', '.')}}</div>
        </div>

        <div class="ingredients">{{$prod->ingredientsNames()}}</div>
    </div>
</a>
@endforeach
@else
<div class="item w-100 p-5">
    <div class="meta-infos">
        <div class="title-price">
            <h3 class="title">Nenhum registro econtrado</h3>
        </div>
    </div>
</div>
@endif