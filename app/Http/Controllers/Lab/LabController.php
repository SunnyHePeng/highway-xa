<?php

namespace App\Http\Controllers\Lab;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, DB, Cache;
use App\Http\Controllers\Mixplant\IndexController;
use App\Models\Device\Device,
    App\Models\Lab\Lab_info,
    App\Models\Lab\Lab_info_detail,
    App\Models\Lab\Lab_info_gjsy_detail,
    App\Models\Lab\Lab_warn_info,
    App\Models\Lab\Lab_user_warn,
    App\Models\Lab\OtherLabInfo,
    App\Models\User\User,
    App\Models\Project\Section,
    App\Models\Lab\Lab_warn_total,
    App\Models\System\Stat_day,
    App\Models\System\Stat_week,
    App\Models\System\Stat_month,
    App\Models\System\Warn_deal_info,
    App\Models\System\Warn_user_set;
use Qiniu\Config;
use Carbon\Carbon;
use App\Soap\LabStatistic;
use App\Extend\Helpers;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * 试验室（除集料试验）
 * Class LabController
 * @package App\Http\Controllers\Lab
 */
class LabController extends IndexController
{
    protected $order = 'id desc';
    protected $device_cat = [1,2,3];
    protected $ispage = 20;
    protected $module = 3;
    public function __construct()
    {
        parent::__construct();
        view()->share(['module'=>'试验室']);
    }

    public function index()
    {
        $model = new Lab_info;
        $url = url('lab/index');
        if($this->user->role == 1 || $this->user->role == 2){
            $view = 'lab.index_project';
        }else{
            $view = 'lab.index';
        }
        $data = $this->getIndex($model, $url, $view);
//        dd($data);
        return $data;
    }

    /*设备列表*/
    public function device()
    {
        $url = url('lab/device');
        $view = 'lab.device';
        $data = $this->getDeviceList($url, $view);

        return $data;
    }

    /*实验数据*/
    public function labData(){
        $model = new Lab_info;
        $url = url('lab/lab_data');
        $view = 'lab.lab_data';
        $title = '当日试验情况';

        $data = $this->getLabDataStat($model, $url, $view, $title);
        return $data;
    }

    /*某个设备的实验数据列表*/
    public function labInfo(){
        $model = new Lab_info;
        $field = [
                'id',
                'device_id',
                'time',
                'syzh',
                'lbph',
                'qddj',
                'sypz',
                'sjgs',
                'sylx',
                'syry',
                'is_warn',
                'is_sec_deal',
                'is_sup_deal',
                'is_pro_deal',
                'warn_level',
                'warn_sx_level',
                'reportFile'
            ];
        $all_sylx=array_keys(Config()->get('common.sylx'));

        $url = url('lab/lab_data_info');
        $data = $this->getLabInfo($model, $field, $url,$all_sylx);

        if(!is_array($data)){
            return $data;
        }
        $data['symc'] = Config()->get('common.sylx');
//        dd($data);
        return view('lab.lab_data_info', $data);
    }

    /*获取某条实验数据信息*/
    public function getDetailInfo(){
        $id = Input::get('id');
        if(!$id){
            return view('admin.error.no_info', ['info'=>'信息错误']);
        }

        $data['lab_info'] = Lab_info::where('id', $id)
                                        ->with(['project'=>function($query){
                                            $query->select(['id','name']);
                                        },'section'=>function($query){
                                            $query->select(['id','name']);
                                        },'sup'=>function($query){
                                            $query->select(['id','name']);
                                        },'device'=>function($query){
                                            $query->select(['id','dcode','model']);
                                        }])
                                        ->first()
                                        ->toArray();
        //钢筋实验和其他实验的试件信息不再一个表
        if(in_array($data['lab_info']['sylx'], [1,2,3,4,11,12,13])){
            $data['detail_info'] = Lab_info_gjsy_detail::where('lab_info_id', $id)->orderByRaw('type asc')->get()->toArray();
        }else{
            $data['detail_info'] = Lab_info_detail::where('lab_info_id', $id)->orderByRaw('type asc')->get()->toArray();
        }
        //获取处理信息
        if($data['lab_info']['is_warn']){
            $data['deal_info'] = $this->getDealInfo((new Warn_deal_info), $id, $data['lab_info']['device_id']);
        }
        $data['symc'] = Config()->get('common.sylx');
//        dd($data);
        //类型不同显示的页面不同
        switch ($data['lab_info']['sylx']) {
            case '砂浆抗压':
                return view('lab._show_sjky_info', $data);
                break;
            case '混凝土抗压':
                dd($data);
                return view('lab._show_sjky_info', $data);
                break;
            case '混凝土抗折':
                return view('lab._show_hntkz_info', $data);
                break;
            case '水泥胶砂抗折':
                return view('lab._show_sjky_info', $data);
                break;
            case '水泥胶砂抗压':
                return view('lab._show_sjky_info', $data);
                break;
            case '5':
                return view('lab._show_sjky_info', $data);
                break;
            case '6':
                return view('lab._show_sjky_info', $data);
                break;
            case '7':
                return view('lab._show_hntkz_info', $data);
                break;
            case '8':
                return view('lab._show_hntkz_info', $data);
                break;
            case '9':
                return view('lab._show_sjky_info', $data);
                break;
            case '10':
                return view('lab._show_sjky_info', $data);
                break;
            default:
                return view('lab._show_gjls_info', $data);
                break;
        }
        return view('lab._show_info', $data);
        
        $result = ['status'=>1,'info'=>'获取成功','data'=>$data];
        return Response()->json($result);
    }
    /**
     * 获取详情视频
     * **/
    public function getVideo(Request $request){
        $labInfo = Lab_info::where('id', $request->id)->first()->toArray();

        //钢筋实验和其他实验的试件信息不再一个表
        if(in_array($labInfo['sylx'], [1,2,3,4])){
            $labInfoDetails = Lab_info_gjsy_detail::where('lab_info_id', $request->id)->orderByRaw('type asc')->get();
        }else{
            $labInfoDetails = Lab_info_detail::where('lab_info_id', $request->id)->orderByRaw('type asc')->get();
        }
//        dd($labInfoDetails);
        return view("lab.labVideo",['labInfoDetails'=>$labInfoDetails]);
    }
    /*处理报警信息*/
    public function deal($id, Request $request){
        //只有试验室信息化管理员，试验室主任可以处理
        if($this->user->role ==5 && $this->user->position_id !=9 && $this->user->position_id !=10){
            $result = ['status'=>0,'info'=>'您没有权限处理,只有试验室信息化管理员/试验室主任可以处理'];
            return view('admin.error.iframe_no_info', $result);
        }

        $model = new Lab_info;
        $deal_model = new Warn_deal_info;
        $url = url('lab/deal');
        //微信打开链接
        $info_url = url('wechat/lab_detail_info');
        $data = $this->doDeal($id, $model, $deal_model, $url, $request, $info_url);
        return $data;
    }

    /*实验次数统计*/
    public function sectionReport()
    {
        $title = '试验';
        $url = url('lab/section_report');
        $view = 'system.tzlb_project';
        $type = 'lab';  
        $model = new Lab_info;
         
        $data = $this->getSectionReport($model, $title, $url, $view, $type);
        return $data;
    }

    /*各类型实验数据统计*/
    public function typeReport()
    {
        $title = '试验';
        $url = url('lab/type_report');
        $view = 'lab.type_report';
        $type = 'lab';  
        $model = new Lab_info;
         
        $data = $this->getTypeReport($model, $title, $url, $view, $type);
        return $data;
    }

    /*报警信息*/
    public function warnInfo(){
        $field = 'id,project_id,supervision_id,section_id,device_id,time,syzh,is_sec_deal,is_sup_deal,warn_info,warn_level,created_at,warn_sx_level,warn_sx_info,reportFile';
        $url = url('lab/warn_data');
        $view = 'lab.warn_info';
        $data = $this->getWarnInfo((new Lab_info), $field, $url, $view);

        return $data;
    }


    /*报警信息统计*/
    public function warnReport(){
        $model = new Stat_day;
        $field = ['supervision_id','section_id','device_id','bj_num','date'];
        $url = url('lab/warn_report');
        //默认一周
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-6 day'));
        if(Input::get('d')){
            $view = 'lab.warn_report_export';
        }else{
            $view = 'lab.warn_report';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);
        
        return $data;
    }

    /*实验报警次数统计*/
    public function sectionWarnReport()
    {
        $title = '报警';
        $url = url('lab/section_warn_report');
        $view = 'system.tzlb_project';
        $type = 'warn';  
        $model = new Lab_info;
         
        $data = $this->getSectionReport($model, $title, $url, $view, $type);
        return $data;
    }

    /*各类型实验数据报警统计*/
    public function typeWarnReport()
    {
        $title = '报警';
        $url = url('lab/type_warn_report');
        $view = 'lab.type_report';
        $type = 'warn';  
        $model = new Lab_info;
         
        $data = $this->getTypeReport($model, $title, $url, $view, $type);
        return $data;
    }

    protected function sectionAndTypeDataReport($url, $field, $group, $name){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        if(!$sup_id && !$sec_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }
        $query = Lab_info::select(DB::raw($field));
        if($pro_id){
            if($this->user->role != 1 && $this->user->role != 2){
                $pro_id = $this->user->project[0];
            }
            $query = $query->where('project_id', $pro_id);
        }
        if($sup_id){
            if($this->user->role == 4 || $this->user->role == 5){
                $sup_id = $this->user->supervision_id;
            }
            $query = $query->where('supervision_id', $sup_id);
        }

        if($sec_id){
            if($this->user->role == 5){
                $sec_id = $this->user->section_id;
            }
            $query = $query->where('section_id', $sec_id);
        }

        $date = Input::get('date') ? Input::get('date') : date('Y-m-d');
        $start_time = strtotime($date);
        $end_time = $start_time + 86400;
        $query = $query->where('time', '>=', $start_time)
                       ->where('time', '<', $end_time);
        if($group == 'section_id'){
            $query = $query->with(['section'=>function($query){
                                $query->select(['id','name']);
                           }]);
        }
        $list = $query ->groupBy($group)
                       ->get()
                       ->toArray();

        /*$chart = [
            'sub_title'=>$date,
            'categories'=>'',
            'series'=>'',
            ];*/

        //if($list){
            if($group == 'sylx'){
                $categories = Config()->get('common.sylx');
                foreach ($categories as $key => $value) {
                    $categories[$key-1] = $value;
                }
                unset($categories[$key]);
                ksort($categories);
            }else{
                $categories = [];
                if($this->user_section['section_list']){
                    foreach ($this->user_section['section_list'] as $key => $value) {
                        $categories[$key] = $value['name'];
                    }  
                }
            }
            $series = $this->getSeries($group, $categories, $list);
            $series[0]['name'] = $name;
            $chart = [
                'sub_title'=>$date,
                'categories'=>$categories,
                'series'=>$series,
                ];
        //}

        $list['date'] = $date;
        $list['chart'] = json_encode($chart);

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);

        return array_merge($list, $tree_data);
    }

    protected function sectionAndTypeWarnReport($url, $field, $group, $name){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        if(!$sup_id && !$sec_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }
        $query = Lab_warn_info::select(DB::raw($field));
        if($pro_id){
            if($this->user->role != 1 && $this->user->role != 2){
                $pro_id = $this->user->project[0];
            }
            $query = $query->where('project_id', $pro_id);
        }
        if($sup_id){
            if($this->user->role == 4 || $this->user->role == 5){
                $sup_id = $this->user->supervision_id;
            }
            $query = $query->where('supervision_id', $sup_id);
        }

        if($sec_id){
            if($this->user->role == 5){
                $sec_id = $this->user->section_id;
            }
            $query = $query->where('section_id', $sec_id);
        }

        $date = Input::get('date') ? Input::get('date') : date('Y-m-d');
        $start_time = strtotime($date);
        $end_time = $start_time + 86400;
        $query = $query->where('time', '>=', $start_time)
                       ->where('time', '<', $end_time);
        if($group == 'section_id'){
            $query = $query->with(['section'=>function($query){
                                $query->select(['id','name']);
                           }]);
        }
        $list = $query ->groupBy($group)
                       ->get()
                       ->toArray();

        $categories = [];
        $series = [];
        if($group == 'warn_type'){
            if($list){
                foreach ($list as $key => $value) {
                    $categories[$key] = $value['warn_type'];
                    $series[0]['data'][$key] = $value['num'];
                }
                $series[0]['name'] = $name;
            }
        }else{
            $categories = [];
            if($this->user_section['section_list']){
                foreach ($this->user_section['section_list'] as $key => $value) {
                    $categories[$key] = $value['name'];
                }  
            }
            $series = $this->getSeries($group, $categories, $list);
            $series[0]['name'] = $name;
        }
            
        $chart = [
                'sub_title'=>$date,
                'categories'=>$categories,
                'series'=>$series,
                ];

        $list['date'] = $date;
        $list['chart'] = json_encode($chart);

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);

        return array_merge($list, $tree_data);
    }

    protected function getSeries($type, $categories, $list){
        $series = [];
        if(!$list){
            foreach ($categories as $key => $value) {
                $series[0]['data'][$key] = 0;
            }
        }else{
            if($type == 'sylx'){
                foreach ($categories as $k => $v) {
                    $is_has = false;
                    foreach ($list as $key => $value) {
                        if($k == $value['sylx']-1){
                            $series[0]['data'][] = $value['num'];
                            $is_has = true;
                        }
                    }
                    if(!$is_has){
                        $series[0]['data'][] = 0;
                    }
                }
            }else{
                foreach ($categories as $k => $v) {
                    $is_has = false;
                    foreach ($list as $key => $value) {
                        if($v == $value['section']['name']){
                            $series[0]['data'][] = $value['num'];
                            $is_has = true;
                        }
                    }
                    if(!$is_has){
                        $series[0]['data'][] = 0;
                    }
                }
            }
        }
        //var_dump($series);
        return $series;
    }

    /*跳转到外部系统*/
    public function olab(){
        $lab_url = Config()->get('common.lab_url');
//        $client = new \SoapClient($lab_url."/LoginService/Service1.asmx?wsdl");
        /*var_dump($this->user->password);
        exit();*/
//        $result = $client->Encrypt(array('s'=>$this->user->username));
//        $userName = $result->EncryptResult;

        $user_id=(string)$this->user->id;

        $url = $lab_url.'/pkpmaspx/AjaxOther/Ajax_autoLogin3.aspx?id='.$user_id;
        header('location:'.$url);
        exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout on connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout on
        $info= curl_exec($ch);
        curl_close($ch);
        return $info;
        
    }

    /*报警设置*/
    public function warnSet(Request $request){
        $url = url('lab/warn_set');
        $set_position = 9;
        //$set_info = '您没有权限，只有试验室信息化管理员可以设置';
        $set_info = '您没有权限，只有信息化管理员可以设置';
        $model = new Warn_user_set;
        $user_model = new User;
        $data = $this->doWarnSet($request, 'warnuser', $this->module, $url, $set_position, $set_info, $model, $user_model);

        return $data;
    }

    /*错误信息处理报告*/
    public function clbg(){
        $field = 'lab_info.id,lab_info.project_id,lab_info.supervision_id,lab_info.section_id,lab_info.device_id,lab_info.time,lab_info.sydw,lab_info.sylx,lab_info.syry,lab_info.jldw,lab_info.warn_info,lab_info.warn_level,lab_info.warn_sx_level,lab_info.warn_sx_info,warn_deal_info.*';
        $model = new Lab_info;
        $view = 'lab.clbg';
        $left_table = 'lab_info';
        $data = $this->getClbg($model, $field, $view, $left_table);

        return $data;
    }

    /*错误信息处理台账*/
    public function tzlb(){
        $model = new Lab_info;
        $left_table = 'lab_info';
        //如果是集团用户 显示各项目处理台账数量显示对比图
        if($this->user->role == 2 || $this->user->role == 1){
            $url = url('lab/tzlb');
            $view = 'system.tzlb_project';
            $data = $this->getTzlbPie($model, $url, $view, $left_table);
            return $data;
        }
        //如果是项目用户 点击显示各标段处理台账数量显示对比图
        if($this->user->role == 3 && Input::get('type') == "sec"){
            $url = url('lab/tzlb?type=sec');
            $view = 'system.tzlb_jsdw';
            $data = $this->getTzlbPie($model, $url, $view, $left_table);
            return $data;
        }
        $field = 'lab_info.id,lab_info.project_id,lab_info.supervision_id,lab_info.section_id,lab_info.device_id,lab_info.time,lab_info.sydw,lab_info.sybh,lab_info.sylx,lab_info.syry,lab_info.jldw,lab_info.warn_info,lab_info.warn_level,lab_info.warn_sx_level,lab_info.warn_sx_info,lab_info.created_at,warn_deal_info.*';
        
        $view = 'lab.tzlb';
        $url = url('lab/tzlb');
        $data = $this->getTzlb($model, $field, $url, $view, $left_table);

        return $data;
    }

    /*统计管理人员每天登录次数*/
    public function statLogin(){
        $user_model = new User;
        $url = url('lab/stat_login');
        $module_id = $this->module;
        $view = 'system.stat_login';

        $data = $this->getLoginStat($user_model, $url, $module_id, $view);
        return $data;
    }

    public function statWeek(){
        $model = new Stat_week;
        $field = ['supervision_id','section_id','device_id','sc_num','bj_num','cl_num','bhgl','week','created_at'];
        $url = url('lab/stat_week');
        //默认一个季度的  12周
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-84 day'));
        if(Input::get('d')){
            $view = 'lab.stat_week_export';
        }else{
            $view = 'lab.stat_week';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    public function statMonth(){
        $model = new Stat_month;
        $field = ['supervision_id','section_id','device_id','sc_num','bj_num','cl_num','bhgl','month','created_at'];
        $url = url('lab/stat_month');
        //默认一年的
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-365 day'));
        if(Input::get('d')){
            $view = 'lab.stat_month_export';
        }else{
            $view = 'lab.stat_month';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    public function video($device_id){
        if ($device_id == 'all') {
            return view('lab.allVideo');
        }
        $model = Device::find($device_id);
        $data = [];
        $data['device'] = $model;
        $data["project_id"] = Input::get('pro_id');
        return view('lab.video', $data);
    }

    public function getSelectVal($type){
        $data = $this->getCommonSelectVal($type);
        return $data;
    }

    /*获取当前未处理的报警信息*/
    public function getTodayWarnLab()
    {
        $start_date=date('Y-m-d', strtotime('-3 day'));
        $end_date=date('Y-m-d');
        $field = 'id,project_id,supervision_id,section_id,device_id,time,syzh,is_sec_deal,is_sup_deal,warn_info,warn_level,created_at,warn_sx_level,warn_sx_info,reportFile';

        $data = $this->getNowWarn((new Lab_info), $field,$start_date,$end_date);
        return $data;
    }

    /*在试验室实时视频页面展示当前设备的试验数据*/
    public function getDataAtVideo($device_id)
    {

        $model=new Lab_info();
        $field=['syzh','sylx','time','sybh'];
        $decode=Input::get('decode');
        //设备编号
//        $device_code='';
//        $video=Config()->get('common.video');

//        foreach($video as $k=>$v){
//            if($v['cam_num']==$decode){
//               $device_code=$k;
//            }
//        }
        //设备id
//        $device_id=$this->getDeiceByCode($device_code);
//        dd($device_id);
        //试验类型
        $cat_id=(new Device)->select('id','cat_id')->where('id',$device_id)->first()->cat_id;
        $category_sylx=Config()->get('common.category_sylx');
        $device_sylx=$category_sylx[$cat_id];

        $sylx_all=Config()->get('common.sylx');
        $sylx_all=array_diff($sylx_all,['混凝土弹性模量','混凝土收缩']);
        $sylx=[];
        foreach ($device_sylx as $v){
            if(array_key_exists($v,$sylx_all)){
                $sylx[$v]=$sylx_all[$v];
            }
        }
//        dd($sylx);

        //获取数据
        $data=$this->getLabInfoAtVideo($model,$device_id,$sylx);
        $data['decode']=$decode;
//        dd($data);
        return view('lab.get_data_atvideo',$data);

    }

    /*其他试验数据*/
    public function otherLab()
    {
        $model = new OtherLabInfo;
        $url = url('lab/other_lab');
        $view = 'lab.other_lab_data';
        $title = '当日试验情况';

        $data = $this->getLabDataStat($model, $url, $view, $title);
        return $data;
    }
    /*其他试验数据列表*/
    public function otherLabInfo()
    {
        $model = new OtherLabInfo;
        $field = [
            'id',
            'device_id',
            'time',
            'syzh',
            'lbph',
            'qddj',
            'sypz',
            'sjgs',
            'sylx',
            'syry',
            'is_warn',
            'is_sec_deal',
            'is_sup_deal',
            'is_pro_deal',
            'warn_level',
            'warn_sx_level',
            'reportFile'
        ];
        $url = url('lab/other_lab_data_info');
        $data = $this->getLabInfo($model, $field, $url);

        if(!is_array($data)){
            return $data;
        }
        $data['symc'] = Config()->get('common.sylx');

        return view('lab.other_lab_data_info', $data);
    }





    /*其他试验数据统计*/
    public function otherSectionReport()
    {
        $title = '其他试验';
        $url = url('lab/other_section_report');
        $view = 'system.tzlb_project';
        $type = 'lab';
        $model = new OtherLabInfo;

        $data = $this->getSectionReport($model, $title, $url, $view, $type);
        return $data;
    }

    /*各类型试验数据统计*/
    public function otherTypeReport()
    {
        $title = '试验';
        $url = url('lab/type_report');
        $view = 'lab.type_report';
        $type = 'lab';
        $model = new OtherLabInfo;

        $data = $this->getTypeReport($model, $title, $url, $view, $type);
        return $data;
    }


    /*获取设备信息*/
    protected function getDeiceByCode($device_code)
    {
        $model=new Device();
        $field=['id','cat_id','dcode','model','name'];
        $device_info=$model->select($field)
                           ->where('dcode',$device_code)
                           ->first();

        $device_id=$device_info['id'];

        return $device_id;
    }

    /* 实时视频页面中获取当前设备的试验数据*/
    protected function getLabInfoAtVideo($model,$device_id,$sylx_all)
    {
        $ispage=3;
        $fields=['id',
            'device_id',
            'device_type',
            'device_cat',
            'time',
            'sybh',
            'syzh',
            'sylx',
            'sypz',
            'reportFile',
            'is_warn'
             ];
        $url=url('lab/getDataAtVideo').'/'.$device_id;

        $sylx=Input::get('sylx');
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');

        $query=$model->select($fields)->where('device_id',$device_id);

        $search['device_id']=$device_id;
        $sylx= $sylx ? $sylx : 0;
        $start_date= $start_date ? $start_date : date('Y-m-d',strtotime('-1 day'));
        $end_date= $end_date ? $end_date : date('Y-m-d',time());
        $url=$url.'?sylx='.$sylx.'&start_date='.$start_date.'&end_date='.$end_date;
        $search['start_date']=$start_date;
        $search['end_date']=$end_date;
        $search['sylx_all']=$sylx_all;
        $search['num']=1;
        $search['now_date']=strtotime(date('Y-m-d',time()));
        $search['tomorrow_date']=strtotime(date('Y-m-d',time()+86400));
        $sylx= $sylx ? $sylx : 0;
        if($sylx !=0){
            $query=$query->where('sylx',$sylx)
                         ->whereBetween('time',[strtotime($start_date),strtotime($end_date)+86400]);
            $search['sylx']=$sylx;
        }else{
            $query=$query
                ->whereBetween('time',[strtotime($start_date),strtotime($end_date)+86400]);
        }

        $sy_data=$query->orderBy('id','desc')
            ->get()
            ->toArray();
        $data=$query->orderBy('id','desc')
                    ->paginate($ispage)
                    ->toArray();

        $report_data=[];
        if(!empty($sy_data)){
                    $report_data=$sy_data[0];
        }


        $total_num=count($sy_data);

        $data['search']=$search;
        $data['url']=$url;
        $sylx_num=[];
        foreach ($sylx_all as $v){
            $sylx_num[$v]=0;
        }

        foreach($sy_data as $v){
             if(array_key_exists($v['sylx'],$sylx_all)){
                 $sylx_num[$sylx_all[$v['sylx']]]=$sylx_num[$sylx_all[$v['sylx']]]+1;
             }
        }

       $data['sylx_num']=$sylx_num;
        $data['total_num']=$total_num;
        $data['report_data']=$report_data;

        return $data;


    }


    /**
     * 标段统计
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Soap\LabStatistic $lab
     * @return \Illuminate\Http\Response
     */
    public function statisticBySection(Request $request, LabStatistic $lab)
    {
        $param = [
            'StartTime' => Carbon::today()->startOfWeek()->toDateString(),
            'EndTime' => Carbon::today()->toDateString(),
        ];

        if($this->user->isNotAdminOrGroup())
            $param['stationcode'] = $this->user->sections->first()->station_code;

        if($request->has('start_date', 'end_date')){
            $param['StartTime'] = $request->start_date;
            $param['EndTime'] = $request->end_date;
        }
        
        $subtitle = sprintf('%s 至 %s', $param['StartTime'], $param['EndTime']);
        
        $station = $lab->GetStationCount($param);

        $station = isset($station['Table']) ? $station['Table'] : [];

        list($series, $legend) = Helpers::highChartColumnWrapper($station);

        return view('lab.manual.section', compact('series', 'legend', 'subtitle'));
    }

    /**
     * 检测项目统计
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Soap\LabStatistic $lab
     * @return \Illuminate\Http\Response
     */
    public function statisticByItem(Request $request, LabStatistic $lab)
    {
        $param = [
            'StartTime' => Carbon::today()->startOfWeek()->toDateString(),
            'EndTime' => Carbon::today()->toDateString(),
        ];

        if($this->user->isNotAdminOrGroup())
            $param['stationcode'] = $this->user->sections->first()->station_code;

        if($request->has('start_date', 'end_date')){
            $param['StartTime'] = $request->start_date;
            $param['EndTime'] = $request->end_date;
        }

        $subtitle = sprintf('%s 至 %s', $param['StartTime'], $param['EndTime']);

        $item = $lab->GetTestItemCount($param);
        $item = isset($item['Table']) ? $item['Table'] : [];

        list($series, $legend) = Helpers::highChartColumnWrapper($item);

        return view('lab.manual.item', compact('series', 'legend', 'subtitle'));
    }

    /**
     * 试验报告
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Soap\LabStatistic $lab
     * @return \Illuminate\Http\Response
     */
    public function labReport(Request $request, LabStatistic $lab)
    {
        $sections = $this->user->sections;

        $section_search = $request->has('section') ? collect($request->section) : $sections->pluck('station_code');

        $section_where_in = Helpers::implodeCollectToSQLsWhereInString($section_search);

        $param = [
            'pageSize' => 15,
            'pageNo' => request('page', 1),
            'where' => "substring(sysPrimaryKey,6,2) in ({$section_where_in})"
        ];

        $res = $lab->GetReportByCondition($param);

        //处理接口数据
        if(!$total = $res['Table']['rsCount']){
            //没有数据时，接口连个空数组都不给
            $items = [];
        }elseif($total == 1){
            //只有一条数据时，竟然返回个对象
            $items = [$res['Table1']];
        }else{
            //终于正常了
            $items = $res['Table1'];
        }

        $reports = new LengthAwarePaginator($items, $res['Table']['rsCount'], $param['pageSize'], $param['pageNo'], ['path' => '/lab/lab_report']); 

        return view('lab.manual.report_list', compact('reports', 'sections'));
    }    

}
