@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Editar endereço de: {{$client->name}}</h2>

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
                <form class="form-default" action="{{route('clients.address.update', ['client' => $client->id, 'address' => $address->id])}}" method="post" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{old('description') ?? $address->description ?? ''}}">
                                <label for="description">Nome</label>
                            </div>
                            @error('description')
                            <span class="field-error mt-2 mb-3">{{$errors->first('description')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" value="{{old('street') ?? $address->street ?? ''}}">
                                <label for="street">Rua</label>
                            </div>
                            @error('street')
                            <span class="field-error mt-2 mb-3">{{$errors->first('street')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="text" name="number" id="number" class="form-control @error('number') is-invalid @enderror" value="{{old('number') ?? $address->number ?? ''}}">
                                <label for="number">Número</label>
                            </div>
                            @error('number')
                            <span class="field-error mt-2 mb-3">{{$errors->first('number')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="text" name="neighborhood" id="neighborhood" class="form-control @error('neighborhood') is-invalid @enderror" value="{{old('neighborhood') ?? $address->neighborhood ?? ''}}">
                                <label for="neighborhood">Bairro</label>
                            </div>
                            @error('neighborhood')
                            <span class="field-error mt-2 mb-3">{{$errors->first('neighborhood')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="texte" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{old('reference') ?? $address->reference ?? ''}}">
                                <label for="reference">Referência</label>
                            </div>
                            @error('reference')
                            <span class="field-error mt-2 mb-3">{{$errors->first('reference')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">

                            <div class="form-floating">
                                @php
                                $currentMain = old('main') ?? $address->main;
                                @endphp
                                <select id="main" class="form-control @error('main') is-invalid @enderror" name="main">
                                    <option value="1" {{ $currentMain == 1 ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ $currentMain == 0 ? 'selected' : '' }}>Não</option>
                                </select>
                                <label for="main">Principal?</label>
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('clients.address.index', $client->id) }}" class="c-button btn-default">Voltar</a>
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