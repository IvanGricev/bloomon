<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Отображает страницу профиля пользователя.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        // Вы можете загрузить связи, например, заказы и отзывы, если это нужно:
        // $user->load('orders', 'reviews');
        return view('profile.show', compact('user'));
    }

    /**
     * Обновляет данные профиля: имя, email и телефон.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'name'  => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string',
        ]);

        $user = Auth::user();
        $user->name  = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Профиль успешно обновлён.');
    }

    /**
     * Изменяет пароль пользователя, проверяя сначала старый пароль.
     *
     * Метод ожидает, что поле "new_password" подтверждено с помощью поля "new_password_confirmation".
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        // Валидация входных данных для смены пароля
        $request->validate([
            'old_password'              => 'required|string',
            'new_password'              => 'required|string|min:6|confirmed', // confirmation требует наличия new_password_confirmation
        ]);

        $user = Auth::user();

        // Проверяем, что старый пароль корректный
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect()->route('profile.show')
                ->withErrors(['old_password' => 'Старый пароль не соответствует.']);
        }

        // Обновляем пароль
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Пароль успешно изменён.');
    }
}