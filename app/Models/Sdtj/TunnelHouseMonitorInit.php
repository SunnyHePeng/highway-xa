<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道洞顶房屋沉降观测初始高程数据
 * Class TunnelHouseMonitorInit
 * @package App\Models\Sdtj
 */
class TunnelHouseMonitorInit extends Common
{
    protected $table = 'tunnel_house_monitor_init';
    protected $guarded=[];

    public $timestamps = false;



}