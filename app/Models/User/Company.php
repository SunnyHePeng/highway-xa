<?php

namespace App\Models\User;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 单位
 */
class Company extends Common
{
    protected $table = 'company';
    protected $fillable = ['id','name','sort','role_id'];
   	protected $rule = [
                    'name'=>'required'
                ];
    public $timestamps = false;
    public function role()
    {
        return $this->belongsTo('App\Models\User\Role','role_id','id');
    }
}