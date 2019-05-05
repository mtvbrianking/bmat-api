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

            <form method="POST" action="{{ route('password.email') }}" class="bg-white shadow-md rounded p-8 mb-4">

                @csrf

                <h2 class="text-grey-dark font-normal text-lg uppercase mb-5 border-b">{{ __('Reset Password') }}</h2>

                @if (session('status'))
                    <div class="bg-green-lightest border-l-4 border-green text-green-darkest p-2 shadow-md mb-4" role="alert">
                        <div class="flex items-center justify-between">
                            <p class="text-sm">{{ session('status') }}</p>
                        </div>
                        <div class="ml-2 hidden">
                            <svg class="fill-current text-green-dark" role="button" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" d="M0 0h24v24H0V0z"/>
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                            </svg>
                        </div>
                    </div>
                @endif

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

                <div class="">
                    <button class="btn btn-outline-blue w-full" type="submit">
                        {{ __('Send Password Reset Link') }}
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
