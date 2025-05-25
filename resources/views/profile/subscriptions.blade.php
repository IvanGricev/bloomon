@extends('main')
<link rel="stylesheet" href="{{ asset('css/subscriptions.css') }}">

@section('content')
<div class="container my-5">
    <h1>Подписки</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h3>Доступные подписки</h3>
    <div class="subscription-grid">
        @foreach($allSubscriptions as $subscription)
            <div class="subscription-card">
                <div class="subscription-card-body">
                    <div class="subscription-card-title">{{ $subscription->name }}</div>
                    <div class="subscription-card-text">{{ $subscription->description }}</div>
                    <div class="subscription-card-info">
                        <span><strong>Цена:</strong> {{ number_format($subscription->price, 2, ',', ' ') }} руб.</span><br>
                        <span><strong>Период:</strong> {{ ucfirst($subscription->period) }} подписка</span><br>
                        <span><strong>Доставка:</strong> {{ $subscription->frequency }}</span>
                    </div>
                </div>
                <div class="subscription-card-footer">
                    @if($userSubscriptions->contains($subscription->id))
                        <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="subscription-btn subscription-btn-warning">Отписаться</button>
                        </form>
                        <a href="{{ route('subscription.payment.form', ['subscription_id' => $subscription->id]) }}" class="subscription-btn subscription-btn-secondary mt-2">
                            Оплатить подписку
                        </a>
                    @else
                        <form action="{{ route('subscriptions.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                            <button type="submit" class="subscription-btn subscription-btn-primary">Подписаться</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection