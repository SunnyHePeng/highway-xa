<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 试验
 */
class Lab_info extends Common
{
    protected $table = 'lab_info';
    protected $fillable = [
                            'id',
                        'project_id',
                        'section_id',
                    'supervision_id',
                        'device_cat',
                        'device_type',
                            'device_id',
                            'time',
                            'sybh',
                        'sydw',
                        'jldw',
                        'wtdw',
                        'sylx',
                        'syzh',
                        'sypz',
                        'sylq',
                        'qddj',
                        'syry',
                        'lbph',
                        'sjgg',
                        'sjgs',
                        'jzsl',
                        'yxlz',
                        'yxqd',
                        'xqfqd',
                        'klqd',
                        'xqflz',
                        'jxzh',
                        'jxqd',
                        'image',
                            'is_warn',
                            'warn_info',
                            'warn_level',
                            'is_warn_para1',
                            'is_warn_para2',
                    'is_warn_para3',
                            'is_sup_deal',
                    'is_sec_deal',
                    'created_at',
                    'is_24_notice',
                    'is_48_notice',
                    'reprotFile',
                    'is_pro_deal',
                    'warn_sx_level',
                    'warn_sx_info'
                        ];
    protected $rule = [
                            'device_id'=>'required',
                            'time'=>'required',
                ];
    public $timestamps = false;

    public function device()
    {
        return $this->belongsTo('App\Models\Device\Device', 'device_id', 'id');
    }

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

    public function detail(){
        return $this->hasMany('App\Models\Lab\Lab_info_detail', 'lab_info_id', 'id');
    }

    public function gjsydetail(){
        return $this->hasMany('App\Models\Lab\Lab_info_gjsy_detail', 'lab_info_id', 'id');
    }

    public function deal()
    {
        return $this->hasOne('App\Models\Lab\Lab_info_deal', 'lab_info_id', 'id');
    }

    public function warn()
    {
        return $this->hasOne('App\Models\Lab\Lab_warn_info', 'lab_info_id', 'id');
    }

}