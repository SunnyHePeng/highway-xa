<?php

namespace App\Http\Controllers\Api;

use App\Models\Mudjack\MudjackInfo;
use App\Models\Mudjack\MudjackInfoDetail;
use App\Models\Stretch\StretchInfo;
use App\Models\Stretch\StretchInfoDetail;
use App\Models\Stretch\StretchMudjackStatWarn;
use App\Models\Stretch\StretchMudjackWarnUser;
use App\Models\Zhangla\Zhangla_info_detail;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests;
use Input, Log, Cache;
use App\Models\Device\Device,
    App\Models\Project\Project,
    App\Models\Project\Section,
    App\Models\Bhz\MessageUser,
    App\Models\Bhz\Snbhz_info,
    App\Models\Lab\Lab_info,
    App\Models\Zhangla\Zhangla_info,
    App\Models\YaJiang\Yajiang_info,
    App\Models\Smog\WasteWaterTreatmentInfo,
    App\Models\YaJiang\Yajiang_info_detail;
use App\Http\Middleware\Checkheader;
use Mockery\Exception;
use App\Models\BeamSpray\BeamInfo;
use App\Models\BeamSpray\BeamSprayRecord;
use App\Models\Smog\Environment;
use App\Send\SendSms,
    App\Send\SendWechat;
use App\Models\Bhz\SnbhzDeviceWarnNumber;

class ApiController extends BaseController
{
    /*张拉模块名*/
   protected $zhangla_module_name='stretch';
   /*压浆模块名称*/
   protected $yajiang_module_name='mudjack';

    public function index(Request $request){
        $station_code = $request->header('Station-Code');
        $device_id = Device::where('dcode',$station_code)->pluck('id');
        $package_type = $request->header('Package-Type');

        if(!$device_id)
            return $this->errorMsg('no_device');
        //Log::info('station_code: '.$station_code);
        //Log::info('device_id: '.$device_id);
        //Log::info('package_type: '.$package_type);
        $data = '';
        switch ($package_type) {
            case 'keep-alive':
                $data = $this->keepAlive($device_id);
                break;
            case 'lab-keep-alive':
                $data = $this->labKeepAlive($device_id);
                break;
            case 'cement':
                $data = $this->cement($device_id);
                break;
            case 'test':
                $data = $this->test($device_id);
                break;
            case 'lab-warn':
                $data = $this->labWarn($device_id);
                break;
            case 'mudjack_upload'://压浆数据采集
                $data = $this->createMudjack($request,$device_id);
                break;
            case 'spray'://喷淋养生数据采集
                $data = $this->spray($request, $device_id);
                break;
            case 'environment'://环境监测数据
                $data = $this->createEnvironment($request, $device_id);
                break;
            case 'waste_water':  //污水处理数据
                $data=$this->createWasteWater($request,$device_id);
                break;
                //张拉数据采集
            case 'stretch_upload':
                $data=$this->createStretch($request,$device_id);
            default:
                # code...
                break;
        }
        //Log::info('data: ');
        //Log::info($data);
        return Response()->json($data);

        //$function_name = $this->getFuncName($package_type);
        //return $this->$function_name($device_id);
    }

    /*保存设备状态 存入缓存中
    *
    *缓存1分钟  然后没有收到心跳包默认不在线
    */
    public function keepAlive($device_id){
        $post = file_get_contents("php://input");
        if(!$post){
            $status = [
                    'J1'=>0,
                    'J2'=>0,
                    'J3'=>0,
                    'J4'=>0,
                    ];
            $post = json_encode($status);
        }else{
            Cache::forever('device_status_time_'.$device_id, time());
        }

        Cache::put('device_status_'.$device_id, $post, 1);

        $result = ['status'=>0];
        return $result;
    }

    public function labKeepAlive($device_id){
        $post = file_get_contents("php://input");
        //Log::info("lab device status: ".$post);

        $status = Cache::get('device_status_'.$device_id);
        if($status){
            $status = json_decode($status, true);
        }else{
            $status = [
                'J1'=>0,
                'J2'=>0,
                'J3'=>0,
                'J4'=>0,
                ];
        }
        if($post){
            $post = json_decode($post, true);
            if(isset($post['J5'])){
                Cache::put('device_status_J5_'.$device_id, $post['J5'], 60);
            }

            if (isset($post['J1']) || isset($post['J2']) || isset($post['J3']) || isset($post['J4'])) {
                $status = json_encode(array_merge($status, $post));
                //Log::info('merged status: '.$status);
                Cache::put('device_status_'.$device_id, $status, 1);
                Cache::forever('device_status_time_'.$device_id, time());
            }
        }

        $result = ['status'=>0];
        return $result;
    }
    /*
    *水泥拌合站采集数据
    */
    public function cement($device_id){
        if(!$device_id){
            $result = $this->errorMsg('no_device');
            return $result;
        }
        $post = file_get_contents("php://input");
        $post = json_decode($post, true);

        //根据设备id 获取设备信息 项目id 标段id
        $device_info = Device::select(['id','project_id','supervision_id','section_id','cat_id','name','dcode'])
                             ->where('id', $device_id)
                             ->first()
                             ->toArray();

        if(!$device_info){
            $result = $this->errorMsg('no_device');
            return $result;
        }

        try{
            //根据D0获取拌和开始时间，结束时间，T1拌和时间改为结束时间
            //D0格式 2018-02-26 09:10:38 878,2018-02-26 09:12:38 878;2018-02-26 09:10:38 878,2018-02-26 09:12:38 878;1899-12-30 00:00:00 000,1899-12-30 00:00:00 000;1899-12-30 00:00:00 000,1899-12-30 00:00:00 000
            $d0 = explode(';', $post['D0']);
            $d0 = explode(',', $d0[0]); //2018-02-26 09:10:38 878,2018-02-26 09:12:38 878
            $kssj = explode(' ', $d0[0]);
            $jssj = explode(' ', $d0[1]);
            $kssj = strtotime($kssj[0].$kssj[1]);
            $jssj = strtotime($jssj[0].$jssj[1]);
            $time = $jssj;//strtotime('20'.$post['T1']);

        }catch (\Exception $e){

            return ['status' => 9, 'message' => '时间参数有误'];
        }

        //根据拌和时间 项目 标段 判断数据是否已存在
        $is_has = Snbhz_info::where('time', $time)
                            ->where('project_id', $device_info['project_id'])
                            ->where('section_id', $device_info['section_id'])
                            ->where('device_id',$device_id)
                            ->first();
        if($is_has){
            $result = ['status'=>0];
            return $result;
        }
        $snbhz_info = [
                'project_id'=>$device_info['project_id'],
                'section_id'=>$device_info['section_id'],
                'supervision_id'=>$device_info['supervision_id'],
                'device_cat'=>$device_info['cat_id'],
                'device_id'=>$device_info['id'],
                'time'=>$time,
                'scdw'=>$post['T2'],
                'jldw'=>$post['T3'],
                'sgdd'=>$post['T8'],
                'jzbw'=>$post['T7'],
                'pfl'=>$post['T5'],
                'pbbh'=>$post['T6'],
                'cph'=>$post['T9'],
                'driver'=>$post['T10'],
                'operator'=>$post['T11'],
                'kssj'=>$kssj,
                'jssj'=>$jssj,
                'is_warn'=>0,
                'warn_info'=>'',
                'created_at'=>time(),
            ];
        $stir_time['kssj']=$kssj;
        $stir_time['jssj']=$jssj;
        $snbhz_info_detail = $this->getSnbhzInfoDetail($post,$stir_time);
        $snbhz_info = array_merge($snbhz_info, $snbhz_info_detail['snbhz_info']);
//        dd($snbhz_info);
        try {
            //更新最新上报时间
            Device::where('id', $device_id)->update(['last_time'=>$time]);
            //添加拌合信息
            $res = Snbhz_info::create($snbhz_info);

            //添加拌合物料信息
            $this->addSnbhzInfoDetail($res->id, $snbhz_info_detail['snbhz_info_detail']);

            //添加拌合总量
            $this->addSnbhzTotalInfo($snbhz_info_detail['total'], $device_info);

            $SnbhzDeviceWarnNumberModel=new SnbhzDeviceWarnNumber();

            //添加或更新记录的拌和设备本月生产次数与报警次数数据
            $this->addProductionNumber($SnbhzDeviceWarnNumberModel,$snbhz_info);

            //添加拌合报警信息 发送报警通知
            if($snbhz_info['is_warn']){
                //$this->addSnbhzWarnInfo($snbhz_info_detail['warn_info'], $res->id, $device_info);
                //如果上传的数据有报警，
                //上传过来的生产时间和当前时间差24小时以上，向长安大学相关人员推送通知
                if($time+86400<time()){
                       $this->sendRetardWarnNotice($snbhz_info);
                }

                $snbhz_info['id'] = $res->id;
                $this->sendBhzWarnNotice($snbhz_info, $device_info['dcode'], $device_info['name']);

            }
            $result = ['status'=>0];
        }catch(\Expection $e){
            $result = $this->errorMsg('snbhz_info');
        }
        return $result;
    }


    /*
    *实验室采集数据
    */
    public function test($device_id=8){
        if(!$device_id){
            $result = $this->errorMsg('no_device');
            return $result;
        }
        $post = $this->getLab();
        return $post;
        $post = json_decode($post, true);
        //$post = json_decode(Input::get('data'), true);
        //根据设备id 获取设备信息 项目id 标段id
        $device_info = Device::select(['id','project_id','supervision_id','section_id','cat_id'])
                             ->where('id', $device_id)
                             ->first()
                             ->toArray();

        if(!$device_info){
            $result = $this->errorMsg('no_device');
            return $result;
        }

        $time = strtotime($post['T1']);
        $lab_info = [
                'project_id'=>$device_info['project_id'],
                'section_id'=>$device_info['section_id'],
                'supervision_id'=>$device_info['supervision_id'],
                'device_cat'=>$device_info['cat_id'],
                'device_id'=>$device_info['id'],
                'time'=>$time,
                'sybh'=>$post['T2'],
                'sydw'=>$post['T3'],
                'jldw'=>$post['T4'],
                'wtdw'=>$post['T5'],
                'sylx'=>$post['T6'],
                'syzh'=>$post['T7'],
                'sypz'=>$post['T8'],
                'sylq'=>$post['T9'],
                'qddj'=>$post['T10'],
                'syry'=>$post['T11'],
                'lbph'=>$post['T12'],
                'sjgg'=>$post['T13'],
                'sjgs'=>$post['D1'],
                'jzsl'=>$post['D2'],
                'yxlz'=>$post['D3'],
                'yxqd'=>$post['D4'],
                'xqfqd'=>$post['D11'],
                'klqd'=>$post['D12'],
                'xqflz'=>$post['D13'],
                'jxzh'=>$post['D14'],
                'jxqd'=>$post['D15'],
                'image'=>$post['image'],
                'is_warn'=>0,
                'warn_info'=>'',
            ];

        //获取实验试件信息
        $lab_info_detail = $this->getLabInfoDetail($post);

        //根据实验类型判断是否合格
        $lab_warn = new LabWarnController;
        $warn_info = $lab_warn->getWarnInfo($post, $lab_info_detail);
        //钢筋实验需要另外添加单个试件合格信息 所有试件信息重新获取
        if(isset($lab_info_detail[0]['jxqd']) || isset($lab_info_detail[0]['jxhz'])){
            $lab_info_detail = $warn_info['detail'];
            $warn_info = $warn_info['warn'];
        }
        $lab_info = array_merge($lab_info, $warn_info);

        try {
            //更新最新上报时间
            Device::where('id', $device_id)->update(['last_time'=>$time]);
            //添加实验信息
            $res = Lab_info::create($lab_info);
            //添加实验物料信息
            $this->addLabInfoDetail($res->id, $lab_info_detail);
            //添加实验报警信息
            if($lab_info['is_warn']){
                $this->addLabWarnInfo($lab_info['sylx'], $lab_info['time'], $warn_info, $res->id, $device_info);
            }
            $result = ['status'=>0];
        }catch(\Expection $e){
            $result = $this->errorMsg('lab_info');
        }
        return $result;
    }

        /*试验室报警数据信息*/
    public function labWarn($device_id){
        if(!$device_id){
            $result = $this->errorMsg('no_device');
            return $result;
        }

        $post = file_get_contents("php://input");
        if(!$post){
            $result = $this->errorMsg('lab_warn');
            return $result;
        }
        //Log::info($post);
        $post = json_decode($post, true);
        Log::info('labWarn');
        Log::info($post);

        $info = Device::select(['project_id','supervision_id','section_id','name'])
                        ->where('id', $device_id)
                        ->first()
                        ->toArray();

        //添加报警数据
        //$this->addLabWarnTotal($device_id, $info);
        //发送报警数据
        $this->sendLabWarnNotice($post, $info);

        $result = ['status'=>0];
        return $result;
    }


    /**
     * 喷淋养生数据采集
     */
    protected function spray($request,$device_id)
    {
        $device = Device::find($device_id);
//        dd($device_id);
        $section_id=$device->section_id;
        $project_id=$device->project_id;
        $supervision_id=$device->supervision_id;
        $beam_site_id=$device->beam_site_id;

        $post = file_get_contents("php://input");
        $post = json_decode($post, true);
        $beam_num=isset($post['T3']) ? $post['T3'] : '';



        $already_beam_info=BeamInfo::where('project_id',$project_id)
            ->where('supervision_id',$supervision_id)
            ->where('section_id',$section_id)
            ->where('beam_site_id',$beam_site_id)
            ->where('device_id',$device_id)
            ->where('beam_num',$beam_num)
            ->first();

        if($already_beam_info){
            $beam_info_id=$already_beam_info->id;
            try{
                $already_beam_info->project_name=isset($post['T1']) ? $post['T1'] : '';
                $already_beam_info->project_place=isset($post['T2']) ? $post['T2'] : '';
                $already_beam_info->start_time=isset($post['T4']) ? $post['T4'] : '';
                $already_beam_info->start_timestamp=isset($post['T4']) ? strtotime($post['T4']) : '';
                $already_beam_info->end_time=isset($post['T5']) ? $post['T5'] : '';
                $already_beam_info->end_timestamp=isset($post['T5']) ? strtotime($post['T5']) : '';
                $already_beam_info->days_spend=isset($post['N1']) ? $post['N1'] : 0;
                $already_beam_info->time_count=isset($post['N2']) ? $post['N2'] : 0;
                $already_beam_info->is_finish=isset($post['N7']) ? $post['N7'] : 0;

                $already_beam_info->save();
            }catch (\Exception $e){
                \Log::warning([$e->getMessage(), __FILE__]);
                \Log::info($request);
                return ['status' => 1, 'message' => '喷淋数据添加失败'];
            }

        }else{

            try{
                $beam=BeamInfo::create([
                    'project_id'=>$project_id,
                    'supervision_id'=>$supervision_id,
                    'section_id'=>$section_id,
                    'device_id'=>$device_id,
                    'beam_site_id'=>$beam_site_id,
                    'project_name'=>isset($post['T1']) ? $post['T1'] : '',
                    'project_place'=>isset($post['T2']) ? $post['T2'] : '',
                    'beam_num'=>isset($post['T3']) ? $post['T3'] : '',
                    'start_time'=>isset($post['T4']) ? $post['T4'] : '',
                    'start_timestamp'=>isset($post['T4']) ? strtotime($post['T4']) : '',
                    'end_time'=>isset($post['T5']) ? $post['T5'] : '',
                    'end_timestamp'=>isset($post['T5']) ? strtotime($post['T5']) : '',
                    'days_spend'=>isset($post['N1']) ? $post['N1'] : '',
                    'time_count'=>isset($post['N2']) ? $post['N2'] : '',
                    'is_finish'=>isset($post['N7']) ? $post['N7'] : '',
                    'type' => isset($post['N8']) ? $post['N8'] : '',
                    'seat_number'=>isset($post['T8']) ? $post['T8'] : '',
                ]);
                $beam_info_id=$beam->id;

            }catch (\Exception $e){
                \Log::warning([$e->getMessage(), __FILE__]);
                \Log::info($request);
                return ['status' => 1, 'message' => '喷淋数据添加失败'];
            }
        }

        try{
            BeamSprayRecord::create([
                'beam_info_id' => $beam_info_id,
                'start_time' => isset($post['T6']) ? $post['T6'] : '',
                'start_timestamp'=>isset($post['T6']) ? strtotime($post['T6']) : '',
                'end_time' => isset($post['T7']) ? $post['T7'] : '',
                'end_timestamp'=>isset($post['T7']) ? strtotime($post['T7']) : '',
                'time_count' => isset($post['N3']) ? $post['N3'] : 0,
                'time_interval' => isset($post['N4']) ? $post['N4'] : 0,
                'temperature' => isset($post['N5']) ? $post['N5'] : '',
                'moisture' => isset($post['N6']) ? $post['N6'] : '',
            ]);

        } catch (\Exception $e) {

            \Log::warning([$e->getMessage(), __FILE__]);
            \Log::info($request);
            return ['status' => 1, 'message' => '喷淋添加失败'];
        }

        return ['status' => 0];
    }

    /**
     * 添加环境监测数据
     *
     * @param \Illuminate\Http\Request $request
     * @param int $device_id
     * @return array
     */
    protected function createEnvironment($request, $device_id)
    {
        $device = Device::find($device_id);

        try{
            $supervision = \DB::table('supervision_section')->where('section_id', '=', $device->section_id)->first();

            $post = file_get_contents("php://input");
//            Log::info('environment start');
//            Log::info($post);
//            Log::info('environment end');
            $post = json_decode($post, true);

            if (empty($post)) {
                throw new \Exception('数据格式有误！');
            }

            Environment::create([
                'project_id' => $device->project_id,
                'section_id' => $device->section_id,
                'supervision_id' => $supervision->supervision_id,
                'device_id' => $device->id,
                'pm25' => isset($post['N1']) ? $post['N1'] : 0,
                'pm10' => isset($post['N2']) ? $post['N2'] : 0,
                'temperature' => isset($post['N3']) ? $post['N3'] : 0,
                'moisture' => isset($post['N4']) ? $post['N4'] : 0,
                'noise' => isset($post['N5']) ? $post['N5'] : 0,
                'datetime' => isset($post['T1']) ? $post['T1'] : '',
                'time'=>isset($post['T1']) ? strtotime($post['T1']):'',
                'place' => isset($post['T2']) ? $post['T2'] : '',
            ]);

        } catch (\Exception $e) {

            \Log::warning([$e->getMessage(), __FILE__]);
            \Log::info($request);
            return ['status' => 1, 'message' => '上传数据失败'];
        }

        return ['status' => 0, 'message' => '上传数据成功'];
    }

    /**
     * 污水处理数据采集
     * @param $request
     * @param $device_id
     */
    protected function createWasteWater($request,$device_id)
    {
        $device = Device::find($device_id);

        $project_id=$device->project_id;

        $supervision_id=$device->supervision_id;

        $section_id=$device->section_id;

        $post = file_get_contents("php://input");
        $post = json_decode($post, true);

        if(empty($post) || !is_array($post)){
            $error_result['status']=1;
            $error_result['info']='数据格式有误';
            return $error_result;
        }
        $data=[
          'project_id'=>$project_id,
          'supervision_id'=>$supervision_id,
          'section_id'=>$section_id,
          'device_id'=>$device_id,
          'time'=>array_key_exists('time',$post) ? $post['time'] : null,
          'enter_instantaneous_flow'=>array_key_exists('enter_instantaneous_flow',$post) ? $post['enter_instantaneous_flow'] : null,
          'exit_instantaneous_flow'=>array_key_exists('exit_instantaneous_flow',$post) ? $post['exit_instantaneous_flow'] : null,
          'voltage'=>array_key_exists('voltage',$post) ? $post['voltage'] : null,
          'water_temperature'=>array_key_exists('water_temperature',$post) ? $post['water_temperature'] : null,
          'enter_BOD'=>array_key_exists('enter_BOD',$post) ? $post['enter_BOD'] : null,
          'exit_BOD'=>array_key_exists('exit_BOD',$post) ? $post['exit_BOD'] : null,
          'pH'=>array_key_exists('pH',$post) ? $post['pH'] : null,
          'chrominance'=>array_key_exists('chrominance',$post) ? $post['chrominance'] : null,
          'place'=>array_key_exists('place',$post) ? $post['place'] : '',
          'created_at'=>time(),
        ];
//        dd($data);

        try{
           WasteWaterTreatmentInfo::create($data);
           $result['status']=0;
           $result['info']='上传成功';
           return $result;
        }catch (Exception $e){
            Log::info($post);
            $result['status']=1;
            $result['info']='上传失败';
            return $result;
        }
    }

    /**
     * 张拉数据采集
     */
    public function createStretch($request,$device_id)
    {
       $device_model=new Device();
       $stretch_model=new StretchInfo();
       $stretch_detail_model=new StretchInfoDetail();


        if (!$device_id) {
            $result = $this->errorMsg('no_device');
            return $result;
        }

        $post = file_get_contents("php://input");
        $post = json_decode($post, true);

        //获取该上传数据设备的信息
        $device_info = $device_model->find($device_id);

        //更新设备数据上传时间
        $device_info->last_time=time();
        $device_info->save();

        //判断上传的数据中是否有梁号信息或梁号数据为空
        if (!isset($post['T1'])) {
            return ['status' => 1, 'message' => '梁号不能为空'];
        }
        //张拉时间
        if(!isset($post['T2'])){

            $g = "/^1\d{9}$/";


            return ['status'=>2,'message'=>'张拉时间不能为空'];
        }else{
            $g = "/^1\d{9}$/";

            if(!preg_match($g,$post['T2'])){

                return ['status'=>3,'message'=>'张拉时间格式不正确'];
            }

        }

        //持荷时间
        if (!isset($post['D22'])) {
              return ['status'=>4,'message'=>'持荷时间不能为空'];
        }

        //延伸量偏差率
        if (!isset($post['D29'])) {
            return ['status'=>6,'message'=>'延伸量误差不能为空'];
        }




        //根据梁号及设备信息查找该梁信息是否有上传，如没有则创键


        $has_stretch_data = $stretch_model->select('id')
                                        ->where('girder_number',$post['T1'])
                                        ->where('device_id',$device_info['id'])
                                        ->first();

        if (!$has_stretch_data) {

            try{

                //创建
                $info_data=[
                    'project_id'=>$device_info->project_id,
                    'supervision_id'=>$device_info->supervision_id,
                    'section_id'=>$device_info->section_id,
                    'beam_site_id'=>$device_info->beam_site_id,
                    'device_id'=>$device_info->id,
                    'girder_number'=>isset($post['T1']) ? $post['T1'] : '',
                    'time'=>isset($post['T2']) ? $post['T2'] : '',
                    'stretch_unit'=>isset($post['T3']) ? $post['T3'] : '',
                    'supervisor_unit'=>isset($post['T4']) ? $post['T4'] : '',
                    'concrete_design_intensity'=>isset($post['T6']) ? $post['T6'] : '',
                    'concrete_reality_intensity'=>isset($post['T7']) ? $post['T7'] : '',
                    'stretch_order'=>isset($post['T8']) ? $post['T8'] : '',
                    'engineering_name'=>isset($post['T9']) ? $post['T9'] : '',
                    'precasting_yard'=>isset($post['T10']) ? $post['T10'] : '',
                    'stretch_craft'=>isset($post['T11']) ? $post['T11'] : '',
                    'component_type'=>isset($post['T13']) ? $post['T13'] : '',
                    'created_at'=>time(),
                ];

                $stretch_new_data=$stretch_model->create($info_data);



                $stretch_info_id=$stretch_new_data->id;


            }catch (Exception $e){

                return $this->errorMsg('zhangla_info');
            }


        } else {


            $stretch_info_id = $has_stretch_data->id;

        }

        $stretch_info=[
            'project_id'=>$device_info->project_id,
            'supervision_id'=>$device_info->supervision_id,
            'section_id'=>$device_info->section_id,
            'beam_site_id'=>$device_info->beam_site_id,
            'device_id'=>$device_info->id,
            'girder_number'=>isset($post['T1']) ? $post['T1'] : '',
            'time'=>isset($post['T2']) ? $post['T2'] : '',
        ];


        //判断数据是否合格
        $warn=$this->getStretchWarn($post);

        $detail_data = [
            'info_id' => $stretch_info_id,
            'stretch_time' => $post['T2'],
            'pore_canal_name' => $post['D0'],
            'steel_strand_number' => $post['D1'],
            'init_stroke_force1' => $post['D2'],
            'init_stroke_length1' => $post['D3'],
            'init_stroke_force2' => $post['D4'],
            'init_stroke_length2' => $post['D5'],
            'first_stroke_force1' => $post['D6'],
            'first_stroke_length1' => $post['D7'],
            'first_stroke_force2' => $post['D8'],
            'first_stroke_length2' => $post['D9'],
            'second_stroke_force1' => $post['D10'],
            'second_stroke_length1' => $post['D11'],
            'second_stroke_force2' => $post['D12'],
            'second_stroke_length2' => $post['D13'],
            'third_stroke_force1' => $post['D14'],
            'third_stroke_length1' => $post['D15'],
            'third_stroke_force2' => $post['D16'],
            'third_stroke_length2' => $post['D17'],
            'fourth_stroke_force1' => $post['D18'],
            'fourth_stroke_length1' => $post['D19'],
            'fourth_stroke_force2' => $post['D20'],
            'fourth_stroke_length2' => $post['D21'],
            'hold_time' => $post['D22'],
            'rebound1' => $post['D23'],
            'rebound2' => $post['D24'],
            'design_stretch_force' => $post['D25'],
            'design_elongation' => $post['D27'],
            'reality_elongation' => $post['D28'],
            'elongation_deviation' => $post['D29'],
            'is_warn' => $warn['is_warn'],
            'warn_info' => $warn['warn_info'],
        ];

        //添加详情数据
        try {

            $stretch_detail_model->create($detail_data);
            //判断是否是报警数据，如是，推送报警信息（暂留，待报警推送人员设置开发完成）
            if($warn['is_warn']){
                /*设备报警统计数据*/
                $statWarnModel=new StretchMudjackStatWarn();
                //有报警数据，增加或更新报警统计数据
                $this->createStatWarn($statWarnModel,$stretch_info,$this->zhangla_module_name);
                /*
                 * 微信，短信报警消息推送
                 * 暂时使用拌合站的微信，短信报警模板
                 * 拌合站微信短信模板没有梁场信息
                 * 后期根据需求修改
                 */
                $warnUserModel=new StretchMudjackWarnUser();

                $this->sendNoticeInStretchMudjack($warnUserModel,$stretch_info,$detail_data,$this->zhangla_module_name);

            }

            return ['status'=>0];

        } catch (Exception $e) {

            return $this->errorMsg('zhangla_info');
        }



    }

    /**
     * 压浆数据采集
     */
    public function createMudjack($request,$device_id)
    {
        $device_model = new Device();
        $mudjack_model = new MudjackInfo();
        $mudjack_detail_model = new MudjackInfoDetail();

        if (!$device_id) {
            $result = $this->errorMsg('no_device');
            return $result;
        }

        try {
            $post = file_get_contents("php://input");
            $post = json_decode($post, true);
        } catch (Exception $e) {
            return ['status' => 1, 'message' => '传入数据格式错误'];
        }


        //获取该上传数据设备的信息
        $device_info = $device_model->find($device_id);

        //更新设备数据上传时间
        $device_info->last_time = time();
        $device_info->save();

        //对上传过来的数据中有些必须的数据的判断
        $judge = $this->judgeMudjackData($post);

        if (isset($judge['status'])) {

            return $judge;
        }

        $hasInfo = $mudjack_model->where('project_id',$device_info->project_id)
                               ->where('supervision_id',$device_info->supervision_id)
                               ->where('section_id',$device_info->section_id)
                               ->where('beam_site_id',$device_info->beam_site_id)
                               ->where('girder_number',$post['T1'])
                               ->first();

        if (!$hasInfo) {

              try{

                  \DB::beginTransaction();
                  $info_data = [
                      'project_id'=>$device_info->project_id,
                      'supervision_id'=>$device_info->supervision_id,
                      'section_id' => $device_info->section_id,
                      'beam_site_id' =>$device_info->beam_site_id,
                      'device_id' => $device_info->id,
                      'girder_number' => isset($post['T1']) ? $post['T1'] : '',
                      'girdertype' => isset($post['T2']) ? $post['T2'] : '',
                      'mudjackdirect' => isset($post['T3']) ? $post['T3'] : '',
                      'concretename' => isset($post['T4']) ? $post['T4'] : '',
                      'mudjackagent' => isset($post['T5']) ? $post['T5'] : '',
                      'groutingagent' => isset($post['T6']) ? $post['T6'] : '',
                      'mobility' => isset($post['T7']) ? $post['T6'] : '',
                      'stirtime' => isset($post['T8']) ? $post['T8'] : '',
                      'environment_temperature' => isset($post['T9']) ? $post['T9'] : '',
                      'seriflux_temperature' => isset($post['T10']) ? $post['T10'] : '',
                      'operating_personnel' => isset($post['T11']) ? $post['T11'] : '',
                      'mudjackmode' => isset($post['T12']) ? $post['T12'] : '',
                      'stepnum' => isset($post['T13']) ? $post['T13'] : '',
                      'stepparam' => isset($post['T14']) ? $post['T14'] : '',
                      'stretchdate' => isset($post['T15']) ? $post['T15'] : '',
                      'time' => isset($post['D2']) ? $post['D2'] : '',
                    ];
                  $res=$mudjack_model->create($info_data);
                  //添加压浆详情数据
                  $this->addMudjackDetail($post,$res,$mudjack_detail_model);

                  \DB::commit();
                  return ['status'=>0];
              }catch (Exception $e) {
                  \DB::rollBack();
                  return $this->errorMsg('zhangla_info');
              }

        } else {
            try {
                \DB::beginTransaction();
                //添加压浆详情数据
                $this->addMudjackDetail($post,$hasInfo,$mudjack_detail_model);
                \DB::commit();
                return ['status'=>0];

            } catch (Exception $e) {
                \DB::rollBack();
                return $this->errorMsg('yajiang_info');
            }
        }

    }

    /**
     * 拌合站数据延迟上传
     * 且有报警数据，生产时间和当前时间差24小时以上
     * 给长安大学相关人员推送通知
     * @param $data
     */
    protected function sendRetardWarnNotice($data)
    {
         $project_model=new Project();
         $section_model=new Section();
         $device_model=new Device();

         $project_id=$data['project_id'];
         $section_id=$data['section_id'];
         $device_id=$data['device_id'];
//         dd($section_id);
         $project=$project_model->select('name')->find($project_id);
         $section=$section_model->select('name')->find($section_id);
         $device=$device_model->select('name')->find($device_id);

         $project_name=$project['name'];
         $section_name=$section['name'];
         $device_name=$device['name'];
         $product_time=date('Y-m-d H:i:s',$data['time']);

         $manage_user=$this->getManageUser();

         if(count($manage_user)>0){
             foreach($manage_user as $v){
                 $sms_parames=[
                     'project'=>$project_name,
                     'section'=>$section_name,
                     'device'=>$device_name,
                     'product_time'=>$product_time
                 ];
//                 Log::info($sms_parames);

                 if($v['user']['phone']){
                     $res = (new SendSms)->send($v['user']['phone'], $sms_parames, 'SMS_145590794');
                     Log::info('sendBhzRetardWarnNotice SendSms '.$v['user']['phone']);
                     Log::info(json_encode($res));
                 }
             }
         }


    }

    /**
     * 获取延迟上传数据报警通知人员
     * @return mixed
     */
    protected function getManageUser()
    {
        $manage_user_model=new MessageUser();

        $manage_user=$manage_user_model->select('user_id')
                                       ->with('user')
                                       ->get()
                                       ->toArray();
        return $manage_user;
    }

}