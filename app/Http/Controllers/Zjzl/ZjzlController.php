<?php

namespace App\Http\Controllers\Zjzl;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mixplant\IndexController;
use Input, Auth, DB, Cache;
use App\Models\Device\Device,
    App\Models\User\User,
    App\Models\User\Log,
    App\Models\Project\Section,
    App\Models\Zjzl\Zjzl_info,
    App\Models\System\Stat_day,
    App\Models\System\Stat_week,
    App\Models\System\Stat_month,
    App\Models\System\Warn_deal_info,
    App\Models\System\Warn_user_set;

class ZjzlController extends IndexController
{
    protected $order = 'id desc';
    protected $device_cat = 5;
    protected $ispage = 20;
    protected $module = 13;
    public function __construct()
    {
        parent::__construct();
        view()->share(['module'=>'桩基质量']);
    }

    /*设备状态  当日生产数 报警数  报警未处理数*/
    public function index(){
        $model = new Zjzl_info;
        $url = url('zjzl/index');
        if($this->user->role == 1 || $this->user->role == 2){
            $view = 'zjzl.index_project';
        }else{
            $view = 'zjzl.index';
        }
        $data = $this->getIndex($model, $url, $view);
        
        return $data;
    }

    /*设备列表*/
    public function device()
    {
        $url = url('zjzl/device');
        $view = 'mixplant.snbhz.device';
        $data = $this->getDeviceList($url, $view);

        return $data;
    }


    /*生产数据*/
    public function zjData(){
        $url = url('zjzl/zj_data');
        $view = 'zjzl.zj_data';
        $data = $this->getProductData($url, $view);

        return $data;
    }

    /*某个设备的数据列表*/
    public function zjInfo($d_id){
        $cat_id = Input::get('cat_id');
        if(!$cat_id || !Input::get('pro_id')){
            $data['info'] = '参数错误';
            return view('admin.error.iframe_no_info', $data);
        }
        
        $model = new Zjzl_info;
        $field = [
            'id',
            'section_id',
            'device_id',
            'device_cat',
            'zxh',
            'azb1_fact',
            'bzb1_fact',
            'czsd_fact',
            'time',
            'czsj',
            'clcdl_fact'
        ];
        $view = 'zjzl.zj_data_info';
        $url = url('zjzl/zj_data_info/'.$d_id);
        $data = $this->getDataByDevice($d_id, $model, $field, $url, 0);

        if(!is_array($data)){
            return $data;
        }
        $data['url'] = $data['url'].'&cat_id='.$cat_id;
        //转换数据为地图可用数据
        foreach ($data['data'] as $key => $value) {
            $data['map'][$key]['name'] = $value['zxh'];
            $data['map'][$key]['value'][] = $value['azb1_fact'];
            $data['map'][$key]['value'][] = $value['bzb1_fact'];
            $data['map'][$key]['value'][] = $value['clcdl_fact'];
            $data['map'][$key]['value'][] = date('Y-m-d H:i:s', $value['time']);
            $data['map'][$key]['value'][] = date('Y-m-d H:i:s', $value['czsj']);
            $data['map'][$key]['value'][] = $value['czsd_fact'];
        }
        $data['map'] = json_encode($data['map']);
        unset($data['data']);
        return view($view, $data);
    }

    /*处理报警信息  此处根据具体需要设置哪些职位可以处理 暂时用的拌和站信息*/
    public function deal($id){
        //只有拌和站站长,操作手，拌和站信息管理员可以处理
        if($this->user->role ==5 && $this->user->position_id !=11 && $this->user->position_id !=12 && $this->user->position_id !=13){
            $result = ['status'=>0,'info'=>'您没有权限处理,只有拌和站站长，操作手，拌和站信息化管理员可以处理'];
            return Response()->json($result);
        }

        $model = new Zjzl_info;
        $deal_model = new Warn_deal_info;
        $url = url('zjzl/deal');
        //微信打开链接
        $info_url = url('wechat/zjzl_detail_info');
        $data = $this->doDeal($id, $model, $deal_model, $url, $request, $info_url);
        return $data;
    }

    /*报警信息*/
    public function warnInfo(){
        $field = 'id,project_id,supervision_id,section_id,device_id,time,is_sec_deal,is_sup_deal,warn_info,warn_level,created_at,warn_sx_level,warn_sx_info';
        $url = url('zjzl/warn_info');
        $view = 'zjzl.warn_info';
        $data = $this->getWarnInfo((new Zjzl_info), $field, $url, $view);
        
        return $data;
    }

    /*报警设置  此处根据具体需要设置哪些职位可以处理 暂时用的拌和站信息*/
    public function warnSet(Request $request){
        $url = url('zjzl/warn_set');
        $set_position = 12;
        $set_info = '您没有权限，只有标段信息化管理员可以设置';
        $model = new Warn_user_set;
        $user_model = new User;
        $data = $this->doWarnSet($request, 'zjzlwarn', $this->module, $url, $set_position, $set_info, $model, $user_model);
        
        return $data;
    }

    /*错误信息处理报告*/
    public function clbg(){
        $field = 'zjzl_info.id,zjzl_info.project_id,zjzl_info.supervision_id,zjzl_info.section_id,zjzl_info.device_id,zjzl_info.time,zjzl_info.jzbw,zjzl_info.warn_info,zjzl_info.warn_level,zjzl_info.warn_sx_level,zjzl_info.warn_sx_info,warn_deal_info.*';
        $model = new Zjzl_info;
        $view = 'zjzl.clbg';
        $left_table = 'zjzl_info';
        $data = $this->getClbg($model, $field, $view, $left_table);

        return $data;
    }

    /*错误信息处理台账*/
    public function tzlb(){
        $model = new zjzl_info;
        $left_table = 'zjzl_info';
        //如果是集团用户 显示各项目处理台账数量显示对比图
        if($this->user->role == 2 || $this->user->role == 1){
            $url = url('zjzl/tzlb');
            $view = 'system.tzlb_project';
            $data = $this->getTzlbPie($model, $url, $view, $left_table);
            return $data;
        }
        //如果是项目用户 点击显示各标段处理台账数量显示对比图
        if($this->user->role == 3 && Input::get('type') == "sec"){
            $url = url('zjzl/tzlb?type=sec');
            $view = 'system.tzlb_jsdw';
            $data = $this->getTzlbPie($model, $url, $view, $left_table);
            return $data;
        }
        $field = 'zjzl_info.id,zjzl_info.project_id,zjzl_info.supervision_id,zjzl_info.section_id,zjzl_info.device_id,zjzl_info.time,zjzl_info.warn_info,zjzl_info.warn_level,zjzl_info.warn_sx_level,zjzl_info.warn_sx_info,warn_deal_info.*';
        $view = 'zjzl.tzlb';
        $url = url('zjzl/tzlb');
        $data = $this->getTzlb($model, $field, $url, $view, $left_table);

        return $data;
    }

    /*统计每天登录次数*/
    public function statLogin(){
        $user_model = new User;
        $url = url('zjzl/stat_login');
        $module_id = $this->module;
        $view = 'system.stat_login';
        
        $data = $this->getLoginStat($user_model, $url, $module_id, $view);
        return $data;
    }

    public function statWeek(){
        $model = new Stat_week;
        $field = ['supervision_id','section_id','device_id','sc_num','scl','bj_num','cl_num','bhgl','week','created_at'];
        $url = url('zjzl/stat_week');
        //默认一个季度的  12周
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-84 day'));
        if(Input::get('d')){
            $view = 'zjzl.stat_week_export';
        }else{
            $view = 'zjzl.stat_week';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    public function statMonth(){
        $model = new Stat_month;
        $field = ['supervision_id','section_id','device_id','sc_num','scl','bj_num','cl_num','bhgl','month','created_at'];
        $url = url('zjzl/stat_month');
        //默认一年的
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-365 day'));
        if(Input::get('d')){
            $view = 'zjzl.stat_month_export';
        }else{
            $view = 'zjzl.stat_month';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    public function getSelectVal($type){
        $data = $this->getCommonSelectVal($type);
        return $data;
    }
}
