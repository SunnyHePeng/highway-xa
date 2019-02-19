<?php

namespace App\Models\Pay;

use App\Models\Common;
use App\Models\System\Code_type;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Input, DB;

/**
 * 未发现对应的表
 */
class Consume extends Common
{
    protected $table = 'consume';
    protected $fillable = ['user_id','no','amount','balance','note','created_at'];
   	protected $rule = [
   					'user_id'=>'required',
                    'amount'=>'required',
                    'created_at'=>'required|numeric'
                ];
    public $timestamps = false;

    /*添加消费信息*/
    public function addConsume($type, $user_id, $amount, $num, $balance, $for_id){
        if($for_id == $user_id){
            $for_user ='自己';
        }else{
            $for_user = User::where('id', $for_id)->pluck('name');
        }
    	$consume = [
                'no' => $this->getNo(Config()->get('common.consume_pre')),
                'user_id'=>$user_id,
                'amount' =>$amount,
                'balance'=>$balance, 
                'note' => '提取'.$num.'张'.Code_type::where('id', $type)->pluck('name').'(为'.$for_user.')',
                'created_at' => time()
                ]; 
        $this->create($consume); 
    }

    public function user(){
    	return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }
    

    /*某段时间消费合计金额*/
    public function consumeByTime($start_time, $end_time){
        $info = $this->select(DB::raw('sum(amount) as count'))
                    ->where('created_at', '>=', $start_time)
                    ->where('created_at', '<', $end_time)
                    ->first()
                    ->toArray();
        return $info['count'];
    }
}