<?php

namespace App\Models\Remove;

use App\Models\Common;
use Input, DB;

/**
 * 征地拆迁日数据信息
 */
class RemoveDay extends Common
{
    protected $table = 'remove_day';
    /**
     * 批量黑名单
     * @var array
     */
    protected $guarded=[];

    public $timestamps = false;

    /**
     * 关联合同段
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }

    /**
     * 关联监理单位
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervision()
    {
        return $this->belongsTo('App\Models\Project\Supervision','supervision_id','id');
    }


}