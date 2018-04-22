<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeleteRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Website;
use DB;
use Validator;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller {

    private $function_key = "category";
    private $function_name = "Chuyên mục";

    public function listCategory(Request $request,$category_type) {
        $response = [
            'title' => 'Chuyên mục'
        ];
        $response['categories'] = Category::where('category_type',$category_type)->get();
        return view('admin.category.listCategory', $response);
    }

    public function doAddCategory(CategoryRequest $request) {
        try {
            $category_slug = str_slug($request->input('txt-slug'));
            $category = Category::select(['category_id'])->where('category_slug', $category_slug)->first();
            if (empty($category)) {
                $category = new Category;
                $category->category_name = $request->input('txt-name');
                $category->category_slug = $category_slug;
                $category->category_type = $request->input('rd-status-type');
                $category->category_status = $request->input('rd-status');
                $category->category_created_at = microtime(true);
                $category->category_created_by = $request->session()->get('user')->user_id;
                try {
                    $category->save();
                    return redirect()->back()->with('success', 'Thêm chuyên mục "' . $category->category_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', 'Chuyên mục đã tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function loadCategory(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'category_id' => "required|alpha_num",
            ], [
                'category_id.required' => "Chuyên mục không hợp lệ",
                'category_id.alpha_num' => "Chuyên mục không hợp lệ",
            ]);
            if (!$validator->fails()) {
                try {
                    $category = Category::where('category_id', $request->input('category_id'))->first();
                    if (!empty($category)) {
                        return response()->json([
                            "status_code" => 200,
                            "data" => $category
                        ]);
                    } else {
                        return response()->json([
                            "status_code" => 404,
                            "message" => "Chuyên mục không tồn tại",
                        ]);
                    }
                } catch (\Exception $ex) {
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

    public function doEditCategory(CategoryRequest $request) {
        if ($request->ajax()) {
            try {
                $category = Category::where('category_id', $request->input('txt-category-id'))->first();
                $category_slug = str_slug($request->input('txt-slug'));

                if (!empty($category)) {

                    $category_exist = Category::select(['category_id'])->where('category_slug', $category_slug)
                        ->where('category_id', '<>', $category->category_id)->first();

                    if (empty($category_exist)) {

                        $category->category_name = $request->input('txt-name');
                        $category->category_slug = $category_slug;
                        $category->category_status = $request->input('rd-status');
                        $category->category_updated_at =microtime(true);
                        $category->category_updated_by = $request->session()->get('user')->user_id;
                        try {
                            $category->save();
                            return response()->json([
                                "status_code" => 200,
                                "message" => 'Sửa chuyên mục "' . $category->category_name . '" thành công',
                                "data" => $category
                            ]);
                        } catch (\Exception $ex) {
                            return response()->json([
                                "status_code" => 500,
                                "message" => "Lỗi trong quá trình xử lý dữ liệu ",
                            ]);
                        }
                    } else {
                        return response()->json([
                            "status_code" => 500,
                            "message" => "Chuyên mục đã tồn tại",
                        ]);
                    }
                } else {
                    return response()->json([
                        "status_code" => 404,
                        "message" => "Chuyên mục không tồn tại",
                    ]);
                }
            } catch (\Exception $exc) {
                return response()->json([
                    "status_code" => 500,
                    "message" => "Lỗi trong quá trình xử lý dữ liệu",
                ]);
            }
        }
        return redirect()->action('Admin\HomeController@index')->with('error', 'Không được truy cập trực tiếp');
    }

    public function doDeleteCategory(DeleteRequest $request) {
        try {
            $category = Category::select(['category_name', 'category_id'])->where('category_id', $request->input('txt-id'))->first();
            if (!empty($category)) {
                try {
                    $name = $category->category_name;
                    $category->delete();
                    return redirect()->back()->with('success', 'Xóa chuyên mục "' . $name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
                }
            } else {
                return redirect()->back()->with('error', 'Chuyên mục không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }



}
