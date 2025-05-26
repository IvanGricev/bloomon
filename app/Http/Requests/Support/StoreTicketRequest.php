<?php

namespace App\Http\Requests\Support;

use App\Http\Requests\BaseRequest;

class StoreTicketRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'attachments.*' => ['nullable', 'file', 'max:5120'], // max 5MB per file
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'Пожалуйста, укажите тему обращения',
            'subject.max' => 'Тема не должна превышать 255 символов',
            'message.required' => 'Пожалуйста, напишите сообщение',
            'message.min' => 'Сообщение должно содержать минимум 10 символов',
            'message.max' => 'Сообщение не должно превышать 1000 символов',
            'attachments.*.file' => 'Файл должен быть загружен',
            'attachments.*.max' => 'Размер файла не должен превышать 5MB',
        ];
    }
} 