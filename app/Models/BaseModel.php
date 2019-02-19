<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
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
