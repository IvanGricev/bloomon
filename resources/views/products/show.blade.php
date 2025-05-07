@extends('main')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Левая колонка: Изображение товара -->
        <div class="col-md-6">
            @if($product->photo)
                <img src="{{ $product->photo }}" class="img-fluid" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/600x400?text=Нет+изображения" class="img-fluid" alt="{{ $product->name }}">
            @endif
        </div>
        
        <!-- Правая колонка: Информация о товаре -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">Категория: {{ $product->category->name ?? 'Не задано' }}</p>
            <h2 class="mt-3">{{ number_format($product->price, 2, ',', ' ') }} руб.</h2>
            <p class="mt-4">{{ $product->description }}</p>
            
            <!-- Форма добавления товара в корзину -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">Количество</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" value="1" min="1">
                </div>
                <button type="submit" class="btn btn-primary">Добавить в корзину</button>
            </form>
        </div>
    </div>

    <hr>

    <!-- Секция Отзывов -->
    <div class="reviews my-5">
        <h3>Отзывы</h3>
        
        @if($product->reviews->isEmpty())
            <p>Отзывов пока нет.</p>
        @else
            @foreach($product->reviews as $review)
                <div class="review mb-3 p-3 border rounded">
                    <h5>{{ $review->user->name }}</h5>
                    <p class="mb-1">
                        @for($i = 1; $i <= $review->rating; $i++)
                            <span class="text-warning">&#9733;</span>
                        @endfor
                        @for($i = 1; $i <= (5 - $review->rating); $i++)
                            <span class="text-secondary">&#9733;</span>
                        @endfor
                    </p>
                    <p>{{ $review->text }}</p>
                    <small class="text-muted">Дата: {{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}</small>
                </div>
            @endforeach
        @endif

        <!-- Форма добавления отзыва для авторизованного пользователя -->
        @auth
            <div class="mt-4">
                <h4>Добавить отзыв</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="rating" class="form-label">Оценка</label>
                        <select name="rating" id="rating" class="form-select" required>
                            <option value="">Выберите оценку</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Отзыв</label>
                        <textarea name="text" id="text" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Отправить отзыв</button>
                </form>
            </div>
        @else
            <p><a href="{{ route('login') }}">Войдите</a>, чтобы оставить отзыв.</p>
        @endauth
    </div>

    <!-- Опционально: Секция Похожих товаров (если реализовано) -->
    <!-- @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <div class="related-products my-5">
            <h3>Похожие товары</h3>
            <div class="row">
                @foreach($relatedProducts as $relProduct)
                    <div class="col-md-3">
                        <div class="card mb-3">
                            @if($relProduct->photo)
                                <img src="{{ $relProduct->photo }}" class="card-img-top" alt="{{ $relProduct->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=Нет+изображения" class="card-img-top" alt="{{ $relProduct->name }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $relProduct->name }}</h5>
                                <p class="card-text"><strong>{{ number_format($relProduct->price, 2, ',', ' ') }} руб.</strong></p>
                                <a href="{{ route('products.show', $relProduct->id) }}" class="btn btn-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif -->
</div>
@endsection