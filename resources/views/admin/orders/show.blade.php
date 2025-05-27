@extends('main')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1>Заказ #{{ $order->id }}</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h4>Информация о заказе</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Пользователь:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
                    <p><strong>Дата заказа:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}</p>
                    <p><strong>Статус:</strong> {{ $order->status }}</p>
                    <p><strong>Сумма:</strong> {{ number_format($order->total_price, 2, ',', ' ') }} руб.</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Адрес доставки:</strong> {{ $order->address ?: 'Не указан' }}</p>
                    <p><strong>Телефон:</strong> {{ $order->phone ?: 'Не указан' }}</p>
                    <p><strong>Дата доставки:</strong> {{ $order->delivery_date }}</p>
                    <p><strong>Время доставки:</strong> {{ $order->delivery_time_slot ?: 'Не указано' }}</p>
                    @if($order->delivery_preferences)
                        <p><strong>Пожелания к доставке:</strong> {{ $order->delivery_preferences }}</p>
                    @endif
                    <p><strong>Способ оплаты:</strong> {{ $order->payment_method === 'card' ? 'Картой' : 'Наличными' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h4>Состав заказа</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Количество</th>
                        <th>Цена</th>
                        <th>Итого</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2, ',', ' ') }} руб.</td>
                            <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} руб.</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                        <td><strong>{{ number_format($order->total_price, 2, ',', ' ') }} руб.</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h4>Управление заказом</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="status" class="form-label">Статус заказа</label>
                    <select name="status" id="status" class="form-select">
                        <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>Новый</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>В обработке</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Отправлен</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Доставлен</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Отменен</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
</div>
@endsection