@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
@section('content')
    <h1>Управление пользователями</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @switch($user->role)
                            @case('admin') Администратор @break
                            @case('client') Клиент @break
                            @default {{ $user->role }}
                        @endswitch
                    </td>
                    <td>
                        @if($user->deleted_at)
                            <span class="text-danger">Удалён</span>
                        @else
                            <span class="text-success">Активен</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">Подробнее</a>
                        @if(!$user->deleted_at)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Восстановить пользователя?')">Восстановить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection