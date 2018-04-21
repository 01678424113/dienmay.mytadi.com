<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Requests\Admin\FunctionAddRequest;
use App\Http\Requests\Admin\FunctionEditRequest;
use App\Http\Requests\Admin\GroupAddRequest;
use App\Http\Requests\Admin\GroupRequest;
use App\Models\Func;
use App\Models\Group;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;

class PermissionController extends Controller
{
    private $function_key = "permission";
    private $function_name = "Phân quyền";

    public function listGroup() {
        $response = [
            'title' => 'Nhóm thành viên'
        ];
        $group_query = Group::orderBy('group_id', 'ASC');
        $response['groups'] = $group_query->paginate(env('PAGINATE_ITEM', 20));
        return view('admin.permission.listGroup', $response);
    }

    public function addGroup() {
        $response = [
            'title' => "Thêm nhóm thành viên"
        ];
        $response['functions'] = Func::select(['function_name', 'function_key'])
            ->orderBy('function_name', 'ASC')
            ->get();
        return view('admin.permission.addGroup', $response);
    }

    public function doAddGroup(GroupRequest $request) {
        try {
            $name = trim($request->input('txt-name'));
            $group = Group::select(['group_id'])->where('group_name', $name)->first();
            if (empty($group)) {
                $group = new Group;
                $group->group_name = $name;
                $group->group_created_by = $request->session()->get('user')->user_id;
                $group->group_created_at = microtime(true);
                try {
                    $group->save();
                    if ($request->has('cb-functions')) {
                        $group_function_data = [];
                        foreach ($request->input('cb-functions') as $func) {
                            $actions = [];
                            if ($request->has('cb-' . $func . '-actions')) {
                                $actions = $request->input('cb-' . $func . '-actions');
                            }
                            array_push($group_function_data, [
                                'group_id' => $group->group_id,
                                'function_key' => $func,
                                'actions' => json_encode($actions)
                            ]);
                        }
                        try {
                            DB::table('sys_group_function')->insert($group_function_data);
                        } catch (\Exception $exc) {
                            return redirect()->back()->with('warning', "Thêm quyền cho nhóm không thành công");
                        }
                    }
                    return redirect()->action('Admin\PermissionController@listGroup')->with('success', 'Thêm nhóm thành viên "' . $group->group_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', "Tên nhóm đã tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function editGroup(Request $request, $group_id) {
        try {
            $group = Group::where('group_id', $group_id)->first();
            if (!empty($group)) {
                $response = [
                    'title' => 'Sửa nhóm: ' . $group->group_name,
                ];
                $group_function = DB::table('sys_group_function')->where('group_id', $group->group_id)->get();
                $map_functions = [];
                foreach ($group_function as $gf) {
                    $map_functions[$gf->function_key] = json_decode($gf->actions);
                }
                $group->functions = $map_functions;
                $response['group'] = $group;
                $response['functions'] = Func::select(['function_name', 'function_key'])
                    ->orderBy('function_name', 'ASC')
                    ->get();
                return view('admin.permission.editGroup', $response);
            } else {
                return redirect()->action('Admin\PermissionController@listGroup')->with('error', "Nhóm thành viên không tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->action('Admin\PermissionController@listGroup')->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function doEditGroup(GroupAddRequest $request, $group_id) {
        try {
            $group = Group::where('group_id', $group_id)->first();
            if (!empty($group)) {
                $name = trim($request->input('txt-name'));
                $group_exist = Group::select(['group_id'])
                    ->where('group_id', '<>', $group->group_id)
                    ->where('group_name', $name)->first();
                if (empty($group_exist)) {
                    $group->group_name = $name;
                    $group->group_updated_by = $request->session()->get('user')->user_id;
                    $group->group_updated_at = microtime(true);
                    try {
                        $group->save();
                        if ($request->has('cb-functions')) {
                            $group_function_data = [];
                            foreach ($request->input('cb-functions') as $func) {
                                $actions = [];
                                if ($request->has('cb-' . $func . '-actions')) {
                                    $actions = $request->input('cb-' . $func . '-actions');
                                }
                                array_push($group_function_data, [
                                    'group_id' => $group->group_id,
                                    'function_key' => $func,
                                    'actions' => json_encode($actions)
                                ]);
                            }
                            try {
                                DB::table('sys_group_function')->where('group_id', $group->group_id)->delete();
                                DB::table('sys_group_function')->insert($group_function_data);
                            } catch (\Exception $exc) {
                                return redirect()->back()->with('warning', "Thêm quyền cho nhóm không thành công");
                            }
                        } else {
                            try {
                                DB::table('sys_group_function')->where('group_id', $group->group_id)->delete();
                            } catch (\Exception $exc) {
                                return redirect()->back()->with('warning', "Thêm quyền cho nhóm không thành công");
                            }
                        }
                        return redirect()->action('Admin\PermissionController@listGroup')->with('success', 'Sửa nhóm thành viên "' . $group->group_name . '" thành công');
                    } catch (\Exception $exc) {
                        return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                    }
                } else {
                    return redirect()->back()->with('error', "Tên nhóm đã tồn tạ 1");
                }
            } else {
                return redirect()->action('Admin\PermissionController@listGroup')->with('error', "Nhóm thành viên không tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function doDeleteGroup(DeleteRequest $request) {
        try {
            $group = Group::select(['group_name', 'group_id'])->where('group_id', $request->input('txt-id'))->first();
            if (!empty($group)) {
                try {
                    $group_name = $group->group_name;
                    $group->delete();
                    DB::table('sys_group_function')->where('group_id', $group->group_id)->delete();
                    return redirect()->back()->with('success', 'Xóa nhóm thành viên "' . $group_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
                }
            } else {
                return redirect()->back()->with('error', 'Nhóm thành viên không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }

    public function listFunction() {
        $response = [
            'title' => 'Chức năng hệ thống'
        ];
        $function_query = Func::orderBy('function_name', 'ASC');
        $response['functions'] = $function_query->paginate(env('PAGINATE_ITEM', 20));
        return view('admin.permission.listFunction', $response);
    }

    public function doAddFunction(FunctionAddRequest $request) {

        try {
            $funcion_key = trim($request->input('txt-function-key'));
            $func = Func::select(['function_id'])
                ->where('function_key', $funcion_key)->first();
            if (empty($func)) {
                $func = new Func;
                $func->function_key = $funcion_key;
                $func->function_name = trim($request->input('txt-function-name'));
                try {
                    $func->save();
                    return redirect()->back()->with('success', 'Thêm chức năng hệ thống "' . $func->function_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', "Chức năng hệ thống đã tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function doEditFunction(FunctionEditRequest $request) {

        try {
            $func = Func::where('function_id', $request->input('txt-function-id'))->first();
            if (!empty($func)) {
                $func->function_name = trim($request->input('txt-function-name'));
                try {
                    $func->save();

                    return redirect()->back()->with('success', 'Sửa chức năng hệ thống "' . $func->function_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', "Chức năng hệ thống không tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function loadFunction(Request $request) {

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'function_id' => "required|alpha_num",
            ], [
                'function_id.required' => "Chức năng hệ thống không hợp lệ",
                'function_id.alpha_num' => "Chức năng hệ thống không hợp lệ",
            ]);
            if (!$validator->fails()) {
                try {
                    $func = Func::where('function_id', $request->input('function_id'))->first();
                    if (!empty($func)) {
                        return response()->json([
                            "status_code" => 200,
                            "data" => $func
                        ]);
                    } else {
                        return response()->json([
                            "status_code" => 404,
                            "message" => "Chức năng hệ thống không tồn tại",
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

    public function doDeleteFunction(DeleteRequest $request) {
        try {
            $function = Func::select(['function_name', 'function_id'])->where('function_id', $request->input('txt-id'))->first();
            if (!empty($function)) {
                try {
                    $function_name = $function->function_name;
                    DB::table('sys_group_function')->where('function_key', $function->function_key)->delete();
                    $function->delete();
                    return redirect()->back()->with('success', 'Xóa chức năng hệ thống "' . $function_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
                }
            } else {
                return redirect()->back()->with('error', 'Chức năng hệ thống không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }
}
