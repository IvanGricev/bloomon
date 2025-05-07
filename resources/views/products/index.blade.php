@extends('main')

@section('content')
<div class="container my-5">
    <h1>Каталог товаров</h1>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset('uploads/products/' . $product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="card-text"><strong>{{ number_format($product->price, 2, ',', ' ') }} руб.</strong></p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Нет доступных товаров.</p>
        @endforelse
    </div>
</div>
@endsection