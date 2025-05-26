@extends('main')

<link rel="stylesheet" href="{{ asset('css/delivery.css') }}">

@section('content')
<div class="delivery-hero">
    <div class="delivery-hero-text">
        <div class="delivery-hero-title">ДОСТАВКА ЦВЕТОВ</div>
        <div class="delivery-hero-desc">
            Мы заботимся о каждом букете и доставляем его быстро, бережно и в удобное для вас время. Узнайте подробности и выберите лучший вариант!
        </div>
        <ul class="delivery-hero-list">
            <li>@include('partials.flowicon') Бесплатная доставка по городу — при заказе от 2000 руб.</li>
            <li>@include('partials.flowicon') Срочная доставка — в течение 2 часов (по договорённости).</li>
            <li>@include('partials.flowicon') Доставка в область — уточняйте условия у менеджера.</li>
            <li>@include('partials.flowicon') Время и адрес согласовываются индивидуально.</li>
    </ul>
    </div>
    <div class="delivery-hero-img">
        <img src="/images/idea.jpg" alt="Доставка цветов">
    </div>
</div>

<div class="delivery-cards">
    <div class="delivery-card">
        <div class="delivery-card-icon">
            <svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#f7b6a3"/><path d="M16 9v14M9 16h14" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
        </div>
        <div class="delivery-card-content">
            <div class="delivery-card-title">Стандартная доставка</div>
            <div class="delivery-card-desc">
                Доставим ваш букет в течение дня — бесплатно при заказе от 2000 руб. Время согласовывается заранее.
            </div>
        </div>
    </div>
    <div class="delivery-card">
        <div class="delivery-card-icon">
            <svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#d97c6a"/><path d="M16 9v8l6 3" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
        </div>
        <div class="delivery-card-content">
            <div class="delivery-card-title">Срочная доставка</div>
            <div class="delivery-card-desc">
                Нужно быстро? Оформите срочную доставку — привезём букет в течение 2 часов (стоимость уточняйте у менеджера).
            </div>
        </div>
    </div>
    <div class="delivery-card">
        <div class="delivery-card-icon">
            <svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#f7b6a3"/><path d="M9 23l14-14M9 9h14v14" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>
        </div>
        <div class="delivery-card-content">
            <div class="delivery-card-title">Доставка в область</div>
            <div class="delivery-card-desc">
                Доставляем букеты за пределы города. Стоимость и сроки рассчитываются индивидуально.
            </div>
        </div>
    </div>
</div>

<div class="delivery-steps">
    <div class="delivery-step">
        <div class="delivery-step-num">1</div>
        <div class="delivery-step-title">Оформление заказа</div>
        <div class="delivery-step-desc">Выберите букет на сайте, добавьте в корзину и укажите детали доставки.</div>
    </div>
    <div class="delivery-step">
        <div class="delivery-step-num">2</div>
        <div class="delivery-step-title">Подтверждение</div>
        <div class="delivery-step-desc">Менеджер свяжется с вами для уточнения времени, адреса и пожеланий.</div>
    </div>
    <div class="delivery-step">
        <div class="delivery-step-num">3</div>
        <div class="delivery-step-title">Сборка и упаковка</div>
        <div class="delivery-step-desc">Флористы собирают букет, бережно упаковывают и готовят к отправке.</div>
    </div>
    <div class="delivery-step">
        <div class="delivery-step-num">4</div>
        <div class="delivery-step-title">Доставка и вручение</div>
        <div class="delivery-step-desc">Курьер доставляет букет вовремя, вручает лично или анонимно — по вашему желанию.</div>
    </div>
</div>
@endsection