<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class FunctionEditRequest extends Request {

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
            'txt-function-id' => "required|alpha_num",
            'txt-function-name' => "required"
        ];
    }

    public function messages() {
        return [
            'txt-function-id.required' => "Chức năng hệ thống không hợp lệ",
            'txt-function-id.alpha_num' => "Chức năng hệ thống không hợp lệ",
            'txt-function-name.required' => "Tên chức năng không được để trống",
        ];
    }

}

