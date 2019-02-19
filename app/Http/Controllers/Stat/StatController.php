<?php

namespace App\Http\Controllers\Stat;

use App\Models\Sdtj\TunnelHouseMonitorInit;
use App\Models\Sdtj\TunnelHouseMonitor;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Mixplant\IndexController;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, Auth, DB, Cache,Log;
use App\Send\SendSms;
use App\Send\SendWechat;
use App\Models\Project\Section;
use App\Models\Project\Supervision;
use App\Models\Sdtj\SdtjDaliyTfkw;
use App\Models\Sdtj\SdtjDaliyLf;
use App\Models\Sdtj\SdtjTotal;
use App\Models\Sdtj\SdtjFinishTfkw;
use App\Models\Sdtj\SdtjFinishLf;
use App\Models\Sdtj\SdtjUserSet;
use App\Models\Sdtj\ResourceSgry;
use App\Models\Sdtj\ResourceJxsb;
use App\Models\Sdtj\StatWarnMess;
use App\Models\Sdtj\SdtjMonitor;
use Mockery\Exception;
use Symfony\Component\Yaml\Tests\A;
use App\Models\WeatherMessage\WeatherMessage;
use App\Models\Sdtj\SystemStat\SystemUseConditionData;

/**
 * 工程进度统计类
 * Class StatController
 * @package App\Http\Controllers\Stat
 */
class StatController extends IndexController
{
    protected $order = 'id desc';
    protected $device_cat = 4;
    protected $ispage = 20;
    protected $module = 28;
    protected $sdtj_user_set_model = '';
    protected $sgry;
    protected $jxsb;
    protected $sdtj_monitor;
    protected $weather_model;
    protected $system_use_condition_model;
    public function __construct()
    {
        parent::__construct();
        view()->share(['module' => '进度统计']);
        $this->sdtj_user_set_model = new SdtjUserSet();
        $this->sgry=new ResourceSgry();
        $this->jxsb=new ResourceJxsb();
        $this->sdtj_monitor=new SdtjMonitor();
        $this->weather_model=new WeatherMessage();
        $this->system_use_condition_model=new SystemUseConditionData();

    }

    /*隧道工程*/
    public function index()
    {
        $request=new Request();
        $role = Auth::user()->role;
        if ($role == 4||$role==5) {
            $data['data'] = $this->getEveryStat();
            $data['section'] = $this->getSection();
//            dd($data);
            $data['start_num'] = 1;
//            dd($data);
            return view('stat.index', $data);
        }

        if ($role == 3 || $role == 1) {
            $data = $this->getDataIndex();
            $list = $this->thatDayData();
            $finish = $this->finishData();
//            dd(array_merge($data,$list,$finish));
            return view('stat.index_project', array_merge($data, $list, $finish));
        }


    }

    /*当天完成进度录入*/
    public function todayAdd(Request $request)
    {
        if ($request->isMethod('get')) {
            $role = Auth::user()->role;
            if ($role != 4) {
                return '没有相关权限';
            }
            $type = $request->get('type');
            $data['section'] = $this->getSection();
            $data['type'] = $type;
            if ($type == 1 || $type == 2) {
                //获取标段信息
                return view('stat.today_add_lf', $data);
            } else {
                return view('stat.today_add_tfkw', $data);
            }


        }
        if ($request->isMethod('post')) {
            $list = $this->inputEnter($request);
            return Response()->json($list);
        }

    }

    /*查看当日进度报告--监理*/
    public function getTodayReport()
    {
        $data = $this->reportForSup();
//        dd($data);
        return view('stat.today_report_sup', $data);

    }

    /**
     * 微信推送通知人员信息
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string
     */
    public function sdInformPeople()
    {
        $role = Auth::user()->role;
        if ($role == 4 || $role == 5) {
            return '您没有相关权限';
        }
        $data['data'] = $this->getSdtjUserSet();

        return view('stat.sd_inform_people', $data);

    }

    /**
     * 添加微信推送通知人员
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function sdInformAdd(Request $request)
    {
        if ($request->isMethod('get')) {
            $data['user'] = $this->getProjectUser();

            return view('stat.sd_inform_add', $data);
        }
        if ($request->isMethod('post')) {
            $data = $this->addUser($request);
            return $data;
        }
    }

    /**
     * 删除微信推送通知人员
     * @param Request $request
     * @return mixed
     */
    public function sdInformDel(Request $request)
    {
        $id = $request->get('id');
        $model = $this->sdtj_user_set_model;
        try {
            $model->where('id', $id)->delete();
            $list['status'] = 1;
            $list['mess'] = '删除成功';
            return $list;
        } catch (Exception $e) {
            $list['status'] = 0;
            $list['mess'] = '删除出错';

        }


    }


    /*隧道工程设置*/
    public function sdSet()
    {
        $role = Auth::user()->role;
        if ($role != 1) {
            return '您没有相关权限';
        }
        $model = new SdtjTotal();
        $data['total'] = $model->select(['id', 'section_id', 'site', 'type', 'type_name', 'zl'])
            ->with('section')
            ->get()
            ->toArray();
        return view('stat.sd_set', $data);
    }

    //
    public function sdSetAdd(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('stat.sd_set_add');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($data['section_id'] == 0) {
                $list['status'] = 0;
                $list['mess'] = '请选择合同段';
                return $list;
            }
            if ($data['site'] == 3) {
                $list['status'] = 0;
                $list['mess'] = '请选择位置';
                return $list;
            }
            $model = new SdtjTotal();
            try {
                $model::create($data);
                $list['status'] = 1;
                $list['mess'] = '添加成功';
                return $list;

            } catch (Exception $e) {
                $list['status'] = 0;
                $list['mess'] = '添加失败';
                return $list;
            }


        }
    }

    /**
     *天气信息统计
     */
    public function weatherStat(Request $request)
    {
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');

        $start_time=$start_date ? strtotime($start_date) : strtotime(date('Y-m-d',time()-30*86400));
        $end_time= $end_date ? strtotime($end_date)+86400 : time()+86400;

        $data=$this->weather_model->select(DB::raw('count(id) as day_num,weather_cate,construction_situation'))
                                  ->whereBetween('time',[$start_time,$end_time])
                                  ->groupBy('weather_cate')
                                  ->get()
                                  ->toArray();

        $search['start_time']=$start_time;
        $search['end_time']=$end_time;

        return view('stat.weather.weather_stat',compact('data','search'));
    }

    /**
     * 历史天气信息
     */
    public function weatherHistory(Request $request)
    {
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');

        $field=[
            'id',
            'time',
            'code',
            'weather_text',
            'temperature_low',
            'temperature_high',
            'weather_cate',
            'construction_situation'
        ];

        $start_time=$start_date ? strtotime($start_date) : strtotime(date('Y-m-d',time()-30*86400));
        $end_time= $end_date ? strtotime($end_date)+86400 : time()+86400;

        $data=$this->weather_model->select($field)
                                  ->whereBetween('time',[$start_time,$end_time])
                                  ->orderBy('id','desc')
                                  ->paginate($this->ispage)
                                  ->toArray();

        $search['start_time']=$start_time;
        $search['end_time']=$end_time;
        $url=url('stat/weather_history');
        $data['search']=$search;
        $data['url']=$url.'?start_date='.date('Y-m-d',$start_time).'&end_date='.date('Y-m-d',$end_time-86400);
//       dd($data);
        return view('stat.weather.weather_history',$data);
    }


    /*监理用户获取标段信息*/
    protected function getSection()
    {
        $supervision_id = Auth::user()->supervision_id;
        $section_id = DB::table('supervision_section')->where('supervision_id', $supervision_id)
            ->first()
            ->section_id;
        $model = new Section();
        $section = $model->select('id', 'name')
            ->where('id', $section_id)
            ->first()
            ->toArray();
        return $section;
    }

    /*今日完成量录入*/
    protected function inputEnter($request)
    {
        $data = $request->all();
        if ($data['section_id'] == 0) {
            $list['status'] = 0;
            $list['mess'] = '请选择合同段';
            return $list;
        }
        $user_model = $this->sdtj_user_set_model;
        $user_data = $user_model->select('id', 'user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'phone', 'openid');
            }])
            ->get()
            ->toArray();
        $section_model = new Section();
        /*短信的要发送的参数*/
        //标段信息
        $section = $section_model->select('name')->where('id', $data['section_id'])->first()->name;

        $time = strtotime(date('Y-m-d', time()));
        if ($data['type'] == 3) {
            $tfkw_model = new SdtjDaliyTfkw();
            $tfkw_finish_model = new SdtjFinishTfkw();

            $time_virify = $tfkw_model->select('time')->where('time', $time)->where('section_id', $data['section_id'])->get()->toArray();
            if (!empty($time_virify)) {
                $list['status'] = 0;
                $list['mess'] = '今日信息已录入，请勿重复录入';
                return $list;
            }
            $section_id = $data['section_id'];
            $old_data = $tfkw_finish_model->select('tfkw_finish')
                ->orderBy('id', 'desc')
                ->where('section_id', $section_id)
                ->first()
                ->tfkw_finish;
//            dd($old_data);


            $tfkw_data['section_id'] = $data['section_id'];
            $tfkw_data['time'] = $time;
            $tfkw_data['tfkw'] = $data['tfkw'];

            $tfkw_finish['section_id'] = $data['section_id'];
            $tfkw_finish['time'] = $time;
            $tfkw_finish['tfkw_finish'] = $old_data + $tfkw_data['tfkw'];
            //短信发送的参数
            $tfkw = $tfkw_data['tfkw'];
            $tf_finish = $tfkw_finish['tfkw_finish'];

            try {
                $tfkw_model::create($tfkw_data);
                $tfkw_finish_model::create($tfkw_finish);
                $list['status'] = 1;
                $list['mess'] = '录入成功';
                return $list;
            } catch (Exception $e) {
                $list['status'] = 0;
                $list['mess'] = '录入失败';
                return $list;
            }
        }

        if ($data['type'] == 1 || $data['type'] == 2) {
            $lf_model = new SdtjDaliyLf();
            $time_virify1 = $lf_model->select('time')->where('time', $time)->where('site', $data['site'])->where('section_id', $data['section_id'])->get()->toArray();
            if (!empty($time_virify1)) {
                $list['status'] = 0;
                $list['mess'] = '今日信息已录入，请勿重复录入';
                return $list;
            }
            if ($data['adjj'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入暗洞掘进今日完成量，如果没有进度请输入0';
                return $list;
            }
            if ($data['cqzh'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入初期支护今日完成量，如果没有进度请输入0';
                return $list;
            }

            if ($data['ygkw'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入仰拱开挖今日完成量，如果没有进度请输入0';
                return $list;
            }
            if ($data['ygjz'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入仰拱浇筑今日完成量，如果没有进度请输入0';
                return $list;
            }
            if ($data['fsbpg'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入防水板铺挂今日完成量，如果没有进度请输入0';
                return $list;
            }
            if ($data['ecjz'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入二衬浇筑今日完成量，如果没有进度请输入0';
                return $list;
            }
            if ($data['gjbz'] == '') {
                $list['status'] = 0;
                $list['mess'] = '请输入二衬钢筋绑扎今日完成量，如果没有进度请输入0';
                return $list;
            }


            if ($data['site'] == 0) {
                $list['status'] = 0;
                $list['mess'] = '请选择位置';
                return $list;
            }

            $lf_finish_model = new SdtjFinishLf();
            $site = $data['site'];
            $data['adjj'] = (float)$data['adjj'];
            $data['cqzh'] = (float)$data['cqzh'];
            $data['ygkw'] = (float)$data['ygkw'];
            $data['ygjz'] = (float)$data['ygjz'];
            $data['fsbpg'] = (float)$data['fsbpg'];
            $data['ecjz'] = (float)$data['ecjz'];
            $data['gjbz']=(float)$data['gjbz'];

//            dd($data);
            $section_id = $data['section_id'];
            $field_list = ['id', 'section_id', 'time', 'adjj', 'cqzh', 'ygkw', 'ygjz', 'fsbpg', 'ecjz','gjbz'];
            $old_finish_data = $lf_finish_model->select($field_list)
                ->orderBy('id', 'desc')
                ->where('site', $site)
                ->where('section_id', $section_id)
                ->first()
                ->toArray();
            $new_finish_data['section_id'] = $data['section_id'];
            $new_finish_data['time'] = $time;
            $new_finish_data['site'] = $site;
            $new_finish_data['adjj'] = $old_finish_data['adjj'] + (float)$data['adjj'];
            $new_finish_data['cqzh'] = $old_finish_data['cqzh'] + (float)$data['cqzh'];
            $new_finish_data['ygkw'] = $old_finish_data['ygkw'] + (float)$data['ygkw'];
            $new_finish_data['ygjz'] = $old_finish_data['ygjz'] + (float)$data['ygjz'];
            $new_finish_data['fsbpg'] = $old_finish_data['fsbpg'] + (float)$data['fsbpg'];
            $new_finish_data['ecjz'] = $old_finish_data['ecjz'] + (float)$data['ecjz'];
            $new_finish_data['gjbz'] = $old_finish_data['gjbz'] + (float)$data['gjbz'];

            $data['time'] = $time;

            //左洞/右洞
            $left_right = '';
            if ($site == 1) {
                $left_right = '左洞';
            }
            if ($site == 2) {
                $left_right = '右洞';
            }
            //当天暗洞掘进数据
            $ad = $data['adjj'];
            $ad_finish = $new_finish_data['adjj'];
            //初期支护当天
            $ch = $data['cqzh'];
            $ch_finish = $new_finish_data['cqzh'];
            //仰拱开挖当天
            $ya = $data['ygkw'];
            $ya_finish = $data['ygkw'];
            //仰拱浇筑当天
            $yd = $data['ygjz'];
            $yd_finish = $new_finish_data['ygjz'];
            //防水板铺挂
            $pg = $data['fsbpg'];
            $pg_finish = $new_finish_data['fsbpg'];
            //二衬浇筑
            $sea = $data['ecjz'];
            $sea_finish = $new_finish_data['ecjz'];

            try {
                $lf_model::create($data);
                $lf_finish_model::create($new_finish_data);


                $list['status'] = 1;
                $list['mess'] = '录入成功';
                return $list;
            } catch (Exception $e) {
                $list['status'] = 0;
                $list['mess'] = '录入失败';
                return $list;
            }
        }


    }

    /*资源配置统计*/

    public function resource()
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 3) {

            $data=$this->getResourceForProject();
            return view('stat.resource_index_project',$data);

        } else {

             $data=$this->getResourceDataForSup();
            return view('stat.resource_index',$data);
        }

    }

    /*资源配置信息录入*/
    public function resourceAdd(Request $request)
    {

        if ($request->isMethod('get')) {
            $type = $request->get('type');
            //监理用户获取标段信息
            $data['section'] = $this->getSection();

            if ($type == 1) {

                return view('stat.resource_add_sgry', $data);
            } else {

                return view('stat.resource_add_jxsb', $data);
            }

        }

        if ($request->isMethod('post')) {
            $add_type = $request->input('type');
            if ($add_type == 1) {
                //添加施工人员数量
                $list=$this->addSgry($request);
                return Response()->json($list);
            } else {
                //添加机械设备数量
                $list=$this->addJxsb($request);
               return Response()->json($list);
            }
        }

    }

    /*报警信息处理情况统计*/
    public function warnMess()
    {
        $data=$this->getWarnMess();
//        dd($data);
        return view('stat.warn_mess_project',$data);
    }

    /*隧道监控量测*/
    public function monitor()
    {
        //超级管理员或者项目管理处用户
        if(Auth::user()->role==1 || Auth::user()->role==3){
            $data=$this->getDataAtMonitor();
//            dd($data);
            return view('stat.monitor_project',$data);
        }
        //监理用户
        if(Auth::user()->role==4||Auth::user()->role==5){

            $data=$this->getDataAtMonitor();
//            dd($data);
            return view('stat.monitor',$data);
        }

    }
    /*隧道监控量测信息录入*/
    public function monitorAdd(Request $request)
    {
         if($request->isMethod('get')){
             if(Auth::user()->role==4){
                 $data['section']=$this->getSection();
                 return view('stat.monitor_add',$data);
             }else{
                 return 'Unauthorized';
             }
         }

         if($request->isMethod('post')){
              $data=$this->monitorDataAdd($request);
              return $data;
         }
    }

    /*隧道监控量测获取数据*/
    protected function getDataAtMonitor()
    {
        $date=Input::get('date');


        $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;

        $search_date=date('Y-m-d',$start_time);

        $model=$this->sdtj_monitor;

        if(Auth::user()->role==1 || Auth::user()->role==3){
              $model=$model->where([]);
              $role=Auth::user()->role;
        }
        if(Auth::user()->role==4||Auth::user()->role==5){
            $role=Auth::user()->role;
           //获得监理对应的标段信息
            $section=$this->getSection();
            $section_id=$section['id'];
            $model=$model->where('section_id',$section_id);
        }
        $data['data']=$model->whereBetween('time',[$start_time,$end_time])
                    ->get()
                    ->toArray();
        if($role==1 || $role==3){
            $list=[];
            foreach($data['data'] as $v){
                $list[$v['section_id']]=$v;
            }
            $data['data']=$list;
            $data['search_date']=$search_date;
            return $data;
        }else{
            $data['section']=$section;
            $data['search_date']=$search_date;
            return $data;
        }

    }

    /*添加监控量测信息*/
    protected function monitorDataAdd($request)
    {
          $data=$request->all();
//          dd($data);
        $model=$this->sdtj_monitor;
          $section_id=$data['section_id'];
          $start_time=strtotime(date('Y-m-d',time()));
          $end_time=$start_time+86400;

          $is_have=$model->where('section_id',$section_id)->whereBetween('time',[$start_time,$end_time])->first();
          if($is_have){
              $list['status']=0;
              $list['mess']='今日已录入，请勿重复录入';
              return $list;
          }

          if($data['l_stake_number']==''){
              $list['status']=0;
              $list['mess']='请输入左洞桩号信息';
              return $list;
          }
          if($data['l_dnwgc_status']==0){
              $list['status']=0;
              $list['mess']='请选择左洞洞内外观察状态';
              return $list;
          }

          if($data['l_zbwy_measure_value']==''){
              $list['status']=0;
              $list['mess']='请输入左洞洞内收敛测量值,如果没有请填写0';
              return $list;
          }

          if($data['l_zbwy_status']==0){
            $list['status']=0;
            $list['mess']='请选择左洞洞内收敛状态';
            return $list;
          }

          if($data['l_gdxc_measure_value']==''){
              $list['status']=0;
              $list['mess']='请输入左洞拱顶下沉测量值,如果没有请填写0';
              return $list;
          }

          if($data['l_gdxc_status']==0){
              $list['status']=0;
              $list['mess']='请选择左洞拱顶下沉状态';
              return $list;
          }

        if($data['l_dbxc_status']==0){
            $list['status']=0;
            $list['mess']='请选择左洞地表下沉状态';
            return $list;
        }

        if($data['l_dbxc_measure_value']==''){
            $list['status']=0;
            $list['mess']='请输入左洞地表下沉测量值,如果没有请填写0';
            return $list;
        }

        if($data['r_stake_number']==''){
            $list['status']=0;
            $list['mess']='请输入右洞桩号信息';
            return $list;
        }
        if($data['r_dnwgc_status']==0){
            $list['status']=0;
            $list['mess']='请选择右洞洞内外观察状态';
            return $list;
        }

        if($data['r_zbwy_measure_value']==''){
            $list['status']=0;
            $list['mess']='请输入右洞洞内收敛测量值,如果没有请填写0';
            return $list;
        }

        if($data['r_zbwy_status']==0){
            $list['status']=0;
            $list['mess']='请选择右洞洞内收敛状态';
            return $list;
        }

        if($data['r_gdxc_measure_value']==''){
            $list['status']=0;
            $list['mess']='请输入右洞拱顶下沉测量值,如果没有请填写0';
            return $list;
        }

        if($data['r_gdxc_status']==0){
            $list['status']=0;
            $list['mess']='请选择右洞拱顶下沉状态';
            return $list;
        }

        if($data['r_dbxc_status']==0){
            $list['status']=0;
            $list['mess']='请选择右洞地表下沉状态';
            return $list;
        }

        if($data['r_dbxc_measure_value']==''){
            $list['status']=0;
            $list['mess']='请输入右洞地表下沉测量值,如果没有请填写0';
            return $list;
        }


          $data['time']=time();
          try{
              $model::create($data);
              $list['status']=1;
              $list['mess']='录入成功';
              return $list;
          }catch (Exception $e){
             $list['status']=0;
             $list['mess']='录入失败';
          }
    }


    /*获取报警统计信息*/
    protected function getWarnMess()
    {

        $date=Input::get('date');

        $model=new StatWarnMess();

        $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;
        $search_date=date('Y-m-d',$start_time);

        //试验室报警信息
        $lab_data=$model->select(['id','section_id','section_name','supervision_name','module_id','bj_num','cl_num','time'])
                        ->whereBetween('time',[$start_time,$end_time])
                        ->where('module_id',3)
                        ->orderBy('section_id','asc')
                        ->get()
                        ->toArray();
        $bhz_data=$model->select(['id','section_id','section_name','supervision_name','module_id','bj_num','cl_num','time'])
                        ->whereBetween('time',[$start_time,$end_time])
                        ->where('module_id',4)
                        ->orderBy('section_id','asc')
                        ->get()
                        ->toArray();

        if(!empty($lab_data) && !empty($bhz_data)){
            foreach($lab_data as $key=>$value){
                foreach($bhz_data as $k=>$v){
                    if($lab_data[$key]['section_id']==$bhz_data[$k]['section_id']){
                        $lab_data[$key]['bhz_bj_num']=$bhz_data[$k]['bj_num'];
                        $lab_data[$key]['lab_bj_num']=$lab_data[$key]['bj_num'];
                        unset($lab_data[$key]['bj_num']);

                        $lab_data[$key]['bhz_cl_num']=$bhz_data[$k]['cl_num'];
                        $lab_data[$key]['lab_cl_num']=$lab_data[$key]['cl_num'];
                        unset($lab_data[$key]['cl_num']);
                    }
                }
            }
        }

        $data['data']=$lab_data;
        $data['search_date']=$search_date;
//        dd($lab_data);
       return $data;

    }

    /*获取每天的进度完成情况*/
    protected function getEveryStat()
    {
        $end_time=strtotime(date('Y-m-d',time()))+86400;
        $start_time=$end_time-691200;
        $lf_model = new SdtjDaliyLf();
        $tfkw_model = new SdtjDaliyTfkw();
        $field = 'sdtj_daliy_lf.id as lf_id,sdtj_daliy_lf.time as lf_time,sdtj_daliy_lf.section_id as lf_section_id,
               sdtj_daliy_lf.site,sdtj_daliy_lf.adjj,sdtj_daliy_lf.cqzh,sdtj_daliy_lf.ygkw,
               sdtj_daliy_lf.ygjz,sdtj_daliy_lf.fsbpg,sdtj_daliy_lf.ecjz,sdtj_daliy_lf.gjbz,sdtj_daliy_lf.bhcl,
               sdtj_daliy_tfkw.id as tfkw_id,sdtj_daliy_tfkw.time as tfkw_time,
               sdtj_daliy_tfkw.tfkw';
        $section_id = $this->getSection()['id'];
        $data = $lf_model->select(DB::raw($field))
            ->where('sdtj_daliy_lf.section_id', $section_id)
            ->whereBetween('sdtj_daliy_lf.time',[$start_time,$end_time])
            ->orderBy('lf_time', 'desc')
            ->leftJoin('sdtj_daliy_tfkw', function ($join) {
                $join->on('sdtj_daliy_lf.time', '=', 'sdtj_daliy_tfkw.time')
                    ->on('sdtj_daliy_lf.section_id', '=', 'sdtj_daliy_tfkw.section_id');
            })
            ->get()
            ->toArray();
//        dd($data);
        return $data;
    }

    /*当日进度汇总报告--监理*/
    protected function reportForSup()
    {
        $time = Input::get('time');
        $section_id = Input::get('section_id');
        $supervision_id = DB::table('supervision_section')
            ->select('supervision_id')
            ->where('section_id', $section_id)
            ->first()
            ->supervision_id;
        $start_time = strtotime(date('Y-m-d', $time));
        $end_time = $start_time + 86400;

        $lf_model = new SdtjDaliyLf();

        $field = 'sdtj_daliy_lf.id as lf_id,sdtj_daliy_lf.time as lf_time,sdtj_daliy_lf.section_id as lf_section_id,
               sdtj_daliy_lf.site,sdtj_daliy_lf.adjj,sdtj_daliy_lf.cqzh,sdtj_daliy_lf.ygkw,
               sdtj_daliy_lf.ygjz,sdtj_daliy_lf.fsbpg,sdtj_daliy_lf.ecjz,sdtj_daliy_lf.gjbz,
               sdtj_daliy_tfkw.id as tfkw_id,sdtj_daliy_tfkw.time as tfkw_time,
               sdtj_daliy_tfkw.tfkw';
        $left_data = $lf_model->select(DB::raw($field))
            ->where('sdtj_daliy_lf.section_id', $section_id)
            ->where('sdtj_daliy_lf.site', 1)
            ->Where('sdtj_daliy_lf.time', $time)
            ->orderBy('lf_time', 'desc')
            ->join('sdtj_daliy_tfkw', function ($join) {
                $join->on('sdtj_daliy_lf.time', '=', 'sdtj_daliy_tfkw.time')
                    ->on('sdtj_daliy_lf.section_id', '=', 'sdtj_daliy_tfkw.section_id');
            })
            ->get()
            ->toArray();
        $right_data = $lf_model->select(['id', 'section_id', 'site', 'adjj', 'cqzh', 'ygkw', 'ygjz', 'fsbpg', 'ecjz','gjbz','bhcl'])
            ->where('section_id', $section_id)
            ->where('site', 2)
            ->where('time', $time)
            ->get()
            ->toArray();
        $data['left_data'] = $left_data;
        $data['right_data'] = $right_data;

        $data['section'] = DB::table('section')->select('name')->where('id', $section_id)->first()->name;
        $data['supervision'] = DB::table('supervision')->select('name')->where('id', $supervision_id)->first()->name;
        $data['time'] = $time;
        $data['total'] = $this->total($section_id);
        $data['finish'] = $this->finishNum($section_id, $time);
        return $data;
    }

    /*获取总量*/
    protected function total($section_id = '')
    {
        $model = new SdtjTotal();
        $field = ['section_id', 'site', 'type', 'type_name', 'zl'];
        if ($section_id != '') {
            $data = $model->select($field)
                ->where('section_id', $section_id)
                ->orderBy('site', 'asc')
                ->get()
                ->toArray();
        } else {
            $data = $model->select($field)
                ->orderBy('site', 'asc')
                ->get()
                ->toArray();
        }

        $left = [];
        $right = [];
        $tfkw = [];
        foreach ($data as $k => $v) {
            if ($v['site'] == 1) {
                $left[] = $v;
            }
            if ($v['site'] == 2) {
                $right[] = $v;
            }
            if ($v['site'] == 0) {
                $tfkw[] = $v;
            }
        }
        $list['left'] = $left;
        $list['right'] = $right;
        $list['tfkw'] = $tfkw;
//        dd($list);
        return $list;
    }

    /*获取完成量*/
    protected function finishNum($section_id = '', $time = '')
    {
        $time = $time ? $time : strtotime(date('Y-m-d', time()));
        $tfkw_finish_model = new SdtjFinishTfkw();
        $lf_finish_model = new SdtjFinishLf();
        $tfkw_field = ['id',
            'section_id',
            'time',
            'tfkw_finish'
        ];
        $lf_field = [
            'id',
            'section_id',
            'time',
            'site',
            'adjj',
            'cqzh',
            'ygkw',
            'ygjz',
            'fsbpg',
            'ecjz',
            'gjbz',
            'bhcl',
        ];
        if ($section_id != '') {
            $lf_finish = $lf_finish_model->select($lf_field)
                ->orderBy('site', 'asc')
                ->where('section_id', $section_id)
                ->where('time', $time)
                ->get()
                ->toArray();
            $left_finish = [];
            $right_finish = [];
            foreach ($lf_finish as $v) {
                if ($v['site'] == 1) {
                    $left_finish = $v;
                }
                if ($v['site'] == 2) {
                    $right_finish = $v;
                }
            }

            $tfkw_finish = $tfkw_finish_model->select($tfkw_field)
                ->where('section_id', $section_id)
                ->where('time', $time)
                ->get()
                ->toArray();
            $data['left_finish'] = $left_finish;
            $data['right_finish'] = $right_finish;

            $data['tfkw_finish'] = $tfkw_finish;
            return $data;
        } else {
            $start_time = time() - 86400;
            $end_time = time() + 86400;
            $lf_finish = $lf_finish_model->select($lf_field)
                ->where('time', $time)
                ->get()
                ->toArray();
            $tfkw_finish = $tfkw_finish_model->select($tfkw_field)
                ->where('time', $time)
                ->get()
                ->toArray();
            $data['lf_finish'] = $lf_finish;
            $data['tfkw_finish'] = $tfkw_finish;
            return $data;
        }

    }

    /*获取能够添加的人员信息*/
    /*
     * 人员角色为项目管理处用户  role=3
     */
    protected function getProjectUser()
    {
        $model = new User();
        $role_id = [1, 3];
        $status = 1;
        $field = ['id', 'role', 'company_id', 'name', 'position_id'];
        $data = $model->select($field)
            ->whereBetween('role', $role_id)
            ->where('status', $status)
            ->with('company')
            ->with('posi')
            ->get()
            ->toArray();
        return $data;

    }

    /*隧道工程添加通知人员*/
    protected function addUser($request)
    {
        $user_model = $this->sdtj_user_set_model;
        $userData = $request->all();
//       $userData['user_id']=(int)$userData['user_id'];
//       dd($userData);
        if ($userData['user_id'] == 0) {
            $list['status'] = 0;
            $list['mess'] = '请选择人员';
            return $list;
        }
        if ($user_model->select('user_id')->where('user_id', $userData['user_id'])->first()) {
            $list['status'] = 0;
            $list['mess'] = '该人员已经添加过，请勿重复添加';
            return $list;
        }


        try {
            $user_model::create($userData);
            $list['status'] = 1;
            $list['mess'] = '添加成功';
            return $list;
        } catch (Exception $e) {
            $list['status'] = 0;
            $list['mess'] = '添加出错';
            return $list;
        }

    }


    /*获取隧道工程通知人员信息*/
    protected function getSdtjUserSet()
    {
        $model = $this->sdtj_user_set_model;
        $data = $model->select('id', 'user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'company_id', 'username', 'phone')
                    ->with('company');
            }])
            ->get()
            ->toArray();
        return $data;
    }


    /*超级管理员或者项目管理处用户获取总量数据*/

    protected function getDataIndex()
    {
        $total_query = new SdtjTotal();
        $total_data = $total_query->select(['id', 'section_id', 'site', 'type', 'type_name', 'zl'])
            ->orderBy('site', 'asc')
            ->with(['section' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get()
            ->toArray();
        $length = count($total_data);
        $left = [];
        $right = [];
        $tfkw = [];
        for ($i = 0; $i < $length; $i++) {
            if ($total_data[$i]['site'] == 1) {
                $left[$total_data[$i]['section_id']][] = $total_data[$i];
            }
            if ($total_data[$i]['site'] == 2) {
                $right[$total_data[$i]['section_id']][] = $total_data[$i];
            }
            if ($total_data[$i]['site'] == 0) {
                $tfkw[$total_data[$i]['section_id']][] = $total_data[$i];
            }
        }

        $data['left'] = $left;
        $data['right'] = $right;
        $data['tfkw'] = $tfkw;
        return $data;
    }

    /*项目管理处用户获取当日完成量*/
    protected function thatDayData()
    {
        $date=Input::get('date');
//        dd($date);
        $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d', time()));
        $end_time = $date ? strtotime($date)+86400 : strtotime(date('Y-m-d', time() + 86400));
        $lf_daliy_model = new SdtjDaliyLf();
        $tfkw_daliy_model = new SdtjDaliyTfkw();
        $times1 = $lf_daliy_model->select('time')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->where('section_id', 19)
            ->get()
            ->toArray();
        $times2 = $lf_daliy_model->select('time')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->where('section_id', 20)
            ->get()
            ->toArray();

        if (!empty($times1) || !empty($times2)) {
            $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d', time()));
            $end_time = $date ? strtotime($date)+86400 : strtotime(date('Y-m-d', time() + 86400));
        } else {
            $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d', time() - 86400));
            $end_time =$date ? strtotime($date)+86400 : strtotime(date('Y-m-d', time()));
        }

        $search_date=date('Y-m-d',$start_time);

        $data13 = $lf_daliy_model->select('id', 'section_id', 'time', 'site', 'adjj', 'cqzh', 'ygkw', 'ygjz', 'fsbpg', 'ecjz','gjbz','bhcl')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->where('section_id', 19)
            ->orderBy('site', 'asc')
            ->get()
            ->toArray();
        $data_13_left = [];
        $data_13_right = [];
        foreach ($data13 as $val) {
            if ($val['site'] == 1) {
                $data_13_left = $val;
            }
            if ($val['site'] == 2) {
                $data_13_right = $val;
            }
        }

        $data14 = $lf_daliy_model->select('id', 'section_id', 'time', 'site', 'adjj', 'cqzh', 'ygkw', 'ygjz', 'fsbpg', 'ecjz','gjbz','bhcl')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->orderBy('site', 'asc')
            ->where('section_id', 20)
            ->get()
            ->toArray();
        $data_14_left = [];
        $data_14_right = [];
        foreach ($data14 as $v) {
            if ($v['site'] == 1) {
                $data_14_left = $v;
            }
            if ($v['site'] = 2) {
                $data_14_right = $v;
            }
        }

        $tfkw_data = $tfkw_daliy_model->select('id', 'section_id', 'time', 'tfkw')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->orderBy('section_id', 'asc')
            ->get()
            ->toArray();
        $data_13_tfkw = [];
        $data_14_tfkw = [];

        foreach ($tfkw_data as $value) {
            if ($value['section_id'] == 19) {
                $data_13_tfkw = $value;
            }
            if ($value['section_id'] == 20) {
                $data_14_tfkw = $value;
            }
        }

        $list['today_13_left'] = $data_13_left;
        $list['today_13_right'] = $data_13_right;
        $list['today_14_left'] = $data_14_left;
        $list['today_14_right'] = $data_14_right;
        $list['today_13_tfkw'] = $data_13_tfkw;
        $list['today_14_tfkw'] = $data_14_tfkw;
        $list['search_date']=$search_date;
        return $list;
    }

    /*项目管理处用户获取累计完成量*/
    protected function finishData()
    {
        $date=Input::get('date');
        $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d', time()));
        $end_time = $date ? strtotime($date)+86400 : strtotime(date('Y-m-d', time() + 86400));

        $lf_finish_model = new SdtjFinishLf();
        $tfkw_finish_model = new SdtjFinishTfkw();
        $times1 = $lf_finish_model->select('time')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->where('section_id', 19)
            ->get()
            ->toArray();
        $times2 = $lf_finish_model->select('time')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->where('section_id', 20)
            ->get()
            ->toArray();

        if (!empty($times1) || !empty($times2)) {
            $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d', time()));
            $end_time = $date ? strtotime($date)+86400 : strtotime(date('Y-m-d', time() + 86400));
        } else {
            $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d', time() - 86400));
            $end_time =$date ? strtotime($date)+86400 : strtotime(date('Y-m-d', time()));
        }

        $data13 = $lf_finish_model->select('id', 'section_id', 'time', 'site', 'adjj', 'cqzh', 'ygkw', 'ygjz', 'fsbpg', 'ecjz','gjbz','bhcl')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->where('section_id', 19)
            ->orderBy('site', 'asc')
            ->get()
            ->toArray();
        $finish_13_left = [];
        $finish_13_right = [];
        foreach ($data13 as $v) {
            if ($v['site'] == 1) {
                $finish_13_left = $v;
            }
            if ($v['site'] == 2) {
                $finish_13_right = $v;
            }
        }

        $data14 = $lf_finish_model->select('id', 'section_id', 'time', 'site', 'adjj', 'cqzh', 'ygkw', 'ygjz', 'fsbpg', 'ecjz','gjbz','bhcl')
            ->where('time', '>=',$start_time)
            ->where('time','<',$end_time)
            ->orderBy('site', 'asc')
            ->where('section_id', 20)
            ->get()
            ->toArray();
        $finish_14_left = [];
        $finish_14_right = [];
        foreach ($data14 as $val) {
            if ($val['site'] == 1) {
                $finish_14_left = $val;
            }
            if ($val['site'] == 2) {
                $finish_14_right = $val;
            }
        }

        $tfkw_data = $tfkw_finish_model->select('id', 'section_id', 'time', 'tfkw_finish')
            ->where('time','>=',$start_time)
            ->where('time','<',$end_time)
            ->orderBy('section_id', 'asc')
            ->get()
            ->toArray();
        $finish_13_tfkw = [];
        $finish_14_tfkw = [];
        foreach ($tfkw_data as $value) {
            if ($value['section_id'] == 19) {
                $finish_13_tfkw = $value;
            }
            if ($value['section_id'] == 20) {
                $finish_14_tfkw = $value;
            }
        }

        $list['finish_13_left'] = $finish_13_left;
        $list['finish_13_right'] = $finish_13_right;
        $list['finish_14_left'] = $finish_14_left;
        $list['finish_14_right'] = $finish_14_right;
        $list['finish_13_tfkw'] = $finish_13_tfkw;
        $list['finish_14_tfkw'] = $finish_14_tfkw;
        return $list;
    }

    /*录入施工人员数量*/
    protected function addSgry($request)
    {
        $model = $this->sgry;
        $data = $request->all();
        $section_id=$data['section_id'];
        $site=$data['site'];
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;
        $already_data=$model->select('id','section_id')
                            ->where('section_id',$section_id)
                            ->where('site',$site)
                            ->whereBetween('time',[$start_time,$end_time])
                            ->first();
        if($already_data){
            $list['status']=0;
            $list['mess']='当日该洞的施工人员数量信息已录入，不能重复录入';
            return $list;
        }

        if ($data['section_id'] == 0) {
            $list['status'] = 0;
            $list['mess'] = '请选择合同段';
            return $list;
        }
        if ($data['site'] == 0) {
            $list['status'] = 0;
            $list['mess'] = '请选择位置';
            return $list;
        }
        if ($data['zzmkw'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;掌子面开挖施工人员&nbsp;&nbsp;数量,如果没有该项目人数，请填写0';
            return $list;
        }
        if ($data['cqzh'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;初期支护施工人员&nbsp;&nbsp;数量,如果没有该项目人数，请填写0';
            return $list;
        }
        if ($data['ygkw'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;仰拱开挖施工人员&nbsp;&nbsp;数量,如果没有该项目人数，请填写0';
            return $list;
        }
        if ($data['ygjz'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;仰拱浇筑施工人员&nbsp;&nbsp;数量,如果没有该项目人数，请填写0';
            return $list;
        }
        if ($data['fsbpg'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;防水板铺挂施工人员&nbsp;&nbsp;数量,如果没有该项目人数，请填写0';
            return $list;
        }
        if ($data['ecjz'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;二衬浇筑施工人员&nbsp;&nbsp;数量,如果没有该项目人数，请填写0';
            return $list;
        }
        $data['time'] = time();
        try {

            $model::create($data);
            $list['status'] = 1;
            $list['mess'] = '录入成功';
            return $list;
        } catch (Exception $e) {
            $list['status'] = 0;
            $list['mess'] = '录入失败';
            return $list;
        }


    }


    /*录入机械设备数量*/
    protected function addJxsb($request)
    {
        $model = $this->jxsb;

        $data = $request->all();

        $section_id=$data['section_id'];
        $site=$data['site'];
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;
        $already_data=$model->select('id','section_id')
            ->where('section_id',$section_id)
            ->where('site',$site)
            ->whereBetween('time',[$start_time,$end_time])
            ->first();
        if($already_data){
            $list['status']=0;
            $list['mess']='当日该洞的机械设备数量信息已录入，不能重复录入';
            return $list;
        }

        if ($data['section_id'] == 0) {
            $list['status'] = 0;
            $list['mess'] = '请选择合同段';
            return $list;
        }
        if ($data['site'] == 0) {
            $list['status'] = 0;
            $list['mess'] = '请选择位置';
            return $list;
        }
        if ($data['spjxs'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;湿喷机械手&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['fsbpgtc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;防水板铺挂台车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['ectc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;二衬台车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['ecystc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;二衬养生台车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['ygmbtc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;仰拱模板台车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['sgdlctc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;水沟电缆槽台车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['wpc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;雾炮车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['wjj'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;挖掘机&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['zzj'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;装载机&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }
        if ($data['zxc'] == '') {
            $list['status'] = 0;
            $list['mess'] = '请输入&nbsp;&nbsp;自卸车&nbsp;&nbsp;数量,如果没有该设备数量，请填写0';
            return $list;
        }

        $data['time'] = time();
        try {

            $model::create($data);
            $list['status'] = 1;
            $list['mess'] = '录入成功';
            return $list;
        } catch (Exception $e) {
            $list['status'] = 0;
            $list['mess'] = '录入失败';
            return $list;
        }

    }

   /*监理用户获取资源配置统计数据*/
   protected function getResourceDataForSup()
   {
       $sgry_model=$this->sgry;
       $jxsb_model=$this->jxsb;

       $start_time=strtotime(date('Y-m-d',time()));
       $end_time=$start_time+86400;

       $section=$this->getSection();
       $section_id=$section['id'];

       $sgry_field=['id','section_id','time','zzmkw','cqzh','ygkw','ygjz','fsbpg','ecjz'];
       $jxsb_field=['id','section_id','time','spjxs','fsbpgtc','ectc','ecystc','ygmbtc','sgdlctc','wpc','wjj','zzj','zxc'];

       $left_sgry=$sgry_model->select($sgry_field)
                             ->where('section_id',$section_id)
                             ->where('site',1)
                             ->whereBetween('time',[$start_time,$end_time])
                             ->orderBy('id','desc')
                             ->first();
       $right_sgry=$sgry_model->select($sgry_field)
                              ->where('section_id',$section_id)
                              ->where('site',2)
                              ->whereBetween('time',[$start_time,$end_time])
                              ->orderBy('id','desc')
                              ->first();
       $left_jxsb=$jxsb_model->select($jxsb_field)
           ->where('section_id',$section_id)
           ->where('site',1)
           ->whereBetween('time',[$start_time,$end_time])
           ->orderBy('id','desc')
           ->first();
       $right_jxsb=$jxsb_model->select($jxsb_field)
           ->where('section_id',$section_id)
           ->where('site',2)
           ->whereBetween('time',[$start_time,$end_time])
           ->orderBy('id','desc')
           ->first();
       $data['section']=$section;
       $data['left_sgry']=$left_sgry;
       $data['right_sgry']=$right_sgry;
       $data['left_jxsb']=$left_jxsb;
       $data['right_jxsb']=$right_jxsb;
       return $data;
   }


   /*项目管理处用户和超级管理员获取资源配置统计数据*/
   protected function getResourceForProject()
   {
       $date=Input::get('date');

       $sgry_model=$this->sgry;
       $jxsb_model=$this->jxsb;

       $start_time = $date ? strtotime($date) : strtotime(date('Y-m-d',time()));
       $end_time=$start_time+86400;

       $search_date=date('Y-m-d',$start_time);

       $sgry_field=['id','section_id','site','time','zzmkw','cqzh','ygkw','ygjz','fsbpg','ecjz'];
       $jxsb_field=['id','section_id','site','time','spjxs','fsbpgtc','ectc','ecystc','ygmbtc','sgdlctc','wpc','wjj','zzj','zxc'];

       $sgry_data=$sgry_model->select($sgry_field)
                             ->where('time','>=',$start_time)
                             ->where('time','<',$end_time)
                             ->get()
                             ->toArray();

       $jxsb_data=$jxsb_model->select($jxsb_field)
                             ->where('time','>=',$start_time)
                             ->where('time','<',$end_time)
                             ->get()
                             ->toArray();

       $left_sgry_13=[];
       $right_sgry_13=[];
       $left_sgry_14=[];
       $right_sgry_14=[];

       $left_jxsb_13=[];
       $right_jxsb_13=[];
       $left_jxsb_14=[];
       $right_jxsb_14=[];

       if(!empty($sgry_data)){
           foreach($sgry_data as $v){
               if($v['section_id']==19 && $v['site']==1){
                   $left_sgry_13=$v;
               }

               if($v['section_id']==19 && $v['site']==2){
                   $right_sgry_13=$v;
               }

               if($v['section_id']==20 && $v['site']==1){
                   $left_sgry_14=$v;
               }
               if($v['section_id']==20 && $v['site']==2){
                   $right_sgry_14=$v;
               }
           }
       }

       if(!empty($jxsb_data)){
           foreach($jxsb_data as $v){
               if($v['section_id']==19 && $v['site']==1){
                   $left_jxsb_13=$v;
               }
               if($v['section_id']==19 && $v['site']==2){
                  $right_jxsb_13=$v;
               }
               if($v['section_id']==20 && $v['site']==1){
                   $left_jxsb_14=$v;
               }
               if($v['section_id']==20 && $v['site']==2){
                   $right_jxsb_14=$v;
               }
           }
       }

       $data['left_sgry_13']=$left_sgry_13;
       $data['right_sgry_13']=$right_sgry_13;

       $data['left_sgry_14']=$left_sgry_14;
       $data['right_sgry_14']=$right_sgry_14;

       $data['left_jxsb_13']=$left_jxsb_13;
       $data['right_jxsb_13']=$right_jxsb_13;

       $data['left_jxsb_14']=$left_jxsb_14;
       $data['right_jxsb_14']=$right_jxsb_14;
       $data['search_date']=$search_date;

       return $data;
   }

    /**
     * 系统应用情况
     */
    public function systemStat()
    {
        //超级管理员或项目管理处用户
        if(Auth::user()->role==1||Auth::user()->role==3){

            $view='stat.systemStat.system_stat_project';
             $data=$this->systemUseCondition();

            return view($view,$data);
        }
        //监理或合同段用户
        if(Auth::user()->role==4||Auth::user()->role==5){

            $data=$this->getSystemReportData();
//            dd($data);
            $view='stat.systemStat.system_stat';
            return view($view,$data);
        }

//        dd($system_cate);
//

    }
    /**
     * 系统进度信息录入(信息化各系统运行情况录入)
     */
    public function systemRunAdd(Request $request)
    {
        if($request->isMethod('get')){
            //获取用户角色
            $role_id=Auth::user()->role;
            if($role_id==4){
                $type='sup';
                //获取该用户所在监理单位
                $supervision=Auth::user()->supervision;
                $supervision_id=$supervision['id'];
                $unit_id=$supervision_id;

            }elseif($role_id==5){

                $type='sec';
                //获取该用户所在的合同段
                $section=Auth::user()->section;
                $unit_id=$section['id'];
            }
            $h_time=date('H',time());
            $m_time=date('i',time());
//            dd($m_time);
            if($h_time=="16"||($h_time=="17" && $m_time=="00")){
                $status=1;
            }else{
                $status=0;
            }
//            dd($status);
            return view('stat.systemStat.system_run_add',compact('type','unit_id','status'));
        }
        if($request->isMethod('post')){

            $model=new SystemUseConditionData();
            $input_list=$request->all();
//            dd($input_list);
            /*判断今日的数据是否已经录入过
            如果已经录入过，则不能再录入*/

            $start_time=strtotime(date('Y-m-d',time()));
            $end_time=$start_time+86400;
              //通过字段type来判断是合同段还是监理用户提交
            if($input_list['type']=='sup'){
               $query=$model->where('type','sup');
            }

            if($input_list['type']=='sec'){
                $query=$model->where('type','sec');
            }

            if($input_list['unit_id']){
                $res=$query->select('id')
                           ->where('unit_id',$input_list['unit_id'])
                           ->whereBetween('time',[$start_time,$end_time])
                           ->first();
                if($res){
                    $result['status']=1;
                    $result['mess']='今日信息已经录入，请勿重复录入';
                    return Response()->json($result);
                }
            }else{
                $result['status']=1;
                $result['mess']='数据提交失败';
                return Response()->json($result);
            }
            //表单数据验证
            if($input_list['lab_data_monitor_status']==0){
                $result['status']=1;
                $result['mess']='请选择试验数据监控系统状态';
                return Response()->json($result);
            }
            if($input_list['blend_data_monitor_status']==0){
                $result['status']=1;
                $result['mess']='请选择拌和数据监控系统状态';
                return Response()->json($result);
            }
            if($input_list['video_monitor_status']==0){
                $result['status']=1;
                $result['mess']='请选择视频监控系统状态';
                return Response()->json($result);
            }
            if($input_list['tunnel_location_status']==0){
                $result['status']=1;
                $result['mess']='请选择隧道定位系统状态';
                return Response()->json($result);
            }
            if($input_list['high_side_monitor_status']==0){
                $result['status']=1;
                $result['mess']='请选择高边坡监测系统状态';
                return Response()->json($result);
            }
            if($input_list['electronic_recode_status']==0){
                $result['status']=1;
                $result['mess']='请选择电子档案管理系统状态';
                return Response()->json($result);
            }
            if($input_list['electronic_recode_status']==0){
                $result['status']=1;
                $result['mess']='请选择电子档案管理系统状态';
                return Response()->json($result);
            }
            if($input_list['lab_data_alarm_status']==0){
                $result['status']=1;
                $result['mess']='请选择试验数据报警';
                return Response()->json($result);
            }
            if($input_list['blend_data_alarm_status']==0){
                $result['status']=1;
                $result['mess']='请选择拌和数据报警';
                return Response()->json($result);
            }
            $input_list['time']=time();
            //数据入库
            try{
               $model::create($input_list);
                $result['status']=0;
                $result['mess']='录入成功';
                return Response()->json($result);
            }catch (\Exception $e) {
                $result['status'] = 1;
                $result['mess'] = '录入失败';
                return Response()->json($result);
            }

        }

    }
    /**
     * 系统应用页面--监理或合同段用户获取今日已经填写的数据
     * 如果今天还没有填写，就显示昨天的数据，如果昨天没有数据，就不显示
     */
    protected function getSystemReportData()
    {
        $field=[
            'time',
            'type',
            'unit_id',
            'lab_data_monitor_status',
            'lab_data_monitor_remark',
            'blend_data_monitor_status',
            'blend_data_monitor_remark',
            'video_monitor_status',
            'video_monitor_remark',
            'tunnel_location_status',
            'tunnel_location_remark',
            'high_side_monitor_status',
            'high_side_monitor_remark',
            'electronic_recode_status',
            'electronic_recode_remark',
            'lab_data_alarm_status',
            'lab_data_alarm_remark',
            'blend_data_alarm_status',
            'blend_data_alarm_remark'
        ];
        //查询当天填写的数据，如果当天的数据还没有填写，就查找前一天填写的数据
        $start_time=strtotime(date('Y-m-d',time()));
        $end_time=$start_time+86400;

        $yestoday_start_time=$start_time-86400;
        $yestoday_end_time=$start_time;
//        dd($yestoday_end_time);

        $query=new SystemUseConditionData();

        //监理用户 type字段为sup
        if(Auth::user()->role==4){
            //获取当前用户所在的监理单位
            $supervision=Auth::user()->supervision;
//            dd($supervision);
            $unit_id=$supervision['id'];
            $unit_name=$supervision['name'];
            $query=$query->where('type','sup')
                         ->where('unit_id',$unit_id);
        }
        //合同段用户
        if(Auth::user()->role==5){
            //获取当前用户所在的监理单位
            $section=Auth::user()->section;
//            dd($supervision);
            $unit_id=$section['id'];
            $unit_name=$section['name'];
            $query=$query->where('type','sec')
                ->where('unit_id',$unit_id);
        }
        $today_query=$query;
        $yestoday_query=clone($query);
//        dd($query);
        $list=$today_query->select($field)
                    ->whereBetween('time',[$start_time,$end_time])
                    ->first();

        if($list){
            $data['data']=$list;

        }else{
            $data['data']=$yestoday_query->select($field)
                                ->whereBetween('time',[$yestoday_start_time,$yestoday_end_time])
                                ->first();
//            dd($data['data']);
        }

        $data['unit_name']=$unit_name;
        return $data;

    }

    /**
     * 系统应用情况数据汇总--超级管理员或项目管理处用户获取监理和合同段用户填写的数据
     * 如果今日还没有填写数据，就显示昨天的数据
     */
    protected function systemUseCondition()
    {
        $date=Input::get('date');

        $field=[
            'id',
            'type',
            'unit_id',
            'time',
            'lab_data_monitor_status',
            'lab_data_monitor_remark',
            'blend_data_monitor_status',
            'blend_data_monitor_remark',
            'video_monitor_status',
            'video_monitor_remark',
            'tunnel_location_status',
            'tunnel_location_remark',
            'high_side_monitor_status',
            'high_side_monitor_remark',
            'electronic_recode_status',
            'electronic_recode_remark',
            'lab_data_alarm_status',
            'lab_data_alarm_remark',
            'blend_data_alarm_status',
            'blend_data_alarm_remark'
        ];
       $start_time= $date ? strtotime($date) : strtotime(date('Y-m-d',time()));

       $end_time=$start_time+86400;

       $search_date=date('Y-m-d',$start_time);

       $yestoday_start_time=$start_time-86400;
       $yestoday_end_time=$start_time;

       //查询监理单位今日是否有提交的数据
        $sup_data_today=$this->system_use_condition_model
                             ->select('id')
                             ->where('type','sup')
                             ->whereBetween('time',[$start_time,$end_time])
                             ->first();

        //查询施工单位(合同段)今日是否有提交的数据
        $sec_data_today=$this->system_use_condition_model
                             ->select('id')
                             ->where('type','sec')
                             ->whereBetween('time',[$start_time,$end_time])
                             ->first();
        if(is_object($sup_data_today) || is_object($sec_data_today)){
              //今日已经有数据提交，所以就查询今日数据
              //监理单位数据
              $sup_data=$this->system_use_condition_model
                             ->select($field)
                             ->where('type','sup')
                             ->whereBetween('time',[$start_time,$end_time])
                             ->with('supervision')
                             ->get()
                             ->toArray();
            $sup=[];
            foreach($sup_data as $v){
                $sup[$v['unit_id']]=$v;
            }
              //施工单位数据
              $sec_data=$this->system_use_condition_model
                             ->select($field)
                             ->where('type','sec')
                             ->whereBetween('time',[$start_time,$end_time])
                             ->with('section')
                             ->get()
                             ->toArray();
            $sec=[];
            foreach($sec_data as $v){
                $sec[$v['unit_id']]=$v;
            }
              $report_time=$start_time;
        }else{
            //今日没有数据提交，查询昨日数据
            //监理单位数据
            $sup_data=$this->system_use_condition_model
                ->select($field)
                ->where('type','sup')
                ->whereBetween('time',[$yestoday_start_time,$yestoday_end_time])
                ->with('supervision')
                ->get()
                ->toArray();
            $sup=[];
            foreach($sup_data as $v){
                $sup[$v['unit_id']]=$v;
            }
            //施工单位数据
            $sec_data=$this->system_use_condition_model
                ->select($field)
                ->where('type','sec')
                ->whereBetween('time',[$yestoday_start_time,$yestoday_end_time])
                ->with('section')
                ->get()
                ->toArray();
            $sec=[];
            foreach($sec_data as $v){
                $sec[$v['unit_id']]=$v;
            }

            $report_time=$yestoday_start_time;
        }

        $data['sup_data']=$sup;
        $data['sec_data']=$sec;
        $data['report_time']=$report_time;
        $data['search_date']=$search_date;
//        dd($data);
        return $data;


    }

    /**
     * 工程设置--隧道洞顶房屋沉降观测
     */
    public function tunnelHouseInit(TunnelHouseMonitorInit $houseMonitorInit)
    {
        $data=$houseMonitorInit->first();

//        dd($data);

        return view('stat.tunnel_house_init',compact('data'));
    }

    /**
     * 工程设置--添加隧道洞顶房屋沉降观测数据
     */
    public function tunnelHouseInitAdd(Request $request, TunnelHouseMonitorInit $houseMonitorInit)
    {
        if ($request->isMethod('get')) {

            return view('stat.tunnel_house_init_add');
        }

        if ($request->isMethod('post')) {

            $data = $houseMonitorInit->first();

            if ($data) {
                $result['status'] = 1;
                $result['mess'] = '已经添加过，不可重复添加';

                return $result;
            }

            $input_data = $request->all();

            try {

                $houseMonitorInit->create($input_data);

                $result['status'] = 0;
                $result['mess'] = '添加成功';
                return $result;

            } catch (Exception $e) {
                Log::info($e);

                $result['status'] = 1;
                $result['mess'] = '添加失败';
                return $result;
            }
        }

    }


    /**
     * 工程设置--编辑隧道洞顶房屋沉降观测数据
     */
    public function tunnelHouseInitEdit(Request $request, TunnelHouseMonitorInit $houseMonitorInit)
    {
        if ($request->isMethod('get')) {
            $data = $houseMonitorInit->first();

            return view('stat.tunnel_house_init_edit', compact('data'));
        }

        if ($request->isMethod('post')) {

            $data = $request->all();

            unset($data['_token']);

            try {
                $houseMonitorInit->where('id', $data['id'])->update($data);

                $result['status'] = 0;
                $result['mess'] = '修改成功';
                return $result;
            } catch (Exception $e) {
                $result['status'] = 1;
                $result['mess'] = '修改失败';
                return $result;
            }

        }
    }


    /**
     * 工程进度统计--隧道洞顶房屋沉降观测
     */
    public function tunnelHouseMonitor(TunnelHouseMonitor $houseMonitor)
    {
        $url=url('stat/tunnel_house_monitor');
        $query = $houseMonitor;

        $role = $this->user->role;

        if ($role == 1 || $role == 3) {
            $query = $query->where([]);
        }

        if ($role == 4) {
            $supervision_id = $this->user->supervision->id;

            if ($supervision_id != 7) {
                $info = '该功能您无访问权限';
                return view('admin.error.no_info', compact('info'));
            }

            $query = $query->where('supervision_id', $supervision_id);
        }

        if ($role == 5) {
            $section_id = $this->user->section->id;

            if ($section_id != 19) {
                $info = '该功能您无访问权限';
                return view('admin.error.no_info', compact('info'));
            }

            $query = $query->where('section_id', $section_id);
        }

        $data=$query->with('write_user','check_user')
                    ->orderBy('id','desc')
                    ->paginate($this->ispage)
                    ->toArray();

        $data['url']=$url;
//        dd($data);
        return view('stat.tunnel_house_monitor',$data);

    }

    /**
     * 添加隧道洞顶房屋沉降观测数据
     */
    public function tunnelHouseMonitorAdd(Request $request,TunnelHouseMonitor $houseMonitor)
    {
        if ($request->isMethod('get')) {
            return view('stat.tunnel_house_monitor_add');
        }

        if ($request->isMethod('post')) {

            $input_data = $request->all();

            if ($input_data['station1'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写1#测点的观测数据';
                return $result;
            }

            if ($input_data['station2'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写2#测点的观测数据';
                return $result;
            }

            if ($input_data['station3'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写3#测点的观测数据';
                return $result;
            }

            if ($input_data['station4'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写4#测点的观测数据';
                return $result;
            }

            if ($input_data['station5'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写5#测点的观测数据';
                return $result;
            }

            if ($input_data['station6'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写6#测点的观测数据';
                return $result;
            }

            if ($input_data['station7'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写7#测点的观测数据';
                return $result;
            }

            if ($input_data['station8'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写8#测点的观测数据';
                return $result;
            }

            if ($input_data['station9'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写9#测点的观测数据';
                return $result;
            }

            if ($input_data['station10'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写10#测点的观测数据';
                return $result;
            }

            if ($input_data['station11'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写11#测点的观测数据';
                return $result;
            }
            if ($input_data['station12'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写12#测点的观测数据';
                return $result;
            }
            if ($input_data['station13'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写13#测点的观测数据';
                return $result;
            }
            if ($input_data['station14'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写14#测点的观测数据';
                return $result;
            }
            if ($input_data['station15'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写15#测点的观测数据';
                return $result;
            }
            if ($input_data['station16'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写16#测点的观测数据';
                return $result;
            }
            if ($input_data['station17'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写17#测点的观测数据';
                return $result;
            }
            if ($input_data['station18'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写18#测点的观测数据';
                return $result;
            }
            if ($input_data['station19'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写19#测点的观测数据';
                return $result;
            }
            if ($input_data['station20'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写20#测点的观测数据';
                return $result;
            }
            if ($input_data['station21'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写21#测点的观测数据';
                return $result;
            }

            if ($input_data['conclusion'] == '') {

                $result['status'] = 1;
                $result['mess'] = '请填写观测结论';
                return $result;
            }

            $write_user_id=$this->user->id;

            $section_id=$this->user->section->id;

            $supervision_id=$this->user->supervision->id;

            $input_data['write_user_id']=$write_user_id;
            $input_data['section_id']=$section_id;
            $input_data['supervision_id']=$supervision_id;
            $input_data['created_at']=time();
            $input_data['is_check']=0;


            try{
               $houseMonitor->create($input_data);

               $result['status']=0;
               $result['mess']='添加成功';
               return $result;

            }catch (Exception $e){

                $result['status']=1;
                $result['mess']='添加失败';
                return $result;
            }

        }
    }

    /**
     * 隧道洞顶房屋沉降观测详细数据
     */
    public function tunnelHouseMonitorDetail($id,TunnelHouseMonitor $houseMonitor,TunnelHouseMonitorInit $houseMonitorInit)
    {
        //获取初始高程
        $init_data=$houseMonitorInit->first();
//        dd($init_data);
        //获取本次的高程数据
        $this_data=$houseMonitor->with('write_user','check_user')
                                     ->find($id);
//        dd($this_data);
        //获取上次的高程数据
        $last_data=$houseMonitor->where('id','<',$id)
                                ->orderBy('id','desc')
                                ->first();
//        dd($last_data);

        //获取次数
        $count=$houseMonitor->where('id','<=',$id)
                            ->count();

        return view('stat.tunnel_house_monitor_detail',compact('init_data','this_data','count','last_data'));
    }

    /**
     * 隧道洞顶房屋沉降观测数据审核(监理用户使用)
     */
    public function tunnelHouseMonitorCheck($id,TunnelHouseMonitor $houseMonitor)
    {

        $user_id=$this->user->id;

        try{
            $info=$houseMonitor->find($id);

            $info->is_check=1;
            $info->check_user_id=$user_id;
            $info->check_time=time();
            $info->save();

            $result['status']=0;
            $result['info']='审核成功';
            return $result;

        }catch (Exception $e){

            $result['status']=1;
            $result['info']='审核失败';

            return $result;
        }

    }

    /**
     * 隧道洞顶房屋沉降观测数据修改(监理用户使用)
     */
    public function tunnelHouseMonitorEdit($id,TunnelHouseMonitor $tunnelHouseMonitor, Request $request)
    {
        if($request->isMethod('get')){

            $data=$tunnelHouseMonitor->find($id);

            return view('stat.tunnel_house_monitor_edit',compact('data'));
        }

        if($request->isMethod('post')){

            $data=$request->all();

            unset($data['_token']);

            try{

                $tunnelHouseMonitor->where('id',$id)->update($data);

                return Response()->json(['status'=>0,'mess'=>'修改成功']);
            }catch (Exception $e){

                return Response()->json(['status'=>1,'mess'=>'修改失败']);
            }
        }

    }








}
