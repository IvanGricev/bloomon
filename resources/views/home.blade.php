@extends('main')

@section('content')
<link rel="stylesheet" href="{{ url('css/home.css') }}">
<div class="main-banner">
    <div class="main-banner__text">
        <div class="main-banner__subtitle">Каждый день -<br>это шанс</div>
        <div class="main-banner__title">расцвести</div>
        <div class="main-banner__desc">
            Мы верим, что каждый букет - это не просто сбор лепестков, а возможность передать самые искренние эмоции и создать незабываемые моменты. Наши профессиональные флористы с любовью подбирают каждую деталь, чтобы ваш подарок стал по-настоящему особенным.
        </div>
        <a href="{{ route('products.index') }}" class="main-banner__btn">ПЕРЕЙТИ В КАТАЛОГ <span>&#8599;</span></a>
    </div>
    <div class="main-banner__img">
        <img src="{{ asset('images/banner.png') }}" alt="Букет цветов">
    </div>
</div>

@php
    $promoImages = [
        asset('images/promo1.jpg'),
        asset('images/promo2.jpg'),
        asset('images/promo3.jpg'),
        asset('images/promo4.jpg'),
    ];
@endphp

<div class="promos-section">
    <h2 class="promos-title">Акции</h2>
    <div class="promos-grid">
        @foreach($promotions as $i => $promo)
            <div class="promo-card promo-card--{{ $i+1 }}">
                <img src="{{ $promoImages[$i] ?? $promoImages[0] }}" alt="{{ $promo->name }}">
                <div class="promo-card__overlay"></div>
                <div class="promo-card__content">
                    <div class="promo-card__name">{{ $promo->name }}</div>
                    <div class="promo-card__discount">{{ $promo->discount }}%<span> скидка</span></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="subscriptions-section">
    <h2 class="subscriptions-title">Популярные подписки</h2>
    <div class="subscriptions-grid">
        @foreach($subscriptions as $subscription)
            <div class="subscription-card">
                <div class="subscription-card__body">
                    <div class="subscription-card__name">{{ $subscription->name }}</div>
                    <div class="subscription-card__desc">{{ $subscription->description }}</div>
                    <div class="subscription-card__price">
                        <strong>{{ number_format($subscription->price, 2, ',', ' ') }} руб.</strong>
                    </div>
                    <div class="subscription-card__meta">
                        @switch($subscription->period)
                            @case('month') Ежемесячная @break
                            @case('year') Годовая @break
                            @default {{ ucfirst($subscription->period) }}
                        @endswitch подписка, доставка: 
                        @switch($subscription->frequency)
                            @case('daily') Ежедневно @break
                            @case('weekly') Еженедельно @break
                            @case('biweekly') Раз в две недели @break
                            @case('monthly') Ежемесячно @break
                            @default {{ $subscription->frequency }}
                        @endswitch
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="subscriptions-btn-wrap">
        <a href="{{ route('subscriptions.index') }}" class="subscriptions-btn">Смотреть все подписки</a>
    </div>
</div>

<div class="delivery-section">
    <div class="delivery-header">
        <h2 class="delivery-title">Доставка</h2>
        <a href="{{ route('delivery') }}" class="delivery-more-link">Узнать подробнее <span>&#8594;</span></a>
                    </div>
    <div class="delivery-card">
        <img src="{{ asset('images/delivery.jpg') }}" alt="Доставка цветов">
        <div class="delivery-card__desc">
            <strong>БЫСТРАЯ И БЕРЕЖНАЯ ДОСТАВКА ПО ГОРОДУ И ОБЛАСТИ.</strong><br>
            Мы доставим ваш букет в течение 2 часов после оформления заказа или в удобное для вас время. Наши курьеры заботятся о каждом букете, чтобы он приехал к вам свежим и красивым.
            </div>
    </div>
</div>

<!-- Модальное окно для добавления записи блога (для администратора) -->
@auth
    @if(auth()->user()->role === 'admin')
    <div class="modal fade" id="addBlogModal" tabindex="-1" aria-labelledby="addBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBlogModalLabel">Добавить запись в блог</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Заголовок</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Основной текст</label>
                            <textarea class="form-control" id="body" name="body" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить запись</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth

@endsection