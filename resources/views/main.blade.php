<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bloomon</title>
    <!-- Стили -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script>
        // Add CSRF token to all AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Add token to fetch requests
            const originalFetch = window.fetch;
            window.fetch = function() {
                const [url, config] = arguments;
                if (config && config.method && config.method !== 'GET') {
                    if (!config.headers) {
                        config.headers = {};
                    }
                    config.headers['X-CSRF-Token'] = token;
                    config.headers['X-Requested-With'] = 'XMLHttpRequest';
                }
                return originalFetch.apply(this, arguments);
            };
        });
    </script>
    @stack('scripts')
</body>
</html>