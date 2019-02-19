<?php

namespace App\Models\User;

use App\Models\Common, App\Models\User\Role;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Input, Validator;
use App\Models\Project\Section;

/**
 * 用户
 */
class User extends Common implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;

    /**
     * 超管角色
     * 
     * @var int
     */
    const ROLE_ADMIN = 1;

    /**
     * 项目管理处
     * 
     * @var int
     */    
    const ROLE_GROUP = 3;

    /**
     * 监理
     * 
     * @var int
     */    
    const ROLE_SUPERVISION = 4;

    /**
     * 标段
     * 
     * @var int
     */    
    const ROLE_SECTION = 5;

    /**
     * 检测
     * 
     * @var int
     */    
    const ROLE_CHECK = 6;    

    protected $table = 'user';
    protected $fillable = [
                        'id',
                        'project_id',
                        'section_id',
                        'supervision_id',
                        'company_id',
                        'department_id',
                        'username',
                        'name',
                        'password',
                        'type',
                        'role',
                        'position_id',
                        'session_id',
                        'ip',
                        'region',
                        'city',
                        'status',
                        'phone',
                        'openid',
                        'wx_last_time',
                        'qqsx_name',
                        'qqsx_pass',
                        'has_sh',
                        'hk_username',
                        'hk_password',
                        'nmgz',
                        'bpjc_user',
                        'bpjc_pass',
                        'IDNumber',
                        'tunnel_monitor_account',
                        'tunnel_monitor_password',
                        'created_at',
                        'updated_at'
                    ];
   	protected $rule = [
                    'username'=>['required','regex:/^[a-zA-Z]{1}([a-zA-Z0-9\.\_]|[-]|[@]){5,19}$/','unique:user,username','min:6','max:20'],//alpha_num
            		'password'=>'required|alpha_dash|min:6|max:20',
                    'phone'=>'unique:user,phone'
                ];
    protected $hidden = ['password', 'remember_token','session_id','ip'];
    protected $dateFormat = 'U';
    public $timestamps = false;

    public function isPerm($uri, $role)
    {
        foreach ($role->perms as $perm) {
            //var_dump($uri);
            //var_dump($perm->url);
            if (stripos($uri, $perm->url) !== false) {
                return true;
            }
        }
        return false;
    }

    public function getUserByRole($role, $field, $pid=0, $ispage=0){
        $info = $this->getWhere($role, $pid);
        $data = $this->getList($this,
                             $info['where'], 
                             $field,
                             'id desc', 
                             $ispage, 
                             '', 
                             'roles', 
                             ['id','display_name']
                            );
        if(!$ispage){
            $data['data'] = $data;
        }
        $data['search'] = $info['search'];
        return $data;
    }

    public function getWhere($role, $pid=0){
        $keyword = str_replace('+', '', trim(Input::get('keyword')));
        $search = $where = [];
        $query = '';
        if($keyword){
            $search['keyword'] = $keyword;
            $where[] = ['username', 'like', '%'.$keyword.'%'];
        }

        $status = Input::get('status');
        if($status != ''){
            $search['status'] = $status;
            $where[] = ['status', '=', $status];
        }
        if(is_array($role)){
            $where[] = ['role', 'in', $role];
        }else{
            $where[] = ['role', '=', $role];
        }
        if($pid){
            $where[] = ['pid', '=', $pid];
        }
        return ['where'=>$where, 'search'=>$search];
    }

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

    public function UpdateBalanceAndCode($balance, $code_num, $user_id){
        $info = [
            'balance'=>$balance,
            'code_num'=>$code_num
            ];
        $this->where('id', $user_id)->update($info);
    }

    public function module()
    {
        return $this->belongsToMany('App\Models\User\Module', 'user_module', 'user_id', 'module_id');
    }

    /*public function project(){
        return $this->belongsToMany('App\Models\Project\Project', 'user_project', 'user_id', 'project_id');
    }*/

    public function project(){
        return $this->belongsToMany('App\Models\Project\Project', 'user_project', 'user_id', 'project_id');
    }
    
    public function supervision(){
        return $this->belongsTo('App\Models\Project\Supervision', 'supervision_id', 'id');
    }

    public function section(){
        return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function posi(){
        return $this->belongsTo('App\Models\User\Position', 'position_id', 'id');
    }

    public function snbhzwarn(){
        return $this->hasOne('App\Models\Bhz\Snbhz_user_warn', 'user_id', 'id');
    }

    public function labwarn(){
        return $this->hasOne('App\Models\Lab\Lab_user_warn', 'user_id', 'id');
    }

    public function company(){
        return $this->belongsTo('App\Models\User\Company', 'company_id', 'id');
    }

    public function roled()
    {
        return $this->hasOne('App\Models\User\Role','id','role');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\User\Department','department_id','id');
    }

    /**
     * 获取本用户所有标段
     * 
     * @return \Illuminate\Supports\Collection
     */
    public function getSectionsAttribute()
    {
        switch ($this->role) {

            case static::ROLE_ADMIN:

            case static::ROLE_GROUP:
                return Section::all();
                break;

            case static::ROLE_SUPERVISION:
                return $this->supervision->sections;
                break;

            case static::ROLE_SECTION:
                return collect([$this->section]);
                break;
            
            default:
                return collect([]);
                break;
        }
    }

    /**
     * 判断用户是否看所有标段
     * 
     * @return bool
     */
    public function isNotAdminOrGroup()
    {
        switch ($this->role) {

            case static::ROLE_ADMIN:

            case static::ROLE_GROUP:
                return false;
                break;
            
            default:
                return true;
                break;
        }        
    }    

        /**
     * 获取项目列表ztree所需数据
     * 
     * @return array
     */
    public function getProjectTreeData()
    {
        $data = $this->project()->with('sup.sec')->get();
        $tem = [];
        foreach ($data as $k1 => $project) {

            $tem[$k1] = [
                'id' => $project->id,
                'name' => $project->name,
                'key' => 'pro_id'
            ];

            foreach ($project->sup as $k2 => $supervision) {

                $tem[$k1]['children'][$k2] = [
                    'id' => $supervision->id,
                    'name' => $supervision->name,
                    'key' => 'sup_id'
                ];

                foreach ($supervision->sec as $k3 => $section) {

                    $tem[$k1]['children'][$k2]['children'][$k3] = [
                        'id' => $section->id,
                        'name' => $section->name,
                        'key' => 'sec_id'
                    ];
                }
            }
        }

        array_unshift($tem, [
            'id' => 0,
            'name' => '全部',
            'key' => 'pro_id'
        ]);

        return $tem;
    }

    /**
     * 关联拌和站上传超时通知用户
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mixplantMessageUser()
    {
        return $this->hasOne(\App\Models\Bhz\MessageUser::class);
    }
}
