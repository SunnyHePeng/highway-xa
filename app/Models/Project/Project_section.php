<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 标段
 * 
 * 此表与section表一致，只不过少几个字段，需要同时维护，暂不清楚此表的存在意义
 */
class Project_section extends Common
{
    protected $table = 'project_section';
    protected $fillable = ['id','project_id','name'];
   	protected $rule = [
   					'project_id'=>'required',
                    'name'=>'required',
                ];
    public $timestamps = false;

    public function project(){
        return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }
}