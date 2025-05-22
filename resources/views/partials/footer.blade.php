<link rel="stylesheet" href="/css/footer.css">
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-col">
            <div class="footer-logo">
                <img src="/images/logo.svg" alt="Bloomon" height="32">
            </div>
            <div class="footer-desc">Цветы с доставкой по городу и области. Каждый день — повод для радости!</div>
        </div>
        <div class="footer-col">
            <h5>Компания</h5>
            <ul>
                <li><a href="{{ route('about') }}">О нас</a></li>
                <li><a href="{{ route('contact') }}">Контакты</a></li>
                <li><a href="{{ route('support.create') }}">Поддержка</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Каталог</h5>
            <ul>
                <li><a href="{{ route('products.index') }}">Букеты</a></li>
                <li><a href="{{ route('subscriptions.index') }}">Подписки</a></li>
                <li><a href="{{ route('blog.index') }}">Блог</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">© {{ date('Y') }} Bloomon. Все права защищены.</div>
</footer> 