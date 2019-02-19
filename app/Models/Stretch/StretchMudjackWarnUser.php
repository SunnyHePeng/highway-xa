<?php

namespace App\Models\Stretch;

use App\Models\BaseModel;
use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Section,
    App\Models\Device\Device;

/**
 * 张拉与压浆数据报警推送人员信息（报警用户设置）
 * 按照module_name区分张拉与压浆
 */
class StretchMudjackWarnUser extends Common
{

    protected $table = 'stretch_mudjack_warn_user';

    //黑名单
    protected $guarded=[];

    public $timestamps = false;

    public function user()
    {
        return  $this->belongsTo('App\Models\User\User','user_id','id');
    }

}