@extends('main')

@section('content')
    <h1>Каталог товаров</h1>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($product->photo)
                        <img src="{{ $product->photo }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="card-text"><strong>{{ $product->price }} руб.</strong></p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Нет доступных товаров.</p>
        @endforelse
    </div>
@endsection