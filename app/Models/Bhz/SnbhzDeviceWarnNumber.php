<?php

namespace App\Models\Bhz;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

/**
 * 拌合站设备生产次数与初级高级报警次数记录
 */
class SnbhzDeviceWarnNumber extends Model
{
    /**
     * 表
     *
     * @var string
     */
    protected $table = 'snbhz_device_warn_number';

    /**
     * 批量黑名单
     *
     * @var array
     */
    public $guarded = [];

    public $timestamps=false;

    /**
     * 关联项目公司
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project\Project','project_id','id');

    }

    /**
     * 关联监理
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervision()
    {
        return $this->belongsTo('App\Models\Project\Supervision','supervision_id','id');

    }

    /**
     * 关联标段
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }

    /**
     * 关联设备
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo('App\Models\Device\Device','device_id','id');
    }


}