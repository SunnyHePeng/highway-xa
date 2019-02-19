<?php

namespace App\Models\Factory;

use App\Models\Common;
use Input, DB;

/**
 * 工厂
 */
class Factory extends Common
{
    protected $table = 'factory';
    protected $fillable = [
    						'id',
    						'name',
    						'data_row',
    						'data_analyse_type',
    						'date_position_row',
    						'date_position_col',
    						'date_formate_type',
    						'time_position_row',
    						'time_position_col',
    						'time_formate_type',
    						'pb_number_position_row',
    						'pb_number_position_col',
    						'design_ysb_position_row',
							'design_ysb_position_col',
							'design_ysb_standard',
							'fact_ysb_position_row',
							'fact_ysb_position_col',
							'hhlwd_position_row',
							'hhlwd_postion_col',
							'hhlwd_standard',
							'hhlwd_bj',
							'hhlwd_bj_pc',
							'lcwd_position_row',
							'lcwd_position_col',
							'lcwd_standard',
							'lcwd_bj',
							'lcwd_bj_pc',
							'lqwd_position_row',
							'lqwd_position_col',
							'lqwd_standard',
							'lqwd_bj',
							'lqwd_bj_pc',
							'cl_position_row',
							'cl_position_col',
  						];
   	protected $rule = [
   					'name'=>'required',
   					'data_row'=>'required',
                    'data_analyse_type'=>'required',
                ];
    public $timestamps = false;

    
}