@extends('main')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Оплата подписки: {{ $subscription->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="subscription-summary mb-4">
                        <h4 class="mb-3">Детали подписки:</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Цена:</strong>
                                    <span class="text-accent">{{ number_format($subscription->price, 2, ',', ' ') }} руб.</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Период:</strong>
                                    {{ ucfirst($subscription->period) }} подписка
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Частота доставки:</strong>
                                    {{ $subscription->frequency }}
                                </p>
                            </div>
                        </div>
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
                            <input type="text" 
                                   class="form-control @error('card_number') is-invalid @enderror" 
                                   id="card_number" 
                                   name="card_number" 
                                   placeholder="1234 5678 9012 3456" 
                                   required 
                                   maxlength="16">
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Срок действия</label>
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
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" 
                                       class="form-control @error('cvv') is-invalid @enderror" 
                                       id="cvv" 
                                       name="cvv" 
                                       placeholder="123" 
                                       required 
                                       maxlength="3">
                                @error('cvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-accent btn-lg">
                                Оплатить {{ number_format($subscription->price, 2, ',', ' ') }} руб.
                            </button>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-accent">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .subscription-summary {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }
    .form-control:focus {
        border-color: #d97c6a;
        box-shadow: 0 0 0 0.2rem rgba(217, 124, 106, 0.25);
    }
    .text-accent {
        color: #d97c6a;
    }
    .btn-accent {
        background-color: #d97c6a;
        border-color: #d97c6a;
        color: white;
    }
    .btn-accent:hover {
        background-color: #c56b5a;
        border-color: #c56b5a;
        color: white;
    }
    .btn-outline-accent {
        color: #d97c6a;
        border-color: #d97c6a;
    }
    .btn-outline-accent:hover {
        background-color: #d97c6a;
        border-color: #d97c6a;
        color: white;
    }
</style>
@endpush

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