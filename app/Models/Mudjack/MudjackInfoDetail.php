<?php

namespace App\Models\Mudjack;

use App\Models\BaseModel;
use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Section,
    App\Models\Device\Device;

/**
 * 压浆详情数据
 */
class MudjackInfoDetail extends Common
{

    protected $table = 'mudjack_info_detail';

    //黑名单
    protected $guarded=[];

    public $timestamps = false;

    public function info()
    {
        return $this->belongsTo('App\Models\Mudjack\MudjackInfo', 'info_id', 'id');
    }

}