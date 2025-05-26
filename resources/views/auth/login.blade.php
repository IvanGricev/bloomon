<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@extends('main')


@section('content')
<div class="login-container">
    <h2>Вход</h2>
    
    <x-error-alert />
    <x-success-message />

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email адрес</label>
            <input type="email" name="email" id="email" placeholder="Введите email" required class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            <x-error-message field="email" />
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password" placeholder="Введите пароль" required class="form-control @error('password') is-invalid @enderror">
            <x-error-message field="password" />
        </div>

        <button type="submit" class="login-btn">Войти</button>
    </form>
    
    <div class="login-links">
        <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрируйтесь здесь</a></p>
    </div>
</div>
@endsection