<?php

namespace App\Models\Smog;

use App\Models\BaseModel;
use App\Models\Device\Device;
use App\Models\Project\Section;

/**
 * 环境监测数据
 */
class Environment extends BaseModel
{
    /**
     * 表
     * 
     * @var string
     */
    protected $table = 'environment_info';

    /**
     * 日期段作用域
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $start_date
     * @param string $end_date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDuration($query, $start_date, $end_date)
    {
        return $query->whereDate('datetime', '>=', $start_date)
                    ->whereDate('datetime', '<=', $end_date);
    }
    
    /**
     * 关联设备
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

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
     * 标段作用域
     * 
     * @param string $section
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSection($query, $section)
    {
        if($section)
            return $query->whereSectionId($section);
        
        return $query;
    }

}
