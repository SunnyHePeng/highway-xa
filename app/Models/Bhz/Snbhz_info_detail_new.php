<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站物料详情
 */
class Snbhz_info_detail_new extends Common
{
    protected $table = 'snbhz_info_detail_new';
    protected $fillable = [
                  'id',
                  'snbhz_info_id',
                  'type',
                  'design',
                  'fact',
                  'pcl'
              ];
    protected $rule = [
                  'snbhz_info_id'=>'required',
              ];
    public $timestamps = false;
}