@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
<div class="container mt-5">
    <h1>Панель администратора</h1>
    <p>Добро пожаловать, {{ Auth::user()->name }}!</p>
</div>
@endsection
