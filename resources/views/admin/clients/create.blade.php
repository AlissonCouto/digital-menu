@extends('admin.master')

@section('content')

<div class="toolbar w-100 d-flex justify-content-end">

    @include('admin.components.toolbar.notification')
    @include('admin.components.toolbar.account')

</div><!-- .toolbar -->

<div class="entity-container">

    <div class="header-container">

        <div class="meta-infos">

            <h2 class="title">Cadastrar Cliente</h2>

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
                <form class="form-default" action="{{route('clients.store')}}" method="post" enctype="multipart/form-data">

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

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="text" name="cpf" id="cpf" class="form-control cpfMask @error('cpf') is-invalid @enderror" value="{{old('cpf')}}">
                                <label for="cpf">CPF</label>
                            </div>
                            @error('cpf')
                            <span class="field-error mt-2 mb-3">{{$errors->first('cpf')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="date" name="birth" id="birth" class="form-control @error('birth') is-invalid @enderror" value="{{old('birth')}}">
                                <label for="birth">Nascimento</label>
                            </div>
                            @error('birth')
                            <span class="field-error mt-2 mb-3">{{$errors->first('birth')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="text" name="phone" id="phone" class="form-control phoneMask @error('phone') is-invalid @enderror" value="{{old('phone')}}">
                                <label for="phone">Telefone</label>
                            </div>
                            @error('phone')
                            <span class="field-error mt-2 mb-3">{{$errors->first('phone')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-5">
                            <div class="form-floating">
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}">
                                <label for="email">E-mail</label>
                            </div>
                            @error('email')
                            <span class="field-error mt-2 mb-3">{{$errors->first('email')}}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-5">
                            <div class="form-floating">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                <label for="password">Senha</label>
                            </div>
                            @error('password')
                            <span class="field-error mt-2 mb-3">{{$errors->first('password')}}</span>
                            @enderror
                        </div>

                        <div id="gender-select" class="col-12 mb-5">

                            <div class="form-floating">
                                <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender">
                                    <option value="f" {{is_null(old('gender')) || old('gender') == 'f' ? 'selected' : ''}}>Feminino</option>
                                    <option value="m" {{old('gender') == 'm' ? 'selected' : ''}}>Masculino</option>
                                    <option value="o" {{old('gender') == 'o' ? 'selected' : ''}}>Outros</option>
                                </select>
                                <label for="gender">Gênero</label>
                            </div>
                            @error('gender')
                            <span class="field-error mt-2 mb-3">{{$errors->first('gender')}}</span>
                            @enderror
                        </div>

                        <div id="gender-text" class="col-12 mb-5 {{is_null(old('gender-other')) ? 'd-none' : ''}}">
                            <div class="form-floating">
                                <input type="text" name="gender-other" id="gender-others" class="form-control @error('gender-other') is-invalid @enderror">
                                <label for="gender-others">Gênero</label>
                            </div>
                            @error('gender-other')
                            <span class="field-error mt-2 mb-3">{{$errors->first('gender-other')}}</span>
                            @enderror
                        </div>

                        <div class="col-12">

                            <div class="d-flex align-items-center justify-content-end">

                                <a href="{{ route('clients.index') }}" class="c-button btn-default">Voltar</a>
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