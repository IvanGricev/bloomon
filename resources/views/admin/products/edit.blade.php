@extends('main')

<link rel="stylesheet" href="{{ asset('css/admin-product-edit.css') }}">

@section('content')
<div class="container my-5">
    <h1>Редактировать товар</h1>

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

    <!-- Форма для редактирования основных данных товара и добавления новых изображений -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Поля редактирования товара -->
        <div class="mb-3">
            <label for="name" class="form-label">Название товара</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $product->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" name="description" id="description" rows="3">{{ $product->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="number" step="0.01" class="form-control" name="price" id="price" value="{{ $product->price }}" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Категория</label>
            <select class="form-select" name="category_id" id="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Количество</label>
            <div class="input-group">
                <input type="number" class="form-control" name="quantity" id="quantity" value="{{ $product->quantity }}" min="0" required>
                <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
            </div>
            <small class="text-muted">Текущее количество: {{ $product->quantity }} {{ trans_choice('единица|единицы|единиц', $product->quantity) }}</small>
        </div>
        
        <!-- Поле для загрузки новых изображений -->
        <div class="mb-3">
            <label class="form-label">Добавить новые изображения (можно выбрать несколько)</label>
            <input type="file" name="new_images[]" class="form-control" multiple>
        </div>
        
        <button type="submit" class="btn btn-primary">Обновить товар</button>
    </form>
    
    <hr>
    
    <!-- Вывод уже загруженных изображений -->
    <h3>Существующие изображения</h3>
    <div class="row">
        @foreach($product->images as $image)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ asset('uploads/products/' . $image->image_path) }}" class="card-img-top" alt="Изображение товара">
                    <div class="card-body text-center">
                        <!-- Форма для удаления изображения -->
                        <form action="{{ route('admin.products.image.destroy', ['product' => $product->id, 'image' => $image->id]) }}" method="POST" onsubmit="return confirm('Удалить изображение?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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


@endsection