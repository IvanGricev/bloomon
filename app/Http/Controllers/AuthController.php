<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Форма регистрации
    public function registerForm()
    {
        return view('auth.register');
    }

    // Регистрация пользователя
    public function register(Request $request)
    {
        $request->validate([
           'name'     => 'required|string|max:255',
           'email'    => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
           'name'     => $request->name,
           'email'    => $request->email,
           'password' => Hash::make($request->password),
           'role'     => 'client'   // по умолчанию клиент
        ]);

        // Авторизуем пользователя
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Регистрация прошла успешно!');
    }

    // Форма входа
    public function loginForm()
    {
        return view('auth.login');
    }

    // Вход пользователя
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Вы успешно вошли в систему!');
        }
        return back()->withErrors(['email' => 'Неверные учетные данные.']);
    }

    // Выход пользователя
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Вы успешно вышли из системы.');
    }
}