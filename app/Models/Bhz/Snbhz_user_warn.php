<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站报警设置（弃）
 */
class Snbhz_user_warn extends Common
{
    protected $table = 'snbhz_user_warn';
    protected $fillable = [
        					'id',
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