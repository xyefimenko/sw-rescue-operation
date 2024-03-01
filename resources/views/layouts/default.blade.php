<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('frontend.site_title') }}</title>

        <!-- Bootstrap CSS -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/jumbotron/">
    </head>

    <body>
        @include('includes.header')

        <main role="main" class="container mt-5">
            @yield('content')
        </main>

        @include('includes.footer')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

    </body>
</html>
