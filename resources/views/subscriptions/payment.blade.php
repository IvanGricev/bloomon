@extends('main')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Оплата подписки: {{ $subscription->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4>Детали подписки:</h4>
                        <p><strong>Цена:</strong> {{ number_format($subscription->price, 2, ',', ' ') }} руб.</p>
                        <p><strong>Период:</strong> {{ ucfirst($subscription->period) }} подписка</p>
                        <p><strong>Частота доставки:</strong> {{ $subscription->frequency }}</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('subscriptions.process_payment', $subscription->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Номер карты</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" 
                                   placeholder="1234 5678 9012 3456" required maxlength="16">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Срок действия</label>
                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                       placeholder="MM/YY" required maxlength="5">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" 
                                       placeholder="123" required maxlength="3">
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Оплатить {{ number_format($subscription->price, 2, ',', ' ') }} руб.</button>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Форматирование номера карты
    const cardNumber = document.getElementById('card_number');
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });

    // Форматирование срока действия
    const expiryDate = document.getElementById('expiry_date');
    expiryDate.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0,2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // Форматирование CVV
    const cvv = document.getElementById('cvv');
    cvv.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
});
</script>
@endpush
@endsection 