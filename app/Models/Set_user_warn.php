<?php

namespace App\Models;

use App\Models\Common;
use Input, DB;

/**
 * 未发现对应的表
 */
class Set_user_warn extends Common
{
    protected $table = 'set_user_warn';
    protected $fillable = [
        					'id',
                  'module_id',
        					'user_id',
      						'cj_0',
      						'cj_24',
      						'cj_48',
      						'zj_0',
      						'zj_24',
      						'zj_48',
      						'gj_0',
      						'gj_24',
      						'gj_48',
  						];
   	protected $rule = [
         			'user_id'=>'required',
                ];
    public $timestamps = false;
}