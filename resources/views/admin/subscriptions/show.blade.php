@extends('main')

@section('content')
<div class="container my-5">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Подписка: {{ $subscription->name }}</h1>
            <div>
                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-primary">Редактировать</a>
                <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту подписку?')">Удалить</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if($subscription->image)
                        <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" alt="{{ $subscription->name }}" class="img-fluid mb-3">
                    @endif
                    <p><strong>Тип подписки:</strong> {{ $subscription->subscription_type }}</p>
                    <p><strong>Частота:</strong> 
                        @switch($subscription->frequency)
                            @case('daily') Ежедневно @break
                            @case('weekly') Еженедельно @break
                            @case('biweekly') Раз в две недели @break
                            @case('monthly') Ежемесячно @break
                            @default {{ $subscription->frequency }}
                        @endswitch
                    </p>
                    <p><strong>Период:</strong> 
                        @switch($subscription->period)
                            @case('month') Ежемесячная @break
                            @case('year') Годовая @break
                            @default {{ ucfirst($subscription->period) }}
                        @endswitch
                    </p>
                    <p><strong>Цена:</strong> {{ number_format($subscription->price, 2, ',', ' ') }} руб.</p>
                    <p><strong>Описание:</strong> {{ $subscription->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Пользователи с этой подпиской</h3>
        </div>
        <div class="card-body">
            @if($subscription->users->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Статус</th>
                            <th>Дата окончания</th>
                            <th>Последний платеж</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscription->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->pivot->status }}</td>
                                <td>{{ $user->pivot->subscription_end_date->format('d.m.Y') }}</td>
                                <td>{{ $user->pivot->last_payment_date ? $user->pivot->last_payment_date->format('d.m.Y') : 'Нет' }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Нет пользователей с этой подпиской</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-primary">Назад к списку</a>
    </div>
</div>
@endsection 