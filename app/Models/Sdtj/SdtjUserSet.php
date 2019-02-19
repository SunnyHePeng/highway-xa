<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 工程进度统计中微信推送接收人
 */
class SdtjUserSet extends Common
{
    protected $table = 'sdtj_user_set';
    protected $fillable = ['id',
        'user_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('App\models\User\User','id','user_id');
    }


}