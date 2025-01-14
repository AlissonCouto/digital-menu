<div class="flavors">

    <input type="hidden" id="img" value="{{ asset('storage/products/pizza.jpg') }}">  

    @foreach($flavors as $item)
        <div class="item">
            <div class="meta-infos">
                <div class="price-title">

                    <h2 class="title"><input class="checkbox" type="checkbox" name="pizza-checkbox" value="{{$item->id}}" data-name="{{$item->name}}" data-price="{{$item->price}}"> {{$item->name}}</h2>
                    <div class="price">R$ {{number_format($item->price, 2, ',', '.')}}</div>

                </div>
                <div class="ingredients">{{$item->ingredientsNames()}}</div>
            </div>

            <div class="d-flex">
                <div class="accordion pizzas" id="accordionIngredientsitemrow->id}}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo-{{$item->id}}">
                            <button class="accordion-button additionals" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIngredients-{{$item->id}}" aria-expanded="true" aria-controls="collapseIngredients-{{$item->id}}">
                                Adicionais
                            </button>
                        </h2>
                    </div>
                </div>

                <!--<div class="accordion pizzas" id="accordionPreferences-{{$item->id}}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo-{{$item->id}}">
                            <button class="accordion-button preferences" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo-{{$item->id}}" aria-expanded="true" aria-controls="collapseTwo-{{$item->id}}">
                                Preferências
                            </button>
                        </h2>
                    </div>
                </div> -->
            </div>

            @php
                $ingredients = $item->ingredients()->get();
                $additionals = $item->additionals()->get();
            @endphp

            @include('components.pizzas.ingredients', ['id' => $item->id, 'ingredients' => $ingredients, 'additionals' => $additionals])
        </div>
    @endforeach

</div> <!-- .flavors -->