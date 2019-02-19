<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道洞顶房屋沉降观测数据
 * Class TunnelHouseMonitor
 * @package App\Models\Sdtj
 */
class TunnelHouseMonitor extends Common
{
    protected $table = 'tunnel_house_monitor';
    protected $guarded=[];

    public $timestamps = false;


    public function write_user()
    {
        return $this->belongsTo('App\Models\User\User','write_user_id','id');
    }

    public function check_user()
    {
        return $this->belongsTo('App\Models\User\User','check_user_id','id');
    }

}