<?php

namespace App\Models\Stretch;

use App\Models\BaseModel;
use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Section,
    App\Models\Device\Device;

/**
 * 张拉详情数据
 */
class StretchInfoDetail extends Common
{

    protected $table = 'stretch_info_detail';

    //黑名单
    protected $guarded=[];

    public $timestamps = false;

    public function info()
    {
        return $this->belongsTo('App\Models\Stretch\StretchInfo', 'info_id', 'id');
    }

}