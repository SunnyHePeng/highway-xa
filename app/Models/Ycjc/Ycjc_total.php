<?php

namespace App\Models\Ycjc;

use App\Models\Common;
use Input, DB;

/**
 * 扬尘统计（弃）
 */
class Ycjc_total extends Common
{
    protected $table = 'ycjc_total';
    protected $fillable = [
        				'id',
		                'project_id',
		                'section_id',
                    	'supervision_id',
		                'num',
      					'date',
  						];

    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function sup()
    {
        return $this->belongsTo('App\Models\Project\Supervision', 'supervision_id', 'id');
    }
}