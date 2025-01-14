@extends('master')

@section('content')

    <div class="single-product">

        <div class="container">

            <div class="infos">
                <div class="header-product">
                    <a href="{{route('welcome')}}"><i class="mdi mdi-arrow-left"></i></a>    

                    Detalhes do produto
                </div>

                <input type="hidden" id="categoryId" value="{{$category->id}}">
                <input type="hidden" id="categoryName" value="{{$category->slug}}">

                @isset($size)
                    <input type="hidden" id="size" value="{{$size->name}}">
                    <input type="hidden" id="sizeId" value="{{$size->id}}">
                @endisset

                @if($category->slug == 'pizzas')
                    <div class="title" id="product-title" data-name="{{'Pizza ' . $size->name}}">

                        {{'Pizza ' . $size->name}}

                    </div>
                @else
                    <div class="meta-infos">
                        <div class="price-title">

                            <h2 class="title" data-name="{{$product->name}}">{{$product->name}}</h2>
                            <div class="price" data-price="{{$product->price}}">R$ {{number_format($product->price, 2, ',', '.')}}</div>
                            <input type="hidden" id="productId" value="{{$product->id}}">
                            <input type="hidden" id="img" value="{{ asset('storage/products/' . $product->img) }}">

                        </div>
                        <div class="ingredients">{{$product->ingredientsNames()}}</div>
                    </div>
                @endif

                @if($category->slug == 'pizzas')
                
                    @if(isset($flavors))
                        @include('components.pizzas.flavors', ['flavors' => $flavors])
                    @endif


                <div class="borders-pastas">

                    <div class="accordion pizzas" id="accordionPreferences">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button preferences" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    Preferências
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionPreferences">
                                <div class="accordion-body">
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

                </div> <!-- .flavors -->
                @endif

                <div class="observations">
                    <div class="title">
                        Observações
                    </div>

                    <div class="text">

                        <textarea id="observations" rows="10"></textarea>
                        <div class="caracteres">0/200</div>

                    </div>
                </div>
            </div>

            <div class="button">
                <div class="quantity-footer">
                    <button class="rem" data-op="rem">
                        <i class="mdi mdi-minus"></i>
                    </button>
                    <span class="total">1</span>
                    <button class="add" data-op="add">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>

                <button type="button" class="add-cart" id="addToCart">
                    <span>Adicionar</span>
                    <span class="value">{{$category->slug == 'pizzas' ? 'R$ 0,00' : number_format($product->price, 2, ',', '.')}}</span>
                </button>
            </div>
        </div>

    </div>

@endsection