<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Пожалуйста, введите email',
            'email.email' => 'Пожалуйста, введите корректный email',
            'email.exists' => 'Пользователь с таким email не найден',
            'password.required' => 'Пожалуйста, введите пароль',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
        ];
    }
} 