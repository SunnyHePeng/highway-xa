<?php

namespace App\Models\Sdtj\SdtjPeopleDevice;

use App\Models\Common;
use Input, DB;

/**
 * 隧道人员，设备日统计
 */
class PeopleDevice extends Common
{
    protected $table = 'sdtj_people_device_day';
    /**
     * 批量黑名单
     * @var array
     */
    protected $guarded=[];

    public $timestamps = false;


}