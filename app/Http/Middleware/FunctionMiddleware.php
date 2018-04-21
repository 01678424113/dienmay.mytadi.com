<?php

namespace App\Http\Middleware;

use Closure;

class FunctionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $function_keys) {
        $user = $request->session()->get('user');
        $function_keys = explode('|', $function_keys);
        foreach ($function_keys as $function_key) {
            if (isset($user->permissions[$function_key])) {
                return $next($request);
            }
        }
        if ($request->ajax()) {
            return response(json_encode([
                "code" => 403,
                "message" => "Không có quyền truy cập",
                "data" => ""
            ]));
        } else {
            return redirect()->action('Admin\HomeController@index')->with('error', 'Không có quyền truy cập');
        }
    }

}
