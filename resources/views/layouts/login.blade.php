<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAMED: @yield('title', 'Login')</title>
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/smartadmin-production.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/smartadmin-skins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/font-google.css') }}">
    @stack('styles')
</head>
<body id="login" class="animated fadeInDown">
    @yield('content')

    <script src="{{ asset('js/admin/plugin/pace/pace.min.js') }}"></script>
    <script src="{{ asset('js/admin/jquery.min.js') }}"></script>
    <script src="{{ asset('js/admin/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/admin/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/admin/plugin/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/admin/app.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @stack('scripts')
</body>
</html>
