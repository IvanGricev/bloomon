@extends('admin.layout')

@section('content')
<div class="container my-5">
    <h1>Создать новый план подписки</h1>
    <form action="{{ route('admin.subscriptions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Название подписки</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Тип подписки (например, «Романтические», «Свадебные»)</label>
            <input type="text" name="subscription_type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Частота доставки</label>
            <input type="text" name="frequency" class="form-control" placeholder="daily, weekly и т.д." required>
        </div>
        <div class="mb-3">
            <label class="form-label">Период подписки</label>
            <input type="text" name="period" class="form-control" placeholder="month или year" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Цена подписки</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Изображение подписки</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Создать подписку</button>
    </form>
</div>
@endsection