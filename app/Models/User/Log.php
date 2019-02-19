<?php

namespace App\Models\User;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 日志
 */
class Log extends Common
{
    protected $table = 'log';
    protected $fillable = ['id','user_id','name','ip','addr','act','type','created_at'];
   	protected $rule = [
                    'name'=>'required',
                    'ip'=>'required',
                    'addr'=>'required',
                    'act'=>'required',
                    'type'=>'required',
                ];
    public $timestamps = false;
    
}