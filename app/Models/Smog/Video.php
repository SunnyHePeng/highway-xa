<?php

namespace App\Models\Smog;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 治污减霾施工相关视频
 */
class Video extends BaseModel
{
    use SoftDeletes;
    /**
     * 表
     * 
     * @var string
     */
    protected $table = 'smog_videos';
}
