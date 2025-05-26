@extends('main')
<link rel="stylesheet" href="{{ asset('css/subscriptions-index.css') }}">
@section('content')
<div class="container my-5">
    <h1 class="mb-4">Мои подписки</h1>

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
        @forelse($userSubscriptions as $subscription)
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
                                <strong>Последняя оплата:</strong><br>
                                {{ $subscription->pivot->last_payment_date ? $subscription->pivot->last_payment_date->format('d.m.Y') : 'Нет данных' }}
                            </p>
                            <p class="mb-2">
                                <strong>Действует до:</strong><br>
                                {{ $subscription->pivot->subscription_end_date ? $subscription->pivot->subscription_end_date->format('d.m.Y') : 'Нет данных' }}
                            </p>
                            <p class="mb-0">
                                <strong>Статус:</strong><br>
                                <span class="badge bg-{{ $subscription->pivot->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $subscription->pivot->status === 'active' ? 'Активна' : 'Отменена' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        @if($subscription->pivot->status === 'active')
                            @if($subscription->pivot->subscription_end_date && $subscription->pivot->subscription_end_date->diffInDays(now()) <= 7)
                                <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 mb-2">Продлить</button>
                                </form>
                            @endif
                            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" 
                                        onclick="return confirm('Вы уверены, что хотите отменить подписку?')">
                                    Отменить
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <p class="mb-0">У вас пока нет активных подписок.</p>
                </div>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Посмотреть доступные подписки</a>
            </div>
        @endforelse
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
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
</style>
@endpush
@endsection