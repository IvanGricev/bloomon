@extends('main')

<link rel="stylesheet" href="{{ asset('css/admin-user-show.css') }}">

@section('content')
<div class="container my-5">
    <div class="card mb-4">
        <div class="card-header">
            <h1>Профиль пользователя: {{ $user->name }}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Роль:</strong> {{ $user->role }}</p>
                    <p>
                        <strong>Статус:</strong>
                        @if($user->deleted_at)
                            <span class="text-danger">Удалён</span>
                        @else
                            <span class="text-success">Активен</span>
                        @endif
                    </p>
                    <p><strong>Дата регистрации:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
                    @if($user->deleted_at)
                        <p><strong>Дата удаления:</strong> {{ $user->deleted_at->format('d.m.Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Заказы пользователя</h3>
        </div>
        <div class="card-body">
            @if($user->orders->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->order_date->format('d.m.Y') }}</td>
                                <td>{{ number_format($order->total_price, 2, ',', ' ') }} руб.</td>
                                <td>
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Завершен</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>У пользователя нет заказов</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Подписки пользователя</h3>
        </div>
        <div class="card-body">
            @if($user->subscriptions->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Дата окончания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->name }}</td>
                                <td>
                                    <form action="{{ route('admin.users.subscriptions.status', ['userId' => $user->id, 'subscriptionId' => $subscription->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="active" {{ $subscription->pivot->status == 'active' ? 'selected' : '' }}>Активна</option>
                                            <option value="paused" {{ $subscription->pivot->status == 'paused' ? 'selected' : '' }}>Приостановлена</option>
                                            <option value="cancelled" {{ $subscription->pivot->status == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $subscription->pivot->subscription_end_date->format('d.m.Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>У пользователя нет активных подписок</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Запросы в поддержку</h3>
        </div>
        <div class="card-body">
            @if($user->supportTickets->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Тема</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->supportTickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->status }}</td>
                                <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>У пользователя нет запросов в поддержку</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Назад к списку</a>
        @if($user->deleted_at)
            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Восстановить пользователя</button>
            </form>
        @else
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">Удалить пользователя</button>
            </form>
        @endif
    </div>
</div>
@endsection