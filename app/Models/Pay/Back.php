<?php

namespace App\Models\Pay;

use App\Models\Common;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Input, DB;

/**
 * 未发现对应的表
 */
class Back extends Common
{
    protected $table = 'back';
    protected $fillable = ['id','user_id','agent_id','no','amount','balance','created_at'];
   	protected $rule = [
                    'user_id'=>'required',
                    'agent_id'=>'required',
                    'no'=>'required',
                    'amount'=>'required|numeric',
                    'created_at'=>'required'
                ];
    public $timestamps = false;

    public function user(){
    	return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }
    
    /*添加返现信息*/
    public function addBack($pid, $user_id, $amount){
        $user = User::select(['id','balance'])->where('id', $pid)->first();

        $amount = $amount*Config()->get('common.back_proportion')/100;
        $balance = $user->balance + $amount;
        $info = [
            'user_id'=>$pid,
            'agent_id'=>$user_id,
            'no'=>$this->getNo(Config()->get('common.back_pre')),
            'amount'=>$amount,
            'balance'=>$balance,
            'created_at'=>time()
            ];
        $this->create($info);
        User::where('id', $pid)->update(['balance'=>$balance]);
    }

    /*某段时间返现合计金额*/
    public function backByTime($start_time, $end_time){
        $info = $this->select(DB::raw('sum(amount) as count'))
                    ->where('created_at', '>=', $start_time)
                    ->where('created_at', '<', $end_time)
                    ->first()
                    ->toArray();
        return $info['count'];
    }
}