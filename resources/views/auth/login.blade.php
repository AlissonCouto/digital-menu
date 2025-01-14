@extends('admin.master')

@section('content')

<div class="container-login">

    <div class="col-left">

        <div class="meta-infos">

            <div class="logo">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logotipo">
            </div>

            <h1 class="title">
                <strong>Somos,</strong> <br>
                Lanxi Delivery
            </h1>

            <p class="text">
                Revolucionamos a gestão do seu delivery.
            </p>

        </div> <!-- .meta-infos -->

    </div> <!-- .col-left -->

    <div class="col-middle">

    </div> <!-- .col-middle -->

    <div class="col-right">

        <div class="auth-session-status">
            <x-auth-session-status class="mb-4" :status="session('status')" />
        </div>

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Senha')" />

                <x-text-input id="password" class="block mt-1 w-full phoneMask"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div>
                <label for="remember_me" class="remember-me">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Manter conectado') }}</span>
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                </label>
            </div>

            <div class="flex items-center justify-end">
                @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
                @endif

                <button type="submit" class="submit">Entrar</button>
            </div>
        </form>

    </div> <!-- .col-right -->

</div> <!-- .container-login -->

@endsection