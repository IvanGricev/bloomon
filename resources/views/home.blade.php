
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

<div class="text-center my-5">
    <h1>Добро пожаловать в Bloomon</h1>
    <p>Лучшие букеты для любого случая.</p>
</div>

<div class="container my-5">
    <!-- Вывод акций -->
    <h2 class="text-center mb-4">Текущие акции</h2>
    <div class="row">
        @foreach($promotions as $promo)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $promo->name }}</h5>
                        <p class="card-text">{{ $promo->description }}</p>
                        <p class="card-text"><strong>{{ $promo->discount }}%</strong> скидка</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="container my-5">
    <!-- Вывод 3 подписок -->
    <h2 class="text-center mb-4">Популярные подписки</h2>
    <div class="row">
        @foreach($subscriptions as $subscription)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $subscription->name }}</h5>
                        <p class="card-text">{{ $subscription->description }}</p>
                        <p class="card-text">
                            <strong>{{ number_format($subscription->price, 2, ',', ' ') }} руб.</strong><br>
                            {{ ucfirst($subscription->period) }} подписка, доставка: {{ $subscription->frequency }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center">
        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Смотреть все подписки</a>
    </div>
</div>

<div class="container my-5">
    <!-- Мини блог -->
    <h2 class="text-center mb-4">Блог Bloomon</h2>
    <div class="row">
        @foreach($blogPosts as $post)
            <div class="col-md-4">
                <div class="card mb-4">
                    @if($post->image)
                        <img src="{{ asset('uploads/blog/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
                    @else
                        <img src="https://via.placeholder.com/350x200?text=No+Image" class="card-img-top" alt="{{ $post->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->body, 100) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @auth
        @if(auth()->user()->role === 'admin')
            <div class="text-center">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBlogModal">
                    Добавить запись в блог
                </button>
            </div>
        @endif
    @endauth
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