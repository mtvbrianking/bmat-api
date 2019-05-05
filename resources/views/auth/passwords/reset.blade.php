<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('extra-css')
</head>

<body class="font-sans bg-grey-lighter">
<div id="app">
    <div class="container mx-auto min-h-screen flex justify-center items-center">
        <div class="mb-4 sm:w-5/6 md:w-1/2 lg:w-1/3 xl:w-1/3">

            <h1 class="text-grey-dark text-center font-medium text-5xl uppercase mb-4">bmat api</h1>

            <form method="POST" action="{{ route('password.update') }}" class="bg-white shadow-md rounded p-8 mb-4">

                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <h2 class="text-grey-dark font-normal text-lg uppercase mb-5 border-b">{{ __('Reset Password') }}</h2>

                <div class="mb-4">
                    <label class="block text-grey-darkest text-sm" for="email">
                        {{ __('E-Mail Address') }}
                    </label>
                    <input class="w-full" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-red" role="alert">
                            <small>{{ $errors->first('email') }}</small>
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-grey-darkest text-sm" for="password">
                        {{ __('Password') }}
                    </label>
                    <input class="w-full" id="password" name="password" type="password" required>
                    @if ($errors->has('password'))
                        <span class="text-red" role="alert">
                            <small>{{ $errors->first('password') }}</small>
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-grey-darkest text-sm" for="password-confirm">
                        {{ __('Confirm Password') }}
                    </label>
                    <input class="w-full" id="password-confirm" name="password_confirmation" type="password" required>
                </div>

                <div class="">
                    <button class="btn btn-outline-blue w-full" type="submit">
                        {{ __('Reset Password') }}
                    </button>
                </div>

            </form>
            <div class="w-full">
                <p class="text-center text-grey text-sm">
                    Remembered your password? <a class="inline-block align-baseline text-blue hover:text-blue-darker"
                   href="{{ route('login') }}">{{ __('Sign In') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ asset('js/app.js') }}"></script>

@stack('extra-js')

</body>
</html>
