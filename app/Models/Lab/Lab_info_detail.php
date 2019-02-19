<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 普通试验试件（除钢筋试验）
 */
class Lab_info_detail extends Common
{
    protected $table = 'lab_info_detail';
    protected $fillable = [
                  'id',
                  'lab_info_id',
                  'type',
                  'lz',
                  'qd',
                  'lingqi',
                  'type1'
              ];
    protected $rule = [
                  'lab_info_id'=>'required',
              ];
    public $timestamps = false;
}