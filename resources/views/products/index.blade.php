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

@push('styles')
<style>
    .search-filter-block {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .categories-filter {
        background: white;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .categories-list {
        max-height: 200px;
        overflow-y: auto;
    }
    
    .form-check {
        margin-bottom: 8px;
    }
    
    .form-check-label {
        cursor: pointer;
    }
</style>
@endpush

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