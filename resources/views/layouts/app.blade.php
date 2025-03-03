<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Bloomon')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    @auth
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}">Панель администратора</a>
        @endif
    @endauth

    @yield('content')
</body>
</html>
