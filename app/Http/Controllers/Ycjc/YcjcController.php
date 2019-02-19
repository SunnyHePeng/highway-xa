<?php

namespace App\Http\Controllers\Ycjc;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mixplant\IndexController;
use Input, Cache;
use App\Models\Ycjc\Ycjc,
    App\Models\Ycjc\Ycjc_total;
class YcjcController extends IndexController
{
    protected $order = 'id desc';
    protected $ispage = 20;
    protected $module = 19;
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
    	$tree = $this->getTreeProjectData();
    	$list['ztree_data'] = json_encode($tree);

    	$pro_id = Input::get('pro_id') ? Input::get('pro_id') : $tree[0]['id'];

        //扬尘监测数据只存储报警数据，首页显示最新上传的数据  存在缓存中
        //不知道扬尘监测是一个项目一个数据还是分监理 标段
        $list = Cache::get('ycjc_'.$pro_id);
        $list = [
            'pm25'=>'30ug',
            'pm10'=>'10ug',
            'wd'=>'19℃',
            'sd'=>'45%',
            'fs'=>'0.1m/s',
            'fx'=>'西北风',
            'zs'=>'45dB',
            'time'=>date('Y-m-d H:i'),
            ];

        $list['weather'] = $this->getWeather();
        //var_dump($list['weather']);
    	$list['ztree_url'] = url('ycjc/index');
    	if($this->user->role == 1 || $this->user->role == 2){
            $data = $this->getPageTreePorjectDataAndUrl(url('ycjc/index'), Input::get('pro_id'));
            
            return view('ycjc.index_project', array_merge($list, $data));
        }
        return view('ycjc.index', $list);
    }

    /*扬尘监测历史报警数据*/
    public function historyData(){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');

        $url_para = '';
        $query = new Ycjc;
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
            $query->where('time', '>=', strtotime($start_date));
            $url_para .= $url_para ? '&start_date='.$start_date : 'start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query->where('time', '<', strtotime($end_date)+86400);
            $url_para .= $url_para ? '&end_date='.$end_date : 'end_date='.$end_date;
        }

        $query = $query->with(['section'=>function($query){
                            $query->select(['id','name']);
                        },'sup'=>function($query){
                            $query->select(['id','name']);
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
        
        $url = url('ycjc/history_data');
        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        if(stripos('?', $tree_data['url'])){
            $url_lj = '&';
        }else{
            $url_lj = '?';
        }
        $tree_data['url'] = $url_para ? $tree_data['url'].$url_lj.$url_para : $tree_data['url'];
        $data = array_merge($list, $tree_data);
        if(Input::get('d')){
            return view('ycjc.history_export', $data);
        }
        return view('ycjc.history', $data);
    }

    /*报警统计*/
    public function warn(){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');

        $url_para = '';
        $query = new Ycjc_total;
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
            $query->where('date', '>=', $start_date);
            $url_para .= $url_para ? '&start_date='.$start_date : 'start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query->where('date', '<=', $end_date);
            $url_para .= $url_para ? '&end_date='.$end_date : 'end_date='.$end_date;
        }

        $query = $query->with(['section'=>function($query){
                            $query->select(['id','name']);
                        },'sup'=>function($query){
                            $query->select(['id','name']);
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
        
        $url = url('ycjc/warn');
        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        if(stripos('?', $tree_data['url'])){
            $url_lj = '&';
        }else{
            $url_lj = '?';
        }
        $tree_data['url'] = $url_para ? $tree_data['url'].$url_lj.$url_para : $tree_data['url'];
        $data = array_merge($list, $tree_data);
        if(Input::get('d')){
            return view('ycjc.warn_export', $data);
        }
        return view('ycjc.warn', $data);
    }

    /*{
        "status":"ok",
        "lang":"zh_CN",  //目前只支持简体中文（zh_CN、zh_SG）、繁体中文（zh_TW、zh_HK），英语（en_US、en_GB）在测试中
        "server_time":1443418222,
        "tzshift":28800, //时区的偏移秒数，如东八区就是 28800 秒，使用秒是为了支持像尼泊尔这样的差 5 小时 45 分钟的地区，它们有非整齐的偏移量
        "location":[
            25.1552, //纬度
            121.6544 //经度
        ],
        "unit":"metric", //目前只支持米制（metric）和科学计量法（SI），英制还有待开发
        "result":{
            "status":"ok",
            "temperature":28.0,  //温度
            "skycon":"RAIN",  //天气概况
            "pm25": 11,       //pm25值   在新的api中增加的字段
            "cloudrate":0.51,  // 云量
            "humidity":0.92,  //相对湿度
            "precipitation":{  //降水
                 "nearest":{ //最近的降水带 //用户补充：nearest这段有时候没有
                     "status":"ok",
                     "distance":0.77, //距离
                     "intensity":0.3125 //角度
                 },
                "local":{ //本地的降水
                    "status":"ok",
                    "intensity":0.2812, //降水强度
                    "datasource":"radar" //数据源
                }
            },
            "wind":{ //风
                "direction":25.33, //风向。单位是度。正北方向为0度，顺时针增加到360度。
                "speed":83.3 //风速，米制下是公里每小时
            }
        }
    }
    由于一天只可获取1000次 故2分钟获取一次
    https://free-api.heweather.com/s6/weather/now?key=0d7cd1a67d2a4d97bf3afa8517181986&location=".urlencode(CN101110101)
    */
    protected function getWeather(){
        if(Cache::get('weather')){
            return Cache::get('weather');
        }
        //准备请求参数
        $key ="I6QLQoOzBxErPbom";
        $location = "108.9,34.2667";//查询地的经纬度
        $data = file_get_contents('https://api.caiyunapp.com/v2/'.$key.'/'.$location.'/realtime.json');
        $data = json_decode($data, true);
        
        if($data['status'] != 'ok' || $data['lang'] != 'zh_CN' || $data['result']['status'] != 'ok'){
            return '';
        }
        $weather = [];
        $weather = $data['result'];
        $weather['time'] = date('Y-m-d H:i:s', $data['server_time']);
        
        unset($data);
        $skycon = [
            'CLEAR_DAY'=>'晴天',
            'CLEAR_NIGHT'=>'晴夜',
            'PARTLY_CLOUDY_DAY'=>'多云',
            'PARTLY_CLOUDY_NIGHT'=>'多云',
            'CLOUDY'=>'阴',
            'RAIN'=>'雨',
            'SNOW'=>'雪',
            'WIND'=>'风',
            'FOG'=>'雾',
            'HAZE'=>'霾',
            'SLEET'=>'冻雨'
        ];
        //天气文字
        $weather['skycon'] = $skycon[$weather['skycon']];
        //风向 0 北风 0-90东北风 90 东风 90-180东南风 180 南风 180-270 西南风 270 西风 270-360西北风
        $direction = $weather['wind']['direction'];
        switch ($direction) {
            case '0':
                $weather['wind']['direction'] = '北风';
                break;
            case ($direction<90 && $direction>0):
                $weather['wind']['direction'] = '东北风';
                break;
            case '90':
                $weather['wind']['direction'] = '东风';
                break;
            case ($direction<180 && $direction>90):
                $weather['wind']['direction'] = '东南风';
                break;
            case '180':
                $weather['wind']['direction'] = '南风';
                break;
            case ($direction<270 && $direction>180):
                $weather['wind']['direction'] = '西南风';
                break;
            case '270':
                $weather['wind']['direction'] = '西风';
                break;
            case ($direction<360 && $direction>270):
                $weather['wind']['direction'] = '西北风';
                break;
        }
        Cache::put('weather', $weather, 2);
        return $weather;
    }
}
