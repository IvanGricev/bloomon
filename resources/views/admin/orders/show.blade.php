@extends('main')

@section('content')
    <h1>Заказ #{{ $order->id }}</h1>
    <p><strong>Пользователь:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
    <p><strong>Дата заказа:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}</p>
    <p><strong>Статус:</strong> {{ $order->status }}</p>
    <p><strong>Сумма:</strong> {{ $order->total_price }} руб.</p>

    <h3>Состав заказа</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Количество</th>
                <th>Цена</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }} руб.</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Изменить статус заказа</h3>
    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select id="status" name="status" class="form-select">
                <option value="new" @if($order->status === 'new') selected @endif>Новый</option>
                <option value="processing" @if($order->status === 'processing') selected @endif>В обработке</option>
                <option value="delivered" @if($order->status === 'delivered') selected @endif>Доставлен</option>
                <option value="cancelled" @if($order->status === 'cancelled') selected @endif>Отменён</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
@endsection