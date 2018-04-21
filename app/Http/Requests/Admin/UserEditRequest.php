<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UserEditRequest extends Request {

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
            'txt-user-id' => "required|alpha_num",
            'txt-name' => "required|regex:/^[a-z0-9\_]+$/|min:3",
            'txt-fullname' => "required",
            'sl-group' => "required|alpha_num",
        ];
    }

    public function messages() {
        return [
            'txt-user-id.required' => "Thành viên không hợp lệ",
            'txt-user-id.alpha_num' => "Thành viên không hợp lệ",
            'txt-name.required' => "Tên đăng nhập không được để trống",
            'txt-name.regex' => "Tên đăng nhập không hợp lệ",
            'txt-name.min' => "Tên đăng nhập phải lớn hơn 3 ký tự",
            'txt-password.required' => "Mật khẩu không được để trống",
            'txt-fullname.required' => "Họ và tên không được để trống",
            'sl-group.required' => "Chưa chọn nhóm thành viên",
            'sl-group.required' => "Nhóm thành viên không hợp lệ",
        ];
    }

}
