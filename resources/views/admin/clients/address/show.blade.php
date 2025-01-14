@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes do endereço</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{old('description') ?? $address->description}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" value="{{old('street') ?? $address->street ?? ''}}">
                                <label for="street">Rua</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="number" id="number" class="form-control @error('number') is-invalid @enderror" value="{{old('number') ?? $address->number ?? ''}}">
                                <label for="number">Número</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="neighborhood" id="neighborhood" class="form-control @error('neighborhood') is-invalid @enderror" value="{{old('neighborhood') ?? $address->neighborhood ?? ''}}">
                                <label for="neighborhood">Bairro</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="texte" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{old('reference') ?? $address->reference ?? ''}}">
                                <label for="reference">Referência</label>
                            </div>
                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('clients.address.index', $client->id) }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection