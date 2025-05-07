@extends('main')

@section('content')
<div class="container my-5">
    <h1>Оформление заказа</h1>
    
    <div class="mb-4">
        <h4>Ваш заказ</h4>
        <ul class="list-group">
            @foreach($cart as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <span>
                        {{ $item['name'] }} ({{ $item['quantity'] }})
                        @if(isset($item['applied_promotion']))
                            <br>
                            <small class="text-success">Акция: {{ $item['applied_promotion'] }} ({{ $item['discount'] }}% скидка)</small>
                        @endif
                    </span>
                    <span>{{ number_format($item['discounted_price'] * $item['quantity'], 2, ',', ' ') }} руб.</span>
                </li>
            @endforeach
        </ul>
        @php
            $discountTotal = $totalOriginal - $totalDiscounted;
        @endphp
        <div class="mt-3">
            <p>Сумма заказа (без скидки): <strong>{{ number_format($totalOriginal, 2, ',', ' ') }} руб.</strong></p>
            <p>Сумма скидки: <strong>{{ number_format($discountTotal, 2, ',', ' ') }} руб.</strong></p>
            <p>Общая сумма к оплате: <strong>{{ number_format($totalDiscounted, 2, ',', ' ') }} руб.</strong></p>
        </div>
    </div>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Адрес доставки</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Контактный телефон</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="delivery_date" class="form-label">Дата доставки</label>
            <input type="date" name="delivery_date" id="delivery_date" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Способ оплаты</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="cash" id="cash" checked>
                <label class="form-check-label" for="cash">
                    Наличными при получении
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
                <label class="form-check-label" for="card">
                    Оплата картой онлайн
                </label>
            </div>
        </div>
        <!-- Передаем итоговую сумму заказа -->
        <input type="hidden" name="total_price" value="{{ $totalDiscounted }}">
        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
    <p class="mt-3 text-muted">
        Если вы выбрали оплату картой, после оформления заказа вы будете перенаправлены на страницу для ввода данных карты.
    </p>
</div>
@endsection