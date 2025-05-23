<link rel="stylesheet" href="{{ url('css/catalog.css') }}">
@extends('main')

@section('content')
<div class="container my-5">
    <!-- Блок поиска и фильтров -->
    <div class="search-filter-block mb-5">
        <form action="{{ route('products.index') }}" method="GET" class="search-form">
            <div class="row">
                <!-- Поисковая строка -->
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Поиск по названию или категории..." 
                               value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Фильтр категорий -->
                <div class="col-md-6">
                    <div class="categories-filter">
                        <h5 class="mb-3">Категории</h5>
                        <div class="categories-list" id="categoriesList">
                            @foreach($categories->take(10) as $category)
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->id }}"
                                           id="category{{ $category->id }}"
                                           {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($categories->count() > 10)
                            <button type="button" 
                                    class="btn btn-link p-0 mt-2" 
                                    id="showMoreCategories"
                                    onclick="toggleCategories()"
                            >
                                Больше категорий
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <h1>Каталог товаров</h1>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <a href="{{ route('products.show', $product->id) }}" class="product-card-link">
                    <div class="product-card" style="background-image: url('{{ $product->images->isNotEmpty() ? asset('uploads/products/' . $product->images->first()->image_path) : 'https://via.placeholder.com/300x200' }}');">
                        <div class="product-card-gradient"></div>
                        <div class="product-card-info">
                            <div class="product-card-title">{{ $product->name }}</div>
                            <div class="product-card-price">{{ number_format($product->price, 2, ',', ' ') }} руб.</div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p>Нет доступных товаров.</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    function toggleCategories() {
        const button = document.getElementById('showMoreCategories');
        const categoriesList = document.getElementById('categoriesList');
        
        if (categoriesList.style.maxHeight) {
            categoriesList.style.maxHeight = null;
            button.textContent = 'Больше категорий';
        } else {
            categoriesList.style.maxHeight = 'none';
            button.textContent = 'Скрыть категории';
        }
    }
</script>
@endpush
@endsection