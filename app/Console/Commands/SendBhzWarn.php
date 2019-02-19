<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bhz\Snbhz_info,
    App\Models\Lab\Lab_info,
    App\Models\System\Warn_user_set,
    App\Models\User\User;
use DB, Log, Cache;
use App\Send\SendSms, App\Send\SendWechat;

/**
 * 拌合站报警（报警升级及报警即将升级消息推送）(该command暂时已废弃执行)
 */
class SendBhzWarn extends Command
{
  /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_bhz_warn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send bhz and lab warn data';
   
    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626 
     */
    public function handle()
    {
      Log::info('send_bhz_warn start');
      $this->sendWarn();

      Log::info('send_bhz_warn end');
    }

    /*24/48小时未处理 通知
    *是谁未处理 标段吗 如果监理24/48小时没处理呢
    *还有 3天没没出来完的怎么办
    */
    protected function sendWarn(){
        $time = time();
        //获取24小时未处理的报警信息 大于等于24小时小于48小时
        $this->noticeUser($time, 24);
        //获取即将到达24小时未处理的报警信息 大于等于23小时小于24小时
        $this->noticeUser($time, 24, '即将');
        //获取48小时未处理的报警信息 大于等于48小时小于72小时
        $this->noticeUser($time, 48);
        //获取即将到达24小时未处理的报警信息 大于等于47小时小于48小时
        $this->noticeUser($time, 48, '即将');
    }

    protected function noticeUser($time, $hour, $pre_notice=''){
        $module = [4, 3];
        foreach ($module as $key => $value) {
            switch ($value) {
                case 3:
                    $model = new Lab_info;
                    break;
                case 4:
                    $model = new Snbhz_info;
                    break;
            }
            $this->getInfoAndNoticeUser($time, $hour, $model, $value, $pre_notice);
        }
    }

    //获取报警到达时限的信息并通知用户
    protected function getInfoAndNoticeUser($time, $hour, $model, $module_id, $pre_notice){
        $query = $model->select(['id','project_id','supervision_id','section_id','device_id','time','created_at','is_warn','warn_level','warn_info'])
                       ->where('is_warn', 1);
        if($pre_notice){
            $query = $query->where('time', '<=', $time-3600*($hour-2))
                           ->where('time', '>', $time-3600*($hour-1));
        }else{
            $query = $query->where('time', '<=', $time-3600*$hour)
                           ->where('time', '>', $time-3600*($hour+24));
        }
        $list = $query->where('is_sec_deal', 0)
                      ->where('is_'.$hour.'_notice', 0)
                      ->with(['device'=>function($query){
                        $query->select(['id','name','dcode']);
                      }])
                      ->orderByRaw('id asc')
                      ->get()
                      ->toArray();
        Log::info(date('Y-m-d H:i:s', $time-3600*($hour-2)));
        Log::info(date('Y-m-d H:i:s', $time-3600*($hour-1)));
        
        Log::info($list);
        if($list){
            foreach ($list as $key => $value) {
                //更新是否推送 修改因时限导致的报警升级
                $update_info = [
                    'is_'.$hour.'_notice'=>1,
                  ];
                //24 小时没处理 初级-》中级-》高级  48小时没处理 中级-》高级
                //获取最新报警级别
                $n_level = $value['warn_level'];
                $new_level = $this->getNewLevel($value['warn_level'], $hour);
                if($new_level){
                    $n_level = $new_level['warn_sx_level'];
                    $update_info = array_merge($update_info, $new_level);
                }
                //获取需通知的用户并发送信息
                $this->getUserAndSend($value, $hour, $module_id, $n_level, $pre_notice);
                //不是提前提醒 更新推送信息
                if(!$pre_notice){
                    $model->where('id', $value['id'])->update($update_info);
                }
            }
        }
    }

    protected function getUserAndSend($data, $hour, $module_id, $new_level, $pre_notice){
        switch ($data['warn_level']) {
            case 1:
                $level = '初级';
                $column = 'cj_'.$hour;
                break;
            case 2:
                $column = 'zj_'.$hour;
                $level = '中级';
                break;
            case 3:
                $column = 'gj_'.$hour;
                $level = '高级';
                break;
            default:
                $column = 'cj_'.$hour;
                $level = '初级';
                break; 
        }
        //升级后的报警级别
        switch ($new_level) {
            case 1:
                $new_level = '初级';
                $column = 'cj_'.$hour;
                break;
            case 2:
                $new_level = '中级';
                $column = 'zj_'.$hour;
                break;
            case 3:
                $new_level = '高级';
                $column = 'gj_'.$hour;
                break;
            default:
                $new_level = '中级';
                $column = 'zj_'.$hour;
                break; 
        }
            
        $user = $this->getBjtzUser($data, $module_id, $column);
        if($user){
            if($module_id == 4){
              $device = '拌和设备';
              $url = 'snbhz_detail_info';
            }else{
              $device = '试验设备';
              $url = 'lab_detail_info';
            }
            foreach ($user as $key => $value) {
                $temp_param = [
                        'dcode'=>$data['device']['name'],
                        'time'=>date('Y-m-d H:i:s',$data['time']),
                        'level'=>$level.','.$pre_notice.$hour.'小时未处理，升级为'.$new_level,
                        'info'=>$data['warn_info']
                    ];
                if($value['phone']){
                    $temp_id = 'SMS_122285132';
                    $res = (new SendSms)->send($value['phone'], $temp_param, $temp_id);
                    Log::info('sendWarnNotice SendSms '.$value['phone']);
                    Log::info(json_encode($res));
                }
                if($value['openid']){
                    $temp_param['first'] = $device.'发生报警,'.$pre_notice.$hour.'小时未处理';
                    $temp_param['time'] = $data['time'];
                    $temp_param['url'] = Config()->get('common.app_url').'/wechat/'.$url.'?id='.$data['id'];
                    $res = (new SendWechat)->sendBj($value['openid'], $temp_param);
                    Log::info('sendWarnNotice SendWechat');
                    Log::info($res);
                }
            }
        } 
    }

    protected function getBjtzUser($info, $module_id, $column){
        $user = Warn_user_set::select(DB::raw('user.id,user.name,user.phone,user.openid'))
                            ->leftJoin('user', function($join){
                                $join->on('user.id', '=', 'warn_user_set.user_id')
                                     ->where('user.status', '=', 1);
                            })
                            ->where('warn_user_set.project_id', '=', $info['project_id'])
                            ->where('warn_user_set.supervision_id', '=', $info['supervision_id'])
                            ->where('warn_user_set.section_id', '=', $info['section_id'])
                            ->where('warn_user_set.module_id', '=', $module_id)
                            ->where('warn_user_set.'.$column, '=', 1)
                            ->whereNotNull('user.name')
                            ->orderByRaw('warn_user_set.id asc')
                            ->get()
                            ->toArray();
        Log::info($user);
        return $user;
    }

    /*
    *24 小时没处理 初级-》中级-》高级  48小时没处理 中级-》高级
    *获取最新报警级别
    */
    protected function getNewLevel($level, $hour){
        if($level<3){
            if($hour == 24){
                $level = $level + 1;
                $level_info = '24小时未处理报警升级';
            }
            if($hour == 48){
                $level = $level + 2;
                $level_info = '48小时未处理报警升级';
            } 

            if($level > 3){
                $level = 3;
            }
            return ['warn_sx_level'=>$level, 'warn_sx_info'=>$level_info];      
        }
        return '';
    }
}