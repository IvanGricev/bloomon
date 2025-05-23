<link rel="stylesheet" href="{{ url('css/catalog.css') }}">
@extends('main')

@section('content')
<div class="container my-5">
    <div class="catalog-header d-flex justify-content-between align-items-start mb-2" style="gap: 24px;">
        <div>
            <div class="catalog-breadcrumbs mb-1">
                <span>Главная</span> / <span>Каталог</span>
            </div>
        </div>
        <div class="catalog-sort">
            <span class="sort-label">Сортировать по:</span>
            <a href="#" class="sort-link active">ЦЕНЕ</a>
            <span class="sort-divider">|</span>
            <a href="#" class="sort-link">РЕЙТИНГУ</a>
            <span class="sort-divider">|</span>
            <a href="#" class="sort-link">НОВИНКЕ</a>
        </div>
    </div>
    <h1 class="catalog-title mb-4">Каталог</h1>

    <!-- Блок поиска и фильтров -->
    <div class="search-filter-block mb-5">
        <form action="{{ route('products.index') }}" method="GET" class="search-form">
            <div class="row">
                <!-- Поисковая строка -->
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control search-input" 
                               placeholder="Поиск по названию или категории..." 
                               value="{{ request('search') }}"
                        >
                        <button class=" search-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Фильтр категорий -->
                <div class="col-md-6 d-flex gap-3">
                    <!-- Категории -->
                    <div class="dropdown categories-dropdown flex-grow-1">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="categoriesDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            Категории
                        </button>
                        <div class="dropdown-menu p-3 w-100" aria-labelledby="categoriesDropdownBtn" style="min-width: 260px;">
                            <form method="GET" action="{{ route('products.index') }}">
                                <div class="categories-list mb-3">
                                    @foreach($categories as $category)
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
                                @if(request('in_stock'))
                                    <input type="hidden" name="in_stock" value="1">
                                @endif
                                <button type="submit" class="btn btn-primary w-100">Применить</button>
                            </form>
                        </div>
                    </div>
                    <!-- Наличие -->
                    <div class="dropdown categories-dropdown flex-grow-1">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="stockDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            Наличие
                        </button>
                        <div class="dropdown-menu p-3 w-100" aria-labelledby="stockDropdownBtn" style="min-width: 180px;">
                            <form method="GET" action="{{ route('products.index') }}">
                                @foreach(request('categories', []) as $cat)
                                    <input type="hidden" name="categories[]" value="{{ $cat }}">
                                @endforeach
                                <div class="form-check mb-3">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="in_stock"
                                           value="1"
                                           id="inStockCheck"
                                           {{ request('in_stock') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="inStockCheck">
                                        Только в наличии
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Применить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

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