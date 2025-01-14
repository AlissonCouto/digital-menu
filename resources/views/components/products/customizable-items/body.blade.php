@if($products->count() > 0)
<div class="body-container customizable-items">
    <div class="header">

        <div class="sizes">
            <h2 class="title">Tamanho da pizza: </h2>

            @if($sizes)
            <div class="options">

                @foreach($sizes as $k => $size)
                <div class="option">
                    <label for="size-{{$size->id}}" class="{{ $k == 0 ? '-checked' : '' }}">
                        {{$size->name}}
                        <input data-size-name="{{$size->name}}" value="{{$size->id}}" id="size-{{$size->id}}" type="radio" name="pizza_size" class="d-none" {{ $k == 0 ? 'checked' : '' }}>
                    </label>
                </div> <!-- .option -->
                @endforeach

            </div> <!-- .options -->
            @endif
        </div> <!-- .sizes -->

        <div class="preferences">

            <button id="button-options" type="button" class="button">
                <i class="mdi mdi-plus"></i>
                Opcionais
            </button>

            <div class="selected"></div>
        </div>

    </div>

    <div class="body">
        <div class="flavors">

            @foreach($products as $product)
            <div id="{{$product->id}}" class="flavor primary">
                <button type="button" class="flavor-remove" id="flavor-remove">
                    <i class="mdi mdi-close"></i>
                </button>

                <div class="title">{{$product->name}}</div>
                <input type="checkbox" name="pizza-checkbox" value="{{$product->id}}" data-name="{{$product->name}}" data-price="{{$product->price}}" class="d-none" checked>

                @if($product->prices)
                @foreach($product->prices as $k => $item)
                <input type="hidden" name="prices[{{$product->id}}][{{$k}}]" data-size="{{$k}}" value="{{$item['price']}}">
                @endforeach
                @endif

                <button class="ingredients" data-id="modal-ingredients-{{$product->id}}">
                    <i class="mdi mdi-notebook-edit"></i>
                    Ingredientes
                </button>
            </div>
            @endforeach

            @if(false)
            <div id="new-flavor" class="flavor new">
                <div class="icon">
                    <i class="mdi mdi-plus-circle"></i>
                </div>

                <div class="title">Novo Sabor</div>
            </div>
            @endif
        </div>

        <div class="footer">
            <button class="cancel">
                <i class="mdi mdi-delete"></i>
                Cancelar
            </button>

            <button class="save-item">
                <i class="mdi mdi-check-circle"></i>
                <span class="text" data-price="{{$totalValueItem}}">Salvar item R$ {{number_format($totalValueItem, 2, ',', '.')}}</span>
            </button>
        </div>
    </div>
</div> <!-- .customizable-items -->

<div id="modais" class="modais">

    @foreach($products as $product)
    @php
    $ingredients = $product->ingredients()->get();
    $additionals = $product->additionals()->get();
    @endphp

    @include('components.modal-admin.modal-ingredients', ['product' => $product, 'ingredients' => $ingredients, 'additionals' => $additionals])
    @endforeach

</div>
@else
@include('components.products.customizable-items.empty')
@endif