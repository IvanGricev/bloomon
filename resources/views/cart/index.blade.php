@extends('main')

@section('content')
<div class="container my-5">
    <h1>Ваша корзина</h1>

    @if(count($cart) === 0)
        <p>Ваша корзина пуста.</p>
    @else
        @foreach($cart as $item)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $item['name'] }}</h5>
                    <p>Оригинальная цена: {{ $item['original_price'] }} руб.</p>
                    @if(isset($item['discount']) && $item['discount'])
                        <p>
                          Акция: {{ $item['applied_promotion'] }} ({{ $item['discount'] }}% скидка)
                          <br>
                          Цена со скидкой: <strong>{{ $item['discounted_price'] }} руб.</strong>
                        </p>
                    @endif
                    <p>Количество: {{ $item['quantity'] }}</p>
                    
                    <!-- Контролы для изменения количества -->
                    <div class="d-flex align-items-center">
                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="productId" value="{{ $item['id'] }}">
                            <button type="submit" name="action" value="decrement" class="btn btn-sm btn-secondary me-2">-</button>
                        </form>
                        <span class="mx-2">{{ $item['quantity'] }}</span>
                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="productId" value="{{ $item['id'] }}">
                            <button type="submit" name="action" value="increment" class="btn btn-sm btn-secondary ms-2">+</button>
                        </form>
                        <!-- Форма для удаления товара -->
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline ms-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Итоговая сумма -->
        @php
            $totalOriginal = collect($cart)->sum(function($item) {
                return $item['original_price'] * $item['quantity'];
            });
            $totalDiscounted = collect($cart)->sum(function($item) {
                return $item['discounted_price'] * $item['quantity'];
            });
        @endphp

        <div class="alert alert-info">
            Сумма заказа (без скидки): <strong>{{ $totalOriginal }} руб.</strong><br>
            Сумма заказа (со скидкой): <strong>{{ $totalDiscounted }} руб.</strong>
        </div>
    @endif

    <a href="{{ route('cart.checkout') }}" class="btn btn-primary">Оформить заказ</a>
</div>
@endsection