@extends('main')

@section('content')
<div class="container my-5">
    <h1>Оформление заказа</h1>
    
    <div class="mb-4">
        <h4>Ваш заказ</h4>
        <ul class="list-group">
            @foreach($cart as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $item['name'] }} ({{ $item['quantity'] }})</span>
                    <span>{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} руб.</span>
                </li>
            @endforeach
        </ul>
        <h5 class="mt-3">Общая сумма: {{ number_format($totalPrice, 2, ',', ' ') }} руб.</h5>
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
        <!-- Передаем общую сумму заказа -->
        <input type="hidden" name="total_price" value="{{ $totalPrice }}">

        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
    <p class="mt-3 text-muted">
        Если вы выбрали оплату картой, после оформления заказа вы будете перенаправлены на страницу для ввода данных карты.
    </p>
</div>
@endsection