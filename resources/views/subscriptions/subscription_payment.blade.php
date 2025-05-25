@extends('main')
<link rel="stylesheet" href="{{ asset('css/card_payment.css') }}">

@section('content')
<div class="container my-5">
    <h1>Оплата подписки</h1>
    <p>Введите данные карточки для оплаты подписки.</p>
    
    <form action="{{ route('subscription.payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="subscription_id" value="{{ $subscriptionId }}">
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
        <button type="submit" class="btn btn-primary">Оплатить подписку</button>
    </form>
</div>
@endsection