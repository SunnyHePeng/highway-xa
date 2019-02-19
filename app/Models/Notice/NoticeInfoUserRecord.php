<?php

namespace App\Models\Notice;

use App\Models\Common;
use Input, DB;

/**
 * 公告查看和下载人员记录表
 */
class NoticeInfoUserRecord extends Common
{
    protected $table = 'notice_info_user_record';
    //黑名单
    protected $guarded=[];

    public $timestamps = false;

    /**
     * 关联用户表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User','user_id','id');
    }

    /**
     * 关联notice_info表
     */
    public function notice_info()
    {
        return $this->belongsTo('App\Models\Notice\NoticeInfo','notice_id','id');
    }


    public function createRecord($type,$user_id,$notice_info_id)
    {
       $data=[
           'notice_info_id'=>$notice_info_id,
           'user_id'=>$user_id,
           'type'=>$type,
           'created_at'=>time(),
       ];

       $result=$this->insert($data);

       return $result;

    }





}