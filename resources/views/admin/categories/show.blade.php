@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes da Categoria: {{$category->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{$category->name}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        @if($category->icon)
                        <h1 class="h1">
                            <i class="mdi mdi-{{$category->icon}}"></i>
                        </h1>


                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" class="form-control" value="{{$category->icon}}">
                                <label for="icon">Icone</label>
                            </div>
                        </div> @endif

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <textarea disabled name="description" id="description">{{old('description') ?? $category->description}}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('categories.index') }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection