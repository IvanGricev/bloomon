@extends('main')

<link rel="stylesheet" href="{{ asset('css/support-index.css') }}">

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Мои запросы в поддержку</h1>
        <a href="{{ route('support.create') }}" class="btn btn-primary">Обратиться в поддержку</a>
    </div>

    @if($tickets->isEmpty())
        <div class="alert alert-info">
            У вас пока нет запросов в поддержку.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Тема</th>
                        {{-- <th>Сообщение</th> --}}
                        <th>Статус</th>
                        <th>Создан</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->subject }}</td>
                            {{-- <td>{{ Str::limit($ticket->message, 20) }}</td> --}}
                            <td>
                                <span class="badge bg-{{ $ticket->status === 'new' ? 'primary' : 
                                    ($ticket->status === 'in_progress' ? 'warning' : 
                                    ($ticket->status === 'answered' ? 'success' : 'secondary')) }}">
                                    {{ $ticket->status_text }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <a href="{{ route('support.show', $ticket) }}" 
                                   class="btn btn-sm btn-primary">
                                    Просмотреть
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 