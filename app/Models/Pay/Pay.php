<?php

namespace App\Models\Pay;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input, DB;

/**
 * 未发现对应的表
 */
class Pay extends Common
{
    protected $table = 'pay';
    protected $fillable = ['id','user_id','no','alipay_no','alipay_amount','balance','status','created_at','updated_at'];
   	protected $rule = [
                    'alipay_no'=>'required',
                    'alipay_amount'=>'required|numeric'
                ];
    public $timestamps = false;

    public function user(){
    	return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }
    

    public function doBack($pid, $amount){
        if($pid){
            
        }
    }

    /*某段时间充值合计金额*/
    public function payByTime($start_time, $end_time){
        $info = $this->select(DB::raw('sum(alipay_amount) as count'))
                    ->where('status', '=', 1)
                    ->where('created_at', '>=', $start_time)
                    ->where('created_at', '<', $end_time)
                    ->first()
                    ->toArray();
        return $info['count'];
    }
}