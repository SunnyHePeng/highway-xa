<?php

namespace App\Models\Factory;

use App\Models\Common;
use Input, DB;

/**
 * 工厂详情
 */
class Factory_detail extends Common
{
    protected $table = 'factory_detail';
    protected $fillable = [
        					'id',
        					'factory_id',
      						'material_id',
      						'order_num',
      						'cl_position_row',
      						'cl_position_col',
      						'fact_z_cjjs',
      						'fact_z_position_row',
      						'fact_z_position_col',
      						'design_z_cjjs',
      						'design_z_position_row',
      						'design_z_position_col',
  						];
   	protected $rule = [
         					'factory_id'=>'required',
         					'material_id'=>'required',
         					'order_num'=>'required',
                ];
    public $timestamps = false;

    public function factory()
    {
        return $this->belongsTo('App\Models\Factory\Factory', 'factory_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\Factory\Material', 'material_id', 'id');
    }
}