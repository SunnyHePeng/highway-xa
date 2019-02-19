<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站报警处理（弃）
 */
class Snbhz_info_deal extends Common
{
    protected $table = 'snbhz_info_deal';
    protected $fillable = [
            					'id',
            					'snbhz_info_id',
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
 					'snbhz_info_id'=>'required',
                ];
    public $timestamps = false;
}