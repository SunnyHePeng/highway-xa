<?php

namespace App\Http\Controllers\Api;

/*
 *
 *
 * */
use App\Models\Bhz\SnbhzDeviceFailurePushUser;
use App\Models\Device\Device;
use App\Send\SendSms;
use App\Send\SendWechat;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Project\Section,
    App\Models\Project\Supervision,
    App\Models\User\User;
use Input, Log, Auth, DB;
use Mockery\Exception;

class MixplantController extends BaseController
{
    protected $witness_model;

    public function __construct()
    {
        parent::__construct();
    }



    /**
     * 拌合站设备与采集端连接异常通知
     */
    public function deviceFailurePush(Request $request)
    {

        $station_code = $request->header('Station-Code');

        $device_id = Device::where('dcode', $station_code)->where('cat_id', 4)->pluck('id');
//     dd($device_id);
        if (!$device_id) {
            return $this->errorMsg('no_device');
        }
        $package_type = $request->header('Package-Type');
        $data = '';
        switch ($package_type) {
            //验证登陆用户
            case 'deviceFailure':
                $device_model = new Device();
                $data = $this->failurePush($request, $device_id, $device_model,$station_code);
                break;
            default:
                $data = ['status' => 1, 'info' => '出现未知错误'];
                break;
        }

        return Response()->json($data);
    }

    protected function failurePush($request,$device_id,$device_model,$device_code)
    {

        $device_data=$device_model->with(['project','section'])->find($device_id);

        $post = file_get_contents("php://input");
        $post = json_decode($post, true);

        $info=array_key_exists('info',$post) ? $post['info'] : '';

        $device_name=$device_data->name;
        $project_name=$device_data->project->name;
        $section_name=$device_data->section->name;
        $time=date('Y-m-d H:i:s',time());

        //短信参数
        $sms_parames=[
            'project'=>$project_name,
            'section'=>$section_name,
            'device'=>$device_name,
            'device_code'=>$device_code,
            'info'=>$info,
        ];

        //短信模板id
        $sms_id='SMS_151670355';

        $push_user_model=new SnbhzDeviceFailurePushUser();

        $push_user_data=$push_user_model->with('user')
            ->get();

        //微信模板id
        $wechat_id='QzGk1eXg-JHU1FiV-LPp22uNyHQijEtYvddYMVFILJulahGhdX0A';

        //微信模板参数
        $wechat_parames=[
            'first'=>'拌和设备与采集端异常通知',
            'device_name'=>$device_name,
            'device_code'=>$device_code,
            'project_name'=>$project_name,
            'section_name'=>$section_name,
            'info'=>$info,
            'time'=>$time,
            'remark'=>'请及时处理',
        ];

        foreach($push_user_data as $v){
            $sms_res=(new SendSms())->send($v->user->phone,$sms_parames,$sms_id);
            $wechat_res=(new SendWechat())->sendDeviceFailure($v->user->openid,$wechat_parames);

            Log::info('sendSms deviceFailurePush  phone:'.$v->user->phone);
            Log::info(json_encode($sms_res));

            Log::info('sendWechat deviceFailurePush  openid:'.$v->user->openid);
            Log::info(json_encode($wechat_res));

        }

        $result['status']=0;
        $result['info']='消息发送成功';
        return $result;

    }




}