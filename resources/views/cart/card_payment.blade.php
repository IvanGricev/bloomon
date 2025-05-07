@extends('main')

@section('content')
<div class="container my-5">
    <h1>Оплата картой</h1>
    <p>Пожалуйста, введите данные вашей карты для завершения оплаты.</p>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p>Сумма заказа (без скидки): <strong>{{ number_format($originalTotal, 2, ',', ' ') }} руб.</strong></p>
            <p>Сумма скидки: <strong>{{ number_format($discountTotal, 2, ',', ' ') }} руб.</strong></p>
            <p>Итоговая сумма к оплате: <strong>{{ number_format($discountedTotal, 2, ',', ' ') }} руб.</strong></p>
        </div>
    </div>

    <form action="{{ route('card.payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="mb-3">
            <label for="card_number" class="form-label">Номер карты</label>
            <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Введите номер карты" required>
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="form-label">Срок действия карты</label>
            <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="MM/YY" required>
        </div>
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" name="cvv" id="cvv" class="form-control" placeholder="CVV" required>
        </div>
        <button type="submit" class="btn btn-primary">Оплатить</button>
    </form>
</div>
@endsection