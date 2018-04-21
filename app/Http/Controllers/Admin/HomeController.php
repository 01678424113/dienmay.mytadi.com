<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ImageUpload;
use Validator;

class HomeController extends Controller {

    public function index() {
        $response = [
            'title' => 'Bảng điều khiển'
        ];
        return view('admin.home', $response);
    }

    public function slug(Request $request) {
        if ($request->ajax()) {
            if ($request->has('slug')) {
                return response()->json([
                            "status_code" => 200,
                            "message" => "",
                            "data" => str_slug($request->input('slug'))
                ]);
            } else {
                return response()->json([
                            "status_code" => 500,
                            "message" => "Dữ liệu đầu vào không đúng",
                            "data" => ""
                ]);
            }
        }
        return redirect()->action('Admin\HomeController@index')->with('error', 'Không được truy cập trực tiếp');
    }

    public function uploadImage(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                        'file-image' => "required|image",
                            ], [
                        'file-image.required' => "Hình ảnh không hợp lệ",
                        'file-image.image' => "Hình ảnh không hợp lệ",
            ]);
            if (!$validator->fails()) {
                try {
                    $name = md5('image' . $request->file('file-image')->getClientOriginalName() . time());
                    $path = ImageUpload::image($request->file('file-image'), $name);
                    return response()->json([
                                "status_code" => 200,
                                "data" => $path
                    ]);
                } catch (\Exception $exc) {
                    return response()->json([
                                "status_code" => 500,
                                "message" => "Lỗi trong quá trình xử lý dữ liệu",
                    ]);
                }
            } else {
                return response()->json([
                            "status_code" => 422,
                            "message" => $validator->errors()->first(),
                ]);
            }
        }
        return redirect()->action('Admin\HomeController@index')->with('error', 'Không được truy cập trực tiếp');
    }

}
