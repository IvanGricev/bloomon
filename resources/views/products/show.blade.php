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
            
            <!-- Форма заказа -->
            <div class="mt-4">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Количество</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control" style="width:100px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                </form>
            </div>
            <!-- Дополнительные кнопки или информация можно добавить здесь -->
        </div>
    </div>
</div>
@endsection