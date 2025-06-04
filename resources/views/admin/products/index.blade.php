@extends('main')

<link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление товарами</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Добавить товар</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Категория</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('uploads/products/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/50" alt="No image">
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 2, ',', ' ') }} руб.</td>
                        <td>
                            <form action="{{ route('admin.products.quantity.update', $product->id) }}" 
                                  method="POST" 
                                  class="d-flex align-items-center">
                                @csrf
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" 
                                           class="form-control quantity-input" 
                                           name="quantity"
                                           value="{{ $product->quantity }}" 
                                           min="0" 
                                           style="text-align: center;">
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            type="submit">
                                        ✓
                                    </button>
                                </div>
                            </form>
                            <small class="text-muted d-block mt-1">
                                {{ trans_choice('единица|единицы|единиц', $product->quantity) }}
                            </small>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn btn-sm btn-primary">Редактировать</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Удалить товар?');"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<style>
    .quantity-input {
        width: 70px !important;
    }
    
    .input-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
@endsection