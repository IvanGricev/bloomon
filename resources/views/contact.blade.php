@extends('main')

    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">

@section('content')
<div class="container-contact">
    <h1>Контакты</h1>
    <p>Свяжитесь с нами:</p>
    <ul>
        <li>Адрес: ул. Примерная, д. 1, Москва</li>
        <li>Телефон: +7 (495) 123-45-67</li>
        <li>Email: info@bloomon.ru</li>
    </ul>

    <h2>Обратная связь</h2>
    <form action="#" method="post">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Ваше имя</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Ваш email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Сообщение</label>
            <textarea class="form-control" name="message" id="message" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>
@endsection