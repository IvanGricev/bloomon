@extends('admin.layout')

@section('title', 'Дашборд')

@section('content')
    <h1 class="h2 mt-4">Панель администратора</h1>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Заказы</h5>
                    <p class="card-text">{{ $ordersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Товары</h5>
                    <p class="card-text">{{ $productsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Пользователи</h5>
                    <p class="card-text">{{ $usersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Акции</h5>
                    <p class="card-text">{{ $promotions->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection