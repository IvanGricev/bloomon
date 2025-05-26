@extends('main')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Доступные подписки</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @foreach($subscriptions as $subscription)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">    
                    @if($subscription->image)
                        <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" 
                             class="card-img-top" 
                             alt="{{ $subscription->name }}"
                             style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $subscription->name }}</h5>
                        <p class="card-text text-muted">{{ $subscription->description }}</p>
                        <div class="subscription-details">
                            <p class="mb-2">
                                <strong>Цена:</strong> 
                                <span class="text-primary">{{ number_format($subscription->price, 2, ',', ' ') }} руб.</span>
                            </p>
                            <p class="mb-2">
                                <strong>Период:</strong> 
                                {{ ucfirst($subscription->period) }} подписка
                            </p>
                            <p class="mb-0">
                                <strong>Частота доставки:</strong> 
                                {{ $subscription->frequency }}
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        @auth
                            @if(auth()->user()->subscriptions->contains($subscription->id))
                                <button class="btn btn-secondary w-100" disabled>Вы уже подписаны</button>
                            @else
                                <a href="{{ route('subscriptions.payment', $subscription->id) }}" 
                                   class="btn btn-accent w-100">Подписаться</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-accent w-100">Войдите для подписки</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .subscription-details {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 1rem;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
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
@endsection