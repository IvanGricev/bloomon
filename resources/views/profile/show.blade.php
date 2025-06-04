@extends('main')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

@section('content')
<div class="container my-5">
    <h1>Профиль пользователя: {{ auth()->user()->name }}</h1>

     <!-- Кнопки для открытия модальных окон -->
     <div class="mb-4">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Редактировать профиль
        </button>
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            Изменить пароль
        </button>
    </div>



    <!-- Секция с подписками -->
    <div class="row my-4">
        <div class="col-12">
            <h3>Ваши подписки:</h3>
            <div class="row">
                @forelse(auth()->user()->subscriptions()->withPivot(['last_payment_date', 'subscription_end_date', 'status'])->get() as $subscription)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($subscription->image)
                                <img src="{{ asset('uploads/subscriptions/' . $subscription->image) }}" class="card-img-top" alt="{{ $subscription->name }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $subscription->name }}</h5>
                                <p class="card-text">{{ $subscription->description }}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Последняя оплата: {{ $subscription->pivot->last_payment_date ? $subscription->pivot->last_payment_date->format('d.m.Y') : 'Нет данных' }}<br>
                                        Действует до: {{ $subscription->pivot->subscription_end_date ? $subscription->pivot->subscription_end_date->format('d.m.Y') : 'Нет данных' }}<br>
                                        Статус: <span class="{{ $subscription->pivot->status === 'active' ? 'text-success' : 'text-danger' }}">
                                            {{ $subscription->pivot->status === 'active' ? 'Активна' : 'Отменена' }}
                                        </span>
                                    </small>
                                </p>
                                @if($subscription->pivot->status === 'active')
                                    @if($subscription->pivot->subscription_end_date && $subscription->pivot->subscription_end_date->diffInDays(now()) <= 7)
                                        <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Продлить</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Отменить</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>У вас пока нет активных подписок.</p>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Посмотреть доступные подписки</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Секция с информацией -->
    <div class="row my-4">
        
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
        <!-- Общая сумма заказов -->
        <div class="col-md-6">
            <h3>Общая сумма ваших заказов:</h3>
            <p class="lead">
                {{ number_format(auth()->user()->orders->sum('total_price'), 2, ',', ' ') }} руб.
            </p>
        </div>
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