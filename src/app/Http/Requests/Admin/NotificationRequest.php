<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target'  => ['required', 'string', 'in:all,frequent_users,owners'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'target.required'  => '送信対象を選択してください。',
            'target.in'        => '送信対象の値が不正です。',
            'subject.required' => '件名を入力してください。',
            'subject.max'      => '件名は255文字以内で入力してください。',
            'message.required' => '本文を入力してください。',
        ];
    }
}