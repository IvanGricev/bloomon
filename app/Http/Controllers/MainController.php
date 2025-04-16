<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    // Отображение основной страницы (например, main.blade.php)
    public function index()
    {
        return view('main');
    }
}