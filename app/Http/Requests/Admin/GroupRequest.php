<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class GroupRequest extends Request {

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
            'txt-name' => "required|min:3|unique:sys_groups,group_name",
        ];
    }

    public function messages() {
        return [
            'txt-name.required' => "Tên nhóm không được để trống",
            'txt-name.unique' => "Tên nhóm đã tồn tại",
            'txt-name.min' => "Tên nhóm phải lớn hơn 3 ký tự",
        ];
    }

}
