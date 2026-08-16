<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAMED: @yield('title', $title ?? 'Admin')</title>
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/smartadmin-production.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/smartadmin-skins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/plugin/tag-it/jquery.tagit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/font-google.css') }}">
    @stack('styles')
    <script src="{{ asset('js/admin/plugin/pace/pace.min.js') }}"></script>
    <script src="{{ asset('js/admin/jquery.min.js') }}"></script>
</head>
<body class="fixed-header fixed-ribbon fixed-navigation smart-style-3"
      rel_url="{{ url('/') }}"
      rel_controller="{{ request()->route()?->getName() }}"
      rel_action="">

@include('partials.admin.header')
@include('partials.admin.menu')

<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.home') }}">Home</a></li>
            @hasSection('breadcrumb')
                @yield('breadcrumb')
            @else
                <li>{{ $title ?? '' }}</li>
            @endif
        </ol>
    </div>

    <div id="content">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="margin-bottom-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="{{ asset('js/admin/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/admin/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/admin/notification/SmartNotification.min.js') }}"></script>
<script src="{{ asset('js/admin/smartwidgets/jarvis.widget.min.js') }}"></script>
<script src="{{ asset('js/admin/plugin/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/admin/plugin/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/admin/app.js') }}"></script>
@stack('scripts')
</body>
</html>
