@extends('main')

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
    <div class="row">
        @foreach($allSubscriptions as $subscription)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $subscription->name }}</h5>
                        <p class="card-text">{{ $subscription->description }}</p>
                        <p>
                            <strong>Цена:</strong> {{ number_format($subscription->price, 2, ',', ' ') }} руб.<br>
                            <strong>Период:</strong> {{ ucfirst($subscription->period) }} подписка<br>
                            <strong>Доставка:</strong> {{ $subscription->frequency }}
                        </p>
                    </div>
                    <div class="card-footer">
                        @if($userSubscriptions->contains($subscription->id))
                            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning btn-block">Отписаться</button>
                            </form>
                            <!-- Если оплата требуется, можно добавить ссылку на оплату -->
                            <a href="{{ route('subscription.payment.form', ['subscription_id' => $subscription->id]) }}" class="btn btn-secondary btn-block mt-2">
                                Оплатить подписку
                            </a>
                        @else
                            <form action="{{ route('subscriptions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                <button type="submit" class="btn btn-primary btn-block">Подписаться</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h3>Мои подписки</h3>
    @if($userSubscriptions->isEmpty())
        <p>Вы ещё не подписаны ни на одну подписку.</p>
    @else
        <ul class="list-group">
            @foreach($userSubscriptions as $sub)
                <li class="list-group-item">
                    <strong>{{ $sub->name }}</strong> – {{ $sub->description }}
                    ({{ number_format($sub->price, 2, ',', ' ') }} руб., {{ ucfirst($sub->period) }}, {{ $sub->frequency }})
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection