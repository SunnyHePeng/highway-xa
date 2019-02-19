<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Response;
use App\Http\Controllers\Controller;
use Input, Auth;
use App\Models\User\User,
    App\Models\Video\Video,
    App\Models\User\Module;
class IndexController extends Controller
{
    public function index(Request $request)
    {
    	if(!Auth::check()){
            return redirect()->guest('manage/login');
        }

        //用户可访问的模块
        $module = [];
        $user = Auth::user();
        $user_info = User::select('id','type','role')
            ->where('id', $user->id)
            ->with(['module'=>function($query){$query->where('shown',1);}])
            ->first()
            ->toArray();
        
        //var_dump($list);
    	if($request->isMethod('get')){
            foreach ($user_info['module'] as $key => $value) {
                $module[$key] = $value['id'];
            }
            //所有模块
            $list = Module::where('pid', 0)
                            ->with(['child'=>function($query){
                                $query->where('shown', 1)->orderByRaw('sort asc, id asc');
                            }])->get()->toArray();
    		return view('index', ['module_user'=>$module,'module'=>$list]);
    	}

    	$id = Input::get('id');
        $is_perm = false;
        foreach ($user_info['module'] as $key => $value) {
            if($id == $value['id']){
                $is_perm = true;
                $url = url($value['url']);
                break;
            }
        }

        if(!$is_perm){
        	$result = ['status'=>0];
        	return Response()->json($result);
        }
        $result = ['status'=>1,'url'=>$url];
        return Response()->json($result);
    }

    public function other(){
        return view('waiting');
    }

    /*视频监控*/
    public function video(Video $video){
        $url = $video->url;
        $status = Input::get('status');
        //集团用户显示所有视频列表，其他直接跳转到对应视频链接
        if(($this->user->role == 1 || $this->user->role == 2) && !isset($status)){
            if (count($url) > 1) {
                return view('video', ['data'=>$url]);
            } else {
                $status = current(array_keys($url));
            }
        } else if($this->user->role == 3 || $this->user->role == 4 || $this->user->role == 5){
            $pro_id = $this->user->project[0];
            foreach ($url as $key => $value) {
                if($value['pro_id'] == $pro_id){
                    $status = $key;
                }
            }
        }
        if(!$this->user->hk_username || !$this->user->hk_password){
            return view('admin.error.no_info', ['info'=>'请先添加对应的海康账号和密码']);
        }
        $url = $video->token($status);
        header('location:'.$url);
        exit;
    }

    /*前期手续*/
    public function qqsx(Request $request){
        if($request->isMethod('get')){
            //根据用户查找其对应的前期手续账号密码  么有找到 显示添加账号页面
            //否则直接根据接口  访问前期手续系统
            if($this->user->qqsx_name){
                $url = $this->getQqsxUrl($this->user->qqsx_name);
                return redirect($url);
            }else{
                return view('qqsx');
            }
        }
        $data['qqsx_name'] = Input::get('name');
        $data['qqsx_pass'] = Input::get('password');
        /*$repassword = Input::get('repassword');
        if(!$data['qqsx_name'] || !$data['qqsx_pass'] || !$repassword){
            $result = ['status'=>0, 'info'=>'请填写账号,密码，确认密码'];
            return Response()->json($result);
        }
        if($data['qqsx_pass'] != $repassword){
            $result = ['status'=>0, 'info'=>'密码和确认密码不一致'];
            return Response()->json($result);
        }*/
        try {
            User::where('id', $this->user->id)->update($data);
            $url = $this->getQqsxUrl($data['qqsx_name'], $data['qqsx_pass']);
            $result = ['status'=>1,'info'=>'添加成功，即将进入前期专项手续系统', 'url'=>$url];
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'添加失败'];
        }
        return Response()->json($result);
    }

    /*测试环境信息
        url：http://120.25.252.45:8888/
        密钥：ASDQWE!@#123mnbn
        测试用户：陕西省发展和改革委员会
          陕西省铁路集团有限公司部门领导
          lisp
    */
    protected function getQqsxUrl($name, $pass=''){
        $app_key = 'ASDQWE!@123mnbn';
        $app_secret = $name;
        $timestamp = time();
        $sign_method = 'md5';
        //拼接参数名与参数值
        $str = 'app_key'.$app_key.'app_secret'.$app_secret.'timestamp'.$timestamp.'sign_method'.$sign_method;
        $sign = strtoupper(md5($app_key.$str.$app_key));
        $url = 'http://tl.sndrc.gov.cn/Railway/Oauth?app_key='.$app_key.'&app_secret='.$app_secret.'&timestamp='.$timestamp.'&sign_method='.$sign_method.'&sign='.$sign;
        return $url;
    }
    /*
     * 农民工资
     * */
    public function getFarmerWages(){
        $nmgz_id= Auth::User()->nmgz;
        header("location:http://gz.xawhgs.com/Services/OutLogin.html?unitId=$nmgz_id");
        exit;
    }

    /* 电子档案*/
    public function electronic()
    {
        $user_id=Auth::user()->id;
        $IDNumer=Auth::user()->IDNumber;
        if($IDNumer){
            header("location: http://121.12.120.199:8080/gpms/IdPostLogin?id=".$user_id);
            exit;
        }else{
            header("location: http://121.12.120.199:7070/eams");
            exit;
        }

    }

    /*边坡监测*/

    public function side()
    {
        $bp_user_name=Auth::user()->bpjc_user ? Auth::user()->bpjc_user : 'abc';
        $bp_user_key=Auth::user()->bpjc_pass ? Auth::user()->bpjc_pass : 'abcd';
//        $bpjc
        header("location: http://39.104.51.43/waihuanbianpoManage/doIndex/".$bp_user_name."/".$bp_user_key);
        exit;
    }

    /*隧道监控量测*/
    public function tunnelMonitor()
    {

       $tunnel_monitor_account=Auth::user()->tunnel_monitor_account ? Auth::user()->tunnel_monitor_account : 'abc';

       $tunnel_monitor_password=Auth::user()->tunnel_monitor_password ? Auth::user()->tunnel_monitor_password : '123';

       $url='http://218.3.150.107:8004/ExternalLogin.ashx';

        $redirection_url=$url.'?userName='.$tunnel_monitor_account.'&password='.$tunnel_monitor_password;

        return redirect($redirection_url);

    }

    /*BIM*/
    public function bim()
    {
        $redirect_url='http://bim.sunserver.cn/Door/Login';

        return redirect($redirect_url);
    }

    /*隧道安全*/
    public function tunnel()
    {
        $redirect_url='http://hsy.89576788.com/';

        return redirect($redirect_url);

    }





}
