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

            <form method="POST" action="{{ route('login') }}" class="bg-white shadow-md rounded p-8 mb-4">

                @csrf

                <div class="mb-5">
                    <label class="block text-grey-darkest" for="email">
                        {{ __('E-Mail Address') }}
                    </label>
                    <input class="w-full floating" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-red" role="alert">
                            <small>{{ $errors->first('email') }}</small>
                        </span>
                    @endif
                </div>

                <div class="mb-5">
                    <label class="block text-grey-darkest" for="password">
                        {{ __('Password') }}
                    </label>
                    <input class="w-full floating" id="password" name="password" type="password" required>
                </div>

                <div class="flex items-center justify-between mb-5">
                    <div class="">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                        <label class="text-grey-darkest" for="remember">{{ __('Remember Me') }}</label>
                    </div>
                    <a class="inline-block align-baseline text-blue hover:text-blue-darker"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>

                <div class="mb-5">
                    <button class="btn btn-outline-blue w-full" type="submit">
                        {{ __('Sign In') }}
                    </button>
                </div>

                <div class="w-full">
                    <p class="text-center text-grey-dark">
                        Don’t have an account? <a class="inline-block align-baseline text-blue hover:text-blue-darker"
                       href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <div class="w-full -mt-8">
        <p class="text-center text-grey">
            &copy;<a href="http://bmatovu.com" class="text-blue-light hover:text-blue no-underline">bmatovu</a>
            All rights reserved.
        </p>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ asset('js/app.js') }}"></script>

@stack('extra-js')

</body>
</html>
