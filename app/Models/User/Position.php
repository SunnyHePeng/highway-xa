<?php

namespace App\Models\User;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 职位
 * 
 * 角色对应的职位
 */
class Position extends Common
{
    protected $table = 'position';
    protected $fillable = ['id','role_id','name'];
   	protected $rule = [
   					'role_id'=>'required',
                    'name'=>'required',
                ];
    public $timestamps = false;
    
    public function role(){
        return $this->belongsTo('App\Models\User\Role', 'role_id', 'id');
    }
}