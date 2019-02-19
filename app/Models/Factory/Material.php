<?php

namespace App\Models\Factory;

use App\Models\Common;
use Input, DB;

/**
 * 材料
 */
class Material extends Common
{
    protected $table = 'material';
    protected $fillable = [
              					'id',
              					'name',
            						'type',
            						'dasign_rate',
            						'warn_rate',
            						'note',
          					];
   	protected $rule = [
           					'name'=>'required',
           					'type'=>'required',
           					'dasign_rate'=>'required',
           					'warn_rate'=>'required',
                ];
    public $timestamps = false;

    public function detail()
    {
        return $this->hasOne('App\Models\Factory\Factory_detail','material_id','id');
    }
}