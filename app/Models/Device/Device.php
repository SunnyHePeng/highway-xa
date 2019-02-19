<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;
use App\Models\Traits\TreeScopeTrait;

/**
 * 设备
 */
class Device extends Common
{
    use TreeScopeTrait;
    
    protected $table = 'device';
    protected $fillable = [
    						'id',
    						'project_id',
                'supervision_id',
    						'section_id',
    						'cat_id',
    						'name',
    						'dcode',
    						'model',
    						'parame1',
    						'parame2',
    						'parame3',
    						'parame4',
                'parame5',
                'parame6',
    						'factory_name',
    						'factory_date',
    						'fzr',
                'phone',
                 'camera1',
                 'camera1_encoderuuid',
                 'camera2',
                 'camera2_encoderuuid',
                'is_online',
                'last_time',
                'beam_site_id'

  						];
   	protected $rule = [
   					'project_id'=>'required',
            'supervision_id'=>'required',
   					'section_id'=>'required',
   					'cat_id'=>'required',
   					'name'=>'required',
   					'dcode'=>'required',
   					'model'=>'required',
   					'factory_name'=>'required',
   					'factory_date'=>'required',
   					'fzr'=>'required',
                    'phone'=>'required',
                ];
    public $timestamps = false;

    public function section(){
    	return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function sup(){
      return $this->belongsTo('App\Models\Project\Supervision', 'supervision_id', 'id');
    }

    public function project(){
      return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }

    public function category(){
    	return $this->belongsTo('App\Models\Device\Device_category', 'cat_id', 'id');
    }

    /*public function collection(){
    	return $this->belongsTo('App\Models\Device\Collection', 'collection_id', 'id');
    }

    public function type(){
    	return $this->belongsTo('App\Models\Device\Device_type', 'device_type', 'id');
    }*/

    public function warn_total(){
      return $this->hasMany('App\Models\Snbhz\Snbhz_warn_total', 'device_id', 'id');
    }

    /**
     * 关联梁场
     */
    public function beam_site()
    {
        return $this->belongsTo('App\Models\Project\BeamSite','beam_site_id','id');
    }
}