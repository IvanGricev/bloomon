<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class StoreProductRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'photo' => ['nullable', 'image', 'max:2048'], // max 2MB
            'quantity' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Пожалуйста, введите название товара',
            'name.max' => 'Название не должно превышать 255 символов',
            'price.required' => 'Пожалуйста, укажите цену',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'category_id.required' => 'Пожалуйста, выберите категорию',
            'category_id.exists' => 'Выбранная категория не существует',
            'photo.image' => 'Файл должен быть изображением',
            'photo.max' => 'Размер изображения не должен превышать 2MB',
            'quantity.required' => 'Пожалуйста, укажите количество',
            'quantity.integer' => 'Количество должно быть целым числом',
            'quantity.min' => 'Количество не может быть отрицательным',
        ];
    }
} 