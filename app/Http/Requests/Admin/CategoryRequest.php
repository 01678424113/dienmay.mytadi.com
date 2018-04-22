<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CategoryRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'txt-name' => "required|unique:categories,category_name",
            'txt-slug' => "required|regex:/^[a-z0-9\-]+$/",
            'sl-parent' => "alpha_num"
        ];
    }

    public function messages() {
        return [
            'txt-name.required' => "Tên chuyên mục không được để trống",
            'txt-name.unique' => "Tên chuyên mục đã tồn tại",
            'txt-slug.required' => "Đường dẫn tĩnh không được để trống",
            'txt-slug.regex' => "Đường dẫn tĩnh không hợp lệ",
            'sl-parent.alpha_num' => "Chuyên mục cha không hợp lệ"
        ];
    }

}
