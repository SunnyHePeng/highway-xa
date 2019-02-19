<?php

namespace App\Models\Stretch;

use App\Models\BaseModel;
use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Section,
    App\Models\Device\Device;

/**
 * 张拉与压浆报警数据统计
 * 根据module_name来区分张拉与压浆
 */
class StretchMudjackStatWarn extends Common
{

    protected $table = 'stretch_mudjack_stat_warn';

    //黑名单
    protected $guarded=[];

    public $timestamps = false;


    /**
     * 关联标段
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    /**
     * 关联设备
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo('App\Models\Device\Device','device_id','id');
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
     * 管理项目公司
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project\Project','project_id','id');
    }

    /**
     * 关联梁场
     */
    public function beam_site()
    {
        return $this->belongsTo('App\Models\Project\BeamSite','beam_site_id','id');
    }

}