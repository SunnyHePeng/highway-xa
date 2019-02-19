<?php

namespace App\Models\Sdtj\SystemStat;

use App\Models\Common;
use Input, DB;

/**
 * 系统使用情况数据
 */
class SystemUseConditionData extends Common
{
    protected $table = 'system_use_condition_data';
    //黑名单
    protected $guarded = [];

    public $timestamps = false;

    public function supervision()
    {
        return $this->belongsTo('App\Models\Project\Supervision','unit_id','id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','unit_id','id');
    }
}