<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 设备分类
 */
class Device_category extends Common
{
    protected $table = 'device_category';
    protected $fillable = [
    						'id',
    						'name',
  						];
   	protected $rule = [
   					'name'=>'required',
                ];
    public $timestamps = false;

    public function collection(){
    	return $this->belongsToMany('App\Models\Device\Collection', 'collection_category', 'category_id', 'collection_id');
    }
}