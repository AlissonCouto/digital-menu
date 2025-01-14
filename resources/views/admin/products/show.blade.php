@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes do Produto: {{$product->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{$product->name}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <textarea disabled name="description" id="description">{{old('description') ?? $product->description}}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{$category->name}}">
                                <label for="name">Categoria</label>
                            </div>
                        </div>

                        @if($product->price)
                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{number_format($product->price, 2, ',', '.')}}">
                                <label for="price">Preço</label>
                            </div>
                        </div>
                        @endif

                        @if($product->img)
                        <div class="col-12 col-md-6 my-5">
                            <img src="{{asset('storage/products/' . $product->img)}}" alt="{{$product->name}}" class="img-fluid">
                        </div>
                        @endif

                        <div class="fields-for-products-ingredients">
                            <h1 class="h2 mt-5">Ingredientes</h1>

                            <div class="ingredients">
                                @foreach($ingredients as $ingredient)
                                <div class="item-ingredient">
                                    <label class="ingredient" for="ingredient-{{$ingredient->id}}">{{$ingredient->name}}</label>
                                    <div class="field">
                                        <input disabled type="checkbox" name="ingredients[]" value="{{$ingredient->id}}" id="ingredient-{{$ingredient->id}}" {{ in_array($ingredient->id, $ingredientsCheckeds) ? 'checked' : '' }}>
                                        <span class="custom-checkbox"></span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>

                        <div id="fields-for-pizzas" class="fields-for-pizzas {{ old('category_id') != 1 && $product->category_id != 1 ? 'd-none' : '' }}">
                            <h1 class="h2 mt-5">Preços por tamanho</h1>

                            <div class="prices-for-sizes">
                                @foreach($pizza_sizes as $size)
                                @if(isset($pizzaPrices[$size->id]['price']))
                                <div class="item-size">
                                    <div class="size">{{$size->name}}</div>
                                    <div class="field">
                                        @php
                                        $pricePizza = '';

                                        if(isset($pizzaPrices[$size->id]['price'])){
                                        $pricePizza = number_format($pizzaPrices[$size->id]['price'], 2, ',', '.');
                                        }
                                        @endphp
                                        <strong>R$ &nbsp;&nbsp;</strong><input disabled type="text" name="pizza_size[{{$size->id}}]" class="priceMask" value="{{ $pricePizza }}">
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>

                            <h1 class="h2 mt-5">Adicionais</h1>

                            <div class="ingredients">
                                @foreach($ingredients as $ingredient)
                                <div class="item-ingredient">
                                    <label class="ingredient" for="additional-{{$ingredient->id}}">{{$ingredient->name}}</label>
                                    <div class="field">
                                        <input disabled type="checkbox" name="additionals[]" value="{{$ingredient->id}}" id="additional-{{$ingredient->id}}" {{ in_array($ingredient->id, $additionalsCheckeds) ? 'checked' : '' }}>
                                        <span class="custom-checkbox"></span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{ $product->menu == 1 ? 'Sim' : 'Não' }}">
                                <label for="name">Cardápio?</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{ $product->status == 1 ? 'Sim' : 'Não' }}">
                                <label for="name">Ativo?</label>
                            </div>
                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('products.index') }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection