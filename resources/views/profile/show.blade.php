@extends('main')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

@section('content')
<div class="container my-5">
    <h1>Профиль пользователя: {{ auth()->user()->name }}</h1>

    <!-- Секция с информацией -->
    <div class="row my-4">
        <!-- Общая сумма заказов -->
        <div class="col-md-6">
            <h3>Общая сумма ваших заказов:</h3>
            <p class="lead">
                {{ number_format(auth()->user()->orders->sum('total_price'), 2, ',', ' ') }} руб.
            </p>
        </div>
        <!-- Отзывы пользователя -->
        <div class="col-md-6">
            <h3>Ваши отзывы:</h3>
            @if(auth()->user()->reviews->isEmpty())
                <p>У вас пока нет отзывов.</p>
            @else
                <ul class="list-group">
                    @foreach(auth()->user()->reviews as $review)
                        <li class="list-group-item">
                            <strong>Товар:</strong> {{ $review->product->name }} <br>
                            <strong>Оценка:</strong>
                            @for($i = 1; $i <= $review->rating; $i++)
                                <span class="text-warning">&#9733;</span>
                            @endfor
                            @for($i = 1; $i <= (5 - $review->rating); $i++)
                                <span class="text-secondary">&#9733;</span>
                            @endfor
                            <br>
                            <p class="mt-2">{{ $review->text }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Кнопки для открытия модальных окон -->
    <div class="mb-4">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Редактировать профиль
        </button>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            Изменить пароль
        </button>
    </div>

    <!-- Модальное окно для редактирования профиля -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Редактировать профиль</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <!-- Форма обновления профиля -->
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="text" name="phone" id="phone" value="{{ auth()->user()->phone ?? '' }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Модальное окно для смены пароля -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Изменить пароль</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <!-- Форма смены пароля -->
                <form action="{{ route('profile.change_password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Старый пароль</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Новый пароль</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Подтверждение нового пароля</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить новый пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection