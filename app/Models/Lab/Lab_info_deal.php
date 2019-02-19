<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 试验室报警处理（弃）
 */
class Lab_info_deal extends Common
{
    protected $table = 'lab_info_deal';
    protected $fillable = [
        					'id',
        					'lab_info_id',
      						'sup_info',
                			'sup_name',
                			'sup_time',
                			'sec_info',
			                'sec_name',
			                'sec_time',
                      'pro_info',
                      'pro_name',
                      'pro_time'
  						    ];
   	protected $rule = [
 					'lab_info_id'=>'required',
                ];
    public $timestamps = false;
}