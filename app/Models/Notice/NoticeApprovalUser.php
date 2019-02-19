<?php

namespace App\Models\Notice;

use App\Models\Common;
use Input, DB;

/**
 * 公告发布后推送微信模板消息人员
 */
class NoticeApprovalUser extends Common
{
    protected $table = 'notice_approval_user';
    //黑名单
    protected $guarded=[];

    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('App\models\User\User','id','user_id');
    }

}