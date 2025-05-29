<?php

namespace App\Http\Requests\Support;

use App\Http\Requests\BaseRequest;

class StoreMessageRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'message' => ['required', 'string', 'max:1000'],
            'attachments.*' => ['nullable', 'file', 'max:5120'], // max 5MB per file
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Пожалуйста, напишите сообщение',
            'message.max' => 'Сообщение не должно превышать 1000 символов',
            'attachments.*.file' => 'Файл должен быть загружен',
            'attachments.*.max' => 'Размер файла не должен превышать 5MB',
        ];
    }
} 