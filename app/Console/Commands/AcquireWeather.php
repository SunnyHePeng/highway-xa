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
use App\Models\WeatherMessage\WeatherMessage;
use App\Weather\Weather;

/**
 * 获取当天的天气信息并保存到数据库
 *   每天的0:05执行脚本获取
 */
class AcquireWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acquire_weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'acquire_weather';
    protected $time, $date;

    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626
     */
    public function handle()
    {
        Log::info('acquire  today weather data and save start');

        $this->weather();

        Log::info('acquire today weather data and save end');
    }

    //调用api获取天气信息
    protected function weather()
    {

        $weather=new Weather();
        $weather_data=$weather->getWeatherMess("xian");
        $weather_data=json_decode($weather_data,true);
        if(!array_key_exists('status_code',$weather_data)){
              //获取天气数据成功无错误
              Log::info('acquire today weather success');
              //将天气数据保存到数据库
              $this->store($weather_data);
        }else{
            //获取天气出错
            Log::info('acquire today weather error');
            Log::info($weather_data);
        }

    }

    //存当天的天气信息到自身服务器数据
    protected function store($data)
    {
//        dd($data);
        $weather_data=[];
        //查询天气时间
        $weather_data['time']=time();
        //天气编号
        $weather_data['code']=$data['results'][0]['daily'][0]['code_day'];
        //天气名称
        $weather_data['weather_text']=$data['results'][0]['daily'][0]['text_day'];
        //最低温度
        $weather_data['temperature_low']=$data['results'][0]['daily'][0]['low'];
        //最高温度
        $weather_data['temperature_high']=$data['results'][0]['daily'][0]['high'];
        //天气分类
        $weather_data['weather_cate']=Config()->get('common.weather_contrast')[$weather_data['code']];
        //施工情况
        $weather_data['construction_situation']=Config()->get('common.weather_construction')[$weather_data['weather_cate']];

        $weather_model=new WeatherMessage();

        $res=$weather_model::create($weather_data);

        if($res){
            Log::info('today weather data save success');
        }else{
            Log::info('today weather data save error');
        }


    }






}