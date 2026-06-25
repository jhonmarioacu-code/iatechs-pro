<!doctype html>
<html lang="es" x-data="portalUi()" x-init="initTheme()" :class="{ 'theme-dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IAtechs Pro')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    @yield('body')
</body>
</html>

