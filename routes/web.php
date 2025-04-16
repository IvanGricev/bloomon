<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BouquetBuilderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPromotionController;

/*
|--------------------------------------------------------------------------
| Публичные маршруты
|--------------------------------------------------------------------------
*/
Route::get('/', [MainController::class, 'index'])->name('home');

// Маршруты авторизации и регистрации
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Если требуется, можно разместить страницу выхода как GET, но чаще используют POST
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Маршруты для авторизованных пользователей
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function(){
    // Работа с заказами
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Конструктор букетов
    Route::get('/bouquet-builder', [BouquetBuilderController::class, 'index'])->name('bouquet-builder.index');
    Route::post('/bouquet-builder', [BouquetBuilderController::class, 'store'])->name('bouquet-builder.store');

    // Отзывы к товарам
    Route::post('/products/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Подписки
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});

/*
|--------------------------------------------------------------------------
| Административные маршруты
|--------------------------------------------------------------------------
|
| Эти маршруты доступны только авторизованным пользователям с ролью администратора.
| Для этого используется middleware 'admin'. Вам нужно создать соответствующий middleware,
| который проверяет, что у текущего пользователя поле 'role' равно 'admin'.
|
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Управление заказами
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Управление товарами (используем ресурсный контроллер)
    Route::resource('products', AdminProductController::class);

    // Управление пользователями
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');

    // Управление акциями
    Route::resource('promotions', AdminPromotionController::class);
});