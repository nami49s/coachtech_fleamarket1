<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'item_image' => 'required|image|mimes:jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|string',
            'name' => 'required|string',
            'brand' => 'nullable|string',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'item_image.required' => '商品画像をアップロードしてください',
            'item_image.image' => '画像ファイルをアップロードしてください',
            'item_image.mimes' => '画像はJPEGまたはPNG形式にしてください',
            'item_image.max' => '画像サイズは2MB以下にしてください',
            'category_id.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品状態を選択してください',
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は半角数字で入力してください',
        ];
    }
}
