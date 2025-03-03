@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="container mt-5">
    <h2>Регистрация</h2>
    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <!-- Отображаемое имя -->
        <div class="form-group">
            <label for="name">Ваше имя</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Электронная почта</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
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

        <!-- Подтверждение пароля -->
        <div class="form-group">
            <label for="password_confirmation">Подтвердите пароль</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control" required>
        </div>

        <!-- Согласие с условиями -->
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input @error('agreement') is-invalid @enderror"
                   name="agreement" id="agreement" {{ old('agreement') ? 'checked' : '' }} required>
            <label class="form-check-label" for="agreement">
                Я согласен с условиями обработки персональных данных
            </label>
            @error('agreement')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Кнопка регистрации -->
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>

    <p class="mt-3">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
</div>
@endsection
