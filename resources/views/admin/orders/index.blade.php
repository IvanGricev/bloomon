@extends('main')

@section('content')
    <h1>Управление заказами</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Дата заказа</th>
                <th>Статус</th>
                <th>Сумма</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->total_price }} руб.</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">Подробнее</a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection