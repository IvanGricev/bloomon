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
            'delivery_time_slot' => ['required', 'date_format:H:i'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
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
            'delivery_time_slot.required' => 'Пожалуйста, выберите время доставки',
            'delivery_time_slot.date_format' => 'Некорректный формат времени доставки',
            'items.required' => 'Корзина пуста',
            'items.min' => 'Корзина пуста',
            'items.*.product_id.required' => 'Ошибка в данных товара',
            'items.*.product_id.exists' => 'Товар не найден',
            'items.*.quantity.required' => 'Укажите количество товара',
            'items.*.quantity.min' => 'Минимальное количество товара - 1',
        ];
    }
} 