<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloomon</title>
    <!-- Подключение стилей -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Шапка сайта -->
    <header>
        @include('partials.navbar')
    </header>

    <!-- Основной контент -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Подвал сайта -->
    <footer class="text-center py-4">
        <p>&copy; 2025 Bloomon. Все права защищены.</p>
    </footer>

    <!-- Подключение скриптов -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>