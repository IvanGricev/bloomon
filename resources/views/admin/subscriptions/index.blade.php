@extends('main')

@section('content')
<div class="container my-5">
    <h1>Управление подписками</h1>
    
    <!-- Вывод flash сообщений о успехе/ошибке -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary mb-3">Создать новую подписку</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Тип</th>
                <th>Частота</th>
                <th>Период</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->id }}</td>
                    <td>{{ $subscription->name }}</td>
                    <td>{{ $subscription->subscription_type }}</td>
                    <td>{{ $subscription->frequency }}</td>
                    <td>{{ $subscription->period }}</td>
                    <td>{{ number_format($subscription->price, 2, ',', ' ') }} руб.</td>
                    <td>
                        <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить подписку?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection