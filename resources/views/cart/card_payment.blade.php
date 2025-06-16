@extends('main')

<link rel="stylesheet" href="{{ asset('css/card_payment.css') }}">
<script src="{{ asset('js/card-validation.js') }}" defer></script>

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
            <input type="text" 
                                   class="form-control @error('card_number') is-invalid @enderror" 
                                   id="card_number" 
                                   name="card_number" 
                                   placeholder="1234 5678 9012 3456" 
                                   required 
                                   maxlength="19">
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="form-label">Срок действия карты</label>
            <input type="text" 
                                       class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" 
                                       name="expiry_date" 
                                       placeholder="MM/YY" 
                                       required 
                                       maxlength="5">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
        </div>
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" 
                                   class="form-control @error('cvv') is-invalid @enderror" 
                                   id="cvv" 
                                   name="cvv" 
                                   placeholder="CVV" 
                                   required 
                                   maxlength="3">
                            @error('cvv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Оплатить</button>
    </form>
</div>
@endsection