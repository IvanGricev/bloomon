@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-product-edit.css') }}">
@section('content')
<div class="container my-5">
    <h1>Редактировать подписку</h1>
    
    <!-- Вывод ошибок -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Название подписки</label>
            <input type="text" name="name" class="form-control" value="{{ $subscription->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Тип подписки</label>
            <input type="text" name="subscription_type" class="form-control" value="{{ $subscription->subscription_type }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Частота доставки</label>
            <input type="text" name="frequency" class="form-control" value="{{ $subscription->frequency }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Период подписки</label>
            <input type="text" name="period" class="form-control" value="{{ $subscription->period }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Цена подписки</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $subscription->price }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control">{{ $subscription->description }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Изображение подписки</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if($subscription->image)
                <p class="mt-2">Текущее изображение:</p>
                <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" alt="{{ $subscription->name }}" style="max-height: 80px;">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Обновить подписку</button>
    </form>
</div>
@endsection