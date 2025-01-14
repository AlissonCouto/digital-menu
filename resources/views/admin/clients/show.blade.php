@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container page-details">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Detalhes do Cliente: {{$client->name}}</h2>

        </div> <!-- .meta-infos -->

    </div> <!-- .header-table -->

    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-12">
                <div class="form-default">

                    <div class="row">

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name') ?? $client->name}}">
                                <label for="name">Nome</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="cpf" id="cpf" class="form-control cpfMask @error('cpf') is-invalid @enderror" value="{{old('cpf') ?? $client->cpf}}">
                                <label for="cpf">CPF</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="date" name="birth" id="birth" class="form-control @error('birth') is-invalid @enderror" value="{{old('birth') ?? $client->birth}}">
                                <label for="birth">Nascimento</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="text" name="phone" id="phone" class="form-control phoneMask @error('phone') is-invalid @enderror" value="{{old('phone') ?? $client->phone}}">
                                <label for="phone">Telefone</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email') ?? $client->email}}">
                                <label for="email">E-mail</label>
                            </div>
                        </div>

                        @php
                        $gender = '';

                        if($client->gender == 'm'){
                        $gender = 'Masculino';
                        }else if($client->gender == 'f'){
                        $gender = 'Feminino';
                        }else{
                        $gender = $client->gender;
                        }
                        @endphp

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input disabled type="gender" name="gender" id="gender" class="form-control" value="{{$gender}}">
                                <label for="gender">Gênero</label>
                            </div>
                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('clients.index') }}" class="c-button btn-default">Voltar</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection