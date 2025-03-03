<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Показать форму регистрации
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Обработка регистрации
    public function register(Request $request)
    {
        // Валидация введенных данных
        $validatedData = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'agreement'=> ['required', 'accepted'],
        ]);

        // Хеширование пароля
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Создание нового пользователя
        $user = User::create($validatedData);

        // Авторизация пользователя
        Auth::login($user);

        // Перенаправление на главную страницу с сообщением об успешной регистрации
        return redirect()->route('home')->with('status', 'Вы успешно зарегистрированы!');
    }

    // Показать форму входа
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Обработка входа
    public function login(Request $request)
    {
        // Валидация введенных данных
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Попытка авторизации
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Перенаправление на главную страницу с сообщением об успешном входе
            return redirect()->intended(route('home'))->with('status', 'Вы успешно вошли в систему!');
        }

        // Если авторизация не удалась, возвращаем обратно с ошибкой
        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    // Обработка выхода
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Перенаправление на страницу входа с сообщением об успешном выходе
        return redirect()->route('login')->with('status', 'Вы успешно вышли из системы!');
    }
}
