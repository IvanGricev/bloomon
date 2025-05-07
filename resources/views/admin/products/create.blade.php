@extends('admin.layout')

@section('content')
<div class="container my-5">
    <h1>Создать новый товар</h1>
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
        <div class="mb-3">
             <label class="form-label">Изображения (можно выбрать несколько)</label>
             <input type="file" name="images[]" class="form-control" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Создать товар</button>
    </form>
</div>
@endsection