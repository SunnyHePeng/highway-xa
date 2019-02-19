<?php

namespace App\Models\Project;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 梁场信息
 */
class BeamSite extends Common
{
    protected $table = 'beam_site';
    //黑名单
    protected  $guarded=[];

    public $timestamps = false;

    /**关联项目公司
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project\Project','project_id','id');
    }

    /**关联监理
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervision()
    {
        return $this->belongsTo('App\Models\Project\Supervision','supervision_id','id');
    }

    /**关联合同段
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }


}