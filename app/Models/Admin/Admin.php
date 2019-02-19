<?php

namespace App\Models\Admin;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Input, Validator;
use App\Models\Common;

/**
 * 未发现对应的表
 */
class Admin extends Common implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;
	
    protected $table = 'admin';
    protected $fillable = ['name','password','role','ip','region','city','status','cp'];

    protected $dateFormat = 'U';
    protected $rule = [
		            'name'=>['required','regex:/^[a-zA-Z]{1}([a-zA-Z0-9\.\_]|[-]|[@]){5,19}$/','unique:admin,name','min:6','max:20'],//alpha_num
            		'password'=>'required|alpha_dash|min:6|max:20',
            		'role' => 'required'
		        ];

    public function checkData($data=[]){

    	$data = $data ? $data : Input::all();

    	$validator = Validator::make($data, $this->rule , $this->messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            $result = ['code'=>1,'info'=>$errors];
            return $result;
        }
        if(isset($data['cp'])){
            $data['cp'] = implode(',', $data['cp']);
        }
		$data['code'] = 0;
        return $data;
    }

    public function checkPass(){
    	$data = Input::all();
    	$validator = Validator::make($data, ['password'=>'required|alpha_dash|min:6'], $this->messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            $result = ['code'=>1,'info'=>$errors];
            return $result;
        }else{
            if(!preg_match("/^(?!([a-zA-Z]+|\d+)$)[a-zA-Z\d]{6,}$/", $data['password'], $matches)){
                $result = ['code'=>1,'info'=>'密码不能为纯数字或字符'];
                return $result;
            }
        }
        $data['code'] = 0;
        return $data;
    }
}
