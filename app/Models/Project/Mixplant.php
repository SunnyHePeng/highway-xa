<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站（弃）
 */
class Mixplant extends Common
{
    protected $table = 'mixplant';
    protected $fillable = ['id','project_id','section_id','name','database_type','status','product_rate','factory','capacity','fzr','phone','created_at'];
   	protected $rule = [
   					'project_id'=>'required',
   					'section_id'=>'required',
                    'name'=>'required',
                    'database_type'=>'required',
                    'status'=>'required',
                    'product_rate'=>'required',
                    'factory'=>'required',
                    'capacity'=>'required',
                    'fzr'=>'required',
                    'phone'=>'required',
                ];
    public $timestamps = false;

    
}