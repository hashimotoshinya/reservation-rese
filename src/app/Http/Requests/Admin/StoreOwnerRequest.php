<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => '名前を入力してください。',
            'name.max'          => '名前は255文字以内で入力してください。',
            'email.required'    => 'メールアドレスを入力してください。',
            'email.email'       => '正しい形式のメールアドレスを入力してください。',
            'email.unique'      => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.min'      => 'パスワードは8文字以上で入力してください。',
            'password.confirmed'=> 'パスワード確認が一致しません。',
        ];
    }
}