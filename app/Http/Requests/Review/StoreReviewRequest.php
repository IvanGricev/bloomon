<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;

class StoreReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'text' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Пожалуйста, поставьте оценку',
            'rating.integer' => 'Оценка должна быть числом',
            'rating.min' => 'Минимальная оценка - 1',
            'rating.max' => 'Максимальная оценка - 5',
            'text.required' => 'Пожалуйста, напишите отзыв',
            'text.min' => 'Отзыв должен содержать минимум 10 символов',
            'text.max' => 'Отзыв не должен превышать 1000 символов',
        ];
    }
} 