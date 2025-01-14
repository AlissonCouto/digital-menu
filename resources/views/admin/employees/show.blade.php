@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes do Funcionário: {{$employee->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name') ?? $employee->name}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="cpf" id="cpf" class="form-control cpfMask @error('cpf') is-invalid @enderror" value="{{old('cpf') ?? $employee->cpf}}">
                                <label for="cpf">CPF</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="date" name="birth" id="birth" class="form-control @error('birth') is-invalid @enderror" value="{{old('birth') ?? $employee->birth}}">
                                <label for="birth">Nascimento</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="phone" id="phone" class="form-control phoneMask @error('phone') is-invalid @enderror" value="{{old('phone') ?? $employee->phone}}">
                                <label for="phone">Telefone</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email') ?? $employee->email}}">
                                <label for="email">E-mail</label>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                @php
                                $type = '';

                                if($employee->user->type == 'admin'){
                                $type = 'Administrador';
                                }

                                if($employee->user->type == 'attendant'){
                                $type = 'Atendente';
                                }
                                @endphp
                                <input disabled type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror" value="{{$type}}">
                                <label for="type">Tipo</label>
                            </div>
                        </div>

                        @if($employee->address()->count() > 0)
                        <h1 class="h2 my-5">Dados de endereço</h1>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror" value="{{old('street') ?? $employee->address->street ?? ''}}">
                                <label for="street">Rua</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="number" id="number" class="form-control @error('number') is-invalid @enderror" value="{{old('number') ?? $employee->address->number ?? ''}}">
                                <label for="number">Número</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="neighborhood" id="neighborhood" class="form-control @error('neighborhood') is-invalid @enderror" value="{{old('neighborhood') ?? $employee->address->neighborhood ?? ''}}">
                                <label for="neighborhood">Bairro</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="texte" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{old('reference') ?? $employee->address->reference ?? ''}}">
                                <label for="reference">Referência</label>
                            </div>
                        </div>
                        @endif

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('deliverydrivers.index') }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection