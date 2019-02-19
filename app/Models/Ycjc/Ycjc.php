<?php

namespace App\Models\Ycjc;

use App\Models\Common;
use Input, DB;

/**
 * 扬尘（弃）
 */
class Ycjc extends Common
{
    protected $table = 'ycjc';
    protected $fillable = [
        				'id',
		                'project_id',
		                'section_id',
                    	'supervision_id',
		                'pm25',
		                'pm10',
        				'wd',
      					'sd',
      					'fs',
		                'fx',
		                'zs',
		                'warn',
		                'time'
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