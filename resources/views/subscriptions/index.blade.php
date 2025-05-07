@extends('main')

@section('content')
<div class="container my-5">
    <h1>Доступные подписки</h1>
    <div class="row">
        @foreach($subscriptions as $subscription)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($subscription->image)
                        <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" class="card-img-top" alt="{{ $subscription->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $subscription->name }}</h5>
                        <p class="card-text">{{ $subscription->description }}</p>
                        <p>
                            <strong>Цена:</strong> {{ number_format($subscription->price, 2, ',', ' ') }} руб.<br>
                            <strong>Период:</strong> {{ ucfirst($subscription->period) }} подписка<br>
                            <strong>Частота доставки:</strong> {{ $subscription->frequency }}
                        </p>
                    </div>
                    <div class="card-footer">
                        @auth
                            <form action="{{ route('subscriptions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                <button type="submit" class="btn btn-primary btn-block">Подписаться</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-block">Войдите для подписки</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection