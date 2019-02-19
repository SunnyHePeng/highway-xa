<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 采集点
 */
class Collection extends Common
{
    protected $table = 'collection';
    protected $fillable = [
    						'id',
    						'project_id',
    						'section_id',
                'foreign_key',
                'cat_id',
    						'name',
  						];
   	protected $rule = [
   					'project_id'=>'required',
    				'section_id'=>'required',
            'foreign_key'=>'required',
            'cat_id'=>'required',
   					'name'=>'required',
                ];
    public $timestamps = false;

    /*public function section(){
    	return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function category(){
      return $this->belongsToMany('App\Models\Device\Device_category', 'collection_category', 'collection_id', 'category_id');
    }*/

    public function getCollectionByCat($pro_id, $sec_id, $cat_id)
    {
        $data = $this->where('cat_id', $cat_id)
                      ->where('project_id', '=', $pro_id)
                      ->where('section_id', '=', $sec_id)
                      ->get()
                      ->toArray();
        return $data;

    }
}