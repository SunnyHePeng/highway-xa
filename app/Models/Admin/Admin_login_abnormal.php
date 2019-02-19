<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;

/**
 * 未发现对应的表
 */
class Admin_login_abnormal extends Common
{
    protected $table = 'admin_login_abnormal';
    protected $fillable = ['uid','name','info','ip','region','city','created_at'];

    protected $dateFormat = 'U';
    public $timestamps = false;
}
