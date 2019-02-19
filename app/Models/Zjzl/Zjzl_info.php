<?php

namespace App\Models\Zjzl;

use App\Models\Common;
use Input, DB;

/**
 * 桩基（弃）
 */
class Zjzl_info extends Common
{
    protected $table = 'zjzl_info';
    protected $fillable = [
                        'id',
                        'project_id',
                        'section_id',
                        'supervision_id',
                        'device_cat',
                        'device_id',
                        'sgdw',
                        'jldw',
                        'bdbm',
                        'clxzjclbh',
                        'tshntbcbh',
                        'clxzjsjxm',
                        'tshntbcczy',
                        'zxh',
                        'azb1_fact',
                        'bzb1_fact',
                        'czsd_design',
                        'czsd_fact',
                        'wgqj_design',
                        'wgqj_fact',
                        'zjptspcd_design',
                        'zjptspcd_fact',
                        'clcdl_design',
                        'clcdl_fact',
                        'bspl_fact',
                        'dgzbscs_design',
                        'dgzbscs_fact',
                        'dghntgrl_design',
                        'dghntgrl_fact',
                        'ljhntbrl_fact',
                        'bcljyxsj_fact',
                        'azb2_fact',
                        'bzb2_fact',
                        'time',
                        'cksj',
                        'czsj',
                        'zsc_design',
                        'zsc_fact',
                        'zksd_design',
                        'zksd_fact',
                        'bgsd_design',
                        'bgsd_fact',
                        'sgxcfs_fact',
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
        return $this->hasMany('App\Models\Zlyj\Zhangla_info_detail', 'zhangla_info_id', 'id');
    }

    public function deal()
    {
        return $this->hasOne('App\Models\Zlyj\Zlyj_info_deal', 'zlyj_info_id', 'id');
    }

}