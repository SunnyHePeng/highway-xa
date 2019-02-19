<?php

namespace App\Http\Middleware;

use Closure,Input,Auth,Session,App;
use Illuminate\Contracts\Auth\Guard;
use App\Models\User\User, App\Models\User\Module, App\Models\User\Role,App\Models\WeatherMessage\WeatherMessage;
use Illuminate\Support\Facades\Cache;
use App\Models\Notice\NoticeInfo;
use App\Models\Notice\NoticeInfoUserRecord;
class Admin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->auth->guest()){
            if($request->ajax()){
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('manage/login');
            }
        }
        $user = $request->user();
        if($user->status != 1){
            Auth::logout();
            return view('errors.admin_error',['error'=>'您的账号已被禁用','url'=>url('manage/login?m=n')]);
        }

        $uri = $request->path();
        $role = $user->roles;
        //判断是否有权限
        if(!$user->isPerm($uri, $role[0])){
//            return $uri;
            return response('Unauthorized.', 403);
        }

        //var_dump($uri);
        //获取当前module
        $pos = strpos($uri, '/');
        $str = substr($uri, 0, $pos);
        //var_dump($str);
        $cur_module = Module::where('icon', $str)->first();
        $module_url = $cur_module->url;
        $cur_module = $cur_module->id;
        
        /*根据用户类型和拥有的权限模块获取左侧导航
        '1'=>'系统管理员',
        '2'=>'集团用户',
        '3'=>'项目用户',
        '4'=>'监理',
        '5'=>'标段'*/
        $module = [];
        $module_name = '';
        $user_info = User::select('id','type','role')
                        ->where('id', $user->id)
                        ->with(['module'=>function($query){
                            $query->where('shown', 1)->orderByRaw('sort asc, id asc');
                        }])
                        ->first()
                        ->toArray();
        foreach ($user_info['module'] as $key => $value) {
            $module[$key] = $value['id'];
            if($value['id'] == $cur_module){
                $module_name = $value['name'];
                $module_url = $value['url'];
            }
        }
        //var_dump($user_info['module']);
        if(!$module || !in_array($cur_module, $module)){
            return response('Not Found.', 404);
        }
        
        $role_model = new Role;
        $role_info = $role_model->getList($role_model, 
                                    [['id', '=', $role[0]['id']]], 
                                    ['id'], 
                                    'id desc', 
                                    '', 
                                    '', 
                                    'perms', 
                                    ['id','pid','url','name','icon'], 
                                    [['status','=',1],['mid','=',$cur_module]],
                                    'sort asc, id asc'
                                    );
        
        $data['active'] = [];
        foreach ($role_info[0]['perms'] as $key => $value) {
            if($value['pid']){
                $data['menu'][$value['pid']]['child'][] = $value;
            }else{
                $data['menu'][$value['id']] = $value;
            }
            if($uri == $value['url']){
                $data['active'] = $value;
            }
        }
        
        $data['user'] = [
                    'id'=>$request->user()->id,
                    'username'=>$request->user()->username,
                    'role'=>$request->user()->role,
                    'role_name'=>$role[0]['display_name'],
                    'module_name'=>$module_name,
                    'module_url'=>$module_url,
                    'module_id'=>$cur_module,
                    'module_list'=>$user_info['module']
                ];
        //从本地获取当天天气信息
        $weather_data=$this->getWeatherToday();

        if($weather_data){
            //本服务器数据库已经存在当天的天气信息
           $result=[
               'code'=>$weather_data->code,
               'text'=>$weather_data->weather_text,
               'temperature_low'=>$weather_data->temperature_low,
               'temperature_high'=>$weather_data->temperature_high,
           ];

        }else{
            $result=[
                'code'=>'99',
                'text'=>'尚未获取到天气信息',
                'temperature_low'=>'N/A',
                'temperature_high'=>'N/A',
            ];
        }
        //获取最新的一条公告信息
        $notice=$this->getLatelyNotice();

        $data['weather']=$result;
        $data['notice']=$notice;
//        var_dump($data);
        view()->share($data);
        return $next($request);
    }
    //获取当天天气信息
    protected function getWeatherToday()
    {
        $weather_model=new WeatherMessage();

        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;

        $weather_data=$weather_model->select(['code','weather_text','temperature_low','temperature_high'])
                                    ->whereBetween('time',[$start_time,$end_time])
                                    ->first();
        return $weather_data;

    }

    //获取最近一条公告信息
    protected function getLatelyNotice()
    {
        $user_id=Auth::user()->id;
//        dd($user_id);

        $notice_model=new NoticeInfo();
        $noticeInfoUser=new NoticeInfoUserRecord();

        $notice=$notice_model->orderBy('id','desc')
                             ->where('status',1)
                             ->first();
        if($notice){
            $notice_id=$notice->id;

            $read=$noticeInfoUser->where('user_id',$user_id)
                ->where('type','s')
                ->where('notice_info_id',$notice_id)
                ->first();
            if($read){
                $is_read=0;
            }else{
                $is_read=1;
            }

            $notice_data=[];
            $notice_data['title']=$notice->title;
            $notice_data['is_read']=$is_read;

            return $notice_data;

        }else{
            $notice_data=[];
            $notice_data['title']='暂时还没有公告信息';
            $notice_data['is_read']=0;
            return $notice_data;

        }


    }



}