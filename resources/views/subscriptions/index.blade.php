@extends('main')

<link rel="stylesheet" href="{{ asset('css/subscriptions-index.css') }}">

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
                                @switch($subscription->period)
                                    @case('month') Ежемесячная @break
                                    @case('year') Годовая @break
                                    @default {{ ucfirst($subscription->period) }}
                                @endswitch подписка
                            </p>
                            <p class="mb-0">
                                <strong>Частота доставки:</strong> 
                                @switch($subscription->frequency)
                                    @case('daily') Ежедневно @break
                                    @case('weekly') Еженедельно @break
                                    @case('biweekly') Раз в две недели @break
                                    @case('monthly') Ежемесячно @break
                                    @default {{ $subscription->frequency }}
                                @endswitch
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

@endsection