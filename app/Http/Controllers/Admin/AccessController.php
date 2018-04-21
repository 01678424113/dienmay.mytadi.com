<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\LoginRequest;

class AccessController extends Controller {

    public function login(Request $request) {
        if (!$request->session()->has('user')) {
            return view('admin.login', [
                'title' => "Đăng nhập"
            ]);
        } else {
            return redirect()->action('Admin\HomeController@index');
        }
    }

    public function doLogin(LoginRequest $request) {
        if (!$request->session()->has('user')) {
            try {
                $user = User::where('user_name', $request->input('txt-username'))
                    ->first();
                if (!empty($user)) {
                    if ($user->user_status == 1) {
                        if ($user->user_password == md5($request->input('txt-password') . 'gugitech')) {

                            $group_function = DB::table('sys_group_function')->where('group_id', $user->user_group_id)
                                ->get();
                            $permissions = [];
                            foreach ($group_function as $gf) {
                                $permissions[$gf->function_key] = json_decode($gf->actions);
                            }
                            $user->permissions = $permissions;
                            $request->session()->put('user', $user);

                            return redirect()->action('Admin\HomeController@index')->with('success', "Đăng nhập thành công");
                        } else {
                            return redirect()->back()->with('error', "Mật khẩu không đúng");
                        }
                    } else {
                        return redirect()->back()->with('error', "Thành viên này đã bị khóa");
                    }
                } else {
                    return redirect()->back()->with('error', "Thành viên không tồn tại");
                }
            } catch (\Exception $exc) {
                return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
            }
        } else {
            return redirect()->action('Admin\HomeController@index');
        }
    }

    public function logout(Request $request) {
        if ($request->session()->has('user')) {
            $request->session()->forget('user');
        }
        return redirect()->action('Admin\AccessController@login');
    }

}
