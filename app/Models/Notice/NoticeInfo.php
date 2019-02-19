<?php

namespace App\Models\Notice;

use App\Models\Common;
use Input, DB;

/**
 * 公告信息
 */
class NoticeInfo extends Common
{
    protected $table = 'notice_info';
    //黑名单
    protected $guarded=[];

    public $timestamps = false;

    public function publish_user()
    {
        return $this->belongsTo('App\Models\User\User','publish_user_id','id');
    }


}