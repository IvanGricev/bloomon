<?php

namespace App\Http\Requests\Subscription;

use App\Http\Requests\BaseRequest;

class StoreSubscriptionRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'], // max 2MB
            'frequency' => ['required', 'string', 'in:weekly,monthly'],
            'delivery_day' => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Пожалуйста, введите название подписки',
            'name.max' => 'Название не должно превышать 255 символов',
            'price.required' => 'Пожалуйста, укажите цену',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'image.image' => 'Файл должен быть изображением',
            'image.max' => 'Размер изображения не должен превышать 2MB',
            'frequency.required' => 'Пожалуйста, выберите частоту доставки',
            'frequency.in' => 'Выбрана некорректная частота доставки',
            'delivery_day.required' => 'Пожалуйста, выберите день доставки',
            'delivery_day.in' => 'Выбран некорректный день доставки',
        ];
    }
} 