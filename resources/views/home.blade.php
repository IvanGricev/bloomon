@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Главная страница</div>
                <div class="card-body">
                    @auth
                        <p>Добро пожаловать, {{ auth()->user()->username }}!</p>
                        <p>Вы успешно авторизованы в системе.</p>
                    @else
                        <p>Добро пожаловать в систему BloomOn!</p>
                        <p>Пожалуйста, выберите действие:</p>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <a href="{{ route('register.create') }}" class="btn btn-primary">Регистрация</a>
                            <a href="{{ route('login.create') }}" class="btn btn-secondary">Вход</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection