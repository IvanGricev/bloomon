@extends('main')

@section('content')
<div class="container my-5">
    <h1>Создать новый товар</h1>
    
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
             <label class="form-label">Название</label>
             <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
             <label class="form-label">Описание</label>
             <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
             <label class="form-label">Цена</label>
             <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <!-- Поле выбора категории -->
        <div class="mb-3">
            <label class="form-label">Категория</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Поле для загрузки нескольких изображений -->
        <div class="mb-3">
             <label class="form-label">Изображения (можно выбрать несколько)</label>
             <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Создать товар</button>
    </form>
</div>
@endsection