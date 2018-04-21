<?php

namespace App\Helpers;

use Session;

class Permission {

    public static function checkFunction($function_keys) {
        $user = Session::get('user');
        if (in_array($user->user_id, [10, 11, 12])) {
            return true;
        }
        $function_keys = explode('|', $function_keys);
        foreach ($function_keys as $function_key) {
            if (isset($user->permissions[$function_key])) {
                return true;
            }
        }
        return false;
    }

    public static function checkAction($function_key, $action_keys) {
        $user = Session::get('user');
        if (in_array($user->user_id, [10, 11, 12])) {
            return true;
        }
        $action_keys = explode('|', $action_keys);
        if (isset($user->permissions[$function_key])) {
            foreach ($action_keys as $action_key) {
                if (in_array($action_key, $user->permissions[$function_key])) {
                    return true;
                }
            }
        }
        return false;
    }

}
