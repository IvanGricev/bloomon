@extends('main')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@section('content')

<div class="container my-5">
    <h1 class="mb-4">Панель администратора</h1>
    
    <!-- Функционал дашборда: карточки с метриками -->
    <div class="row mb-5">
        <div class="col-md-2">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Заказы</h5>
                    <p class="card-text">{{ $ordersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Товары</h5>
                    <p class="card-text">{{ $productsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Пользователи</h5>
                    <p class="card-text">{{ $usersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Акции</h5>
                    <p class="card-text">{{ $promotions->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Подписки</h5>
                    <p class="card-text">{{ $subscriptionsCount }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Навигационное меню для перехода в другие разделы админ-панели -->
    <h2>Навигация по разделам</h2>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="{{ route('admin.orders.index') }}">Заказы</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('admin.products.index') }}">Товары</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('admin.users.index') }}">Пользователи</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('admin.promotions.index') }}">Акции</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('admin.subscriptions.index') }}">Подписки</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('admin.support.index') }}">Запросы в поддержку</a>
        </li>
    </ul>

    
</div>
@endsection