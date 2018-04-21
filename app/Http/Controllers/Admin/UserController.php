<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Requests\Admin\UserAddRequest;
use App\Http\Requests\Admin\UserEditRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use App\Http\Requests\Admin\LoginRequest;

class UserController extends Controller
{
    private $function_key = "thanh-vien";
    private $function_name = "Thành viên";

    public function listUser(Request $request) {
        $response = [
            'title' => 'Thành viên'
        ];
        $users_query = User::select([
            'users.user_id',
            'users.user_name',
            'users.user_fullname',
            'users.user_status',
            'users.user_created_at',
            'sys_groups.group_id',
            'users.user_group_id',
            'sys_groups.group_name'
        ])
            ->join('sys_groups', 'sys_groups.group_id', '=', 'users.user_group_id')
            ->orderBy('users.user_fullname', 'ASC');
        $response['users'] = $users_query->paginate(env('PAGINATE_ITEM', 20));
        $response['groups'] = Group::select(['group_id', 'group_name'])->get();
        return view('admin.user.listUser', $response);
    }

    public function doAddUser(UserAddRequest $request) {
        try {
            $username = trim($request->input('txt-name'));
            $user = User::select(['user_id'])->where('user_name', $username)->first();
            if (empty($user)) {
                $user = new User;
                $user->user_name = $username;
                $user->user_group_id = $request->input('sl-group');
                $user->user_password = md5($request->input('txt-password') . 'gugitech');
                $user->user_fullname = trim($request->input('txt-fullname'));
                $user->user_status = $request->input('rd-status');
                $user->user_created_at = round(microtime(true) * 1000);
                $user->user_created_by = $request->session()->get('user')->user_id;
                try {
                    $user->save();

                    return redirect()->back()->with('success', 'Thêm thành viên thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', "Tên đăng nhập đã tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function doEditUser(UserEditRequest $request) {
        try {

            $user = User::where('user_id', $request->input('txt-user-id'))->first();
            if (!empty($user)) {
                $username = trim($request->input('txt-name'));
                $user_exist = User::select('user_id')
                    ->where('user_name', $username)
                    ->where('user_id', '<>', $user->user_id)
                    ->first();
                if (empty($user_exist)) {
                    $user->user_name = $username;
                    $user->user_group_id = $request->input('sl-group');
                    if ($request->input('txt-password') != "") {
                        $user->user_password = md5($request->input('txt-password') . 'gugitech');
                    }
                    $user->user_fullname = trim($request->input('txt-fullname'));
                    $user->user_status = $request->input('rd-status');
                    $user->user_updated_at = round(microtime(true) * 1000);
                    $user->user_updated_by = $request->session()->get('user')->user_id;
                    try {
                        $user->save();
                      /*  try {
                            UserActivity::insert([
                                'activity_user_id' => $request->session()->get('user')->user_id,
                                'activity_content' => 'Đã sửa thành viên "' . $user->user_name . '" thành công',
                                'activity_useragent' => $request->header('User-Agent'),
                                'activity_ipaddress' => $request->ip(),
                                'activity_function_key' => $this->function_key,
                                'activity_function_name' => $this->function_name,
                                'activity_created_at' => round(microtime(true) * 1000),
                            ]);
                        } catch (\Exception $exc) {

                        }*/
                        return redirect()->back()->with('success', 'Sửa thành viên "' . $user->user_name . '" thành công');
                    } catch (\Exception $exc) {
                        return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                    }
                } else {
                    return redirect()->back()->with('error', "Tên đăng nhập đã tồn tại");
                }
            } else {
                return redirect()->back()->with('error', "Thành viên không tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function loadUser(Request $request) {

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'user_id' => "required|alpha_num",
            ], [
                'user_id.required' => "Thành viên không hợp lệ",
                'user_id.alpha_num' => "Thành viên không hợp lệ",
            ]);

            if (!$validator->fails()) {
                try {
                    $user = User::select(['user_id', 'user_name', 'user_fullname', 'user_group_id', 'user_status'])
                        ->where('user_id', $request->input('user_id'))->first();
                    if (!empty($user)) {
                        return response()->json([
                            "status_code" => 200,
                            "data" => $user
                        ]);
                    } else {
                        return response()->json([
                            "status_code" => 404,
                            "message" => "Thành viên không tồn tại",
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

    public function doDeleteUser(DeleteRequest $request) {
        try {
            $user = User::select(['user_name', 'user_id'])->where('user_id', $request->input('txt-id'))->first();
            if (!empty($user)) {
                try {
                    $user_name = $user->user_name;
                    $user->delete();
                  /*  try {
                        UserActivity::insert([
                            'activity_user_id' => $request->session()->get('user')->user_id,
                            'activity_content' => 'Đã xóa thành viên "' . $user_name . '" thành công',
                            'activity_useragent' => $request->header('User-Agent'),
                            'activity_ipaddress' => $request->ip(),
                            'activity_function_key' => $this->function_key,
                            'activity_function_name' => $this->function_name,
                            'activity_created_at' => round(microtime(true) * 1000),
                        ]);
                    } catch (\Exception $exc) {

                    }*/
                    return redirect()->back()->with('success', 'Xóa thành viên "' . $user_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
                }
            } else {
                return redirect()->back()->with('error', 'Thành viên không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }
}
