<?php

namespace App\Http\Controllers\Admin;

use App\Models\User\Department;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\User,
    App\Models\User\Role,
    App\Models\Project\Project,
    App\Models\Project\Supervision,
    App\Models\User\Module,
    App\Models\User\Position,
    App\Models\User\Company,
    App\Models\User\Department as Depart,
    App\Models\Project\Section;
use Redirect, Input, Auth, Validator, DB;
class AdminController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.admin.admin';
    protected $url = '';
    protected $status = [0=>'禁用',1=>'正常'];
    protected $has_sh = [0=>'没有',1=>'有'];
    protected $type = [
                    '1'=>'系统管理员',
                    '2'=>'总监办',
                    '3'=>'驻地办',
                    '4'=>'合同段',
                    '5'=>'拌合站/梁场/隧道'   //总监办和驻地办功能类似
                ];

    public function __construct()
    {
        parent::__construct();

        $this->url = url('manage/admin');
        $this->model = new User;
        $this->field = 'user.id,user.username,user.name,user.status,user.type,user.role,user.section_id,user.supervision_id,user.phone,user.position_id,user.has_sh,role.display_name,user_project.project_id,position.name as position_name,company.name as company_name,user.hk_username,user.hk_password';
    }

    public function index(){
        //$this->setUserToJy();
        //$this->model->where('id', 1)->update(['has_sh'=>1]);
        $where = $search = [];
        $project_list = $supervision_list = $url_para = '';
        
        //获取项目信息
        $project_list = $this->getProjectList();
        if(!$project_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何项目，请先添加项目']);
        }

        $pro_id = Input::get('pro_id') ? Input::get('pro_id') : $project_list[0]['id'];
        if($pro_id){
            $search['pro_id'] = $pro_id;
            $where[] = ['project_id', '=', $pro_id];
            $url_para .= $url_para ? '&pro_id='.$pro_id : 'pro_id='.$pro_id;
        }

        //该项目监理信息
        $supervision_list = $this->model->getList((new Supervision), [['project_id', '=', $pro_id]], ['id','name'], 'id desc');
        
        //角色
        $role_list = $this->model->getList((new Role), [['id', '!=', 1]], ['id','display_name'], 'id asc');
        if(!$role_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何角色，请先添加角色']);
        }
        
        //管理员获取项目所有用户 其他根据监理 标段获取
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $query = $this->model->select(DB::raw($this->field))
                             ->where('user.id', '!=', 1);
        if($this->user->supervision_id){
            $query = $query->where('user.supervision_id', $this->user->supervision_id);
        }
        if($this->user->section_id){
            $query = $query->where('user.section_id', '=', $this->user->section_id);
        }

        //根据搜索条件筛选
        $role = Input::get('role') ? Input::get('role') : '';
        if($role){
            $search['role'] = $role;
            $query = $query->where('user.role', '=', $role);
            $url_para .= $url_para ? '&role='.$role : 'role='.$role;
        }

        $name = Input::get('name') ? Input::get('name') : '';
        if($name){
            $search['name'] = $name;
            $query = $query->where('user.name', 'like', '%'.$name.'%');
            $url_para .= $url_para ? '&name='.$name : 'name='.$name;
        }

        $tel = Input::get('tel') ? Input::get('tel') : '';
        if($tel){
            $search['tel'] = $tel;
            $query = $query->where('user.phone', 'like', '%'.$tel.'%');
            $url_para .= $url_para ? '&tel='.$tel : 'tel='.$tel;
        }
        $list = $query->leftJoin('role', 'user.role', '=', 'role.id')
                    ->leftJoin('user_project', function($join) use ($pro_id)
                        {
                            $join->on('user.id', '=', 'user_project.user_id')
                                 ->on('user_project.project_id', '=', DB::Raw($pro_id));
                        })
                    ->leftJoin('position', 'user.position_id', '=', 'position.id')
                    ->leftJoin('company', 'user.company_id', '=', 'company.id')
                    ->whereNotNull('user_project.project_id')
                    ->orderByRaw($this->order)
                    ->paginate($this->ispage)
                    ->toArray();
//        dd($list);
        //var_dump($list);
        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['project'] = $project_list;
        $list['supervision'] = $supervision_list;
        $list['roles'] = $role_list;
        $list['type'] = $this->type;
        $list['position'] = Position::orderByRaw('id asc')->get()->toArray();
        $list['status'] = $this->status;
        $list['has_sh'] = $this->has_sh;
        $list['company'] = Company::orderByRaw('id asc')->get()->toArray();


//        $list['department_list'] = Department::get();

        //var_dump($list);
        return view($this->list_view, $list);
    }

    public function show($id)
    {
        $data = $this->model->with(['project'=>function($query){
                                $query->select('id','name');
                            }, 'supervision'=>function($query){
                                $query->select('id','name');
                            }, 'section'=>function($query){
                                $query->select('id','name');
                            }, 'roles'=>function($query){
                                $query->select('id','display_name');
                            }])->find($id);
        $mod = $data->module->toArray();
        if($data){
            $data['project_id'] = $data['project']['name'];
            $data['supervision_id'] = $data['supervision']['name'];
            $data['section_id'] = $data['section']['name'];
            $data['role'] = $data['roles'][0]['display_name'];
            $data['created_at'] = date('Y-m-d H:i', $data['created_at']);
            $data['status'] = $this->status[$data['status']];
            //子系统
            $data['mod'] = '';
            if($mod){
                foreach ($mod as $key => $value) {
                    $data['mod'] .= $data['mod'] ? '，'.$value['name'] : $value['name'];
                }
            }
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {  
        $data = $this->model->checkData();
        if(!$data['code']){
            $data['password'] = bcrypt($data['password']);
            $data['created_at'] = time();
            
            try {
                $res = $this->model->create($data);
                //添加对应角色
                if(Input::get('role')){
                    $res->roles()->attach([Input::get('role')]);
                }
                $result = ['status'=>1,'info'=>'添加成功'];

                $this->addLog($this->user->username.'添加新用户：'.$data['name'], 'a');
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'添加失败'];
            }
        }else{
            $result = ['status'=>0,'info'=>$data['info']];
        }
        return Response()->json($result);
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = $this->model
                    ->select(['id','name','username','phone','role','company_id','department_id','position_id','hk_username','nmgz','bpjc_user','bpjc_pass','IDNumber','tunnel_monitor_account','tunnel_monitor_password'])
                    ->find($id);
        if($data){
            //根据角色获取对应职位
            $data['company_list']=Company::where('role_id',$data['role'])->orderByRaw('id asc')->get()->toArray();
            $data['position_list'] = Position::where('role_id', $data['role'])->orderByRaw('id asc')->get()->toArray();
            $data['department_list'] = Department::where('role_id', $data['role'])->orderByRaw('id asc')->get()->toArray();
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        
        return Response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $admin = $this->model->find($id);
        if($id != $this->user->id && !$this->hasPer($admin)){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        $is_password = 0;
        if(!empty(Input::get('password')) && Input::get('act_type') == 'pass'){  //修改密码
            $data = $this->model->checkPass();
            if(!$data['code']){
                $data['password'] = bcrypt($data['password']);
                $is_password = 1;
            }else{
                $result = ['status'=>0,'info'=>$data['info']];
                return Response()->json($result);
            }
        }else{
            $data = Input::all();
            unset($data['password']);
        }
        if(Input::get('act_type') == 'edit' && isset($data['role'])){
            if($data['role'] == 1 || $data['role']==2 || $data['role']==3){
                $data['supervision_id'] = 0;
                $data['section_id']=0;
            }
            if($data['role'] == 4){
                $data['section_id']=0;
            }
        }
        if(Input::get('change_type') == 'has_sh' && $data['has_sh'] == 1){
            //判断所属角色该项目是否已经有人拥有审核权限
            $res = $this->isHasSh($id);
            if($res){
                $result = ['status'=>0,'info'=>'所属项目/监理/标段已设置审核权限，请不要重复设置'];
                return Response()->json($result); 
            }
        }
        try {
            if(!empty($data['hk_password'])){
                $data['hk_password'] = strtoupper(md5($data['hk_password']));
            }else{
                unset($data['hk_password']);
            }
             if(!empty($data['nmgz'])){
//                $data['nmgz'] = strtoupper(md5($data['nmgz']));
            }else{
                unset($data['nmgz']);
            }
            if(!empty($data['bpjc_user'])){
                $data['bpjc_user'] = $data['bpjc_user'];
            }else{
                unset($data['bpjc_user']);
            }
            if(!empty($data['bpjc_pass'])){
                $data['bpjc_pass']=$data['bpjc_pass'];
            }else{
              unset($data['bpjc_pass']);
            }
            if(!empty($data['IDNumber'])){
                $data['IDNumber']=$data['IDNumber'];
            }
            if(!empty($data['tunnel_monitor_account'])){
                $data['tunnel_monitor_account']=$data['tunnel_monitor_account'];
            }
            if(!empty($data['tunnel_monitor_password'])){
                $data['tunnel_monitor_password']=$data['tunnel_monitor_password'];
            }

            $admin->fill($data);
            $admin->save();
            //添加对应角色
            if(Input::get('role')){
                $admin->roles()->sync([Input::get('role')]);
            }

            //$user = Auth::user();
            if($is_password){
                //更新建研用户信息
//                $this->hasLab($admin, '');
                if($this->user->id == $id){
                    Auth::logout();
                }
            }
            $result = ['status'=>1,'info'=>'修改成功'];

            $this->addLog($this->user->username.'修改用户：'.$admin->name.'的信息', 'm');
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'修改失败'];
        }
        
        return Response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        
        $user = $this->model->find($id);
        if(!$this->hasPer($user)){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        $data = $this->model->destroy($id);
        if($data){
            $result = ['status'=>1,'info'=>'删除成功', 'data'=>$id];

            $this->addLog($this->user->username.'删除用户：'.$user->name, 'd');
        }else{
            $result = ['status'=>0,'info'=>'删除失败'];
        }
        return Response()->json($result);
    }

    public function abnormal($id){
        if(!$id){
            $result = ['status'=>0,'info'=>'参数错误，请重新操作'];
        }else{
            $res = Admin_login_abnormal::where('id',$id)->update(['status'=>1]);
            if($res){
                $result = ['status'=>1,'info'=>'处理成功', 'data'=>$id];
            }else{
                $result = ['status'=>0,'info'=>'处理失败']; 
            }
        }
        return Response()->json($result);
    }

    public function userMod(Request $request){
        if($request->isMethod('get')){
            $list = (new Module)->select('id','name')->where('pid','!=', 0)->get()->toArray();
            if($list && Input::get('u_id')){
                $data['list'] = $list;
                $data['u_id'] = Input::get('u_id');
                $user = $this->model->find(Input::get('u_id'));
                $data['mod'] = $user->module->toArray();
                if($data['mod']){
                    foreach ($data['mod'] as $key => $value) {
                        $data['mod'][$key] = $value['id'];
                    }
                }
                return view('admin.admin.user_module', $data);
            }
            return view('admin.error.no_info', ['info'=>'暂时没有模块信息']);
        }

        $result = ['status'=>0,'info'=>'设置失败'];
        if(Input::get('u_id')){
            $user = $this->model->find(Input::get('u_id'));
            if(!$this->hasPer($user)){
                $result = ['status'=>0,'info'=>'您没有权限'];
                return Response()->json($result);
            }
            try {
                $mod_id = Input::get('mod_id') ? Input::get('mod_id') : [];
                $user->module()->sync($mod_id);
                //判断是否管理实验室
//                $this->hasLab($user, $mod_id);
                $result = ['status'=>1,'info'=>'设置成功'];

                $this->addLog($this->user->username.'修改用户：'.$user->name.'的模块权限', 'm');
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'设置失败'];
            }
        }

        return Response()->json($result);
    }

    public function userInfo(Request $request)
    {
        $id = Input::get('u_id');
        if($request->isMethod('get')){
            if(!$id){
                return view('admin.error.no_info', ['info'=>'暂时没有信息']);
            }
            $data = $this->model->with(['project'=>function($query){
                                    $query->select('id','name');
                                }, 'supervision'=>function($query){
                                    $query->select('id','name');
                                }, 'section'=>function($query){
                                    $query->select('id','name');
                                }, 'roles'=>function($query){
                                    $query->select('id','display_name');
                                }, 'posi'=>function($query){
                                    $query->select('id','name');
                                }, 'company'=>function($query){
                                    $query->select('id','name');
                                },'department'=>function($query){
                                        $query->select('id','name');
                                }])->find($id);
            //var_dump($data->toArray());
            if($data){
                //子系统
                $mod = $data->module->toArray();
                return view('admin.admin.user_info', $data);
            }
            return view('admin.error.no_info', ['info'=>'暂时没有信息']);
        }

        //判断是否有权限
        $user = $this->model->find(Input::get('u_id'));
        if(!$this->hasPer($user)){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        //var_dump($res);
        $result = ['status'=>0,'info'=>'设置失败'];
        if(Input::get('u_id')){
            try {
                $data['status'] = Input::get('status');
                $user->update($data);
//                $this->hasLab($user, '');
                $result = ['status'=>1,'info'=>'设置成功'];
                $this->addLog($this->user->username.'审核用户：'.$user->name.'的状态为'.$this->status[$data['status']], 'c');
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'设置失败'];
            }
        }

        return Response()->json($result);
    }

    //判断是否有权限修改和删除
    //项目角色拥有审核权限可以删除修改任意用户  监理角色拥有审核权限的可以修改监理类型 标段角色拥有审核权限的可以修改标段类型
    protected function hasPer($user){
        if(!$this->user->has_sh){
            return false;
        }
        $user_project = $user->project->toArray();
        $user_project = $user_project[0]['id'];
        if($this->user->role == 3){  //项目
            if($user_project != $this->user->project[0]){
                return false;
            }
        }

        if($this->user->role == 4){  //监理
            if($user_project != $this->user->project[0] || $user->role!=4){
                return false;
            }
        }

        if($this->user->role == 5){  //标段
            if($user_project != $this->user->project[0] || $user->section_id != $this->user->section_id || $user->role!=5){
                return false;
            }
        }
        return true;
    }

    protected function isHasSh($id){
        $data = $this->model->find($id);
        switch ($data['role']) {
            case '2':
                $where[] = ['role', '=', 2];
                break;
            case '3':
                $where[] = ['role', '=', 3];
                $where[] = ['project_id', '=', $data['project_id']];
                break;
            case '4':
                $where[] = ['role', '=', 4];
                $where[] = ['project_id', '=', $data['project_id']];
                $where[] = ['supervision_id', '=', $data['supervision_id']];
                break;
            case '5':
                $where[] = ['role', '=', 5];
                $where[] = ['project_id', '=', $data['project_id']];
                $where[] = ['supervision_id', '=', $data['supervision_id']];
                $where[] = ['section_id', '=', $data['section_id']];
                break;
        }
        $where[] = ['has_sh', '=', 1];
        $where[] = ['status', '=', 1];
        $info = $this->model -> getList($this->model, $where, ['id'], 'id asc');
        if($info){
            return true;
        }else{
            return false;
        }
    }

    //判断是否可以管理试验室  并同步信息
    public function hasLab($user, $mod_id){
        if(!$mod_id){
            $mod_id = $user->module->toArray();
        }
        //没有可管理的模块
        if(!$mod_id){
            return true;
        }
        //判断是否管理试验室
        $is_lab = false;
        //var_dump($mod_id);
        foreach ($mod_id as $key => $value) {
            if($value == 3){   //可以管理试验室  自动同步到建研数据库
                $is_lab = true;
                break;
            }
        }
        if($is_lab){       //自动同步到建研数据库
            return $this->SyncUserToJy($user);
        }
    }

    protected function SyncUserToJy($user){
        $client = new \SoapClient(Config()->get('common.lab_url')."/LoginService/Service1.asmx?wsdl");
        //$result = $client->GetUserRoleList();
        //var_dump($result);
        //$userName = $result->GetUserRoleListResult; 
        //echo $userName;
        $result = $client->Encrypt(array('s'=>$user->username));
        $userName = $result->EncryptResult;

        $result = $client->Encrypt(array('s'=>$user->name));
        $userAccount = $result->EncryptResult;

        $result = $client->Encrypt(array('s'=>$user->password));
        $password = $result->EncryptResult;

        $role = $user->position_id ? Position::where('id', $user->position_id)->pluck('name') : '';
        $result = $client->Encrypt(array('s'=>$role));
        $userrole = $result->EncryptResult;

        $company = $user->company_id ? Company::where('id', $user->company_id)->pluck('name') : '';
        $result = $client->Encrypt(array('s'=>$company));
        $unitName = $result->EncryptResult;

        $params = array(  
            'userName' => $userName,  
            'userAccount' => $userAccount,  
            'password'=> $password,  
            'userrole'=> $userrole,  
            'unitName'=> $unitName  
        ); 
        //var_dump($params);
        $result=$client->SyncUser($params);  
        if($result->SyncUserResult == 'OK'){
            return true;
        }else{
            return false;
        }
    }

    protected function getUserRoleByJy($role){
        $info = [
            '1'=>['id'=>'22de9360-c606-4485-92f3-e0cdd9e2c44b', 'name'=>'项目经理'],  //项目部信息化管理员
            '2'=>['id'=>'b97bffe1-2d75-484c-9c66-bf18f660d37b', 'name'=>'项目常务副经理'], //
            '3'=>['id'=>'fb67e96e-7f12-45fc-bf24-62109e70d129', 'name'=>'总工'],
            '4'=>['id'=>'2d5b5b95-bdac-47ac-b09a-7449c3a4706a', 'name'=>'质量总监'],
            '5'=>['id'=>'7bc1c0c9-1e72-42da-8363-f6b7fcf40ccf', 'name'=>'安全总监'],
            '6'=>['id'=>'308222e8-5b97-4bd5-a9fb-295508b5abbe', 'name'=>'安质部长'],
            '7'=>['id'=>'e3a126b0-3b55-4423-9cde-3107dd785e82', 'name'=>'工程部长'],
            '8'=>['id'=>'4f5c15d8-3b32-4932-b1d5-18fc8ede5d4e', 'name'=>'项目部信息化管理员'],
            '9'=>['id'=>'d225eb37-8832-4924-9cce-55e37d5ab500', 'name'=>'试验室信息化管理员'],
            '10'=>['id'=>'69b3c352-08f8-4142-8ee7-25e8b7335559', 'name'=>'试验室主任'],
            '11'=>['id'=>'3a1581c0-ffd4-476d-96d8-adec038b70a2', 'name'=>'拌合站站长'],
            '12'=>['id'=>'242ce7d9-c999-439c-9b21-93da64a4ab32', 'name'=>'拌合站信息化管理员'],
            '13'=>['id'=>'', 'name'=>''],
            ];
            /*a2e97e2c-5365-4e0a-bf75-32b90278f7f1试验员
            f29859a2-5432-4e05-b5e8-3f401e300f90设备管理员
            efb111aa-2308-48e2-b1fc-54181ec35e3f系统管理员
            9b88503e-4ae7-4eb2-9ed9-e35eb774cbbe人员档案管理
            325f0749-edd0-41a0-9888-edd6501ceeb4技术标准管理*/
        if(isset($info[$role]['name'])){
            return $info[$role]['name'];
        }else{
            return '';
        }
        
    }

    //把可管理试验室的用户信息同步到建研
    protected function setUserToJy(){
        $list = User::where('status', 1)
                    ->with(['module'=>function($query){
                        $query->where('id', 3);
                    }])
                    ->orderByRaw('id asc')
                    ->get();
        var_dump(count($list));
        foreach ($list as $key => $value) {
            if($value['module']){
                $res = $this->SyncUserToJy($value);
                var_dump($res);
                var_dump($value->id);
                //exit();
            }
        }
        
    }
    /*
     * 用户新增
     * **/
    public function addUser(Request $request){
        if($request->isMethod('get')){
            if(!$this->user->has_sh){
                return view('admin.error.iframe_no_info', ['info'=>'具有审核权限的用户才可以添加用户']);
            }
            //获取项目信息
            $model = new Project;
            $project_list = $model->getList($model, [], ['id','name'], 'id desc');
            if(!$project_list){
                return view('admin.error.iframe_no_info', ['info'=>'暂时没有任何项目，请先添加项目']);
            }
            //角色
            $model = new Role;
            $role_list = $model->getList($model, [['id', '!=', 1]], ['id','display_name'], 'id asc');
            if(!$role_list){
                return view('admin.error.iframe_no_info', ['info'=>'暂时没有任何角色，请先添加角色']);
            }
            //子系统
            $list['module'] = (new Module)->select('id','name')->where('pid','!=', 0)->get()->toArray();
            $list['project'] = $project_list;
            $list['roles'] = $role_list;
            $list['company'] = Company::orderByRaw('sort asc, id asc')->get()->toArray();
            $department=new Depart();
            $list['department']=$department->select(['id','name'])->get()->toArray();
            //$list['position'] = Position::orderByRaw('id asc')->get()->toArray();
            return view('admin.admin.addUser', $list);
        }
        //判断手机号是否唯一
        $phone = Input::get('mobile');
        if(User::where('phone', $phone)->first()){
            $result = ['status'=>0, 'info'=>'该手机号已被注册'];
            return Response()->json($result);
        }
        $project_id = Input::get('project_id');
        if(!$project_id){
            $result = ['status'=>0,'info'=>'请选择所属项目'];
            return Response()->json($result);
        }

        $role = Input::get('role');
        $supervision_id = Input::get('supervision_id');
        $section_id = Input::get('section_id');
        //注册监理用户
        if ($role == 4 && !$supervision_id) {
            $result = ['status' => 0, 'info' => '请在监理信息选择框处选择所属监理'];
            return Response()->json($result);
        }
        //注册合同段用户
        if ($role == 5 && !$supervision_id) {

            $result = ['status' => 0, 'info' => '请选择监理信息'];
            return Response()->json($result);
        }

        if ($role == 5 && !$section_id) {

            $result = ['status' => 0, 'info' => '请选择标段信息'];
            return Response()->json($result);
        }

        if(count($project_id) > 1 && Input::get('role') != 2){
            $result = ['status'=>0,'info'=>'只有集团用户可以选择多个项目'];
            return Response()->json($result);
        }

        $model = new User;
        $data = $model->checkData();
        if(!$data['code']){
            $data['password'] = bcrypt($data['password']);
            $data['ip'] = $model->get_client_ip();
            $data['created_at'] = time();
            $data['status'] = 1;
            try {
                unset($data['project_id']);
                $res = $model->create($data);
                //添加所属项目
                if(Input::get('project_id')){
                    $res->project()->attach(Input::get('project_id'));
                }
                //添加对应角色
                if(Input::get('role')){
                    $res->roles()->attach([Input::get('role')]);
                }
                //添加权限
                $mod_id = Input::get('mod_id') ? Input::get('mod_id') : [];
                $res->module()->sync($mod_id);
          
                $result = ['status'=>1,'info'=>'添加成功'];
                $this->addLog($this->user->username.'添加用户：'.$data['username'].'的信息', 'm');
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'添加失败'];
            }
        }else{
            $result = ['status'=>0,'info'=>$data['info']];
        }
        return Response()->json($result);
    }
}
