<?php

namespace App\Models\Bhz;

use App\Models\BaseModel;
use App\Models\User\User;

/**
 * 接收超时通知的用户
 */
class MessageUser extends BaseModel
{
    /**
     * 表
     * 
     * @var string
     */
    protected $table = 'message_user';

    /**
     * 关联用户
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 同步更新
     * 
     * @param array $ids
     * @return bool
     */
    public function sync(array $ids)
    {
        $origin = $this->lists('user_id');
        
        $this->whereIn('user_id', $origin->diff($ids))->delete();

        $insert = collect($ids)->diff($origin->intersect($ids))->map(function ($item, $index) {
            return ['user_id' => $item];
        })->toArray();

        return $this->insert($insert);
    }
}
