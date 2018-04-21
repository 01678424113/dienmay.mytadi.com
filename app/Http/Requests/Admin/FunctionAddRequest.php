<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class FunctionAddRequest extends Request {

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
            'txt-function-key' => "required|regex:/^[a-z0-9\_]+$/|min:3",
            'txt-function-name' => "required",
        ];
    }

    public function messages() {
        return [
            'txt-function-key.required' => "Mã chức năng không được để trống",
            'txt-function-key.regex' => "Mã chức năng không hợp lệ",
            'txt-function-key.min' => "Mã chức năng phải lớn hơn 3 ký tự",
            'txt-function-name.required' => "Tên chức năng không được để trống",
        ];
    }

}
