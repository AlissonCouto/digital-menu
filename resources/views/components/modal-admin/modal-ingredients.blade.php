<div id="modal-ingredients-{{$product->id}}" class="modal modal-ingredients borders-pastas" tabindex="-1">
    <div class="overlayer-ingredients">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-plus"></i> Ingredientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mx-auto">

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="header">Ingredientes</div>

                                @foreach($ingredients as $ingredient)
                                <div class="item">
                                    <div class="meta-infos">
                                        <div class="price-title">

                                            <h2 class="title"><input class="checkbox" name="ingredient[{{$product->id}}]" type="checkbox" value="{{$ingredient->id}}" data-name="{{$ingredient->name}}" checked> {{$ingredient->name}}</h2>

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

                                            <h2 class="title"><input class="checkbox" name="additional[{{$product->id}}]" type="checkbox" value="{{$additional->id}}" data-name="{{$additional->name}}"> {{$additional->name}}</h2>

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

    </div>
</div>