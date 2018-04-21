<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoginRequest extends Request {

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
            'txt-phone' => [
                'required',
                'regex:/^0(1|9)[0-9]{8,9}$/'
            ],
            'txt-password' => "required"
        ];
    }

    public function messages() {
        return [
            'txt-phone.required' => "Số điện thoại không được để trống",
            'txt-phone.regex' => "Số điện thoại không hợp lệ",
            'txt-password.required' => "Mật khẩu không được để trống",
        ];
    }

}
