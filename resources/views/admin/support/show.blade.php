@extends('main')
<link rel="stylesheet" href="{{ asset('css/support-user-show.css') }}">
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ $ticket->subject }}</h2>
                    <div>
                        <form action="{{ route('admin.support.status.update', $ticket) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            <select name="status" 
                                    class="form-select form-select-sm d-inline-block w-auto" 
                                    onchange="this.form.submit()">
                                <option value="new" {{ $ticket->status === 'new' ? 'selected' : '' }}>Новый</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>На рассмотрении</option>
                                <option value="answered" {{ $ticket->status === 'answered' ? 'selected' : '' }}>Отвечен</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Закрыт</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="ticket-messages">
                        @foreach($messages as $message)
                            <div class="message {{ $message->user->is_admin ? 'operator' : 'user' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $message->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $message->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                </div>
                                <div class="message-content mt-2">
                                    {{ $message->message }}
                                </div>
                                @if($message->attachments->isNotEmpty())
                                    <div class="attachments mt-2">
                                        @foreach($message->attachments as $attachment)
                                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                                <div class="attachment-image mb-2">
                                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                                                       target="_blank" 
                                                       class="image-preview">
                                                        <img src="{{ asset('storage/' . $attachment->file_path) }}" 
                                                             alt="{{ $attachment->original_name }}"
                                                             class="img-thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-secondary me-2">
                                                    <i class="fas fa-paperclip"></i> {{ $attachment->original_name }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($ticket->status !== 'closed')
                        <form action="{{ route('admin.support.message.store', $ticket) }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="message" class="form-label">Ваш ответ</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="3" 
                                          required></textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="attachments" class="form-label">Вложения (опционально)</label>
                                <input type="file" 
                                       class="form-control @error('attachments.*') is-invalid @enderror" 
                                       id="attachments" 
                                       name="attachments[]" 
                                       multiple 
                                       accept="image/*">
                                <div class="form-text">Можно загрузить несколько изображений. Максимальный размер каждого файла - 5MB.</div>
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">Назад к списку</a>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info mt-4">
                            Этот тикет закрыт.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Информация о пользователе -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Информация о пользователе</h3>
                </div>
                <div class="card-body">
                    <h4>{{ $user->name }}</h4>
                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-1"><strong>Телефон:</strong> {{ $user->phone ?? 'Не указан' }}</p>
                    <p class="mb-1"><strong>Дата регистрации:</strong> {{ $user->created_at->format('d.m.Y') }}</p>
                </div>
            </div>

            <!-- Последние заказы -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Последние заказы</h3>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <p class="text-muted">Нет заказов</p>
                    @else
                        <div class="list-group">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Заказ #{{ $order->id }}</h6>
                                        <small>{{ $order->created_at->format('d.m.Y') }}</small>
                                    </div>
                                    <p class="mb-1">{{ number_format($order->total_price, 2, ',', ' ') }} руб.</p>
                                    <small class="text-muted">
                                        @switch($order->status)
                                            @case('pending') Ожидает оплаты @break
                                            @case('processing') В обработке @break
                                            @case('completed') Выполнен @break
                                            @case('cancelled') Отменён @break
                                            @case('new') Новый @break
                                            @case('shipped') Отправлен @break
                                            @case('delivered') Доставлен @break
                                            @default {{ $order->status }}
                                        @endswitch
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Подписки -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Подписки</h3>
                </div>
                <div class="card-body">
                    @if($subscriptions->isEmpty())
                        <p class="text-muted">Нет активных подписок</p>
                    @else
                        <div class="list-group">
                            @foreach($subscriptions as $subscription)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $subscription->name }}</h6>
                                        <small>
                                            @switch($subscription->pivot->status)
                                                @case('active') Активна @break
                                                @case('paused') Приостановлена @break
                                                @case('cancelled') Отменена @break
                                                @default {{ $subscription->pivot->status }}
                                            @endswitch
                                        </small>
                                    </div>
                                    <p class="mb-1">{{ number_format($subscription->price, 2, ',', ' ') }} руб./мес.</p>
                                    <small class="text-muted">
                                        До {{ $subscription->pivot->subscription_end_date->format('d.m.Y') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .message {
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
    }

    .message-content {
        white-space: pre-wrap;
    }

    .attachments {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .attachment-image {
        position: relative;
    }

    .attachment-image img {
        transition: transform 0.2s;
    }

    .attachment-image img:hover {
        transform: scale(1.05);
    }

    .image-preview {
        display: inline-block;
    }
</style>
@endpush
@endsection 