<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 标段
 */
class Section extends Common
{
    /**
     * 13标
     * 
     * @var int
     */
    const SECTION_THIRTEENTH = 19;

    /**
     * 14标
     * 
     * @var int
     */
    const SECTION_FOURTEENTH = 20;

    protected $table = 'section';
    protected $fillable = ['id','project_id','psection_id','name','begin_position','end_position','cbs_name','bhz_num','lc_num','sd_num','fzr','phone','created_at'];
   	protected $rule = [
   					'project_id'=>'required',
                    'psection_id'=>'required',
                    'name'=>'required',
                    'begin_position'=>'required',
                    'end_position'=>'required',
                    'cbs_name'=>'required',
                    'fzr'=>'required',
                    'phone'=>'required',
                ];
    public $timestamps = false;

    public function updateColumn($id, $column, $type){
        if($type == 'add'){
            Section::where('id', $id)->increment($column, 1);
        }else{
            Section::where('id', $id)->decrement($column, 1);
        }
    }

    public function sup()
    {
        return $this->belongsToMany('App\Models\Project\Supervision', 'supervision_section', 'section_id', 'supervision_id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }

    public function device(){
        return $this->hasMany('App\Models\Device\Device', 'section_id', 'id');
    }
}