@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
@section('content')
    <h1>Мои заказы</h1>
    @if($orders->isEmpty())
        <p>У вас пока нет заказов.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}</td>
                        <td>{{ $order->total_price }} руб.</td>
                        <td>
                            @switch($order->status)
                                @case('new') Новый @break
                                @case('pending') Ожидает оплаты @break
                                @case('processing') В обработке @break
                                @case('shipped') Отправлен @break
                                @case('delivered') Доставлен @break
                                @case('completed') Выполнен @break
                                @case('cancelled') Отменен @break
                                @case('paid') Оплачен @break
                                @default {{ $order->status }}
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Подробнее</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection