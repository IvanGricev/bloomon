@extends('main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Детали заказа #{{ $order->id }}</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Информация о заказе</h2>
                <p><strong>Статус:</strong> {{ $order->status }}</p>
                <p><strong>Дата заказа:</strong> {{ $order->order_date->format('d.m.Y H:i') }}</p>
                <p><strong>Дата доставки:</strong> {{ $order->delivery_date->format('d.m.Y') }}</p>
                <p><strong>Время доставки:</strong> {{ $order->delivery_time_slot }}</p>
                <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                <p><strong>Телефон:</strong> {{ $order->delivery_phone }}</p>
                <p><strong>Способ оплаты:</strong> 
                    @if($order->payment_method === 'cash')
                        Наличными при получении
                    @else
                        Оплата картой онлайн
                    @endif
                </p>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Сумма заказа</h2>
                <p class="text-2xl font-bold text-primary">{{ number_format($order->total_price, 2) }} ₽</p>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Состав заказа</h3>
            @if($order->items->isEmpty())
                <p>В заказе нет товаров.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Товар</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Количество</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена за единицу</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($item->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="h-16 w-16 object-cover rounded">
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->product->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($item->price, 2) }} ₽
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($item->price * $item->quantity, 2) }} ₽
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($order->status === 'pending' && $order->payment_method === 'card')
            <div class="mt-8">
                <a href="{{ route('card.payment.form', ['order_id' => $order->id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                    Оплатить заказ
                </a>
            </div>
        @endif
    </div>
</div>
@endsection