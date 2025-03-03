<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Отображение панели администратора.
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
