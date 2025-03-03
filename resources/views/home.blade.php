@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
<div class="container mt-5">
    <h1>Добро пожаловать на Bloomon!</h1>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @auth
        <p>Здравствуйте, {{ Auth::user()->name }}!</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Выйти</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">Войти</a> или <a href="{{ route('register') }}">Зарегистрироваться</a></p>
    @endauth
</div>
@endsection
