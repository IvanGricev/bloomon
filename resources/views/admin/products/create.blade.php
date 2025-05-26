@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin-product-edit.css') }}">
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

        <div class="mb-3">
            <label for="quantity" class="form-label">Начальное количество</label>
            <div class="input-group">
                <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', 0) }}" min="0" required>
                <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
            </div>
            <small class="text-muted">Укажите начальное количество товара на складе</small>
        </div>

        <!-- Поле для загрузки нескольких изображений -->
        <div class="mb-3">
             <label class="form-label">Изображения (можно выбрать несколько)</label>
             <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Создать товар</button>
    </form>
</div>

@push('scripts')
<script>
    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 0) {
            input.value = currentValue - 1;
        }
    }
    
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        input.value = currentValue + 1;
    }
</script>
@endpush

@push('styles')
<style>
    .input-group .form-control {
        text-align: center;
    }
    
    .input-group .btn {
        width: 40px;
    }
</style>
@endpush
@endsection