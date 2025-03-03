@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="container mt-5">
    <h2>Вход в систему</h2>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label for="email">Электронная почта</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Пароль -->
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Запомнить меня -->
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input"
                   name="remember" id="remember">
            <label class="form-check-label" for="remember">Запомнить меня</label>
        </div>

        <!-- Кнопка входа -->
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>

    <p class="mt-3">Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
</div>
@endsection
