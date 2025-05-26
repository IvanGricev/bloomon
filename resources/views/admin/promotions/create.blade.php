@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-product-edit.css') }}">
@section('content')
<div class="container my-5">
    <h1>Создать акцию</h1>
    
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

    <form action="{{ route('admin.promotions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Название акции</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Описание акции</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Скидка (%)</label>
            <input type="number" name="discount" class="form-control" min="0" max="100" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Дата начала</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Дата окончания</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Применимо к категориям</label>
            <select name="category_ids[]" class="form-control" multiple required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Используйте Ctrl или Command для выбора нескольких категорий.</small>
        </div>
        <button type="submit" class="btn btn-primary">Создать акцию</button>
    </form>
</div>
@endsection