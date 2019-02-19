<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB, Log, Cache;
use App\Send\SendSms;
use App\Send\SendWechat;
use App\Models\Sdtj\SdtjUserSet;
use App\Models\Sdtj\StatWarnMess;
use App\Models\Project\Section;
use App\Models\Lab\Lab_info;
use App\Models\Bhz\Snbhz_info;
use Mockery\Exception;

/**
 * 天气信息月统计推送
 * 每月1号10:00执行一次
 */
class WeatherStatPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather_stat_push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'weather_stat_push';
    protected $time, $date;

    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626
     */
    public function handle()
    {
        Log::info('weather_stat_month_push start');
        $this->sendWechat();

        Log::info('weather_stat_month_push end');
    }


    //发送微信消息
    protected function sendWechat()
    {
        $user_data=$this->getUser();
        foreach($user_data as $v){
            $temp_wechat['url']=Config()->get('common.app_url').'/stat/weather_stat';
            $temp_wechat['first']='天气月统计通知';
            $temp_wechat['word']='天气月统计报表';
            $temp_wechat['con']='详细内容点击详情查看';
            $temp_wechat['time']=time();

            $res = (new SendWechat)->sendSdtj($v['user']['openid'], $temp_wechat);
            Log::info('send_weather_stat_month_push SendWechat');
            Log::info($v['user']['openid']);
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