<?php

namespace App\Models\System;

use App\Models\Common;
use Input, DB;

/**
 * 报警用户设置（拌合站和试验室）
 *根据module_id来区分拌合站与试验室
 */
class Warn_user_set extends Common
{
    protected $table = 'warn_user_set';
    protected $fillable = [
        					'id',
                  'project_id',
                  'supervision_id',
                  'section_id',
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