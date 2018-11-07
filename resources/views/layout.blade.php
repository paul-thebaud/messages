<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Messages - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('style')
</head>
<body>
@yield('content')

@stack('scripts')
</body>
</html>
