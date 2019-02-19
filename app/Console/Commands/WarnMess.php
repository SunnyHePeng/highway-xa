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
 * 报警信息统计通知（进度统计）
 * 该command每天的8:10开始该command通过windows计划任务自动执行，每5分钟执行一次
 * 执行的时候先判断下面监理把相关数据填写是否填写完整了，
 * 如果填写完整了，在下个5分钟执行微信模板消息推送，
 * 如果未填写完整，则不执行推送，
 *
 */
class WarnMess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warnmess';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'warnmess';
    protected $time, $date;

    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626
     */
    public function handle()
    {
        Log::info('stat_warn_mess start');
        $this->stat_warn_mess();

        Log::info('stat_warn_mess end');
    }

    /*数据统计和发送微信推送消息*/
    protected function stat_warn_mess()
    {
        $this->stat_data();
        $this->sendWechat();

    }
    /*试验室和拌合站报警数据统计*/
    protected function stat_data()
    {
       $lab_model=new Lab_info();
       $snbhz_model=new Snbhz_info();
       $stat_warn_mess_model=new StatWarnMess();

       $start_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d',time()-86400))))+28800;
       $end_date=$start_date+86400;

       $lab_bj_field='count(lab_info.id) as bj_num,section_id';
       $lab_cl_field='count(lab_info.id) as cl_num,section_id';
       $lab_module_id=3;

       $bhz_bj_field='count(snbhz_info.id) as bj_num,section_id';
       $bhz_cl_field='count(snbhz_info.id) as cl_num,section_id';
       $bhz_module_id=4;

       $section_data=$this->getSection();

       $lab_bj_data=$this->getWarnData($lab_model,$lab_bj_field,$lab_cl_field,$lab_module_id,$start_date,$end_date,$section_data);
       $bhz_bj_data=$this->getWarnData($snbhz_model,$bhz_bj_field,$bhz_cl_field,$bhz_module_id,$start_date,$end_date,$section_data);

       foreach($lab_bj_data as $v){
           try{
               $stat_warn_mess_model::create($v);
           }catch (Exception $e){
               Log::info('stat_warn_mess  data success');
               Log::info($v);
           }
       }

        foreach($bhz_bj_data as $v){
            try{
                $stat_warn_mess_model::create($v);
            }catch (Exception $e){
                Log::info('stat_warn_mess error');
                Log::info($v);
            }
        }
        Log::info('stat_warn_mess data ok');
    }

    protected function getWarnData($model,$bj_field,$cl_field,$module_id,$start_date,$end_date,$section_data)
    {
        $bj_data=[];
        $cl_data=[];
        foreach($section_data as $value){

            $bj_list=$model->select(DB::raw($bj_field))
                ->whereBetween('time',[$start_date,$end_date])
                ->where('is_warn',1)
                ->where('section_id',$value['id'])
                ->get()
                ->toArray();
            $bj_list[0]['section_name']=$value['name'];
            $bj_list[0]['supervision_name']=$value['sup'][0]['name'];
            $bj_list[0]['module_id']=$module_id;
            if($bj_list[0]['section_id']==''){
                $bj_list[0]['section_id']=$value['id'];
            }
            $bj_data[]=$bj_list[0];

            $cl_list=$model->select(DB::raw($cl_field))
                ->whereBetween('time',[$start_date,$end_date])
                ->where('is_warn',1)
                ->where('is_sec_deal',1)
                ->where('is_sup_deal',1)
                ->where('section_id',$value['id'])
                ->get()
                ->toArray();
            $cl_list[0]['section_name']=$value['name'];
            $cl_list[0]['supervision_name']=$value['sup'][0]['name'];
            $cl_list[0]['module_id']=$module_id;
            if($cl_list[0]['section_id']==''){
                $cl_list[0]['section_id']=$value['id'];
            }
            $cl_data[]=$cl_list[0];
        }

        foreach($bj_data as $k=>$v){
            foreach($cl_data as $key=>$val){
                if($bj_data[$k]['section_id'] == $cl_data[$key]['section_id']){
                    $bj_data[$k]['cl_num']=$cl_data[$key]['cl_num'];
                    $bj_data[$k]['time']=time();
                }
            }
        }
        return $bj_data;
    }



    /*短信测试*/
    protected function sendsms()
    {

        $temp_param = [
            'section'=>'abc',
            'tfkw'=>1000  ,
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
            $temp_wechat['url']=Config()->get('common.app_url').'/stat/warn_mess';
            $temp_wechat['first']='报警信息处理情况更新';
            $temp_wechat['word']='报警信息处理情况统计';
            $temp_wechat['con']='详细内容点击详情查看';
            $temp_wechat['time']=time();

            $res = (new SendWechat)->sendSdtj($v['user']['openid'], $temp_wechat);
            Log::info('send_warn_mess SendWechat');
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

    //获取标段
    protected function getSection()
    {
       $model=new Section();
       $data=$model->select('id','name')
                   ->with(['sup'=>function($query){
                     $query->select(['id','name']);
                   }])
                   ->get()
                   ->toArray();
       return $data;
    }







}