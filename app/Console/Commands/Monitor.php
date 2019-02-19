<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB, Log, Cache;
use App\Send\SendSms;
use App\Send\SendWechat;
use App\Models\Sdtj\SdtjUserSet;
use App\Models\Sdtj\SdtjMonitor;
use App\Models\Sdtj\SdtjMonitorSend;

/**
 * 隧道监控量测统计通知
 * 每天的8:10该command开始自动执行，每5分钟执行一次
 * 执行的时候先判断下面监理把相关数据填写是否填写完整了，
 * 如果填写完整了，在下个5分钟执行微信模板消息推送，
 * 如果未填写完整，则不执行推送，
 */
class Monitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'monitor';
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
        $status_model=new SdtjMonitorSend();
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;
        $send_status=$status_model->select('id','status')->where('status',0)->whereBetween('time',[$start_time,$end_time])->first();
        $already_status=$status_model->select('id','status')->where('status',1)->whereBetween('time',[$start_time,$end_time])->first();
        if(is_object($send_status) && !is_object($already_status)){
            Log::info('sdtjmonitor sendstart');
            $res=$this->sendWechat();
            Log::info('sdtjmonitor sendend');
            $send_status->status=1;
            $send_status->save();

        }elseif(!is_object($send_status) && !is_object($already_status)){
            Log::info(' sdtjmonitor data not ok not sendwechat');
            $this->getAllSub();
        }

    }

    //获取是否全部提交
    protected function getAllSub()
    {
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;

        $monitor_model=new SdtjMonitor();
        $monitor_13=$monitor_model->select('id','section_id')->where('section_id',19)->whereBetween('time',[$start_time,$end_time])->first();
        $monitor_14=$monitor_model->select('id','section_id')->where('section_id',20)->whereBetween('time',[$start_time,$end_time])->first();
        if(is_object($monitor_13) && is_object($monitor_14)){
            $data['time']=time();
            $data['status']=0;
            $status_model=new SdtjMonitorSend();
            $status_model::create($data);

        }

    }



    protected function sendsms()
    {

        $temp_param = [
            'section'=>'abc',
            'tfkw'=>1000,
            'tfkw_finish'=>'bcd',
        ];
        $res = (new SendSms)->send('15399060323', $temp_param, 'SMS_135031190');
        Log::info('sendSdtjNotice SendSms '.'15399060323');
        Log::info(json_encode($res));
    }
    //发送微信消息
    protected function sendWechat()
    {
        $user_data=$this->getUser();
        foreach($user_data as $v){
            $temp_wechat['url']=Config()->get('common.app_url').'/stat/monitor';
            $temp_wechat['first']='隧道监控量测报表更新';
            $temp_wechat['word']='白鹿原隧道监控量测报表';
            $temp_wechat['con']='详细内容点击详情查看';
            $temp_wechat['time']=time();

            $res = (new SendWechat)->sendSdtj($v['user']['openid'], $temp_wechat);
            Log::info('sendsdtjMonitorNotice SendWechat');
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