<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 项目
 */
class Project extends Common
{
    protected $table = 'project';
    protected $fillable = ['id','name','section_num','supervision_num','mileage','summary','created_at'];
   	protected $rule = [
                    'name'=>'required',
                    'mileage'=>'required',
                    'supervision_num'=>'required',
                    'section_num'=>'required'
                ];
    public $timestamps = false;

    public function sup(){
        return $this->hasMany('App\Models\Project\Supervision', 'project_id', 'id');
    }
    
}