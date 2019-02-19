<?php

namespace App\Models\Traits;

/**
 * ztree筛选
 * 
 * 主要针对ztree数据进行过滤（项目、监理、标段），链式调用，无需判断。
 */
trait TreeScopeTrait
{
    /**
     * 项目作用域
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $project_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfProject($query, $project_id = 0)
    {
        if(empty($project_id)) return $query;
        
        return $query->whereProjectId($project_id);        
    }

    /**
     * 监理作用域
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $supervision_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSupervision($query, $supervision_id = 0)
    {
        if(empty($supervision_id)) return $query;
        
        return $query->whereSupervisionId($supervision_id);        
    }

    /**
     * 标段作用域
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $section_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSection($query, $section_id = 0)
    {
        if(empty($section_id)) return $query;
        
        return $query->whereSectionId($section_id);        
    }
}
