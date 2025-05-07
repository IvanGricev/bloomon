@extends('main')

@section('content')
<div class="container my-5">
    <h1>Заказ #{{ $order->id }}</h1>
    <p><strong>Дата заказа:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}</p>
    <p><strong>Статус:</strong> {{ $order->status }}</p>
    <p><strong>Общая сумма:</strong> {{ number_format($order->total_price, 2, ',', ' ') }} руб.</p>
    <p><strong>Адрес доставки:</strong> {{ $order->address ?? 'Не указан' }}</p>
    <p><strong>Телефон:</strong> {{ $order->phone ?? 'Не указан' }}</p>
    <p>
        <strong>Способ оплаты:</strong> 
        @if($order->payment_method === 'cash')
            Наличными при получении
        @else
            Оплата картой онлайн
        @endif
    </p>

    <h3 class="mt-4">Состав заказа</h3>
    @if($order->orderItems->isEmpty())
        <p>В заказе нет товаров.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена за единицу</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2, ',', ' ') }} руб.</td>
                        <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} руб.</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Вернуться к заказам</a>
</div>
@endsection