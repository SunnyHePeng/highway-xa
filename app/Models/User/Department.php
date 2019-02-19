<?php

namespace App\Models\User;

use App\Models\Common;
use Input, DB;

/**
 * 部门
 */
class Department extends Common
{
    protected $table = 'department';
    protected $fillable = ['id','role_id','name'];
    protected $rule = [
        'role_id'=>'required',
        'name'=>'required',
    ];

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo('App\Models\User\Company','company_id','id');
    }
    public function role()
    {
       return $this->belongsTo('App\Models\User\Role','role_id','id');
    }

}