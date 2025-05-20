@extends('main')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Левая колонка: Изображения товара -->
        <div class="col-md-6">
            @if($product->images->isNotEmpty())
                <div id="carouselProduct{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $key => $image)
                            <div class="carousel-item @if($key == 0) active @endif">
                                <img src="{{ asset('uploads/products/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Предыдущий</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Следующий</span>
                    </button>
                </div>
            @else
                <img src="https://via.placeholder.com/600x400" class="img-fluid" alt="{{ $product->name }}">
            @endif
        </div>
        
        <!-- Правая колонка: Информация о товаре и форма заказа -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <h2>{{ number_format($product->price, 2, ',', ' ') }} руб.</h2>
            <p>{{ $product->description }}</p>
            
            <!-- Информация о наличии -->
            <div class="availability-info mb-3">
                @if($product->quantity > 0)
                    @if($product->quantity <= 25)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Осталось всего {{ $product->quantity }} {{ trans_choice('товар|товара|товаров', $product->quantity) }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            В наличии
                        </div>
                    @endif
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        Нет в наличии
                    </div>
                @endif
            </div>
            
            <!-- Форма заказа -->
            <div class="mt-4">
                @if($product->quantity > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Количество</label>
                            <div class="input-group" style="width: 150px;">
                                <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->quantity }}" 
                                       class="form-control text-center"
                                       onchange="validateQuantity(this.value)">
                                <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                            </div>
                            <small class="text-muted">Доступно: {{ $product->quantity }} {{ trans_choice('единица|единицы|единиц', $product->quantity) }}</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                    </form>
                @else
                    <button class="btn btn-secondary" disabled>Нет в наличии</button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const maxQuantity = {{ $product->quantity }};
    
    function validateQuantity(value) {
        const input = document.getElementById('quantity');
        value = parseInt(value);
        
        if (isNaN(value) || value < 1) {
            input.value = 1;
        } else if (value > maxQuantity) {
            input.value = maxQuantity;
        }
    }
    
    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
    
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < maxQuantity) {
            input.value = currentValue + 1;
        }
    }
    
    // Валидация формы перед отправкой
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        const quantity = parseInt(document.getElementById('quantity').value);
        if (quantity > maxQuantity) {
            e.preventDefault();
            alert('Запрошенное количество превышает доступное');
        }
    });
</script>
@endpush

@push('styles')
<style>
    .availability-info .alert {
        margin-bottom: 0;
    }
    
    .input-group .form-control {
        text-align: center;
    }
    
    .input-group .btn {
        width: 40px;
    }
</style>
@endpush
@endsection