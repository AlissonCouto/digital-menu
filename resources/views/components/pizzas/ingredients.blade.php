<div class="borders-pastas">

    <div class="accordion pizzas" id="accordionIngredients-{{$id}}">
        <div class="accordion-item">
            <!--<h2 class="accordion-header" id="headingTwo-{{$id}}">
                <button class="accordion-button additionals" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIngredients-{{$id}}" aria-expanded="true" aria-controls="collapseIngredients-{{$id}}">
                    Adicionais
                </button>
            </h2> -->
            <div id="collapseIngredients-{{$id}}" class="accordion-collapse collapse" aria-labelledby="headingTwo-{{$id}}" data-bs-parent="#accordionIngredients-{{$id}}">
                <div class="accordion-body">
                    <div class="row">

                        <div class="col-12 col-md-6">
                            <div class="header">Ingredientes</div>

                            @foreach($ingredients as $ingredient)
                            <div class="item">
                                <div class="meta-infos">
                                    <div class="price-title">

                                        <h2 class="title"><input class="checkbox" name="ingredient[{{$id}}]" type="checkbox" value="{{$ingredient->id}}" data-name="{{$ingredient->name}}" checked> {{$ingredient->name}}</h2>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="header">Adicionais</div>

                            @foreach($additionals as $additional)
                            <div class="item">
                                <div class="meta-infos">
                                    <div class="price-title">

                                        <h2 class="title"><input class="checkbox" name="additional[{{$id}}]" type="checkbox" value="{{$additional->id}}" data-name="{{$additional->name}}"> {{$additional->name}}</h2>

                                        @if($additional->price > 0)
                                        <div class="price">R$ {{number_format($border->price, 2, ',', '.')}}</div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- .flavors -->