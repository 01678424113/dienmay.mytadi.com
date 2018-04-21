<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Func extends Model
{
    //
    protected $table = 'sys_functions';
    protected $primaryKey = 'function_id';
    public $timestamps = false;
}
