<?php

namespace App\Models\BeamSpray;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * 喷淋记录
 */
class BeamSprayRecord extends Model
{
    /**
     * 批量黑名单
     *
     * @var array
     */
    public $guarded = [];

    /**
     * 格式化时间
     *
     * @param \DateTime|int $value
     * @return int
     */
    public function fromDateTime($value)
    {
        return time();
    }
}
