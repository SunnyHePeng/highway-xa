<?php

namespace App\Models\Stretch;

use App\Models\BaseModel;
use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Section,
    App\Models\Device\Device;

/**
 * 张拉与压浆数据报警处理信息
  * 按照module_name来区分张拉与压浆
 */
class StretchMudjackWarnDealInfo extends Common
{

    protected $table = 'stretch_mudjack_warn_deal_info';

    //黑名单
    protected $guarded=[];

    public $timestamps = false;


}