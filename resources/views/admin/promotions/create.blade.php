@extends('main')

@section('content')
    <h1>Создать акцию</h1>
    <form action="{{ route('admin.promotions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Название акции</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">Скидка</label>
            <input type="number" step="0.01" class="form-control" name="discount" id="discount" required>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Дата начала</label>
            <input type="date" class="form-control" name="start_date" id="start_date" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Дата окончания</label>
            <input type="date" class="form-control" name="end_date" id="end_date" required>
        </div>
        <button type="submit" class="btn btn-success">Создать акцию</button>
    </form>
@endsection