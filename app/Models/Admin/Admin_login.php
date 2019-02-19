<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Common;

/**
 * 未发现对应的表
 */
class Admin_login extends Common
{
    protected $table = 'admin_login';
    protected $fillable = ['name','role','date','count','ip','region','city','browser','created_at'];

    protected $dateFormat = 'U';
    public $timestamps = false;
}
