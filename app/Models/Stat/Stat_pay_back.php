<?php

namespace App\Models\Stat;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 未发现对应的表
 */
class Stat_pay_back extends Common
{
    protected $table = 'stat_pay_back';
    protected $fillable = ['id','date','pay','back','consume','created_at'];
   	public $timestamps = false;

}