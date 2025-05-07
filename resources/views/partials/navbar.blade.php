<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <!-- Логотип/Бренд -->
    <a class="navbar-brand" href="{{ route('home') }}">Bloomon</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Основное меню -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('products.index') }}">Каталог</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('ideas') }}">Идеи</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('delivery') }}">Доставка</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('about') }}">О нас</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('contact') }}">Контакты</a>
        </li>
      </ul>
      
      <!-- Правая часть меню -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        @auth
          <!-- Ссылка на Корзину -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('cart.index') }}">
              <i class="bi bi-cart"></i> Корзина
            </a>
          </li>
          <!-- Выпадающее меню пользователя -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              {{ auth()->user()->name }} <i class="bi bi-caret-down-fill"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="{{ route('profile.show') }}">Профиль</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('orders.index') }}">Мои заказы</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('profile.subscriptions') }}">Подписки</a>
              </li>
              @if(auth()->user()->role === 'admin')
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('admin.index') }}">Админ панель</a>
                </li>
              @endif
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="dropdown-item">Выйти</button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item">
            <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Вход</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary" href="{{ route('register') }}">Регистрация</a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>