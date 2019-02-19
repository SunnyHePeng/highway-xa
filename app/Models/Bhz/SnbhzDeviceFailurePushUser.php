<?php

namespace App\Models\Bhz;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

/**
 * 拌合站设备与采集端连接异常时通知人员信息
 */
class SnbhzDeviceFailurePushUser extends Model
{
    /**
     * 表
     *
     * @var string
     */
    protected $table = 'snbhz_device_failure_push_user';

    /**
     * 批量黑名单
     *
     * @var array
     */
    public $guarded = [];

    public $timestamps=false;

    public function user()
    {
        return $this->belongsTo('App\Models\User\User','user_id','id');

    }

}