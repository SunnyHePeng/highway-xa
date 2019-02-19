<?php

namespace App\Models\Project;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * 地图信息
 */
class Map extends Common
{
    protected $table = 'map';
    protected $fillable = ['id','project_id','type','name','content','jwd','sort'];
   	protected $rule = [
                    'name'=>'required',
                    'type'=>'required',
                    'project_id'=>'required',
                    'jwd'=>'required',
                ];
    public $timestamps = false;
    
    public function project(){
        return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }
}