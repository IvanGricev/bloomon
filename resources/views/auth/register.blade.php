@extends('main')

<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

@section('content')
<div class="auth-container">
    <h2>Регистрация</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" name="name" id="name" placeholder="Введите имя" required class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email адрес</label>
            <input type="email" name="email" id="email" placeholder="Введите email" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password" placeholder="Введите пароль" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Повторите пароль" required class="form-control">
        </div>
        <button type="submit" class="register-btn">Зарегистрироваться</button>
    </form>
    <div class="auth-links">
        Есть аккаунт? <a href="{{ route('login') }}">Войдите здесь</a>
    </div>
</div>
@endsection