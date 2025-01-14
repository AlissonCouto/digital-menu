@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Cadastrar Produto</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            @if(session()->has('success'))
            <div class="col-12 m-b-5">
                <div class="alert alert-{{ session('success')['success'] ? 'success' : 'danger' }}">
                    {{ session('success')['message'] }}
                </div>
            </div>
            @endif

            <div class="col-12">
                <form class="form-default" action="{{route('products.store')}}" method="post" enctype="multipart/form-data">

                    @csrf
                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
                                <label for="name">Nome</label>
                            </div>
                            @error('name')
                            <span class="field-error mt-2 mb-3">{{$errors->first('name')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <textarea name="description" id="description">{{old('description')}}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                            @error('description')
                            <span class="field-error mt-2 mb-3">{{$errors->first('description')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">

                            <div class="form-floating">
                                <select id="category_id" class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                    <option value="" selected>Selecione</option>

                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <label for="category_id">Categoria</label>
                            </div>
                            @error('category_id')
                            <span class="field-error mt-2 mb-3">{{$errors->first('category_id')}}</span>
                            @enderror

                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input type="text" name="price" id="price" class="form-control priceMask @error('price') is-invalid @enderror" value="{{old('price')}}">
                                <label for="price">Preço</label>
                            </div>
                            @error('price')
                            <span class="field-error mt-2 mb-3">{{$errors->first('price')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">
                            <label for="file-field" class="input-file">
                                <input type="file" id="file-field" name="file" class="d-none">
                                <strong>Escolher Imagem</strong>
                                <div class="icon">
                                    <i class="mdi mdi-image-area"></i>
                                </div>
                            </label>
                        </div>

                        <div class="fields-for-products-ingredients">
                            <h1 class="h2 mt-5">Ingredientes</h1>

                            @error('ingredients')
                            <span class="field-error mt-2 mb-3">{{$errors->first('ingredients')}}</span>
                            @enderror

                            <div class="ingredients">
                                @foreach($ingredients as $ingredient)
                                <div class="item-ingredient">
                                    <label class="ingredient" for="ingredient-{{$ingredient->id}}">{{$ingredient->name}}</label>
                                    <div class="field">
                                        <input type="checkbox" name="ingredients[]" value="{{$ingredient->id}}" id="ingredient-{{$ingredient->id}}">
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>

                        <div id="fields-for-pizzas" class="fields-for-pizzas {{ old('category_id') != 1 ? 'd-none' : '' }}">
                            <h1 class="h2 mt-5">Preços por tamanho</h1>

                            @error('pizza_size')
                            <span class="field-error mt-2 mb-3">{{$errors->first('pizza_size')}}</span>
                            @enderror

                            <div class="prices-for-sizes">
                                @foreach($pizza_sizes as $size)
                                <div class="item-size">
                                    <div class="size">{{$size->name}}</div>
                                    <div class="field">
                                        <strong>R$ &nbsp;&nbsp;</strong><input type="text" name="pizza_size[{{$size->id}}]" class="priceMask">
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <h1 class="h2 mt-5">Adicionais</h1>
                            @error('additionals')
                            <span class="field-error mt-2 mb-3">{{$errors->first('additionals')}}</span>
                            @enderror

                            <div class="ingredients">
                                @foreach($ingredients as $ingredient)
                                <div class="item-ingredient">
                                    <label class="ingredient" for="additional-{{$ingredient->id}}">{{$ingredient->name}}</label>
                                    <div class="field">
                                        <input type="checkbox" name="additionals[]" value="{{$ingredient->id}}" id="additional-{{$ingredient->id}}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">

                            <div class="form-floating">
                                <select id="menu" class="form-control @error('menu') is-invalid @enderror" name="menu">
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                </select>
                                <label for="menu">Cardápio?</label>
                            </div>

                        </div>

                        <div class="col-12 col-md-6 mb-5">

                            <div class="form-floating">
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                </select>
                                <label for="status">Ativo?</label>
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('products.index') }}" class="c-button btn-default">Voltar</a>
                                <button type="submit" class="c-button btn-save">Salvar</button>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

@endsection