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
                        <button class="search-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Фильтры -->
                <div class="col-md-6 d-flex gap-3">
                    <!-- Категории -->
                    <div class="dropdown categories-dropdown flex-grow-1">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="categoriesDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            Категории
                        </button>
                        <div class="dropdown-menu p-3 w-100" aria-labelledby="categoriesDropdownBtn" style="min-width: 260px;">
                            <form method="GET" action="{{ route('products.index') }}" id="categoriesForm">
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
                            <form method="GET" action="{{ route('products.index') }}" id="stockForm">
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
        @if(request('search') || request('categories') || request('in_stock'))
            <div class="text-end mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>
                    Сбросить фильтры
                </a>
            </div>
        @endif
    </div>

    <div class="row" id="productsGrid">
        @forelse($products as $product)
            <div class="col-md-4 mb-4 product-item">
                <a href="{{ route('products.show', $product->id) }}" class="product-card-link">
                    <div class="product-card" style="background-image: url('{{ $product->images->isNotEmpty() ? asset('uploads/products/' . $product->images->first()->image_path) : 'https://via.placeholder.com/300x200' }}');">
                        <div class="product-card-gradient"></div>
                        <div class="product-card-info">
                            <div class="product-card-title">{{ $product->name }}</div>
                            <div class="product-card-price">{{ number_format($product->price, 2, ',', ' ') }} руб.</div>
                            <div class="product-card-availability">
                                @if($product->quantity > 0)
                                    @if($product->quantity <= 20)
                                        <span class="text-warning">Осталось {{ $product->quantity }}</span>
                                    @else
                                        <span class="text-success">В наличии</span>
                                    @endif
                                @else
                                    <span class="text-danger">Нет в наличии</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">Нет доступных товаров.</p>
            </div>
        @endforelse
    </div>

    @if($products->hasMorePages())
        <div class="text-center mt-4">
            <button id="loadMoreBtn" class="btn btn-outline-primary">Показать еще</button>
        </div>
    @endif
</div>

@push('scripts')
<script>
    let currentPage = 1;
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            currentPage++;
            const url = new URL(window.location.href);
            url.searchParams.set('page', currentPage);
            
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newProducts = doc.querySelectorAll('.product-item');
                    
                    newProducts.forEach(product => {
                        document.getElementById('productsGrid').appendChild(product);
                    });
                    
                    if (!doc.querySelector('.product-item')) {
                        loadMoreBtn.style.display = 'none';
                    }
                });
        });
    }

    // Автоматическая отправка форм при изменении чекбоксов
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush
@endsection