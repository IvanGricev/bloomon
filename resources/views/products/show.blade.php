@extends('main')

@section('content')
<link rel="stylesheet" href="{{ url('css/product.css') }}">
<style>
    /* Скрываем стандартные стрелочки для input type="number" */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Стили для отзывов */
    .reviews-section {
        font-family: 'Montserrat', sans-serif;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }
    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .reviews-title {
        font-size: 1.5rem;
        font-weight: 600;
    }
    .reviews-count {
        color: #666;
    }
    .review-form {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    .rating-input {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .rating-input input[type="radio"] {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        font-size: 1.2rem;
        font-weight: 600;
        color: #666;
        background: #fff;
        border: 2px solid #ddd;
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        position: relative;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input[type="radio"]:checked ~ label {
        color: #fff;
        background: #ffc107;
        border-color: #ffc107;
        transform: scale(1.1);
    }
    .rating-input label:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 0.3rem;
        font-size: 0.875rem;
        white-space: nowrap;
        z-index: 1;
    }
    .rating-hint {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.5rem;
    }
    .review-item {
        border-bottom: 1px solid #eee;
        padding: 1.5rem 0;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .review-author {
        font-weight: 600;
    }
    .review-date {
        color: #666;
        font-size: 0.9rem;
    }
    .review-rating {
        color: #ffc107;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }
    .review-text {
        color: #333;
        line-height: 1.5;
    }
    .no-reviews {
        text-align: center;
        padding: 2rem;
        color: #666;
    }
    .rating-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: #ffc107;
    }
    .rating-max {
        font-size: 1rem;
        color: #666;
    }
</style>
<div class="product-container">
    <div class="product-breadcrumbs">
        <a href="/">Главная</a> <span>/</span> <a href="{{ route('products.index') }}">Каталог</a> <span>/</span> <span>{{ $product->name }}</span>
    </div>
    <div class="product-row">
        <!-- Левая колонка: Изображения товара -->
        <div class="product-image-block">
            @if($product->images->isNotEmpty())
                <img src="{{ asset('uploads/products/' . $product->images->first()->image_path) }}" class="product-image" alt="{{ $product->name }}">
            @else
                <div class="product-image" style="background: #ddd;"></div>
            @endif
        </div>
        <!-- Правая колонка: Информация о товаре и форма заказа -->
        <div class="product-info-block">
            <div class="product-title">{{ $product->name }}</div>
            <div class="product-price-row">
                <span class="product-price">{{ number_format($product->price, 2, ',', ' ') }} руб.</span>
                @if(isset($product->old_price) && $product->old_price > $product->price)
                    <span class="product-old-price">{{ number_format($product->old_price, 2, ',', ' ') }} руб.</span>
                @endif
            </div>
            <div class="product-description">{{ $product->description }}</div>
            <div class="product-availability">
                @if($product->quantity > 0)
                    @if($product->quantity <= 20)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Осталось всего {{ $product->quantity }} {{ trans_choice('товар|товара|товаров', $product->quantity) }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            В наличии
                        </div>
                    @endif
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        Нет в наличии
                    </div>
                @endif
            </div>
            <div class="mt-4">
                @if($product->quantity > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="mb-3">
                            <div class="product-qty-label">Количество</div>
                            <div class="product-qty-group">
                                <button type="button" class="product-qty-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}" class="product-qty-input" onchange="validateQuantity(this.value)">
                                <button type="button" class="product-qty-btn" onclick="increaseQuantity()">+</button>
                            </div>
                            <small class="text-muted">Доступно: {{ $product->quantity }} {{ trans_choice('единица|единицы|единиц', $product->quantity) }}</small>
                        </div>
                        <button type="submit" class="product-cart-btn">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Добавить в корзину
                        </button>
                    </form>
                @else
                    <button class="product-cart-btn" disabled>
                        <i class="fas fa-ban me-2"></i>
                        Нет в наличии
                    </button>
                @endif
            </div>
        </div>
    </div>
    {{-- Рандомные товары --}}
    @php
        $randomProducts = \App\Models\Product::where('id', '!=', $product->id)
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();
    @endphp
    @if($randomProducts->count())
        <div class="related-products-section">
            <div class="related-products-title">Похожие товары</div>
            <div class="related-products-row">
                @foreach($randomProducts as $item)
                    <a href="{{ route('products.show', $item->id) }}" class="related-product-card">
                        <div class="related-product-image" style="background-image: url('{{ $item->images->isNotEmpty() ? asset('uploads/products/' . $item->images->first()->image_path) : 'https://via.placeholder.com/300x200' }}');"></div>
                        <div class="related-product-info">
                            <div class="related-product-name"><b>{{ $item->name }}</b></div>
                            <div class="related-product-price">{{ number_format($item->price, 2, ',', ' ') }} руб.</div>
                            <div class="related-product-availability">
                                @if($item->quantity <= 20)
                                    <span class="text-warning">Осталось {{ $item->quantity }}</span>
                                @else
                                    <span class="text-success">В наличии</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Секция отзывов --}}
    <div class="reviews-section">
        <div class="reviews-header">
            <div>
                <h2 class="reviews-title">Отзывы</h2>
                <div class="reviews-count">
                    {{ $product->reviews->count() }} {{ trans_choice('отзыв|отзыва|отзывов', $product->reviews->count()) }}
                </div>
            </div>
            @if($product->reviews->isNotEmpty())
                <div class="average-rating">
                    <div class="h4 mb-0">
                        {{ number_format($product->reviews->avg('rating'), 1) }}
                    </div>
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($product->reviews->avg('rating')))
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif
        </div>

        {{-- Форма добавления отзыва --}}
        @auth
            <div class="review-form">
                <h3 class="h5 mb-3">Оставить отзыв</h3>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ваша оценка</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                                <label for="rating{{ $i }}" title="{{ $i }} {{ trans_choice('балл|балла|баллов', $i) }}">
                                    {{ $i }}
                                </label>
                            @endfor
                        </div>
                        <div class="rating-hint mt-1">
                            <small class="text-muted">Наведите на цифру, чтобы увидеть оценку</small>
                        </div>
                        @error('rating')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="review-text" class="form-label">Ваш отзыв</label>
                        <textarea name="text" id="review-text" 
                                class="form-control @error('text') is-invalid @enderror" 
                                rows="4" 
                                placeholder="Расскажите о вашем опыте использования товара..." 
                                required></textarea>
                        @error('text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-paper-plane me-2"></i>
                        Отправить отзыв
                    </button>
                </form>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Чтобы оставить отзыв, пожалуйста, <a href="{{ route('login') }}">войдите</a> в свой аккаунт
            </div>
        @endauth

        {{-- Список отзывов --}}
        @if($product->reviews->isNotEmpty())
            <div class="reviews-list">
                @foreach($product->reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="review-author">{{ $review->user->name }}</div>
                            <div class="review-date">{{ $review->created_at->format('d.m.Y') }}</div>
                        </div>
                        <div class="review-rating">
                            <span class="rating-number">{{ $review->rating }}</span>
                            <span class="rating-max">/5</span>
                        </div>
                        <div class="review-text">{{ $review->text }}</div>
                        @if(auth()->id() === $review->user_id)
                            <div class="mt-2">
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?')">
                                        <i class="fas fa-trash-alt"></i> Удалить
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-reviews">
                <i class="far fa-comment-dots fa-3x mb-3"></i>
                <p>Пока нет отзывов. Будьте первым, кто оставит отзыв!</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const maxQuantity = {{ $product->quantity }};
    function validateQuantity(value) {
        const input = document.getElementById('quantity');
        value = parseInt(value);
        if (isNaN(value) || value < 1) {
            input.value = 1;
        } else if (value > maxQuantity) {
            input.value = maxQuantity;
        }
    }
    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < maxQuantity) {
            input.value = currentValue + 1;
        }
    }
    document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
        const quantity = parseInt(document.getElementById('quantity').value);
        if (quantity > maxQuantity) {
            e.preventDefault();
            alert('Запрошенное количество превышает доступное');
        }
    });
</script>
@endpush
@endsection