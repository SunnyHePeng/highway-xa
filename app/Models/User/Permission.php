<?php 
namespace App\Models\User;

use Zizaco\Entrust\EntrustPermission;
use Input, Validator;

/**
 * 权限
 */
class Permission extends EntrustPermission
{
	protected $table = 'permission';
	protected $fillable = ['mid','pid', 'url', 'name', 'icon', 'description', 'status', 'sort', 'created_at', 'updated_at'];
    protected $rule = [
                    'url'=>'required',
                    'name'=>'required'
                ];
    protected $dateFormat = 'U';

    protected $messages = [
                    'required' => '字段 :attribute 的值必须填写',
                    'unique' => ' :attribute 已经存在了',
                    'numeric' =>'字段 :attribute 需为数字',
                    'alpha_num' => '字段 :attribute 仅允许字母和数字',
                    'min' => '字段 :attribute 至少 :min 位',
                    'max' => '字段 :attribute 最多 :max 位',
                    'regex' => '用户名至少6位，由字母、数字、_、-、.、@组成，且以字母开始',
                    'alpha_dash' => '字段 :attribute 仅允许由字母、数字、折号（-）以及底线（_）',
                ];
    public function getList($model, $filter=[], $fields=[], $order='id desc', $ispage=0, $limit='', $with='', $with_field=[], $with_filter=[])
    { 
        //$model = new $model;

        $query = $model->select($fields)->where(function($query) use($filter){        
                    foreach ($filter as $v) {
                        if($v[1] == 'in'){
                            $query->whereIn($v[0], $v[2]); 
                        }else{
                            $query->where($v[0], $v[1], $v[2]);
                        }     
                    }             
                })->orderByRaw($order);

        if($with){
            $query = $query->with([$with => function ($query) use($with_field, $with_filter) {
                        $query->select($with_field);
                        foreach ($with_filter as $v) {
                            if($v[1] == 'in'){
                                $query->whereIn($v[0], $v[2]); 
                            }else{
                                $query->where($v[0], $v[1], $v[2]);
                            }     
                        } 
                    }]);
        }

        if($ispage){
            $data = $query->paginate($ispage)->toArray();
        }else{
            if($limit){
                $query = $query->take($limit);
            }
            $data = $query->get()->toArray();
        }

        return $data;
    }

    public function checkData($data=[]){
        $data = $data ? $data : Input::all();

        $validator = Validator::make($data, $this->rule , $this->messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            $result = ['code'=>1,'info'=>$errors];
            return $result;
        }
        $data['code'] = 0;
        return $data;
    }

    public function child()
    {
        return $this->hasMany('App\Models\User\Permission', 'pid', 'id');
    }

    /*public function mod()
    {
        return $this->belongsTo('App\Models\User\Module', 'mid', 'id');
    }*/

    public function mod(){
        return $this->belongsTo('App\Models\User\Module', 'mid', 'id');
    }
}