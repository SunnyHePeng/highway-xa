<?php

namespace App\Models\Sdtj\SdtjPeopleDevice;

use App\Models\Common;
use Input, DB;

/**
 * 隧道人员，设备月统计
 */
class PeopleDeviceMonthStatistics extends Common
{
    protected $table = 'sdtj_people_device_month';
    /**
     * 批量黑名单
     * @var array
     */
    protected $guarded=[];

    public $timestamps = false;


}