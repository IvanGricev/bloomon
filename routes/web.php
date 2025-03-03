<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Маршруты для гостевых пользователей
Route::middleware('guest')->group(function () {
    // Страница регистрации
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    // Обработка регистрации
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Страница входа
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Обработка входа
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {
    // Выход из системы
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Маршруты администратора
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

});