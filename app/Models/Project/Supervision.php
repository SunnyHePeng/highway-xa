<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 监理
 */
class Supervision extends Common
{
    protected $table = 'supervision';
    protected $fillable = ['id','project_id','name','type','company','position','fzr','phone','created_at'];
   	protected $rule = [
   					'project_id'=>'required',
                    'name'=>'required',
                    'type'=>'required',
                    'company'=>'required',
                    //'position'=>'required',
                    'fzr'=>'required',
                    'phone'=>'required',
                ];
    public $timestamps = false;

    public function sec()
    {
        return $this->belongsToMany('App\Models\Project\Section', 'supervision_section', 'supervision_id', 'section_id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }

    /**
     * 标段
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'supervision_section');
    }    
}