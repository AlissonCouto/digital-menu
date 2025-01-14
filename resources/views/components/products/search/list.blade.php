@if($products)

@foreach($products as $k => $row)
<label class="item {{ $k == 0 ? '-selected' : '' }}" for="product-{{$row->id}}">
    <input type="radio" name="selected-product" class="d-none" value="{{$row->id}}" data-url="{{route('search.product.by.id')}}" id="product-{{$row->id}}" {{ $k == 0 ? 'checked' : '' }}>

    <strong class="category">{{ $row->category->name }}: &nbsp;</strong>
    <span class="product"> {{ $row->name }} &nbsp;</span>

    @php
    $price = $row->price;
    @endphp

    @if($row->category->slug != 'pizzas')
    <strong class="price">R$ {{ number_format($price, 2, ',', '.') }}</strong>
    @endif
</label>
@endforeach

@endif