<?php

namespace App\Http\Controllers\Api;

use App\Models\Device\Device;
use App\Models\Project\BeamSite;
use App\Models\Stretch\StretchMudjackStatWarn;
use App\Models\Stretch\StretchMudjackWarnUser;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, DB, Session, Cache, Log, Mail;
use App\Models\Bhz\Snbhz_info_detail_new,
    App\Models\Bhz\Snbhz_warn_info,
    App\Models\Bhz\Snbhz_warn_total,
    App\Models\Bhz\Snbhz_product_total,
    App\Models\Lab\Lab_info_detail,
    App\Models\Lab\Lab_warn_info,
    App\Models\Lab\Lab_warn_total,
    App\Models\User\User,
    App\Models\System\Warn_user_set,
    App\Models\Lab\Lab_info_gjsy_detail;
use App\Send\SendSms, App\Send\SendWechat;
use Mockery\Exception;

class BaseController extends Controller
{
    


    /*验证时间戳*/
    public function checkTimestamp($timestamp){
        $time = time();
        $abss = abs($timestamp-$time);
        if($abss > Config()->get('common.time_difference')){
            return false;
        }
        return true;
    }

    /*错误信息*/
    public function errorMsg($key){
        $error = [
            'device_status'=>['status'=>4,'message'=>'更新设备状态失败'],
            'no_device'=>['status'=>5,'message'=>'设备不存在'],
            'snbhz_info'=>['status'=>6,'message'=>'拌合站信息添加失败'],
            'lab_info'=>['status'=>7,'message'=>'试验室信息添加失败'],
            'zhangla_info'=>['status'=>8,'message'=>'张拉信息添加失败'],
            'yajiang_info'=>['status'=>9,'message'=>'压浆信息添加失败'],
        ];
        return $error[$key];
    }

    /*根据package_type 获取函数名*/
    public function getFuncName($package_type){
        if(stripos('-', $package_type)){
            $name_arr = explode('-', $package_type);
            $name = '';
            foreach ($name_arr as $key => $value) {
                $name .= $name ? ucfirst($value) : $value;
            }
            return $name;
        }
        return $package_type;
    }

    /*添加水泥拌合站详细屋里物料信息*/
    protected function addSnbhzInfoDetail($snbhz_info_id, $snbhz_info_detail){
        foreach ($snbhz_info_detail as $key => $value) {
            $snbhz_info_detail[$key]['snbhz_info_id'] = $snbhz_info_id;
        }
        Snbhz_info_detail_new::insert($snbhz_info_detail);
    }

    /*添加报警信息*/
    protected function addSnbhzWarnInfo($warn_info, $snbhz_info_id, $device_info){
        $num = 1;//count($warn_info);
        /*foreach ($warn_info as $key => $value) {
            $warn_info[$key]['project_id'] = $device_info['project_id'];
            $warn_info[$key]['supervision_id'] = $device_info['supervision_id'];
            $warn_info[$key]['section_id'] = $device_info['section_id'];
            $warn_info[$key]['device_id'] = $device_info['id'];
            $warn_info[$key]['snbhz_info_id'] = $snbhz_info_id;
        }
        Snbhz_warn_info::insert($warn_info);*/
        $res = Snbhz_warn_total::where('device_id', $device_info['id'])
                                ->where('date', date('Y-m-d'))
                                ->first();
        if($res){
            Snbhz_warn_total::where('id', $res->id)->increment('num', $num);
        }else{
            $data = [
                'project_id'=>$device_info['project_id'],
                'supervision_id'=>$device_info['supervision_id'],
                'section_id'=>$device_info['section_id'],
                'device_id'=>$device_info['id'],
                'num'=>$num,
                'date'=>date('Y-m-d')
                ];
            Snbhz_warn_total::create($data);
        }
    }

    /*添加拌合总量*/
    protected function addSnbhzTotalInfo($total, $device_info){
        $res = Snbhz_product_total::where('device_id', $device_info['id'])
                                   ->where('date', date('Y-m-d'))
                                   ->first();
        if($res){
            Snbhz_product_total::where('id', $res->id)->increment('num', $total);
        }else{
            $data = [
                'project_id'=>$device_info['project_id'],
                'supervision_id'=>$device_info['supervision_id'],
                'section_id'=>$device_info['section_id'],
                'device_id'=>$device_info['id'],
                'num'=>$total,
                'date'=>date('Y-m-d')
                ];
            Snbhz_product_total::create($data);
        }
    }

    /*获取水泥拌合站物料信息和报警信息*/
    protected function getSnbhzInfoDetail($post,$stir_time){
        $snbhz_info['warn_info'] = '';
        $total = 0;
        $warn_info = [];
        //判断搅拌时间是否合格
//        $stirTimeInfo=$this->getStirTimeIsQualified($stir_time);

        for($i=1; $i<10; $i++){
            $snbhz_info_detail[$i]['type'] = $i;
            if($i < 4){
                $snbhz_info_detail[$i]['design'] = $post['D'.$i]['design'];
                $snbhz_info_detail[$i]['fact'] = $post['D'.$i]['actual'];
            }
            switch ($i) {
                case 4:     //砂总和
                    $snbhz_info_detail[$i]['design'] = $post['D4']['design']+$post['D5']['design'];
                    $snbhz_info_detail[$i]['fact'] = $post['D4']['actual']+$post['D5']['actual'];
                    break;
                case 5:     //水泥总和
                    $snbhz_info_detail[$i]['design'] = $post['D6']['design']+$post['D7']['design']+$post['D8']['design']+$post['D9']['design'];
                    $snbhz_info_detail[$i]['fact'] = $post['D6']['actual']+$post['D7']['actual']+$post['D8']['actual']+$post['D9']['actual'];
                    break;
                case 6:     //粉煤灰总和
                    $snbhz_info_detail[$i]['design'] = $post['D10']['design']+$post['D11']['design']+$post['D12']['design'];
                    $snbhz_info_detail[$i]['fact'] = $post['D10']['actual']+$post['D11']['actual']+$post['D12']['actual'];
                    break;
                case 7:     //水总和
                    $snbhz_info_detail[$i]['design'] = $post['D13']['design']+$post['D14']['design']+$post['D15']['design'];
                    $snbhz_info_detail[$i]['fact'] = $post['D13']['actual']+$post['D14']['actual']+$post['D15']['actual'];
                    break;
                case 8:     //外加剂总和
                    $snbhz_info_detail[$i]['design'] = $post['D16']['design']+$post['D17']['design'];
                    $snbhz_info_detail[$i]['fact'] = $post['D16']['actual']+$post['D17']['actual'];
                    break;
                case 9:     //引气剂
                    $snbhz_info_detail[$i]['design'] = $post['D18']['design'];
                    $snbhz_info_detail[$i]['fact'] = $post['D18']['actual'];
                    break;
            }
            
            $snbhz_info_detail[$i]['pcl'] = $this->getPcl($snbhz_info_detail[$i]['fact'], $snbhz_info_detail[$i]['design']);
            $snbhz = Config()->get('common.snbhz_info_detail.'.$i);//
            if(abs($snbhz_info_detail[$i]['pcl']) > $snbhz['pcl']){
                $snbhz_info['is_warn'] = 1;
                $level = $this->getBhzWarnLevel(abs($snbhz_info_detail[$i]['pcl']), $snbhz);   //报警级别1初级2中级3高级
                if(isset($snbhz_info['warn_level']) && $snbhz_info['warn_level']){
                    $snbhz_info['warn_level'] = $snbhz_info['warn_level'] > $level ? $snbhz_info['warn_level'] : $level;
                }else{
                    $snbhz_info['warn_level'] = $level;
                }
                $snbhz_info['warn_info'] .= $snbhz_info['warn_info'] ? ','.$snbhz['name'] : $snbhz['name'];

                /*$warn_info[]=[
                    'warn_type' => $snbhz['name'],
                    'design_value' => $post['D'.$i]['design'],
                    'fact_value' => $post['D'.$i]['actual'],
                    'design_pcl' => $snbhz['pcl'],
                    'fact_pcl' => $snbhz_info_detail[$i]['pcl']
                    ];*/
            }
            $total += $snbhz_info_detail[$i]['fact'];
        }
        
        if($snbhz_info['warn_info']){

            $snbhz_info['warn_info'] .= '超标';
              //搅拌时间不合格
//            if($stirTimeInfo['status']==1){
//
//               $snbhz_info['warn_info']=$snbhz_info['warn_info'].','.$stirTimeInfo['warn_info'];
//
//            }

        } else {

//            if($stirTimeInfo['status']==1){
//                $snbhz_info['is_warn']=1;
//                $snbhz_info['warn_info'] = $stirTimeInfo['warn_info'];
//                $snbhz_info['warn_level']=1;
//            }

        }
        
        return ['snbhz_info_detail'=>$snbhz_info_detail, 'snbhz_info'=>$snbhz_info, 'warn_info'=>$warn_info, 'total'=>$total];
    }

    /*计算偏差值*/
    protected function getPcl($fact, $design){
        if($design == $fact){
            $pcl = 0;
        }else{
            $cz = $fact - $design;
            $pcl = round($cz / $design * 100, 2);
        }
        return $pcl;
    }

    /*判断拌和站报警级别*/
    /*11月27将修改报警级别判断，将之前的报警等级初，中，高等级修改为初，高级*/
    /*当偏差率pcl<=初级报警上限cj时，为初级报警*/
    /*当偏差率pcl>高级报警下限gj是，为高级报警*/
    protected function getBhzWarnLevel($pcl, $snbhz){
        if($pcl <= $snbhz['cj']){
            $level = 1;
        }elseif($pcl > $snbhz['gj']){
            $level = 3;
        }
        return $level;
    }

    /*立即发送拌和站报警通知
     *发送给标段和监理中 设置为对应等级立即通知的用户
     */
    protected function sendBhzWarnNotice($data, $dcode, $dname){
        switch ($data['warn_level']) {
            case 1:
                $column = 'cj_0';
                $level = '初级';
                break;
            case 2:
                $column = 'zj_0';
                $level = '中级';
                break;
            case 3:
                $column = 'gj_0';
                $level = '高级';
                break;
        }
        //获取用户信息
        $user = $this->getBjtzUser($data, 4, 'snbhzwarn', $column);

        if($user){
            foreach ($user as $key => $value) {
                $temp_param = [
                        'dcode'=>$dname,//.'-'.$dcode,
                        'time'=>date('Y-m-d H:i:s',$data['time']),
                        'level'=>$level,
                        'info'=>$data['warn_info']
                    ];
                if($value['phone']){
                    $res = (new SendSms)->send($value['phone'], $temp_param, 'SMS_122285132');
                    Log::info('sendBhzWarnNotice SendSms '.$value['phone']);
                    Log::info(json_encode($res));
                }
                if($value['openid']){
                    $temp_param['first'] = '拌和设备发生报警';
                    $temp_param['time'] = $data['time'];
                    $temp_param['url'] = Config()->get('common.app_url').'/wechat/snbhz_detail_info?id='.$data['id'];
                    $res = (new SendWechat)->sendBj($value['openid'], $temp_param);
                    Log::info('sendBhzWarnNotice SendWechat');
                    Log::info($res);
                }
            }
        } 
    }

    /*获取实验试件信息*/
    protected function getLabInfoDetail($post){
        for($i=5; $i<11; $i++){
            if(isset($post['D'.$i]['value']) && isset($post['D'.$i]['intensity'])){
                if($post['D'.$i]['value'] || $post['D'.$i]['intensity']){
                    $lab_info_detail[$i-5]['type'] = $i-4;
                    $lab_info_detail[$i-5]['lz'] = $post['D'.$i]['value'];
                    $lab_info_detail[$i-5]['qd'] = $post['D'.$i]['intensity'];
                    if(isset($post['D'.$i]['load']) || isset($post['D'.$i]['strength'])){
                        $lab_info_detail[$i-5]['jxhz'] = $post['D'.$i]['load'];
                        $lab_info_detail[$i-5]['jxqd'] = $post['D'.$i]['strength'];
                    }
                }
            }
        }
        
        return $lab_info_detail;
    }

    /*添加实验详细物料信息*/
    protected function addLabInfoDetail($lab_info_id, $lab_info_detail){
        foreach ($lab_info_detail as $key => $value) {
            $lab_info_detail[$key]['lab_info_id'] = $lab_info_id;
        }
        if(isset($lab_info_detail[0]['jxhz']) || isset($lab_info_detail[0]['jxqd'])){
            Lab_info_gjsy_detail::insert($lab_info_detail);
        }else{
            Lab_info_detail::insert($lab_info_detail);
        }
    }

    /*添加实验报警信息*/
    protected function addLabWarnInfo($sylx, $time, $warn_info, $lab_info_id, $device_info){
        if($sylx == '砂浆抗压' || $sylx == '混凝土抗压' || $sylx == '混凝土抗折' || $sylx == '水泥胶砂抗压' || $sylx == '水泥胶砂抗折'){
            $info1 = $warn_info['is_warn_para1'] ? $sylx.'有效强度' : '';
            $info2 = $warn_info['is_warn_para2'] ? $sylx.'加荷速率' : '';
        }else{
            $info1 = $warn_info['is_warn_para1'] ? $sylx.'下屈服强度' : '';
            $info2 = $warn_info['is_warn_para2'] ? $sylx.'抗拉强度' : '';
        }
        if($info1 && $info2){
            $warn['warn_type'] = $info1.','.$info2.'不合格';
        }else{
            $warn['warn_type'] = ($info1 ? $info1 : $info2).'不合格';
        }
        
        $warn['project_id'] = $device_info['project_id'];
        $warn['supervision_id'] = $device_info['supervision_id'];
        $warn['section_id'] = $device_info['section_id'];
        $warn['device_id'] = $device_info['id'];
        $warn['lab_info_id'] = $lab_info_id;
        $warn['time'] = $time;
        Lab_warn_info::insert($warn);
        $res = Lab_warn_total::where('device_id', $device_info['id'])
                                ->where('date', date('Y-m-d'))
                                ->first();
        if($res){
            Lab_warn_total::where('id', $res->id)->increment('num', 1);
        }else{
            $data = [
                'project_id'=>$device_info['project_id'],
                'supervision_id'=>$device_info['supervision_id'],
                'section_id'=>$device_info['section_id'],
                'device_id'=>$device_info['id'],
                'num'=>1,
                'date'=>date('Y-m-d')
                ];
            Lab_warn_total::create($data);
        }
    }

    /*添加实验报警信息*/
    protected function addLabWarnTotal($device_id, $info){
        $res = Lab_warn_total::where('device_id', $device_id)
                                ->where('date', date('Y-m-d'))
                                ->first();
        if($res){
            Lab_warn_total::where('id', $res->id)->increment('num', 1);
        }else{
            $data = [
                'project_id'=>$info['project_id'],
                'supervision_id'=>$info['supervision_id'],
                'section_id'=>$info['section_id'],
                'device_id'=>$device_id,
                'num'=>1,
                'date'=>date('Y-m-d')
                ];
            Lab_warn_total::create($data);
        }
    }

    /*立即发送试验报警通知
     *发送给标段和监理中 设置为对应等级立即通知的用户
     */
    protected function sendLabWarnNotice($data, $info){
        //获取用户信息
        $user = $this->getBjtzUser($info, 3, 'labwarn', 'cj_0');
        
        if($user){
            foreach ($user as $key => $value) {
                //if($value['module'] && $value['labwarn']){
                    $temp_param = [
                            'dcode'=>$info['name'],//.'-'.$data['code'],
                            'time'=>date('Y-m-d H:i:s',$data['time']),
                            'level'=>'高级',
                            'info'=>$data['info']
                        ];
                    if($value['phone']){
                        Log::info($temp_param);
                        $res = (new SendSms)->send($value['phone'], $temp_param, 'SMS_122285132');
                        Log::info('sendLabWarnNotice SendSms '.$value['phone']);
                        Log::info(json_encode($res));
                    }
                    if($value['openid']){
                        $temp_param['first'] = '试验设备发生报警';
                        $temp_param['time'] = $data['time'];
                        $temp_param['url'] = Config()->get('common.app_url').'/wechat/lab_detail_info?id='.$data['lab_info_id'];
                        $res = (new SendWechat)->sendBj($value['openid'], $temp_param);
                        Log::info('sendLabWarnNotice SendWechat');
                        Log::info($res);
                    }
                //}
            }
        } 
    }

    /*获取报警通知人员*/
    protected function getBjtzUser($info, $module_id, $with, $column){
        $pro_id = $info['project_id'];
        $sec_id = $info['section_id'];
        $sup_id = $info['supervision_id'];
        
        $user = Warn_user_set::select(DB::raw('user.id,user.name,user.phone,user.openid'))
                            ->leftJoin('user', function($join){
                                $join->on('user.id', '=', 'warn_user_set.user_id')
                                     ->where('user.status', '=', 1);
                            })
                            ->where('warn_user_set.project_id', '=', $pro_id)
                            ->where('warn_user_set.supervision_id', '=', $sup_id)
                            ->where('warn_user_set.section_id', '=', $sec_id)
                            ->where('warn_user_set.module_id', '=', $module_id)
                            ->where('warn_user_set.'.$column, '=', 1)
                            ->whereNotNull('user.name')
                            ->orderByRaw('warn_user_set.id asc')
                            ->get()
                            ->toArray();
        Log::info($user);
        return $user;
    }

    public function getBhz(){
        $data = [
            'T1'=>date('YmdHis'),//'20171013101314',
            'T2'=>'生产单位',
            'T3'=>'监理单位',
            'T4'=>'设备编号',
            'T5'=>'盘方量',
            'T6'=>'配比编号',
            'T7'=>'浇注位置',
            'T8'=>'施工地点',
            'T9'=>'车辆编号',
            'T10'=>'司机姓名',
            'T11'=>'生产人员',
            'D1'=>['design'=>'680','actual'=>'660'],
            'D2'=>['design'=>'600','actual'=>'600'],
            'D3'=>['design'=>'700','actual'=>'700'],
            'D4'=>['design'=>'650','actual'=>'650'],
            'D5'=>['design'=>'660','actual'=>'660'],
            'D6'=>['design'=>'600','actual'=>'600'],
            'D7'=>['design'=>'500','actual'=>'500'],
            'D8'=>['design'=>'505','actual'=>'505'],
            'D9'=>['design'=>'510','actual'=>'510'],
            'D10'=>['design'=>'800','actual'=>'800'],
            'D11'=>['design'=>'800','actual'=>'800'],
            'D12'=>['design'=>'660','actual'=>'660'],
            'D13'=>['design'=>'780','actual'=>'780'],
            'D14'=>['design'=>'660','actual'=>'660'],
            'D15'=>['design'=>'560','actual'=>'560'],
            'D16'=>['design'=>'650','actual'=>'651'],
            'D17'=>['design'=>'660','actual'=>'660'],
            'D18'=>['design'=>'800','actual'=>'801'],
            ];
        return json_encode($data);
    }

    public function getLab(){
        /*水泥胶砂 $device_id=10
        $data = [
            'T1'=>date('YmdHis'),//'20171013101314',
            'T2'=>'实验编号',
            'T3'=>'实验单位',
            'T4'=>'监理单位',
            'T5'=>'委托单位',
            'T6'=>'混凝土抗压',
            'T7'=>'实验组号',
            'T8'=>'试验品种',
            'T9'=>'试验龄期（天）',
            'T10'=>'M10',
            'T11'=>'实验人员',
            'T12'=>'类别牌号',
            'T13'=>'试件规格',
            'D1'=>3,
            'D2'=>'0.8',
            'D3'=>'80000',
            'D4'=>'',
            'D5'=>['value'=>'660','intensity'=>'10.5'],
            'D6'=>['value'=>'650','intensity'=>'12.4'],
            'D7'=>['value'=>'500','intensity'=>'8.4'],
            'D8'=>['value'=>'','intensity'=>''],
            'D9'=>['value'=>'','intensity'=>''],
            'D10'=>['value'=>'','intensity'=>''],
            'D11'=>'下屈服强度',
            'D12'=>'抗拉强度',
            'D13'=>'下屈服力值',
            //'D14'=>'下屈服强度',
            'D14'=>'极限载荷',
            'D15'=>'极限强度',
            'image'=>'R0lGODlhHAAmAKIHAKqqqsvLy0hISObm5vf394uLiwAAAP///yH5B…EoqQqJKAIBaQOVKHAXr3t7txgBjboSvB8EpLoFZywOAo3LFE5lYs/QW9LT1TRk1V7S2xYJADs='
            ];
            //src="data:image/png;base64,{{ $value['level_img'] }}"
        */

        //混凝土抗压/抗折 $device_id=9
        /*$data = [
            'T1'=>date('YmdHis'),//'20171013101314',
            'T2'=>'实验编号',
            'T3'=>'实验单位',
            'T4'=>'监理单位',
            'T5'=>'委托单位',
            'T6'=>'混凝土抗折',
            'T7'=>'实验组号',
            'T8'=>'C20',
            'T9'=>'试验龄期（天）',
            'T10'=>'C20',
            'T11'=>'实验人员',
            'T12'=>'类别牌号',
            'T13'=>'100*100',
            'D1'=>3,
            'D2'=>'4',
            'D3'=>'80000',
            'D4'=>'',
            'D5'=>['value'=>'660','intensity'=>'21'],
            'D6'=>['value'=>'650','intensity'=>'19'],
            'D7'=>['value'=>'500','intensity'=>'20'],
            'D8'=>['value'=>'','intensity'=>''],
            'D9'=>['value'=>'','intensity'=>''],
            'D10'=>['value'=>'','intensity'=>''],
            'D11'=>'下屈服强度',
            'D12'=>'抗拉强度',
            'D13'=>'下屈服力值',
            //'D14'=>'下屈服强度',
            'D14'=>'极限载荷',
            'D15'=>'',
            'image'=>'R0lGODlhHAAmAKIHAKqqqsvLy0hISObm5vf394uLiwAAAP///yH5B…EoqQqJKAIBaQOVKHAXr3t7txgBjboSvB8EpLoFZywOAo3LFE5lYs/QW9LT1TRk1V7S2xYJADs='
            ];
            //src="data:image/png;base64,{{ $value['level_img'] }}"
        */
        //水泥胶砂抗压/抗折 $device_id=8
        /*$data = [
            'T1'=>date('YmdHis'),//'20171013101314',
            'T2'=>'实验编号',
            'T3'=>'实验单位',
            'T4'=>'监理单位',
            'T5'=>'委托单位',
            'T6'=>'水泥胶砂抗压',
            'T7'=>'实验组号',
            'T8'=>'P.II',
            'T9'=>'28',
            'T10'=>'52.5',
            'T11'=>'实验人员',
            'T12'=>'类别牌号',
            'T13'=>'40*100',
            'D1'=>3,
            'D2'=>'50',
            'D3'=>'80000',
            'D4'=>'',
            'D5'=>['value'=>'660','intensity'=>'57'],
            'D6'=>['value'=>'650','intensity'=>'59'],
            'D7'=>['value'=>'500','intensity'=>'50'],
            'D8'=>['value'=>'660','intensity'=>'53'],
            'D9'=>['value'=>'660','intensity'=>'52'],
            'D10'=>['value'=>'660','intensity'=>'51'],
            'D11'=>'下屈服强度',
            'D12'=>'抗拉强度',
            'D13'=>'下屈服力值',
            //'D14'=>'下屈服强度',
            'D14'=>'极限载荷',
            'D15'=>'',
            'image'=>'R0lGODlhHAAmAKIHAKqqqsvLy0hISObm5vf394uLiwAAAP///yH5B…EoqQqJKAIBaQOVKHAXr3t7txgBjboSvB8EpLoFZywOAo3LFE5lYs/QW9LT1TRk1V7S2xYJADs='
            ];
            //src="data:image/png;base64,{{ $value['level_img'] }}"
          */  
        //热轧光圆钢筋/预应力筋 $device_id=10
        $data = [
            'T1'=>date('YmdHis'),//'20171013101314',
            'T2'=>'实验编号',
            'T3'=>'实验单位',
            'T4'=>'监理单位',
            'T5'=>'委托单位',
            'T6'=>'热轧光圆钢筋',
            'T7'=>'实验组号',
            'T8'=>'HRB400',
            'T9'=>'28',
            'T10'=>'HRB400',
            'T11'=>'实验人员',
            'T12'=>'HRB400',
            'T13'=>'HRB400',
            'D1'=>3,
            'D2'=>'50',
            'D3'=>'80000',
            'D4'=>'',
            'D5'=>['value'=>'660','intensity'=>'400','load'=>120,'strength'=>540],
            'D6'=>['value'=>'650','intensity'=>'410','load'=>120,'strength'=>550],
            'D7'=>['value'=>'500','intensity'=>'390','load'=>120,'strength'=>550],
            'D8'=>['value'=>'','intensity'=>''],
            'D9'=>['value'=>'','intensity'=>''],
            'D10'=>['value'=>'','intensity'=>''],
            'D11'=>'下屈服强度',
            'D12'=>'抗拉强度',
            'D13'=>'下屈服力值',
            //'D14'=>'下屈服强度',
            'D14'=>'极限载荷',
            'D15'=>'',
            'image'=>''
            ];
            //src="data:image/png;base64,{{ $value['level_img'] }}"
        return json_encode($data);
    }

    /**
     * 判断搅拌时间是否合格
     */
    protected function getStirTimeIsQualified($stir_time)
    {   //搅拌开始时间
        $kssj = $stir_time['kssj'];
        //搅拌结束时间
        $jssj = $stir_time['jssj'];

        //实际搅拌时间
        $actual_time = $jssj - $kssj;

        //最小搅拌时间
        $mix_stir_time = Config()->get('common.snbhz_device_mix_stir_time');

        //实际搅拌时间是否小于最小搅拌时间
        if ($actual_time < $mix_stir_time) {

            $stirTimeInfo['status']=1;
            $stirTimeInfo['warn_info']='搅拌时间不合格';

        } else {

            $stirTimeInfo['status']=0;
        }
        return $stirTimeInfo;

    }

    /**
     * 添加或更新本月设备生产次数及初级，高级报警次数
     * @param $SnbhzDeviceWarnNumberModel
     * @param $snbhz_info
     */
    protected function addProductionNumber($SnbhzDeviceWarnNumberModel, $snbhz_info)
    {
        //判断该条数据是不是有报警
        if ($snbhz_info['is_warn']) {
            //查询该设备该月是否已经有数据
            $res = $this->getProductionNumber($SnbhzDeviceWarnNumberModel, $snbhz_info);

            if ($res) {
                //更新
                $res->increment('production_num');

                if ($snbhz_info['warn_level'] == 1) {

                    $res->increment('cj_warn_num');

                } elseif ($snbhz_info['warn_level'] == 3) {

                    $res->increment('gj_warn_num');
                }

            } else {
                //插入
                $data = [
                    'project_id' => $snbhz_info['project_id'],
                    'supervision_id' => $snbhz_info['supervision_id'],
                    'section_id' => $snbhz_info['section_id'],
                    'device_id' => $snbhz_info['device_id'],
                    'device_cat' => $snbhz_info['device_cat'],
                    'production_num' => 1,
                    'cj_warn_num' => 0,
                    'gj_warn_num' => 0,
                    'month' => date('m', $snbhz_info['time']),
                    'year' => date('Y', $snbhz_info['time']),
                ];

                if ($snbhz_info['warn_level'] == 1) {
                    $data['cj_warn_num'] = 1;
                } elseif ($snbhz_info['warn_level'] == 3) {
                    $data['gj_warn_num'] = 1;
                }

                $SnbhzDeviceWarnNumberModel->create($data);
            }
        } else {
            //无报警
            //查询该设备该月是否已经有数据
            $res = $this->getProductionNumber($SnbhzDeviceWarnNumberModel, $snbhz_info);

            if ($res) {
                //更新
                $res->increment('production_num');

            } else {
                //插入
                $data = [
                    'project_id' => $snbhz_info['project_id'],
                    'supervision_id' => $snbhz_info['supervision_id'],
                    'section_id' => $snbhz_info['section_id'],
                    'device_id' => $snbhz_info['device_id'],
                    'device_cat' => $snbhz_info['device_cat'],
                    'production_num' => 1,
                    'cj_warn_num' => 0,
                    'gj_warn_num' => 0,
                    'month' => date('m', $snbhz_info['time']),
                    'year' => date('Y', $snbhz_info['time']),
                ];

                $SnbhzDeviceWarnNumberModel->create($data);

            }

        }
    }

    /**
     * 查询该设备在本月是否已经存在生产次数的数据
     */
    protected function getProductionNumber($model, $info)
    {
        $res = $model->where('project_id', $info['project_id'])
                     ->where('supervision_id', $info['supervision_id'])
                     ->where('section_id', $info['section_id'])
                     ->where('device_id', $info['device_id'])
                     ->where('device_cat', $info['device_cat'])
                     ->where('month', date('m', $info['time']))
                     ->where('year', date('Y', $info['time']))
                     ->first();

        return $res;

    }


    /**
     * 判断张拉数据是否合格
     */
    protected function getStretchWarn($data)
    {
        $is_warn = 0;
        $warn_info = '';
        //获取张拉数据报警标准
        $warn_standard = Config()->get('common.stretch_warn_standard');

        //延伸量偏差判断
        if (abs($data['D29']) > $warn_standard['elongation_max']) {
            $is_warn = 1;
            $warn_info = '延伸量偏差率不合格';
        }
        //持荷时间判断
        if ($data['D22'] < $warn_standard['hold_time_min']) {
            $is_warn = 1;
            if ($warn_info) {
                $warn_info = $warn_info . ',' . '持荷时间不合格';
            } else {
                $warn_info = '持荷时间不合格';
            }
        }

        $warn['is_warn']=$is_warn;
        $warn['warn_info']=$warn_info;

        return $warn;

    }

    /**
     * 张拉或压浆数据有报警时在报警统计表中写入数据
     */
    protected function createStatWarn($statWarnModel,$info_data,$module_name)
    {
        $product_time=strtotime(date('Y-m-d',$info_data['time']));
        //查询是否有当前设备当天生产时间范围内的报警统计数据
        $stat=$statWarnModel->where('project_id',$info_data['project_id'])
                            ->where('supervision_id',$info_data['supervision_id'])
                            ->where('section_id',$info_data['section_id'])
                            ->where('device_id',$info_data['device_id'])
                            ->where('beam_site_id',$info_data['beam_site_id'])
                            ->where('module_name',$module_name)
                            ->where('created_at','=',$product_time)

                            ->first();

        //当天该设备存在报警数据
        if ($stat) {
            $stat->warn_number=$stat->warn_number+1;
            $stat->updated_at=time();
            $stat->save();
        } else {
           //创建该设备今天的报警数据
            $data['project_id']=$info_data['project_id'];
            $data['supervision_id']=$info_data['supervision_id'];
            $data['section_id']=$info_data['section_id'];
            $data['beam_site_id']=$info_data['beam_site_id'];
            $data['device_id']=$info_data['device_id'];
            $data['module_name']=$module_name;
            $data['warn_number']=1;
            $data['created_at']=$product_time;
            $data['updated_at']='';

            $statWarnModel->create($data);
        }
    }

    /**
     * 张拉，压浆数据有报警数据时发送微信短信
     * 该功能微信短信模板暂时是使用的拌和站的微信短信报警推送模板，
     * 后期可根据实际需求进行修改
     */
    protected function sendNoticeInStretchMudjack($warnUserModel,$info_data,$detail_data,$module_name)
    {
        //获取要推送的人员
        $warn_user_data=$warnUserModel->select('user_id')
                                      ->with('user')
                                      ->where('project_id',$info_data['project_id'])
                                      ->where('supervision_id',$info_data['supervision_id'])
                                      ->where('section_id',$info_data['section_id'])
                                      ->where('module_name',$module_name)
                                      ->get();

        $device = Device::find($info_data['device_id']);

        $beam_site=BeamSite::find($info_data['beam_site_id']);

        if ($module_name == 'stretch') {
            $time = $detail_data['stretch_time'];
            $dcode = '张拉设备：' . $device->name;
            $first = '张拉设备发生报警';
            $url = url('stretch/warn_info');
            $warn_info=$info_data['girder_number'].$detail_data['pore_canal_name'].','.$detail_data['warn_info'];
        }

        if ($module_name == 'mudjack') {
            $time = $info_data['time'];
            $dcode = '压浆设备:' . $device->name;
            $first = '压浆设备发送报警';
            $url = url('mudjack/warn_info');
            $warn_info=$info_data['girder_number'].$detail_data['pore_canal_name'].','.$detail_data['warn_info'];
        }

        $smsParame = [
            'dcode' => $dcode,//.'-'.$dcode,
            'time' => date('Y-m-d H:i:s', $time),
            'level' => '',
            'info' => $warn_info
        ];

        $wechatParame = [
            'first' => $first,
            'time' => $time,
            'url' => $url
        ];

        if ($warn_user_data) {
            foreach ($warn_user_data as $value) {
                if (($value->user->phone)) {
                    $res = (new SendSms)->send($value->user->phone, $smsParame, 'SMS_122285132');
                    Log::info('sendStretchWarnNotice SendSms ' . $value->user->phone);
                    Log::info(json_encode($res));
                }

//                if ($value->user->openid) {
//                    $res = (new SendWechat)->sendBj($value->user->openid, $wechatParame);
//                    Log::info('sendStretchWarnNotice SendWechat');
//                    Log::info($res);
//                }


            }
        }

    }

    /**
     * 压浆，压浆数据中有些数据不能为空的判断
     *
     */
    protected function judgeMudjackData($post)
    {
        //对传入的梁号数据判断
        if (!isset($post['T1'])) {
            return ['status' => 2, 'message' => '梁号信息不能为空'];
        }

        //压浆开始时间
        if (!isset($post['D2'])) {

            return ['status' => 3, 'message' => '压浆开始时间不能为空'];
        } else {
            $g = "/^1\d{9}$/";

            if (!preg_match($g, $post['D2'])) {

                return ['status' => 4, 'message' => '压浆开始时间格式不正确'];
            }

        }

        //压浆结束时间
        if (!isset($post['D3'])) {

            return ['status' => 6, 'message' => '压浆结束时间不能为空'];
        } else {
            $g = "/^1\d{9}$/";

            if (!preg_match($g, $post['D3'])) {

                return ['status' => 7, 'message' => '压浆结束时间格式不正确'];
            }

        }

        //水胶比
        if (!isset($post['D9'])) {
            return ['status' => 9, 'message' => '水胶比信息不能为空'];
        }

        //进浆压力
        if (!isset($post['D5'])) {
            return ['status' => 10, 'message' => '进浆压力不能为空'];
        }
        //返浆压力
        if (!isset($post['D6'])) {
            return ['status' => 11, 'message' => '返浆压力不能为空'];
        }
        //保压时间
        if (!isset($post['D7'])) {
            return ['status' => 13, 'message' => '保压时间不能为空'];
        }
        //稳压值
        if (!isset($post['D8'])) {
            return ['status' => 14, 'message' => '稳压值不能为空'];
        }

    }


    /**
     * 添加压浆详情数据
     */
    protected function addMudjackDetail($post,$info,$mudjack_detail_model)
    {
        $detail_data = [
            'info_id' => $info->id,
            'pore_canal_name' => isset($post['D1']) ? $post['D1'] : '',
            'pore_canal_length' => isset($post['D4']) ? $post['D4'] : '',
            'start_time' => isset($post['D2']) ? $post['D2'] : '',
            'end_time' => isset($post['D3']) ? $post['D3'] : '',
            'enter_pressure' => isset($post['D5']) ? $post['D5'] : '',
            'out_pressure' => isset($post['D6']) ? $post['D6'] : '',
            'duration_time' => isset($post['D7']) ? $post['D7'] : '',
            'stabilize_pressure' => isset($post['D8']) ? $post['D8'] : '',
            'wcratio' => isset($post['D9']) ? $post['D9'] : '',
        ];


        //判断详情数据是否报警
        $warn_data=$this->getMudjackWarn($post);


        if ($warn_data['is_warn']) {


            $detail_data['is_warn'] = 1;
            $detail_data['warn_info'] = $warn_data['warn_info'];
            $detail_data['is_sec_deal'] = 0;
            $detail_data['is_sup_deal'] = 0;

            //写入或更新报警统计数据表
            $module_name = $this->yajiang_module_name;
            $info_data = [
                'project_id'=>$info->project_id,
                'supervision_id'=>$info->supervision_id,
                'section_id'=>$info->section_id,
                'beam_site_id'=>$info->beam_site_id,
                'device_id' => $info->device_id,
                'time'=>$post['D2'],
                'girder_number'=>$post['T1']
                ];
            $statWarnModel = new StretchMudjackStatWarn();
            $this->createStatWarn($statWarnModel,$info_data,$module_name);
            //推送短信微信报警
            $warnUserModel = new StretchMudjackWarnUser();

            $this->sendNoticeInStretchMudjack($warnUserModel,$info_data,$detail_data,$module_name);

        } else {
            $detail_data['is_warn']=0;
            $detail_data['warn_info']='';
            $detail_data['is_sec_deal']='';
            $detail_data['is_sup_deal']='';
        }
        $mudjack_detail_model->create($detail_data);
    }

    /**
     * 压浆，判断详情数据是否报警
     */
    protected function getMudjackWarn($post)
    {
       $warn=[
           'is_warn'=>0,
           'warn_info'=>'',
       ];

        /**
         * 关于报警判断依据(依照外环高速信息化管理办法20181116文档，详见文档)
         */

        //报警判别标准
        $standard = Config()->get('common.mudjack_warn_standard');

        //水胶比判断

        if ($post['D9'] < $standard['wcratio_min'] || $post['D9'] > $standard['wcratio_max']) {

            $warn['is_warn'] = 1;
            $warn['warn_info'] = $warn['warn_info'] . '水胶比不合格;';
        }

        //进浆压力判断
        if ($post['D5'] < $standard['enter_pressure_min'] || $post['D5'] > $standard['enter_pressure_max']) {

            $warn['is_warn'] = 1;
            $warn['warn_info'] = $warn['warn_info'] . '进浆压力不合格;';
        }

        //保压时间
        /**
         * 关于保压时间
         * 数据是否合格的判断依据是依照外环高速信息化系统管理办法20181116文档中的压浆报警依据(详见文档)
         * 保压时间要依照孔长度，但现阶段没有设备，无法确定设备端是否能够提供孔长度数据，故该项判断取最大值
         * 后期有设备之后根据实际情况修改
         */
        if ($post['D7'] < $standard['duration_time_min']) {
            $warn['is_warn'] = 1;
            $warn['warn_info'] = $warn['warn_info'] . '保压时间不合格;';
        }

        //稳压值
        if ($post['D8'] != $standard['stabilize_pressure']) {
            $warn['is_warn'] = 1;
            $warn['warn_info'] = $warn['warn_info'] . '稳压值不合格;';
        }

        if ($post['D5'] > $standard['pressure_max']) {
            $warn['is_warn'] = 1;
            $warn['warn_info'] = $warn['warn_info'] . '进浆压力超过最大压力限值;';
        }

        if ($post['D6'] > $standard['pressure_max']) {
            $warn['is_warn'] = 1;
            $warn['warn_info'] = $warn['warn_info'] . '返浆压力超过最大压力限值;';
        }

        return $warn;
    }





}