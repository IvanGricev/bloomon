@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">

@section('content')
    <h1>Управление акциями</h1>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-success mb-3">Создать акцию</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Скидка</th>
                <th>Начало</th>
                <th>Окончание</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promotions as $promotion)
                <tr>
                    <td>{{ $promotion->id }}</td>
                    <td>{{ $promotion->name }}</td>
                    <td>{{ $promotion->discount }}</td>
                    <td>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d.m.Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($promotion->end_date)->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                        <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection