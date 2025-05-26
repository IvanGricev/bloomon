@extends('main')

<link rel="stylesheet" href="{{ asset('css/admin-user-show.css') }}">

@section('content')
<div class="container-user">
    <h1>Профиль пользователя: {{ $user->name }}</h1>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Роль:</strong> {{ $user->role }}</p>
    <p>
        <strong>Статус:</strong>
        @if($user->deleted_at)
            Удалён
        @else
            Активен
        @endif
    </p>
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Назад к списку</a>
</div>
@endsection