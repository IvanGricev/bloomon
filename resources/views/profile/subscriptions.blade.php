@extends('main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Мои подписки</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($userSubscriptions as $subscription)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($subscription->image)
                    <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" alt="{{ $subscription->name }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $subscription->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ $subscription->description }}</p>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">
                            Последняя оплата: {{ $subscription->pivot->last_payment_date ? $subscription->pivot->last_payment_date->format('d.m.Y') : 'Нет данных' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Действует до: {{ $subscription->pivot->subscription_end_date ? $subscription->pivot->subscription_end_date->format('d.m.Y') : 'Нет данных' }}
                        </p>
                        <p class="text-sm font-medium {{ $subscription->pivot->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                            Статус: {{ $subscription->pivot->status === 'active' ? 'Активна' : 'Отменена' }}
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold">{{ number_format($subscription->price, 2) }} ₽</span>
                        
                        @if($subscription->pivot->status === 'active')
                            @if($subscription->pivot->subscription_end_date && $subscription->pivot->subscription_end_date->diffInDays(now()) <= 7)
                                <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                        Продлить
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Отменить
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <p class="text-gray-500 text-center">У вас пока нет активных подписок.</p>
            </div>
        @endforelse
    </div>

    @if($allSubscriptions->isNotEmpty())
        <h2 class="text-2xl font-bold mt-12 mb-6">Доступные подписки</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($allSubscriptions as $subscription)
                @if(!$userSubscriptions->contains($subscription->id))
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($subscription->image)
                            <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" alt="{{ $subscription->name }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-2">{{ $subscription->name }}</h2>
                            <p class="text-gray-600 mb-4">{{ $subscription->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold">{{ number_format($subscription->price, 2) }} ₽</span>
                                <form action="{{ route('subscriptions.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                        Подписаться
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection