<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UserAddRequest extends Request {

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
            'txt-name' => "required|regex:/^[a-z0-9\_]+$/|min:3",
            'txt-password' => "required|min:3",
            'txt-fullname' => "required",
            'sl-group' => "required|alpha_num",
        ];
    }

    public function messages() {
        return [
            'txt-name.required' => "Tên đăng nhập không được để trống",
            'txt-name.regex' => "Tên đăng nhập không hợp lệ",
            'txt-name.min' => "Tên đăng nhập phải lớn hơn 3 ký tự",
            'txt-password.required' => "Mật khẩu không được để trống",
            'txt-password.min' => "Mật khẩu phải lớn hơn 3 ký tự",
            'txt-fullname.required' => "Họ và tên không được để trống",
            'sl-group.required' => "Chưa chọn nhóm thành viên",
            'sl-group.required' => "Nhóm thành viên không hợp lệ",
        ];
    }

}
