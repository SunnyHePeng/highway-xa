<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 钢筋试验试件详情
 */
class Lab_info_gjsy_detail extends Common
{
    protected $table = 'lab_info_gjsy_detail';
    protected $fillable = [
                  'id',
                  'lab_info_id',
                  'type',
                  'lz',
                  'qd',
                  'is_qd_warn',
                  'jxhz',
                  'jxqd',
                  'is_jxqd_warn',
              ];
    protected $rule = [
                  'lab_info_id'=>'required',
              ];
    public $timestamps = false;
}