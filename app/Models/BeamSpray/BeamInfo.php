<?php

namespace App\Models\BeamSpray;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TreeScopeTrait;

/**
 * 预制梁
 */
class BeamInfo extends Model
{
    use TreeScopeTrait;

    /**
     *  喷淋已完成
     *
     * @var int
     */
    const IS_FINISHED = 1;

    /**
     *  喷淋未完成
     *
     * @var int
     */
    const IS_NOT_FINISHED = 0;

    /**
     * 批量黑名单
     *
     * @var array
     */
    public $guarded = [];

    /**
     * 表
     *
     * @var string
     */
    protected $table = 'beam_info';

    /**
     * 格式化时间
     *
     * @param \DateTime|int $value
     * @return int
     */
    public function fromDateTime($value)
    {
        return time();
    }

    /**
     * 格式化完成状态
     *
     * @return string
     */
    public function getFinishStatusAttribute()
    {
        return self::IS_FINISHED == $this->is_finish ? '已完成' : '未完成';
    }

    /**
     * 关联项目
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project\Project','project_id','id');
    }

    /**
     * 关联监理
     */
    public function sup()
    {
        return $this->belongsTo('App\Models\Project\Supervision','supervision_id','id');
    }

    /**
     * 关联合同段
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }

    /**
     * 关联合同段
     */
    public function beam_site()
    {
        return $this->belongsTo('App\Models\Project\BeamSite','beam_site_id','id');
    }


}
