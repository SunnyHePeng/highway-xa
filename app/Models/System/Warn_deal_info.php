<?php

namespace App\Models\System;

use App\Models\Common;
use Input, DB;

/**
 * 报警信息处理(拌合站与试验室报警处理信息)
 * 按照module_id来区分拌合站与试验室
 */
class Warn_deal_info extends Common
{
    protected $table = 'warn_deal_info';
    protected $fillable = [
            					'id',
                      'module_id',
                      'device_id',
            					'info_id',
          						'sup_info',
                      'sup_img',
                      'sup_file',
                			'sup_name',
                			'sup_time',
                			'sec_info',
                      'sec_img',
                      'sec_file',
			                'sec_name',
			                'sec_time',
                      'pro_info',
                      'pro_img',
                      'pro_file',
                      'pro_name',
                      'pro_time'
  						    ];
   	protected $rule = [
 					'module_id'=>'required',
          'device_id'=>'required',
          'info_id'=>'required',
                ];
    public $timestamps = false;
}