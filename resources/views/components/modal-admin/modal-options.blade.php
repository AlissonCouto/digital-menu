<div id="modal-options" class="modal modal-options borders-pastas" tabindex="-1">
    <div class="overlayer-options">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-plus"></i> Opcionais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mx-auto">

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="header">Massa</div>

                                @foreach($pastas as $k => $pasta)
                                <div class="item">
                                    <div class="meta-infos">
                                        <div class="price-title">

                                            <h2 class="title"><input class="checkbox" name="pasta" id="{{$pasta->id}}" value="{{$pasta->id}}" data-name="{{$pasta->name}}" data-price="{{$pasta->price}}" type="radio" {{$k == 0 ? 'checked' : ''}}> {{$pasta->name}}</h2>

                                            @if($pasta->price > 0)
                                            <div class="price">R$ {{number_format($pasta->price, 2, ',', '.')}}</div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="header">Borda</div>

                                @foreach($borders as $border)
                                <div class="item">
                                    <div class="meta-infos">
                                        <div class="price-title">

                                            <h2 class="title"><input class="checkbox" name="border" type="radio" id="{{$border->id}}" value="{{$border->id}}" data-name="{{$border->name}}" data-price="{{$border->price}}"> {{$border->name}}</h2>

                                            @if($border->price > 0)
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