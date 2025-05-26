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