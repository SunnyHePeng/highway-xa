<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB, Log, Cache;
use App\Send\SendSms;
use App\Send\SendWechat;
use App\Models\Sdtj\SdtjUserSet;
use App\Models\Sdtj\ResourceSendStatus;
use App\Models\Sdtj\ResourceJxsb;
use App\Models\Sdtj\ResourceSgry;

/**
 * 资源配置统计通知
 * 该command每天的8:10开始该command通过windows计划任务自动执行，每5分钟执行一次
 * 执行的时候先判断下面监理把相关数据填写是否填写完整了，
 * 如果填写完整了，在下个5分钟执行微信模板消息推送，
 * 如果未填写完整，则不执行推送，
 *
 */
class Resource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resource';
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
        $status_model=new ResourceSendStatus();
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;
        $send_status=$status_model->select('id','status')->where('status',0)->whereBetween('time',[$start_time,$end_time])->first();
        $already_status=$status_model->select('id','status')->where('status',1)->whereBetween('time',[$start_time,$end_time])->first();
        if(is_object($send_status) && !is_object($already_status)){
            Log::info('resource sendstart');
            $res=$this->sendWechat();
            Log::info('resource sendend');
            $send_status->status=1;
            $send_status->save();

        }elseif(!is_object($send_status) && !is_object($already_status)){
            Log::info(' resource data not ok not sendwechat');
            $this->getAllSub();
        }

    }

    //获取是否全部提交
    protected function getAllSub()
    {
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;

        $sgry_model=new ResourceSgry();
        $jxsb_model=new ResourceJxsb();

        $left_sgry_13=$sgry_model->select('id','time')->where('section_id',19)->where('site',1)->whereBetween('time',[$start_time,$end_time])->first();
        $right_sgry_13=$sgry_model->select('id','time')->where('section_id',19)->where('site',2)->whereBetween('time',[$start_time,$end_time])->first();

        $left_sgry_14=$sgry_model->select('id','time')->where('section_id',20)->where('site',1)->whereBetween('time',[$start_time,$end_time])->first();
        $right_sgry_14=$sgry_model->select('id','time')->where('section_id',20)->where('site',2)->whereBetween('time',[$start_time,$end_time])->first();

        $left_jxsb_13=$jxsb_model->select('id','time')->where('section_id',19)->where('site',1)->whereBetween('time',[$start_time,$end_time])->first();
        $right_jxsb_13=$jxsb_model->select('id','time')->where('section_id',19)->where('site',2)->whereBetween('time',[$start_time,$end_time])->first();

        $left_jxsb_14=$jxsb_model->select('id','time')->where('section_id',20)->where('site',1)->whereBetween('time',[$start_time,$end_time])->first();
        $right_jxsb_14=$jxsb_model->select('id','time')->where('section_id',20)->where('site',2)->whereBetween('time',[$start_time,$end_time])->first();

        if(is_object($left_sgry_13) && is_object($right_sgry_13) && is_object($left_sgry_14) && is_object($right_sgry_14) && is_object($left_jxsb_13) && is_object($right_jxsb_13) && is_object($left_jxsb_14) && is_object($right_jxsb_14)){

            $data['time']=time();
            $data['status']=0;
            $status_model=new ResourceSendStatus();
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
        $res = (new SendSms)->send('', $temp_param, 'SMS_135031190');
        Log::info('sendSdtjNotice SendSms '.'');
        Log::info(json_encode($res));
    }
    //发送微信消息
    protected function sendWechat()
    {
        $user_data=$this->getUser();
        foreach($user_data as $v){
            $temp_wechat['url']=Config()->get('common.app_url').'/stat/resource';
            $temp_wechat['first']='资源配置统计更新';
            $temp_wechat['word']='白鹿原隧道资源配置统计';
            $temp_wechat['con']='详细内容点击详情查看';
            $temp_wechat['time']=time();

            $res = (new SendWechat)->sendSdtj($v['user']['openid'], $temp_wechat);
            Log::info('sendResourceNotice SendWechat');
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