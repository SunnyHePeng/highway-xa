<?php

namespace App\Models\User;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 模块
 */
class Module extends Common
{
    protected $table = 'module';
    protected $fillable = ['id','name','url','icon','pid','sort','is_new','shown'];
   	protected $rule = [
                    'name'=>'required',
                    'url'=>'required',
                    'icon'=>'required',
                ];
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsToMany('App\Models\User\User', 'user_module', 'module_id', 'user_id');
    }

    public function per()
    {
        return $this->hasMany('App\Models\User\Permission', 'mid', 'id');
    }

    public function child()
    {
        return $this->hasMany('App\Models\User\Module', 'pid', 'id');
    }
}