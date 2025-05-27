@extends('main')
<link rel="stylesheet" href="{{ asset('css/support-show.css') }}">
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ $ticket->subject }}</h2>
                    <span class="badge bg-{{ $ticket->status === 'new' ? 'primary' : 
                        ($ticket->status === 'in_progress' ? 'warning' : 
                        ($ticket->status === 'answered' ? 'success' : 'secondary')) }}">
                        {{ $ticket->status_text }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="ticket-messages">
                        <!-- Первое сообщение (создание тикета) -->
                        <div class="message-row user">
                            <div class="message-avatar" style="display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.3rem;color:#d97c6a;background:#fff0ee;">
                                {{ mb_substr($ticket->user->name,0,1) }}
                            </div>
                            <div class="message user">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $ticket->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $ticket->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                </div>
                                <div class="message-content mt-2">
                                    {{-- $ticket->message убрано, так как тикет больше не содержит это поле --}}
                                </div>
                                @if($ticket->attachments->isNotEmpty())
                                    <div class="attachments mt-2">
                                        @foreach($ticket->attachments as $attachment)
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
                        </div>

                        <!-- Последующие сообщения -->
                        @foreach($messages as $message)
                            <div class="message-row user">
                                <div class="message-avatar" style="display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.3rem;color:#d97c6a;background:#fff0ee;">
                                    {{ mb_substr($message->user->name,0,1) }}
                                </div>
                                <div class="message user">
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
                            </div>
                        @endforeach
                    </div>

                    @if($ticket->status !== 'closed')
                        <form action="{{ route('support.message.store', $ticket) }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="message" class="form-label">Ваше сообщение</label>
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
                                <a href="{{ route('support.index') }}" class="btn btn-secondary">Назад к списку</a>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info mt-4">
                            Этот тикет закрыт. Для нового вопроса создайте новый тикет.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 