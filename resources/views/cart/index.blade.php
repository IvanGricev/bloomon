@extends('main')

<link rel="stylesheet" href="{{ asset('css/cart.css') }}">

@section('content')
<div class="container my-5">
    <div class="cart-products-panel">
        <h1>Ваша корзина</h1>
        @if(count($cart) === 0)
            <p>Ваша корзина пуста.</p>
        @else
            @foreach($cart as $item)
                @php
                    $product = \App\Models\Product::with('images')->find($item['id']);
                    $image = ($product && $product->images->isNotEmpty())
                        ? asset('uploads/products/' . $product->images->first()->image_path)
                        : 'https://via.placeholder.com/120x160';
                @endphp
                <div class="cart-item">
                    <div class="cart-item-img">
                        <img src="{{ $image }}" alt="{{ $item['name'] }}">
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-title">{{ $item['name'] }}</div>
                        <div class="cart-item-prices">
                            <span class="cart-item-label">Оригинальная цена:</span> {{ $item['original_price'] }} руб.<br>
                            @if(isset($item['discount']) && $item['discount'])
                                <span class="discount">Акция: {{ $item['applied_promotion'] }} ({{ $item['discount'] }}% скидка)</span><br>
                                <span class="cart-item-label">Цена со скидкой:</span> <strong>{{ $item['discounted_price'] }} руб.</strong>
                            @endif
                        </div>
                        <div class="cart-item-qty"><span class="cart-item-label">Количество:</span> {{ $item['quantity'] }}</div>
                        <div class="cart-item-controls">
                            <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="productId" value="{{ $item['id'] }}">
                                <button type="submit" name="action" value="decrement">-</button>
                            </form>
                            <span>{{ $item['quantity'] }}</span>
                            <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="productId" value="{{ $item['id'] }}">
                                <button type="submit" name="action" value="increment">+</button>
                            </form>
                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="cart-summary-panel">
        <div class="cart-summary-title">Итого</div>
        @php
            $totalOriginal = collect($cart)->sum(function($item) {
                return $item['original_price'] * $item['quantity'];
            });
            $totalDiscounted = collect($cart)->sum(function($item) {
                return $item['discounted_price'] * $item['quantity'];
            });
            $discount = $totalOriginal - $totalDiscounted;
        @endphp
        <ul class="cart-summary-list">
            <li><span>Сумма</span><span class="total">{{ $totalOriginal }} руб.</span></li>
            <li><span>Скидка</span><span class="discount">-{{ $discount }} руб.</span></li>
            <li><span>Итого</span><span class="final">{{ $totalDiscounted }} руб.</span></li>
        </ul>
        <a href="{{ route('cart.checkout') }}" class="cart-summary-btn">Оформить заказ</a>
    </div>
</div>
@endsection