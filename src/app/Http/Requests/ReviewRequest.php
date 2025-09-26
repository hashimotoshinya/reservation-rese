<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => '評価を入力してください。',
            'rating.integer'  => '評価は数値で入力してください。',
            'rating.between'  => '評価は1〜5の範囲で入力してください。',
            'comment.string'  => 'コメントは文字列で入力してください。',
            'comment.max'     => 'コメントは500文字以内で入力してください。',
        ];
    }
}