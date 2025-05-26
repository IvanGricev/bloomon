<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;

class StoreOrderRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'payment_method' => ['required', 'string', 'in:card,cash'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'delivery_time_slot' => ['required', 'string', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]-([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'delivery_preferences' => ['nullable', 'string', 'max:1000'],
            'total_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'address.required' => 'Пожалуйста, укажите адрес доставки',
            'address.max' => 'Адрес не должен превышать 255 символов',
            'phone.required' => 'Пожалуйста, укажите номер телефона',
            'phone.regex' => 'Пожалуйста, укажите корректный номер телефона',
            'payment_method.required' => 'Пожалуйста, выберите способ оплаты',
            'payment_method.in' => 'Выбран некорректный способ оплаты',
            'delivery_date.required' => 'Пожалуйста, выберите дату доставки',
            'delivery_date.date' => 'Некорректный формат даты',
            'delivery_date.after' => 'Дата доставки должна быть не раньше завтрашнего дня',
            'delivery_time_slot.required' => 'Пожалуйста, выберите время доставки',
            'delivery_time_slot.regex' => 'Некорректный формат времени доставки',
            'delivery_preferences.max' => 'Пожелания к доставке не должны превышать 1000 символов',
            'total_price.required' => 'Ошибка расчета стоимости заказа',
            'total_price.numeric' => 'Некорректная стоимость заказа',
            'total_price.min' => 'Стоимость заказа не может быть отрицательной',
        ];
    }
} 