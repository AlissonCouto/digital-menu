@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes do Ingrediente: {{$ingredient->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{$ingredient->name}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        @if($ingredient->additional_price)
                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{number_format($ingredient->additional_price, 2, ',', '.')}}">
                                <label for="price">Preço</label>
                            </div>
                        </div>
                        @endif

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('ingredients.index') }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection