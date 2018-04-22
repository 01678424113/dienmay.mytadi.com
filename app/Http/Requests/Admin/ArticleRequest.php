<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ArticleRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'txt-title' => "required",
            'txt-summary' => "required",
            'sl-category' => "required|alpha_num",
            'file-featured' => "image",
        ];
    }

    public function messages() {
        return [
            'txt-title.required' => "Tiêu đề không được để trống",
            'txt-summary.required' => "Mô tả ngắn không được để trống",
            'sl-category.required' => "Chưa chọn chuyên mục",
            'sl-category.alpha_num' => "Chuyên mụ không hợp lệ",
            'file-featured.image' => "Ảnh featured không hợp lệ",
        ];
    }

}
