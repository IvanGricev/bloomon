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
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentSubscriptionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\AdminReviewController;

/*
|--------------------------------------------------------------------------
| Публичные маршруты
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/ideas', function () {
    return view('ideas');
})->name('ideas');

Route::get('/delivery', function () {
    return view('delivery');
})->name('delivery');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Маршруты авторизации и регистрации
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты для блога
Route::get('/blog/create', [BlogController::class, 'create'])->middleware('auth')->name('blog.create');
Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
Route::get('/blog/{post}/edit', [BlogController::class, 'edit'])->middleware('auth')->name('blog.edit');
Route::put('/blog/{post}', [BlogController::class, 'update'])->middleware('auth')->name('blog.update');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// Маршруты для товаров
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Публичная страница подписок
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

/*
|--------------------------------------------------------------------------
| Маршруты для авторизованных пользователей
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Личный кабинет
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');

    // Заказы
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Конструктор букетов
    Route::get('/bouquet-builder', [BouquetBuilderController::class, 'index'])->name('bouquet-builder.index');
    Route::post('/bouquet-builder', [BouquetBuilderController::class, 'store'])->name('bouquet-builder.store');

    // Отзывы к товарам
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Подписки
    Route::get('/profile/subscriptions', [SubscriptionController::class, 'profileIndex'])->name('profile.subscriptions');
    Route::get('/subscriptions/{id}/payment', [SubscriptionController::class, 'showPaymentForm'])->name('subscriptions.payment');
    Route::post('/subscriptions/{id}/payment', [SubscriptionController::class, 'processPayment'])->name('subscriptions.process_payment');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::post('/subscriptions/{id}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');

    // Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/delivery-time-slots', [OrderController::class, 'getTimeSlots'])->name('delivery.time-slots');
    // Оплата картой
    Route::get('/card-payment', [PaymentController::class, 'showCardPaymentForm'])->name('card.payment.form');
    Route::post('/card-payment', [PaymentController::class, 'processCardPayment'])->name('card.payment.process');

    // Страница подписок в профиле
    Route::get('/profile/subscriptions', [SubscriptionController::class, 'profileIndex'])->name('profile.subscriptions');

    // Оплата подписок
    Route::get('/subscription-payment', [PaymentSubscriptionController::class, 'showPaymentForm'])->name('subscription.payment.form');
    Route::post('/subscription-payment', [PaymentSubscriptionController::class, 'processPayment'])->name('subscription.payment.process');

    // Маршруты для поддержки
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{ticket}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/message', [SupportController::class, 'storeMessage'])->name('support.message.store');
});

/*
|--------------------------------------------------------------------------
| Административные маршруты (только для администраторов)
|--------------------------------------------------------------------------
| Для этих маршрутов настроен middleware 'admin', который проверяет,
| что Auth::user()->role === 'admin'.
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Главная страница админ-панели
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

    // Управление заказами
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Управление товарами
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/products/{product}/image/{image}', [AdminProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::post('/products/{id}/quantity', [AdminProductController::class, 'quickUpdateQuantity'])->name('products.quantity.update');

    // Управление пользователями
    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/{id}', 'show')->name('users.show');
        Route::put('/users/{id}', 'update')->name('users.update');
        Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        Route::post('/users/{id}/restore', 'restore')->name('users.restore');
        Route::put('/users/{userId}/subscriptions/{subscriptionId}/status', 'updateSubscriptionStatus')->name('users.subscriptions.status');
    });

    // Управление акциями
    Route::resource('promotions', AdminPromotionController::class);

    // Управление подписками
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create', [AdminSubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [AdminSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{id}', [AdminSubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::get('/subscriptions/{id}/edit', [AdminSubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('/subscriptions/{id}', [AdminSubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{id}', [AdminSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Управление поддержкой
    Route::get('/support', [AdminSupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticket}', [AdminSupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/status', [AdminSupportController::class, 'updateStatus'])->name('support.status.update');
    Route::post('/support/{ticket}/message', [AdminSupportController::class, 'storeMessage'])->name('support.message.store');

    // Маршруты для управления заказами
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Маршруты для управления отзывами
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::put('/reviews/{id}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});