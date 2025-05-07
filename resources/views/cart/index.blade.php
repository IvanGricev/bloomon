@extends('main')

@section('content')
<div class="container my-5">
    <h1>Ваша корзина</h1>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(empty($cart))
        <p>Ваша корзина пуста.</p>
    @else
        <form action="{{ route('cart.update') }}" method="post">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price'], 2, ',', ' ') }} руб.</td>
                        <td>
                            <input type="number" name="quantities[{{ $item['id'] }}]" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width:80px;">
                        </td>
                        <td>{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} руб.</td>
                        <td>
                            <form action="{{ route('cart.remove', $item['id']) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Обновить корзину</button>
        </form>
        <a href="{{ route('cart.checkout') }}" class="btn btn-success mt-3">Перейти к оформлению</a>
    @endif
</div>
@endsection