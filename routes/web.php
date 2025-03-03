<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

// Маршруты аутентификации
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.create');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.create');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});