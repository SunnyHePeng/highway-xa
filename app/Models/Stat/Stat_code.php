<?php

namespace App\Models\Stat;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 未发现对应的表
 */
class Stat_code extends Common
{
    protected $table = 'stat_code';
    protected $fillable = ['id','type','sale_count','code_count','activation_count','date','created_at'];
   	public $timestamps = false;
    
    public function type()
    {
        return $this->hasOne('App\Models\System\Code_type', 'id', 'type');
    }
}