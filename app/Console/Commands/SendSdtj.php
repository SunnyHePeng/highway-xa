<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB, Log, Cache;
use App\Send\SendSms;
use App\Send\SendWechat;
use App\Models\Sdtj\SdtjUserSet;
use App\Models\Sdtj\SdtjDaliyTfkw;
use App\Models\Sdtj\SdtjDaliyLf;
use App\Models\Sdtj\SdtjStatus;

/**
 * 隧道统计通知
 * 该command每天的8:10开始该command通过windows计划任务自动执行，每5分钟执行一次
 * 执行的时候先判断下面监理把相关数据填写是否填写完整了，
 * 如果填写完整了，在下个5分钟执行微信模板消息推送，
 * 如果未填写完整，则不执行推送，
 */
class SendSdtj extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendsdtj';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sdtj';
    protected $time, $date;

    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626
     */
    public function handle()
    {
        $this->sdtjsendWechat();
    }


    protected function sdtjsendWechat()
    {
        $status_model=new SdtjStatus();
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;
        $send_status=$status_model->select('id','send_status')->where('send_status',0)->whereBetween('time',[$start_time,$end_time])->first();
        $status=$status_model->select('id','send_status')->where('send_status',1)->whereBetween('time',[$start_time,$end_time])->first();
        if(is_object($send_status) && !is_object($status)){
            Log::info('sdtj sendstart');
            $res=$this->sendWechat();
            Log::info('sdtj sendend');
            $send_status->send_status=1;
            $send_status->save();

        }elseif(!is_object($send_status) && !is_object($status)){
            Log::info(' sdtj data not ok not sendwechat');
            $this->getAllSub();
        }

    }

    //获取是否全部提交
    protected function getAllSub()
    {
        $time=strtotime(date('Y-m-d',time()));

       $lf_model=new SdtjDaliyLf();
       $tfkw_model=new SdtjDaliyTfkw();
       $sub_13_left=$lf_model->select('id','time')->where('time',$time)->where('site',1)->where('section_id',19)->get()->toArray();
       $sub_13_right=$lf_model->select('id','time')->where('time',$time)->where('site',2)->where('section_id',19)->get()->toArray();
       $sub_14_left=$lf_model->select('id','time')->where('time',$time)->where('site',1)->where('section_id',20)->get()->toArray();
       $sub_14_right=$lf_model->select('id','time')->where('time',$time)->where('site',2)->where('section_id',20)->get()->toArray();
       $sub_13_tfkw=$tfkw_model->select('id','time')->where('time',$time)->where('section_id',19)->get()->toArray();
       $sub_14_tfkw=$tfkw_model->Select('id','time')->where('time',$time)->where('section_id',20)->get()->toArray();
       if(!empty($sub_13_left) && !empty($sub_13_right) && !empty($sub_13_tfkw) && !empty($sub_14_left) && !empty($sub_14_right) && !empty($sub_14_tfkw)){
           $now_time=time();
           $data['time']=$now_time;
           $data['send_status']=0;
           $status_model=new SdtjStatus();
           $status_model::create($data);
       }

    }



    protected function sendsms()
    {

        $temp_param = [
            'section'=>'abc',
            'tfkw'=>1000  ,
            'tfkw_finish'=>'bcd',
        ];
        $res = (new SendSms)->send('', $temp_param, 'SMS_135031190');
        Log::info('sendSdtjNotice SendSms '.'');
        Log::info(json_encode($res));
    }
    //发送微信消息
    protected function sendWechat()
    {
       $user_data=$this->getUser();
       foreach($user_data as $v){
           $temp_wechat['url']=Config()->get('common.app_url').'/stat/index';
           $temp_wechat['first']='隧道工程进度更新';
           $temp_wechat['word']='白鹿原隧道进度统计';
           $temp_wechat['con']='详细内容点击详情查看';
           $temp_wechat['time']=time();

           $res = (new SendWechat)->sendSdtj($v['user']['openid'], $temp_wechat);
                        Log::info('sendSdtjNotice SendWechat');
                        Log::info($res);
       }
    }
    //获取用户信息
    protected function getUser()
    {
       $model=new SdtjUserSet();
       $data=$model->select('id','user_id')
                   ->with(['user'=>function(){

                   }])
                   ->get()
                   ->toArray();
       return $data;
    }








}