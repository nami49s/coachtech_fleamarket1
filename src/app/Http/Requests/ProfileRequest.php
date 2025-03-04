<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'profile_image' => 'nullable|file|max:2048|mimes:jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.file' => 'プロフィール画像は画像ファイルを選択してください',
            'profile_image.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください',
            'profile_image.max' => 'プロフィール画像のサイズは2MB以下にしてください',
        ];
    }
}
