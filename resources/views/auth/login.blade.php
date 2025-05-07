@extends('main')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <h2>Вход</h2>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email адрес</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Введите email" required>
            </div>

            <div class="form-group mt-3">
                <label for="password">Пароль</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" required>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Войти</button>
        </form>
        <p class="mt-3">Нет аккаунта? <a href="{{ route('register') }}">Зарегистрируйтесь здесь</a></p>
    </div>
</div>
@endsection