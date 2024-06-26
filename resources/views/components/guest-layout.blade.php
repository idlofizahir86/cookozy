<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet">

    <!-- Styles -->
    @if (App::environment('production'))
        <link href="{{ secure_url('css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
    @endif

</head>
<body>
    <div id="app">
        <main class="py-0">
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts -->
    @if (App::environment('production'))
        <script src="{{ secure_url('js/app.js') }}" ></script>
    @else
        <script src="{{ url('js/app.js') }}"></script>
    @endif
</body>
</html>
