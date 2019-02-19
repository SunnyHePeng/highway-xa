<?php

namespace App\Http\Controllers\Mixplant;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, Auth, DB, Cache;
use App\Models\Device\Device,
    App\Models\User\User,
    App\Models\User\Log,
    App\Models\Project\Section,
    App\Models\Bhz\Snbhz_info,
    App\Models\Bhz\Snbhz_info_detail_new,
    App\Models\Bhz\Snbhz_warn_info,
    App\Models\Bhz\Snbhz_warn_total,
    App\Models\Bhz\Snbhz_product_total,
    App\Models\System\Stat_day,
    App\Models\System\Stat_week,
    App\Models\System\Stat_month,
    App\Models\Bhz\Snbhz_user_warn,
    App\Models\System\Warn_deal_info,
    App\Models\System\Warn_user_set;
use App\Models\Bhz\SnbhzDeviceFailurePushUser;
use App\YSCloud\YSCloud;

/**
 * 拌合站
 * Class SnbhzController
 * @package App\Http\Controllers\Mixplant
 */
class SnbhzController extends IndexController
{
    protected $order = 'id desc';
    protected $device_cat = 4;
    protected $ispage = 20;
    protected $module = 4;
    public function __construct()
    {
        parent::__construct();
        view()->share(['module'=>'拌和站']);
    }

    public function index(){
        $model = new Snbhz_info;
        $url = url('snbhz/index');
        if($this->user->role == 1 || $this->user->role == 2){
            $view = 'mixplant.project.index';
        }else{
            $view = 'mixplant.snbhz.index';
        }
        $data = $this->getIndex($model, $url, $view);
        
        return $data;
    }

    /*设备列表*/
    public function device()
    {
        $url = url('snbhz/device');
        $view = 'mixplant.snbhz.device';
        $data = $this->getDeviceList($url, $view);

        return $data;
    }


    /*拌合生产数据*/
    public function productData(){
        $url = url('snbhz/product_data');
        $view = 'mixplant.snbhz.product_data';
        $data = $this->getProductData($url, $view);

        return $data;
    }

    /*某个设备的拌合数据列表*/
    public function productInfo($d_id){
        $model = new Snbhz_info;
        $field = [
                'id',
                'section_id',
                'device_id',
                'time',
                'scdw',
                'sgdw',
                'sgdd',
                'jzbw',
                'is_warn',
                'warn_level',
                'warn_sx_level',
                'warn_info',
                'operator',
                'is_sec_deal',
                'is_sup_deal',
                'is_pro_deal',
                'pbbh',
                'pfl'
            ]; 
        $url = url('snbhz/product_data_info/'.$d_id);
        $data = $this->getDataByDevice($d_id, $model, $field, $url);

        if(!is_array($data)){
            return $data;
        }

        return view('mixplant.snbhz.product_data_info', $data);
    }

    /*处理报警信息*/
    public function deal($id, Request $request){
        //只有拌和站站长,操作手，拌和站信息管理员可以处理
        if($this->user->role ==5 && $this->user->position_id !=11 && $this->user->position_id !=12 && $this->user->position_id !=13){
            $result = ['status'=>0,'info'=>'您没有权限处理,只有拌和站站长，操作手，拌和站信息化管理员可以处理'];
            return view('admin.error.iframe_no_info', $result);
        }

        $model = new Snbhz_info;
        $deal_model = new Warn_deal_info;
        $url = url('snbhz/deal');
        //微信打开链接
        $info_url = url('wechat/snbhz_detail_info');
        $data = $this->doDeal($id, $model, $deal_model, $url, $request, $info_url);
        return $data;
    }

    /*获取某条拌合信息的物料和处理信息*/
    public function getDetailInfo(){
        $id = Input::get('id');
        if(!$id){
            $result = ['status'=>0,'info'=>'获取失败'];
            return Response()->json($result);
        }

        $data['snbhz_info'] = Snbhz_info::where('id', $id)
                                        ->with(['project'=>function($query){
                                            $query->select(['id','name']);
                                        }])
                                        ->first()
                                        ->toArray();
        $data['snbhz_info']['time'] = date('Y-m-d H:i', $data['snbhz_info']['time']);                                
        $data['detail_info'] = Snbhz_info_detail_new::where('snbhz_info_id', $id)->orderByRaw('type asc')->get()->toArray();
        if($data['snbhz_info']['is_warn']){
            $data['deal_info'] = $this->getDealInfo((new Warn_deal_info), $id, $data['snbhz_info']['device_id']);
        }
        $data['snbhz_detail'] = Config()->get('common.snbhz_info_detail');
        //var_dump($data);
        return view('mixplant.snbhz.show_bh_info', $data);
    }

    /*拌合数据报表*/
    public function dataReport(){
        //获取拌合设备
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $field = ['id','name','dcode'];
        $device = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
        if(!$device){
            $tree_data = $this->getPageTreeDataAndUrl(url('snbhz/data_report'), $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无设备信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        $url_para = '';

        //获取拌合信息
        $field = [
                'id',
                'supervision_id',
                'section_id',
                'device_id',
                'time',
                'pbbh',
                'pfl',
                'cph'
            ];
        $query = Snbhz_info::select($field);

        $d_id = Input::get('d_id') ? Input::get('d_id') : $device[0]['id'];  
        if($d_id){
            $search['d_id'] = $d_id;
            $query = $query->where('device_id', '=', $d_id);
            $url_para .= $url_para ? '&d_id='.$d_id : 'd_id='.$d_id;
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
            $url_para .= $url_para ? '&start_date='.$start_date : 'start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<', strtotime($end_date)+86400);
            $url_para .= $url_para ? '&end_date='.$end_date : 'end_date='.$end_date;
        }
        
        $query = $query->with(['section'=>function($query){
                            $query->select(['id','name']);
                        },'sup'=>function($query){
                            $query->select(['id','name']);
                        },'device'=>function($query){
                            $query->select(['id','dcode','model']);
                        }, 'detail'])
                       ->orderByRaw($this->order);
        $d = Input::get('d');
        if($d == 'all'){
            $list['data'] = $query->get()->toArray();
        }else{
            $list = $query->paginate($this->ispage)
                          ->toArray();
        }
        
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        if($d){
            return view('mixplant.snbhz.data_report_export', $list);
        }

        $url = url('snbhz/data_report');
        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);

        $list['device'] = $device;
        $list['search'] = $search;
        
        if(stripos('?', $tree_data['url'])){
            $url_lj = '&';
        }else{
            $url_lj = '?';
        }
        $tree_data['url'] = $url_para ? $tree_data['url'].$url_lj.$url_para : $tree_data['url'];
        return view('mixplant.snbhz.data_report', array_merge($list, $tree_data));
    }

    /*拌合数据曲线*/
    public function dataCurve(){
        $data = $this->getCurve('fact', url('snbhz/data_curve'));
        if(!is_array($data)){
            return $data;
        }
        return view('mixplant.snbhz.data_curve', $data);
    }

    /*偏差率曲线*/
    public function deviationCurve(){
        $data = $this->getCurve('pcl', url('snbhz/deviation_curve'));
        if(!is_array($data)){
            return $data;
        }
        return view('mixplant.snbhz.data_pcl_curve', $data);
    }

    /*报警信息*/
    public function warnInfo(){
        $field = 'id,project_id,supervision_id,section_id,device_id,time,is_sec_deal,is_sup_deal,warn_info,warn_level,created_at,warn_sx_level,warn_sx_info';
        $url = url('snbhz/warn_info');
        $view = 'mixplant.snbhz.warn_info';
        $data = $this->getWarnInfo((new Snbhz_info), $field, $url, $view);
        
        return $data;
    }


    /*报警信息统计*/
    public function warnReport(){
        $model = new Stat_day;
        $field = ['supervision_id','section_id','device_id','bj_num','date'];
        $url = url('snbhz/warn_report');
        //默认一周
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-6 day'));
        if(Input::get('d')){
            $view = 'mixplant.snbhz.warn_report_export';
        }else{
            $view = 'mixplant.snbhz.warn_report';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);
        
        return $data;
    }

    /*报警对比图*/
    public function warnCompare(){
        $data = $this->getTotalColumnData((new Stat_day), url('snbhz/warn_compare'));
        if(!is_array($data)){
            return $data;
        }
        return view('mixplant.snbhz.warn_compare', $data);
    }

    /*日生产统计*/
    public function productReport(){
        $model = new Stat_day;
        $field = ['supervision_id','section_id','device_id','sc_num','scl','bj_num','cl_num','bhgl','date'];
        $url = url('snbhz/product_report');
        //默认一个季度的  12周
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-84 day'));
        if(Input::get('d')){
            $view = 'mixplant.snbhz.product_report_export';
        }else{
            $view = 'mixplant.snbhz.product_report';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    /*生产对比图 周统计*/
    public function productCompare(){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $url = url('snbhz/product_compare');
        //DB::connection()->enableQueryLog();
        $model = new Snbhz_stat_week;
        $query = $model->select(['device_id','sc_num','bj_num','bhgl','date_start','date_end','week']);
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
        //用户可访问的section
        $section = $this->getUserSection($pro_id, $sup_id);
        //var_dump($section);
        if(!$section){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无标段信息';
            return view('admin.error.tree_no_info', $tree_data);
        }

        
        $sec_id = Input::get('sec_id') ? Input::get('sec_id') : $section[0]['id'];

        $field = ['id','name','dcode'];
        $device = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
        if(!$device){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无设备信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        
        if($sec_id){
            if(is_array($sec_id)){
                $query = $query->whereIn('section_id', $sec_id);
            }else{
                $query = $query->where('section_id', $sec_id);
            }
            $search['sec_id'] = json_encode($sec_id);
        }
        //获取日期周数
        $list['date'] = $this->getDate();
        $week = Input::get('week') ? Input::get('week') : date('W')-1;
        if($week){
            $search['week'] = $week;
            $query = $query->where('week', '=', $week);
        }

        $info = $query->orderByRaw('id desc')
                      ->get()
                      ->toArray();
        //print_r(DB::getQueryLog());
        //组合表格数据
        $sub_title = $list['date'][$week];
        $categories = [];
        $series = [];
        if($device){
            $series[0]['name'] = '生产次数';
            $series[1]['name'] = '报警次数';
            $series[2]['name'] = '不合格率';
            foreach ($device as $key => $value) {
                $is_cz = 0;
                $categories[$key] = $value['name'].'-'.$value['dcode'];
                foreach ($info as $k => $v) {
                    if($value['id'] == $v['device_id']){
                        $series[0]['data'][$key] = $v['sc_num'];
                        $series[1]['data'][$key] = $v['bj_num'];
                        $series[2]['data'][$key] = $v['bhgl'];
                        $is_cz = 1;
                    }
                }
                if(!$is_cz){
                    $series[0]['data'][$key] = 0;
                    $series[1]['data'][$key] = 0;
                    $series[2]['data'][$key] = 0;
                }
            }  
        }

        $chart = [
                'sub_title'=>$sub_title,
                'categories'=>$categories,
                'series'=>$series,
                ];
        $list['section'] = $section;
        $list['chart'] = json_encode($chart);
        $list['search'] = $search;
        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        return view('mixplant.snbhz.product_compare', array_merge($list, $tree_data));
    }

    /*报警设置*/
    public function warnSet(Request $request){
        $url = url('snbhz/warn_set');
        $set_position = 12;
        //$set_info = '您没有权限，只有拌和站信息化管理员可以设置';
        $set_info = '您没有权限，只有信息化管理员可以设置';
        $model = new Warn_user_set;
        $user_model = new User;
        $data = $this->doWarnSet($request, 'snbhzwarn', $this->module, $url, $set_position, $set_info, $model, $user_model);
        
        return $data;
    }

    /*获取日生产统计和报警统计信息*/
    protected function getTotalData($model, $url, $field){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        if(!$sup_id && !$sec_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }
        $dfield = ['id','name','dcode'];
        $device = $this->getDevice($dfield, $pro_id, $sup_id, $sec_id);
        if(!$device){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无设备信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        $url_para = '';

        $query = $model->select($field);
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
        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            if(in_array('date_start', $field)){
                $query = $query->where('date_start', '>=', $start_date);
            }else{
                $query = $query->where('date', '>=', $start_date);
            }
            $url_para .= '&start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            if(in_array('date_end', $field)){
                $query = $query->where('date_end', '<=', $end_date);
            }else{
                $query = $query->where('date', '<=', $end_date);
            }
            
            $url_para .= '&end_date='.$end_date;
        }
        
        $query = $query->with(['section'=>function($query){
                            $query->select(['id','name']);
                        },'sup'=>function($query){
                            $query->select(['id','name']);
                        }, 'device'=>function($query){
                            $query->select(['id','name','dcode']);
                        }])
                      ->orderByRaw('id desc');

        $d = Input::get('d');
        if($d == 'all'){
            $list['data'] = $query->get()->toArray();
        }else{
            $list = $query->paginate($this->ispage)
                          ->toArray();
        }

        $list['search'] = $search;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        if(stripos('?', $tree_data['url'])){
            $url_lj = '&';
        }else{
            $url_lj = '?';
        }
        $tree_data['url'] = $url_para ? $tree_data['url'].$url_lj.$url_para : $tree_data['url'];
        return array_merge($list, $tree_data);
    }

    /*获取日生产统计和报警统计对比信息*/
    protected function getTotalColumnData($model, $url){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $field = ['id','dcode'];
        $device = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
        if(!$device){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无设备信息';
            return view('admin.error.tree_no_info', $tree_data);
        }

        $query = $model->select(['device_id','bj_num','date'])
                        ->where('module_id', $this->module);
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
        //用户可访问的section
        $section = $this->getUserSection($pro_id, $sup_id);
        //var_dump($section);
        if(!$section){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无标段信息';
            return view('admin.error.tree_no_info', $tree_data);
        }

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        

        $sec_id = Input::get('sec_id') ? Input::get('sec_id') : $section[0]['id'];
        if($sec_id){
            if(is_array($sec_id)){
                $query = $query->whereIn('section_id', $sec_id);
            }else{
                $query = $query->where('section_id', $sec_id);
            }
            $search['sec_id'] = json_encode($sec_id);
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('date', '>=', $start_date);
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('date', '<=', $end_date);
        }
        
        $warn = $query->with(['device'=>function($query){
                            $query->select(['id','dcode','name']);
                        }])
                      ->orderByRaw('id desc')
                      ->get()
                      ->toArray();
        //组合表格数据
        $chart = [
            'sub_title'=>$start_date.' ~ '.$end_date,
            'categories'=>[],
            'series'=>[],
            ];
        if($warn){
            $data = [];
            foreach ($warn as $key => $value) {
                $data[$value['device_id']][$value['date']] = $value['bj_num'];
                $data[$value['device_id']]['name'] = $value['device']['name'].'-'.$value['device']['dcode'];
            }

            $start_time = strtotime($start_date);
            $end_time = strtotime($end_date);
            for($i = 0; $start_time <= $end_time; $i++){
                $chart['categories'][$i] = date('Y-m-d', $start_time);
                $start_time = $start_time + 86400;
                foreach ($data as $key => $value) {
                    $data[$key]['val'][$i] = isset($value[$chart['categories'][$i]]) ? $value[$chart['categories'][$i]] : 0;
                }
            }

            $i=0;
            foreach ($data as $value) {
                $chart['series'][$i]['name'] = $value['name'];
                $chart['series'][$i]['data'] = $value['val'];
                $i++;
            }
            
        }
        
        $list['chart'] = json_encode($chart);
        $list['search'] = $search;
        $list['section'] = $section;

        return array_merge($list, $tree_data);
    }
   
    /*获取曲线信息*/
    protected function getCurve($column, $url){
        //获取拌合设备
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $field = ['id','name','dcode'];
        $device = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
        if(!$device){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无设备信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        $url_para = '';
        
        //获取拌合信息
        $field = [
                'id',
                'device_id',
                'time'
            ];
        $query = Snbhz_info::select($field);

        //判断条件
        $d_id = Input::get('d_id') ? Input::get('d_id') : $device[0]['id'];  
        if($d_id){
            $search['d_id'] = $d_id;
            $query = $query->where('device_id', '=', $d_id);
        }

        $date =  Input::get('date') ? Input::get('date') : date('Y-m-d');
        $search['date'] = $date;
        $start_date = Input::get('start_date') ? Input::get('start_date') : '00:00';
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($date.' '.$start_date));
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('H:i');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<', strtotime($date.' '.$end_date));
        }
        
        $list = $query->with(['detail'=>function($query){
                            $query->orderByRaw('type asc');
                        }])
                       ->orderByRaw('id asc')
                       ->get()
                       ->toArray();
        $chart = ['categories'=>[],'series'=>[]];
        if($list){
            $snbhz_info = Config()->get('common.snbhz_info_detail');
            foreach ($snbhz_info as $key => $value) {
                $chart['series'][$key-1]['name'] = $value['name'];
            }
            
            foreach ($list as $key => $value) {
                $chart['categories'][$key] = date('H:i', $value['time']);
                foreach ($value['detail'] as $k => $v) {
                    $chart['series'][$k]['data'][$key] = $v[$column];
                }
            }

            //子标题
            $device_info = Device::where('id', $d_id)
                                 ->with(['section'=>function($query){
                                        $query->select(['id','name'])
                                              ->with(['sup'=>function($query){
                                                    $query->select(['id','name']);
                                                }]);
                                    }])
                                 ->first();
            $chart['sub_title'] = $device_info['section']['sup'][0]['name']
                                .' | '.$device_info['section']['name']
                                .' | '.$device_info['dcode']
                                .' | '.date('Y-m-d', strtotime($date)).' '.$start_date.'-'.$end_date;
        }
        $data['device'] = $device;
        $data['search'] = $search;
        $data['chart'] = json_encode($chart);

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        return array_merge($data, $tree_data);
    }

    protected function getDate(){
        $week = date('W');
        $year = date('Y');
        if($week < 12){
            //获取去年的结果
            $num = 12 - $week;
            $year = $year - 1;
            $week_num = $this->week($year);
            for($i=$week_num-$num; $i<$week_num; $i++){
                $info = $this->weekday($year, $i);
                $date[$i] = date('Y-m-d', $info['start']).'-'.date('Y-m-d', $info['end']).'-第'.$i.'周';
            }
        }
        $year = date('Y');
        for($i=1; $i<$week; $i++){
            $info = $this->weekday($year, $i);
            $date[$i] = date('Y-m-d', $info['start']).'-'.date('Y-m-d', $info['end']).'-第'.$i.'周';
        }
        
        return $date;
    }
    public function weekday($year,$week=1){ 
        $year_start = mktime(0,0,0,1,1,$year); 
        $year_end = mktime(0,0,0,12,31,$year); 
         
        // 判断第一天是否为第一周的开始 
        if (intval(date('W',$year_start))===1){ 
            $start = $year_start;//把第一天做为第一周的开始 
        }else{ 
            $week++; 
            $start = strtotime('+1 monday',$year_start);//把第一个周一作为开始 
        } 
         
        // 第几周的开始时间 
        if ($week===1){ 
            $weekday['start'] = $start; 
        }else{ 
            $weekday['start'] = strtotime('+'.($week-0).' monday',$start); 
        } 
         
        // 第几周的结束时间 
        $weekday['end'] = strtotime('+1 sunday',$weekday['start']); 
        if (date('Y',$weekday['end'])!=$year){ 
            $weekday['end'] = $year_end; 
        } 
        return $weekday; 
    } 

    /** 
     * 计算一年有多少周，每周从星期一开始， 
     * 如果最后一天在周四后（包括周四）算完整的一周，否则不计入当年的最后一周 
     * 如果第一天在周四前（包括周四）算完整的一周，否则不计入当年的第一周 
     * @param int $year 
     * return int 
     */ 
    public function week($year){ 
        $year_start = mktime(0,0,0,1,1,$year); 
        $year_end = mktime(0,0,0,12,31,$year); 
        if (intval(date('W',$year_end))===1){ 
            return date('W',strtotime('last week',$year_end)); 
        }else{ 
            return date('W',$year_end); 
        } 
    } 

    /*错误信息处理报告*/
    public function clbg(){
        $field = 'snbhz_info.id,snbhz_info.project_id,snbhz_info.supervision_id,snbhz_info.section_id,snbhz_info.device_id,snbhz_info.time,snbhz_info.scdw,snbhz_info.jzbw,snbhz_info.warn_info,snbhz_info.warn_level,snbhz_info.warn_sx_level,snbhz_info.warn_sx_info,warn_deal_info.*';
        $model = new Snbhz_info;
        $view = 'mixplant.snbhz.clbg';
        $left_table = 'snbhz_info';
        $data = $this->getClbg($model, $field, $view, $left_table);

        return $data;
    }

    /*错误信息处理台账*/
    public function tzlb(){
        $model = new Snbhz_info;
        $left_table = 'snbhz_info';
        //如果是集团用户 显示各项目处理台账数量显示对比图
        if($this->user->role == 2 || $this->user->role == 1){
            $url = url('snbhz/tzlb');
            $view = 'system.tzlb_project';
            $data = $this->getTzlbPie($model, $url, $view, $left_table);
            return $data;
        }
        //如果是项目用户 点击显示各标段处理台账数量显示对比图
        if($this->user->role == 3 && Input::get('type') == "sec"){
            $url = url('snbhz/tzlb?type=sec');
            $view = 'system.tzlb_jsdw';
            $data = $this->getTzlbPie($model, $url, $view, $left_table);
            return $data;
        }
        $field = 'snbhz_info.id,snbhz_info.project_id,snbhz_info.supervision_id,snbhz_info.section_id,snbhz_info.device_id,snbhz_info.time,snbhz_info.scdw,snbhz_info.jzbw,snbhz_info.warn_info,snbhz_info.warn_level,snbhz_info.warn_sx_level,snbhz_info.warn_sx_info,warn_deal_info.*';
        $view = 'mixplant.snbhz.tzlb';
        $url = url('snbhz/tzlb');
        $data = $this->getTzlb($model, $field, $url, $view, $left_table);

        return $data;
    }

    /*统计拌和站人员 监理每天登录次数*/
    public function statLogin(){
        $user_model = new User;
        $url = url('snbhz/stat_login');
        $module_id = $this->module;
        $view = 'system.stat_login';
        
        $data = $this->getLoginStat($user_model, $url, $module_id, $view);
        return $data;
    }

    public function statWeek(){
        $model = new Stat_week;
        $field = ['supervision_id','section_id','device_id','sc_num','scl','bj_num','cl_num','bhgl','week','created_at'];
        $url = url('snbhz/stat_week');
        //默认一个季度的  12周
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-84 day'));
        if(Input::get('d')){
            $view = 'mixplant.snbhz.stat_week_export';
        }else{
            $view = 'mixplant.snbhz.stat_week';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    public function statMonth(){
        $model = new Stat_month;
        $field = ['supervision_id','section_id','device_id','sc_num','scl','bj_num','cl_num','bhgl','month','created_at'];
        $url = url('snbhz/stat_month');
        //默认一年的
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-365 day'));
        if(Input::get('d')){
            $view = 'mixplant.snbhz.stat_month_export';
        }else{
            $view = 'mixplant.snbhz.stat_month';
        }
        $module_id = $this->module;
        $data = $this->getStat($model, $field, $url, $view, $start_date, $end_date, $module_id);

        return $data;
    }

    public function getSelectVal($type){
        $data = $this->getCommonSelectVal($type);
        return $data;
    }
    /*获取当前未处理的有效报警信息*/
    public function getTodayWarnBhz()
    {
        $start_date=date('Y-m-d', strtotime('-3 day'));
        $end_date=date('Y-m-d');
        $field = 'id,project_id,supervision_id,section_id,device_id,time,is_sec_deal,is_sup_deal,warn_info,warn_level,created_at,warn_sx_level,warn_sx_info';

        $data = $this->getNowWarn((new snbhz_info), $field,$start_date,$end_date);

        return $data;
    }

    // 实时视频预览
    public function videoPreview($device_id) {
        $model = Device::find($device_id);
        $data = [];
        $data['device'] = $model;
        $data["project_id"] = Input::get('pro_id');
        return view('mixplant.snbhz.video_preview', $data);
    }

    /*某个设备的拌合数据列表*/
    public function getDataAtVideo($device_id){
        $model = new Snbhz_info;
        $field = [
            'id',
            'section_id',
            'device_id',
            'time',
            'scdw',
            'sgdw',
            'sgdd',
            'jzbw',
            'is_warn',
            'warn_level',
            'warn_sx_level',
            'warn_info',
            'operator',
            'is_sec_deal',
            'is_sup_deal',
            'is_pro_deal',
            'pbbh',
            'pfl'
        ];
        $url = url('snbhz/product_data_at_video/'.$device_id);
        $data = $this->getDataByDevice($device_id, $model, $field, $url);

        if(!is_array($data)){
            return $data;
        }
        $list=$this->getDayTotal($device_id,$model);
        $data['list']=$list;
        return view('mixplant.snbhz.product_data_at_video', $data);
    }
   /*实时视频页面中最新一条拌和数据详细信息*/

   public function getNewInfoAtVideo()
   {
        $device_id=Input::get('device_id');
        $model=new Snbhz_info();
        $info=$model->select('id','time','device_id')
                       ->where('device_id',$device_id)
                       ->orderBy('time','desc')
                       ->first();
        $info_id=$info->id;

       $data['snbhz_info'] = Snbhz_info::where('id', $info_id)
           ->with(['project'=>function($query){
               $query->select(['id','name']);
           }])
           ->first()
           ->toArray();
       $data['snbhz_info']['time'] = date('Y-m-d H:i', $data['snbhz_info']['time']);
       $data['detail_info'] = Snbhz_info_detail_new::where('snbhz_info_id', $info_id)->orderByRaw('type asc')->get()->toArray();
       if($data['snbhz_info']['is_warn']){
           $data['deal_info'] = $this->getDealInfo((new Warn_deal_info), $info_id, $data['snbhz_info']['device_id']);
       }
       $data['snbhz_detail'] = Config()->get('common.snbhz_info_detail');
       //var_dump($data);
       return view('mixplant.snbhz.new_bh_info_video', $data);

   }

  /*拌合站单台设备日生产总量及按配比编号得出每个配比编号总量*/
  protected function getDayTotal($device_id,$model)
  {
     $start_time=strtotime(date('Y-m-d',time()));
     $end_time=$start_time+86400;
     $bhz_pbbh=Config()->get('common.bhz_pbbh');
     $list=[];
     $list['total']=0;
     foreach($bhz_pbbh as $val){
         $list[$val]='';
     }
     $list['total']=$model->select(DB::raw('pbbh, sum(pfl) as pfl'))
                 ->where('device_id',$device_id)
                 ->whereBetween('time',[$start_time,$end_time])
                 ->groupBy('pbbh')
                 ->get()
                 ->toArray();
     return $list;
  }

    /**
     * 拌合站设备与采集端连接异常时通知人员信息
     * @param SnbhzDeviceFailurePushUser $deviceFailurePushUser
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function deviceFailurePushUser(SnbhzDeviceFailurePushUser $deviceFailurePushUser)
    {

        $push_user_data=$deviceFailurePushUser->with('user')
            ->get();

//        dd($push_user_data);

        return view('mixplant.snbhz.device_failure_push_user',compact('push_user_data'));
    }

    /**
     * 添加拌合站设备与采集端连接异常时通知人员
     * @param Request $request
     * @param User $user
     * @param SnbhzDeviceFailurePushUser $deviceFailurePushUser
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function addDeviceFailurePushUser(Request $request,User $user,SnbhzDeviceFailurePushUser $deviceFailurePushUser)
    {
        if($request->isMethod('get')){
            //获取用户信息
            $user_data=$user->with('posi')
                ->where('role',1)
                ->orWhere('role',3)
                ->get();
//            dd($user_data);
            return view('mixplant.snbhz.add_device_failure_push_user',compact('user_data'));

        }
        if($request->isMethod('post')){
            $user_id=$request->get('user_id');

            if(!$user_id){
                $result['status']=1;
                $result['mess']='请选择用户信息';
                return $result;
            }

            $data['user_id']=$user_id;

            try{
                $deviceFailurePushUser->create($data);

                $result['status']=0;
                $result['mess']='添加成功';
                return $result;

            }catch (Exception $e){
                $result['status']=1;
                $result['mess']='添加出现未知错误';
                return $result;
            }
        }
    }

    /**
     *删除拌合站设备与采集端链接异常通知人员信息
     */
    public function delDeviceFailurePushUser($id,SnbhzDeviceFailurePushUser $deviceFailurePushUser)
    {
        try{
            $deviceFailurePushUser->where('id',$id)->delete();
            $result['status'] = 1;
            $result['info'] = '删除成功';
            $result['id'] = $id;
            return $result;
        }catch (Exception $e){
            $result['status'] = 0;
            $result['info'] = '删除失败';
            return $result;
        }

    }

    /**
     *
     * 拌合站设备实时视频预览
     * 之前的拌合站视频是在8700平台，
     * 后期需要将拌合站视频放到萤石云，该方法为萤石云调用
     */
    public function videoLive($device_id)
    {
        //获取萤石云的appKey及accessToken
        $ysCloud=new YSCloud;
        $appKey=$ysCloud->getAppKey();
        $accessToken=$ysCloud->getAccessToken();
        $device=Device::find($device_id);
//        dd($device);
//        dd($accessToken);
        return view('mixplant.snbhz.video_live', compact('appKey','accessToken','device'));
    }

}
