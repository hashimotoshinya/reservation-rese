<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'area_id'     => 'required|exists:areas,id',
            'genre_id'    => 'required|exists:genres,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => '店舗名を入力してください。',
            'name.max'             => '店舗名は255文字以内で入力してください。',
            'area_id.required'     => 'エリアを選択してください。',
            'area_id.exists'       => '選択したエリアは存在しません。',
            'genre_id.required'    => 'ジャンルを選択してください。',
            'genre_id.exists'      => '選択したジャンルは存在しません。',
            'description.required' => '店舗説明を入力してください。',
            'image.image'          => 'アップロードできるのは画像ファイルのみです。',
            'image.max'            => '画像サイズは2MB以内にしてください。',
        ];
    }
}