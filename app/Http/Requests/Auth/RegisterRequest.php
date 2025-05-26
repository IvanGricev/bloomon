<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Пожалуйста, введите имя',
            'name.max' => 'Имя не должно превышать 255 символов',
            'email.required' => 'Пожалуйста, введите email',
            'email.email' => 'Пожалуйста, введите корректный email',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Пожалуйста, введите пароль',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'address.max' => 'Адрес не должен превышать 255 символов',
        ];
    }
} 