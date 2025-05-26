<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;

class StoreReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Ошибка в данных товара',
            'product_id.exists' => 'Товар не найден',
            'rating.required' => 'Пожалуйста, поставьте оценку',
            'rating.integer' => 'Оценка должна быть числом',
            'rating.min' => 'Минимальная оценка - 1',
            'rating.max' => 'Максимальная оценка - 5',
            'comment.required' => 'Пожалуйста, напишите отзыв',
            'comment.min' => 'Отзыв должен содержать минимум 10 символов',
            'comment.max' => 'Отзыв не должен превышать 1000 символов',
        ];
    }
} 