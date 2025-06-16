@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-product-edit.css') }}">
@section('content')
<div class="container my-5">
    <h1>Создать новую подписку</h1>
    
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
            <input type="text" name="frequency" class="form-control" placeholder="ежедневно, еженедельно и т.д." required>
        </div>
        <div class="mb-3">
            <label class="form-label">Период подписки</label>
            <input type="text" name="period" class="form-control" placeholder="месяц или год" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Цена подписки</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Создать подписку</button>
    </form>
</div>
@endsection