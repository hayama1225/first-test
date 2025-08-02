<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'last_name'   => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'gender'      => ['required', 'in:1,2,3'],             // 1=男性,2=女性,3=その他
            'email'       => ['required', 'email', 'max:255'],
            'tel'         => ['required', 'regex:/^\d{10,11}$/'],  // 半角数字のみ 10〜11桁
            'address'     => ['required', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'detail'      => ['required', 'string', 'max:120'],     // 120文字以内
        ];
    }

    public function attributes(): array
    {
        return [
            'last_name'   => '姓',
            'first_name'  => '名',
            'gender'      => '性別',
            'email'       => 'メールアドレス',
            'tel'         => '電話番号',
            'address'     => '住所',
            'building'    => '建物名',
            'category_id' => 'お問い合わせの種類',
            'detail'      => '内容',
        ];
    }
}
