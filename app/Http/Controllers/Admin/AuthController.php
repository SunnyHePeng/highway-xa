<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project\Section;
use App\Models\Project\Supervision;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Response;
use App\Http\Controllers\Controller;
use App\Send\SendSms, App\Send\SendWechat;
use App\Models\User\User,
    App\Models\Project\Project,
    App\Models\User\Role,
    App\Models\User\Module,
    App\Models\User\Position,
    App\Models\User\Company,
    App\Models\User\Department,
    App\Models\User\Log;
use Redirect, Input, Auth, Session, DB;
class AuthController extends Controller
{
    protected $request;
    protected $model;
    public function __construct(Request $request, User $model)
    {
        $this->request = $request;
        $this->model = $model;
    }
    
    public function login(){
        if($this->request->isMethod('get')){
            if(Input::get('m') == 'n'){
                $this->logout();
            }
            if(Auth::check()){
                $verify = strtolower(Input::get('verify'));
                if($verify != Session::pull('verifycode')){
                   $error = '验证码错误'; 
                   return view('errors.admin_error',['error'=>$error]);
                }
                $name = Auth::User()->name;
                if($this->isWechat()){
                    $this->BdUserInfo();
                    $url = 'index';
                }else{
                    $url = $this->getUrlByType();
                    if($url == 'manage/verifyip'){
                        return redirect($url);
                    } 
                }
                $this->LoginInfo($name.'登录成功', Auth::User()->id, $name, 'l');
                return redirect()->intended($url);
            }else{
                return view('admin.auth.login');
            }
        }else{
            $username = Input::get('username');
            $password = Input::get('password');
            $remember = Input::get('remember');
            $verify = strtolower(Input::get('verify'));
            if($verify != Session::pull('verifycode')){
               $error = '验证码错误'; 
            }else{
                if(Auth::attempt(['username' => $username, 'password' => $password], $remember)){
                    if(Auth::User()->status == 1){
                        $name = Auth::User()->name;
                        if($this->isWechat()){
                            $this->BdUserInfo();
                            $url = 'index';
                        }else{
//                            $url = $this->getUrlByType();
//                            if($url == 'manage/verifyip'){
//                                return redirect($url);
//                            } 
                             $this->LoginInfo($name.'登录成功', Auth::User()->id, $name, 'l');
                        return redirect()->intended("index");
                        }
                        $this->LoginInfo($name.'登录成功', Auth::User()->id, $name, 'l');
                        return redirect()->intended($url);
                    }elseif(Auth::User()->status == 2){  //第一次登录，修改密码
                        return view('admin.auth.pass');
                    }else{
                        Auth::logout();
                        $error = '您已被禁止登陆';
                    }
                }else{
                    //记录登录失败次数
                    Session::put('login_num', Session::get('login_num')+1);
                    $error = '登陆失败,请检查用户名和密码是否正确';
                }
            }
            return view('errors.admin_error',['error'=>$error]);
        }
    }

    public function logout(){
        Auth::logout();
        return view('admin.auth.login');
    }

    /*记录用户登录地址*/
    public function LoginInfo($info, $id, $name, $type){
        //记录登陆时间
        $ip = $this->model->get_client_ip();
//        $area = $this->model->ip_to_area($ip);
//        $region = isset($area['region']) ? $area['region'] : '';
//        $city = isset($area['city']) ? $area['city'] : '';

        $info = [
            'ip'=>$ip,
//            'addr'=>$region.' '.$city,
            'act'=>$info,
            'user_id'=>$id,
            'name'=>$name,
            'type'=>$type,
            'created_at'=>time()
        ];
        (new Log)->create($info);
    }

    /*判断用户登录ip是否与上次一致*/
    protected function getUrlByType(){
        $ip = $this->model->get_client_ip();
        if($ip != Auth::User()->ip){   //不一致 验证手机号
            return 'manage/verifyip';
        }
        $url = 'index';
        return $url;
    }

    /*注册*/
    public function register(){
        if($this->request->isMethod('get')){
            //获取项目信息
            $model = new Project;
            $project_list = $model->getList($model, [], ['id','name'], 'id desc');
            if(!$project_list){
                return view('admin.error.no_info', ['info'=>'暂时没有任何项目，请先添加项目']);
            }
            //获取部门信息

            //角色
            $model = new Role;
            $role_list = $model->getList($model, [['id', '!=', 1]], ['id','display_name'], 'id asc');
            if(!$role_list){
                return view('admin.error.no_info', ['info'=>'暂时没有任何角色，请先添加角色']);
            }

            //子系统
            $list['module'] = (new Module)->select('id','name')->where('pid','!=', 0)->get()->toArray();
            $list['project'] = $project_list;
            $list['roles'] = $role_list;
            $list['company'] = Company::orderByRaw('sort asc, id asc')->get()->toArray();
//            $list['position'] = Position::orderByRaw('id asc')->get()->toArray();
            return view('admin.auth.register', $list);
        }
        //判断手机号是否唯一
        $phone = Input::get('mobile');
        if(User::where('phone', $phone)->first()){
            $result = ['status'=>0, 'info'=>'该手机号已被注册'];
            return Response()->json($result);
        }
        //判断验证码是否正确
        $verify = Input::get('verify');
        if(!$verify){
            $result = ['status'=>0,'info'=>'请输入验证码'];
            return Response()->json($result);
        }
        //Log::info(Session::get('pmobile'));
        //Log::info(Session::get('pcode'));
        if(Input::get('phone') != Session::get('pmobile') || $verify != Session::get('pcode')){
            $result = ['status'=>0,'info'=>'验证码错误'];
            return Response()->json($result);
        }
        $project_id = Input::get('project_id');
        if(!$project_id){
            $result = ['status'=>0,'info'=>'请选择所属项目'];
            return Response()->json($result);
        }

        if(count($project_id) > 1 && Input::get('role') != 2){
            $result = ['status'=>0,'info'=>'只有集团用户可以选择多个项目'];
            return Response()->json($result);
        }
        $company_id=Input::get('company_id');
        if(!$company_id){
            $result=['status'=>0,'info'=>'请选择所属单位'];
            return Response()->json($result);
        }
        $department_id=Input::get('department_id');
        if(!$department_id){
            $result=['status'=>0,'info'=>'请选择所属部门'];
            return Response()->json($result);
        }
        $position_id=Input::get('position_id');
        if(!$position_id){
            $result=['status'=>0,'info'=>'请选择所属部门'];
            return Response()->json($result);
        }
        $model = new User;
        $data = $model->checkData();

        if(!$data['code']){
            $data['password'] = bcrypt($data['password']);
            $data['ip'] = $model->get_client_ip();
            $data['created_at'] = time();
            $data['status'] = 0;
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
                
                $result = ['status'=>1,'info'=>'注册成功，请等待管理员审核后再登录'];

                $this->LoginInfo('注册新用户：'.$data['name'], $res->id, $data['name'], 'r');
                //给上级发送通知
                $data['project_id'] = $project_id[0];
                $this->sendInfo($data);
                Session::forget('pmobile');
                Session::forget('pcode');
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'注册失败'];
            }
        }else{
            $result = ['status'=>0,'info'=>$data['info']];
        }
        return Response()->json($result);
    }

    /*修改密码时验证用户*/
    public function verifyInfo(Request $request){
        if($request->isMethod('get')){
            return view('admin.auth.verifyinfo');
        }
        //验证用户名并获取验证的手机
        $username = Input::get('username');
        if(!$username){
            $result = ['status'=>0,'info'=>'请输入用户名'];
            return Response()->json($result);
        }
        $info = User::where('username', $username)->where('status', 1)->pluck('phone');
        if(!$info){
            $result = ['status'=>0,'info'=>'用户名错误或未通过审核'];
            return Response()->json($result);
        }
        Session::put('verifyinfo',['username'=>$username, 'phone'=>$info]);
        $result = ['status'=>1,'info'=>'提交成功', 'url'=>url('manage/findpass')];
        return Response()->json($result);
    }
    
    /*修改密码时验证手机*/
    public function findPass(Request $request){
        /*$data = [
                'name'=>'123',
                'username'=>'123',
                'project_id'=>4,
                'section_id'=>2,
                'supervision_id'=>1,
                'company'=>'1234',
                'position'=>'122',
                'created_at'=>time(),
                'role'=>5
            ];
        $this->sendInfo($data);
        exit();*/
        if($request->isMethod('get')){
            if(!Session::get('verifyinfo')){
                return redirect('manage/verifyInfo');
            }
            $verifyinfo = Session::get('verifyinfo');
            return view('admin.auth.findpass',['phone'=>substr_replace($verifyinfo['phone'],'****',3,4)]);
        }
        if(!Session::get('verifyinfo')){
            $result = ['status'=>0,'info'=>'参数错误'];
            return Response()->json($result);
        }
        $verify = Input::get('verify');
        if(!$verify){
            $result = ['status'=>0,'info'=>'请输入验证码'];
            return Response()->json($result);
        }
        //判断验证码是否正确
        $verifyinfo = Session::get('verifyinfo');
        //var_dump($verifyinfo);
        //var_dump(Session::get('pmobile'));
        //var_dump(Session::get('pcode'));
        //var_dump($verify);
        if($verifyinfo['phone'] != Session::get('pmobile') || $verify != Session::get('pcode')){
            $result = ['status'=>0,'info'=>'验证码错误'];
            return Response()->json($result);
        }
        Session::forget('pmobile');
        Session::forget('pcode');
        $result = ['status'=>1,'info'=>'验证成功','url'=>url('manage/changepass')];
        return Response()->json($result);
    }

    /*修改密码*/
    public function changePass(Request $request){
        if($request->isMethod('get')){
            if(!Session::get('verifyinfo')){
                return redirect('manage/verifyInfo');
            }
            $verifyinfo = Session::get('verifyinfo');
            $verifyinfo['phone'] = substr_replace($verifyinfo['phone'],'****',3,4);
            return view('admin.auth.changepass', $verifyinfo);
        }
        if(!Session::get('verifyinfo')){
            $result = ['status'=>0,'info'=>'参数错误'];
            return Response()->json($result);
        }
        $password = Input::get('password');
        $repassword = Input::get('repassword');
        if(!$password || !$repassword){
            $result = ['status'=>0,'info'=>'请输入新密码和确认密码'];
            return Response()->json($result);
        }
        if($password != $repassword){
            $result = ['status'=>0,'info'=>'新密码和确认密码不一致'];
            return Response()->json($result);
        }
        $model = new User;
        $data = $model->checkPass();
        if($data['code']){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }else{
            $info['password'] = bcrypt($data['password']);
            $verifyinfo = Session::get('verifyinfo');
            $res = $model->where('username', $verifyinfo['username'])
                         ->where('phone', $verifyinfo['phone'])
                         ->update($info);
            if($res){
                $result = ['status'=>1,'info'=>'修改成功，重新登录', 'url'=>url('manage/login')];
                Session::forget('verifyinfo');
                //同步用户信息到建研系统
                $res = $model->where('username', $verifyinfo['username'])
                             ->where('phone', $verifyinfo['phone'])
                             ->first();
//                (new AdminController)->hasLab($res, '');
                return Response()->json($result);
            }else{  
                $result = ['status'=>0,'info'=>'修改失败，请重新操作'];
                return Response()->json($result);
            }
        }
        
    }

    public function verifyIp(Request $request){
        if($request->isMethod('get')){
            if(!Auth::check()){
                return redirect()->guest('manage/login');
            }
            $phone = Auth::User()->phone;
            Session::put('verifyinfo',['id'=>Auth::User()->id,'username'=>Auth::User()->username, 'phone'=>$phone]);
            Auth::logout();
            return view('admin.auth.verifyip',['phone'=>substr_replace($phone,'****',3,4)]);
        }
        if(!Session::get('verifyinfo')){
            $result = ['status'=>0,'info'=>'参数错误'];
            return Response()->json($result);
        }
        $verify = Input::get('verify');
        if(!$verify){
            $result = ['status'=>0,'info'=>'请输入验证码'];
            return Response()->json($result);
        }
        //判断验证码是否正确
        $verifyinfo = Session::get('verifyinfo');
        if($verifyinfo['phone'] != Session::get('pmobile') || $verify != Session::get('pcode')){
            $result = ['status'=>0,'info'=>'验证码错误'];
            return Response()->json($result);
        }
        Session::forget('pmobile');
        Session::forget('pcode');
        //记录登录Ip
        User::where('id', $verifyinfo['id'])->update(['ip'=>$this->model->get_client_ip()]);
        Auth::loginUsingId($verifyinfo['id']);
        $result = ['status'=>1,'info'=>'验证成功','url'=>url('index')];
        Session::forget('verifyinfo');
        return Response()->json($result);
    }

    /*注册成功，给上级发送通知*/
    /*protected function sendInfo($data){
        //获取上级手机号和微信openid
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
        $model = new User;
        $info = $model -> getList($model, $where, ['name','username','phone','openid'], 'id asc');
        //Log::info('sendInfo');
        //var_dump($info);
        if($info){
            $info = $info[0];

            //发送短信
            $temp_param = [
                'name'=>$data['name'],
                'username'=>$data['username']
                ];
            //Log::info('sendInfo SendSms');
            if($info['phone']){
                (new SendSms)->send($info['phone'], $temp_param, 'SMS_117523209');
            }
            
            //发送微信
            if($info['openid']){
                $temp_param['company'] = $data['company'];
                $temp_param['position'] = $data['position'];
                $temp_param['role'] = Role::where('id', $data['role'])->pluck('display_name');
                $temp_param['time'] = $data['created_at'];
                (new SendWechat)->sendSh($info['openid'], $temp_param);
            }
        }else{
            //没有找到直接上级  则找上上级
            if($data['role'] == 4 || $data['role'] == 5){
                $data['role'] = 3;
            }elseif($data['role'] == 3){
                $data['role'] = 2;
            }
            $this->sendInfo($data);
        }
    }*/
    protected function sendInfo($data){
        //获取上级手机号和微信openid
        $model = new User;
        $project_id = $data['project_id'];
        $query = $model->select(DB::raw('user.name,user.username,user.phone,user.openid,user_project.project_id'))
                      ->leftJoin('user_project', function($join) use($project_id){
                        $join->on('user.id', '=', 'user_project.user_id')
                             ->on('user_project.project_id', '=', DB::raw($project_id));
                      })
                      ->whereNotNull('user_project.project_id')
                      ->where('user.has_sh', '=', 1)
                      ->where('user.status', '=', 1);
        switch ($data['role']) {
            case '2':
                $query = $query->where('user.role', '=', 2);
                break;
            case '3':
                $query = $query->where('user.role', '=', 3);
                break;
            case '4':
                $query = $query->where('user.role', '=', 4)
                                ->where('user.supervision_id', '=', $data['supervision_id']);
                break;
            case '5':
                $query = $query->where('user.role', '=', 5)
                                ->where('user.supervision_id', '=', $data['supervision_id'])
                                ->where('user.section_id', '=', $data['section_id']);
                break;
        }
        $info = $query->orderByRaw('user.id asc')->get()->toArray();
        if($info){
            $info = $info[0];

            //发送短信
            $temp_param = [
                'name'=>$data['name'],
                'username'=>$data['username']
                ];
            //Log::info('sendInfo SendSms');
            if($info['phone']){
                (new SendSms)->send($info['phone'], $temp_param, 'SMS_117523209');
            }
            
            //发送微信
            if($info['openid']){
                $temp_param['company'] = Company::where('id', $data['company_id'])->pluck('name');
                $temp_param['position'] = $data['position'];
                $temp_param['role'] = Role::where('id', $data['role'])->pluck('display_name');
                $temp_param['time'] = $data['created_at'];
                (new SendWechat)->sendSh($info['openid'], $temp_param);
            }
        }else{
            //没有找到直接上级  则找上上级
            if($data['role'] == 4 || $data['role'] == 5){
                $data['role'] = 3;
            }elseif($data['role'] == 3){
                $data['role'] = 2;
            }elseif($data['role'] == 2){
                return '';
            }
            $this->sendInfo($data);
        }
    }

    /*判断是否微信内部登录*/
    protected function isWechat(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ){
            return true;
        } 
        return false;
    }

    protected function BdUserInfo(){
        if(Session::get('wechat_openid')){
            User::where('id', Auth::User()->id)->update(['openid'=>Session::get('wechat_openid'), 'wx_last_time'=>time()]);
        }
    }

}
