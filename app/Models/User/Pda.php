<?php

namespace App\Models\User;

use App\Models\Common;
use Illuminate\Database\Eloquent\Model;
use Input, Validator;

/**
 * PDA 用户（弃）
 */
class Pda extends Common
{
    protected $table = 'pda';
    protected $fillable = [
                        'id',
                        'project_id',
                        'company',
                        'id',
				        'name',
				        'username',
				        'phone',
				        'phone_model',
				        'phone_system',
				        'phone_px',
                        'session_id',
                        'ip',
                        'region',
                        'city',
                        'status',
                        'created_at',
                        'updated_at'
                    ];
   	protected $rule = [
                    'username'=>['required','regex:/^[a-zA-Z]{1}([a-zA-Z0-9\.\_]|[-]|[@]){5,19}$/','unique:user,username','min:6','max:20'],//alpha_num
            		'password'=>'required|alpha_dash|min:6|max:20',
                ];
    protected $hidden = ['password', 'remember_token','session_id','ip'];
    protected $dateFormat = 'U';
    public $timestamps = false;

    public function checkPass(){
        $data = Input::all();
        $validator = Validator::make($data, ['password'=>'required|alpha_dash|min:6'], $this->messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            $result = ['code'=>1,'info'=>$errors];
            return $result;
        }else{
            if(!preg_match("/^(?!([a-zA-Z]+|\d+)$)[a-zA-Z\d]{6,15}$/", $data['password'], $matches)){
                $result = ['code'=>1,'info'=>'密码不能为纯数字或字符'];
                return $result;
            }
        }
        $data['code'] = 0;
        return $data;
    }
}
