<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB, Log, Input, Validator;
class Common extends Model
{
    protected $messages = [
                    'required' => '字段 :attribute 的值必须填写',
                    'unique' => ' :attribute 已经存在了',
                    'numeric' =>'字段 :attribute 需为数字',
                    'alpha_num' => '字段 :attribute 仅允许字母和数字',
                    'min' => '字段 :attribute 至少 :min 位',
                    'max' => '字段 :attribute 最多 :max 位',
                    'regex' => '用户名至少6位，由字母、数字、_、-、.、@组成，且以字母开始',
                    'alpha_dash' => '字段 :attribute 仅允许由字母、数字、折号（-）以及底线（_）',
                ];

	public function getList($model, $filter=[], $fields=[], $order='id desc', $ispage=0, $limit='', $with='', $with_field=[], $with_filter=[], $with_order='')
    { 
        //$model = new $model;

        $query = $model->select($fields)->where(function($query) use($filter){        
                    foreach ($filter as $v) {
                        if($v[1] == 'in'){
                            $query->whereIn($v[0], $v[2]); 
                        }else{
                            $query->where($v[0], $v[1], $v[2]);
                        }     
                    }             
                });

        if($with){
            $query = $query->with([$with => function ($query) use($with_field, $with_filter, $with_order) {
                        $query->select($with_field);
                        foreach ($with_filter as $v) {
                            if($v[1] == 'in'){
                                $query->whereIn($v[0], $v[2]); 
                            }else{
                                $query->where($v[0], $v[1], $v[2]);
                            }     
                        } 
                        if($with_order){
                            $query->orderByRaw($with_order);
                        }
                    }]);
        }
        if($ispage){
            $data = $query->orderByRaw($order)->paginate($ispage)->toArray();
        }else{
            if($limit){
                $query = $query->take($limit);
            }
            $data = $query->orderByRaw($order)->get()->toArray();
        }

        return $data;
    }

    //生成随机字串
    public function generate_code($length = 6) {  
        // 密码字符集，可任意添加你需要的字符  
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';  //不添加特殊字串，需要的话可直接加!@#$%^&*()-_ []{}<>~`+=,.;:/?|
        $password = '';  
        for ($i = 0;$i < $length;$i++){  
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；  17,812
        // 第二种是取字符数组 $chars 的任意元素  
        // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);  
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
        }  
        return $password;  
    }

    public function getNo($type, $length=5){
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';  
        for ($i = 0;$i < $length;$i++){  
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
        }
        $str = $type.$str.date('YmdHis'); 
        return $str; 
    }

    public function getNullData($arr){
        foreach ($arr as $key => $value) {
            $data[$value] = '';
        }
        return $data;
    }

    public function checkData($data=[]){
        $data = $data ? $data : Input::all();

        $validator = Validator::make($data, $this->rule , $this->messages);
        if($validator->fails()){
            $errors = $validator->errors()->first();
            $result = ['code'=>1,'info'=>$errors];
            return $result;
        }
        $data['code'] = 0;
        return $data;
    }

    public function getNum($num){
        $chars = '0123456789';
        $str = ''; 
        $max = strlen($chars) - 1;
        for($i = 0;$i < $num;$i++){  
            $str .= $chars[ mt_rand(0, $max) ];  
        }  
        return $str;
    }
    
    static function get_client_ip(){
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
            $ip = getenv("HTTP_CLIENT_IP");
        }elseif(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }elseif(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
            $ip = getenv("REMOTE_ADDR");
        }elseif(isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = "unknown";
        }
        return $ip;
    }

    static function get_client_ip_server(){
        if($_SERVER("HTTP_CLIENT_IP") && strcasecmp($_SERVER("HTTP_CLIENT_IP"), "unknown")){
            $ip = $_SERVER("HTTP_CLIENT_IP");
        }elseif($_SERVER("HTTP_X_FORWARDED_FOR") && strcasecmp($_SERVER("HTTP_X_FORWARDED_FOR"), "unknown")){
            $ip = $_SERVER("HTTP_X_FORWARDED_FOR");
        }elseif($_SERVER("REMOTE_ADDR") && strcasecmp($_SERVER("REMOTE_ADDR"), "unknown")){
            $ip = $_SERVER("REMOTE_ADDR");
        }elseif(isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = "unknown";
        }
        return $ip;
    }
    /*根据ip获取地址信息  
    淘宝接口返回值为以下格式：
    array( 
        ［code］ => 0 // 0：查询成功，1：查询失败 
        ［data］ => array (
            [ip] => 116.255.205.145 // 待查询的那个IP地址 
            [country] => 中国 
            [country_id] => CN 
            [area] => 华中 
            [area_id] => 400000 
            [region] => 河南省 
            [region_id] => 410000 
            [city] => 郑州市 
            [city_id] => 410100 
            [county] => // 县 
            [county_id] => -1 
            [isp] => 景安计算机网络 
            [isp_id] => 1000152  国外的话从area开始后面的都为空
        ) 
    ) 
    */
    public function ip_to_area($ip){
        $ip_info_api= 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip; 
        $area_info= @file_get_contents($ip_info_api); 
        $area_info= json_decode($area_info,true);
        if(empty($area_info) || $area_info['code'] != 0){
            $area_info = $this->ip_to_area_by_sina($ip);
        }
        if($area_info['code'] == 0){
            return $area_info['data'];
        }else{
            Log::info('get area error by ip '.$ip.' data '.json_encode($area_info));
            return false;
        }
    }

    public function ip_to_area_by_sina($ip){
        $ip_info_api= 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip; 
        $area_info= @file_get_contents($ip_info_api); 
        $area_info= json_decode($area_info,true);
        if($area_info['ret'] == 1){
            $info['code']=0;
            $province = empty($area_info['province']) ? '' : $area_info['province'].'省';
            $city = empty($area_info['city']) ? '' : $area_info['city'].'市';
            $info['data'] = ['country'=>$area_info['country'],'region'=>$province,'city'=>$city];
            return $info;
        }else{
            Log::info('get area error by sina ip '.$ip.' data '.json_encode($area_info));
            return false;
        }
    }

    public function getGroupData($table, $field, $filter, $order, $group, $ispage=0){
        if($ispage){
            $list = DB::table($table)
                ->select(DB::raw($field))
                ->where(function($query) use($filter){        
                    foreach ($filter as $v) {
                        $query->where($v[0], $v[1], $v[2]);        
                    }             
                })->groupBy($group)->orderByRaw($order)->paginate($ispage)->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key] = (array)$value;
            }
        }else{
            $list = DB::table($table)
                ->select(DB::raw($field))
                ->where(function($query) use($filter){        
                    foreach ($filter as $v) {
                        $query->where($v[0], $v[1], $v[2]);        
                    }             
                })->groupBy($group)->orderByRaw($order)->get();//->toArray()
            //var_dump($list);
            foreach ($list as $key => $value) {
                $list[$key] = (array)$value;
            }
        }
        return $list;
    }

    public function getCount($model, $filter){
        $count = $model->where(function($query) use($filter){        
                    foreach ($filter as $v) {
                        $query->where($v[0], $v[1], $v[2]);        
                    }             
                })->count();
        return $count;
    }

    /**
    *转为CDN加速链接
    */
    public function getCdnLink($path){
        if(stripos($path, 'http://') === false){
            if(Config()->get('common.cdn_link')){
                return Config()->get('common.cdn_link').Config()->get('common.show_path').$ext.'/'.$path;
            }else{
                return Config()->get('common.app_url').Config()->get('common.show_path').$path;
            }
        }
        return $path;
    }

    function writeFile($info){
        $fp = fopen("./uploads/push.txt", "a+"); //./auto
        fwrite($fp, date("Y-m-d H:i:s").'   '.$info."\n"); 
        fclose($fp);    
    }

    function getBrowser(){
        $agent=$_SERVER["HTTP_USER_AGENT"];
        if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')){ //ie11判断
            return "ie";
        }elseif(strpos($agent,'Firefox')!==false){
            return "firefox";
        }elseif(strpos($agent,'Chrome')!==false){
            return "chrome";
        }elseif(strpos($agent,'Opera')!==false){
            return 'opera';
        }elseif((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false){
            return 'safari';
        }else{
            return 'unknown';
        }
    }
 
    function getBrowserVer(){
        if (empty($_SERVER['HTTP_USER_AGENT'])){    //当浏览器没有发送访问者的信息的时候
            return 'unknow';
        }
        $agent= $_SERVER['HTTP_USER_AGENT'];   
        if (preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs)){
            return $regs[1];
        }elseif(preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs)){
            return $regs[1];
        }elseif(preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs)){
            return $regs[1];
        }elseif(preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs)){
            return $regs[1];
        }elseif((strpos($agent,'Chrome')==false)&&preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs)){
            return $regs[1];
        }else{
            return 'unknow';
        }
    }

    function getRoundInfo($table, $limit){
        $max = DB::table($table)->max('id');
        $min = DB::table($table)->min('id');
        $rand = mt_rand($min, $max);
        $res = DB::select("SELECT * FROM $table WHERE id >= ? LIMIT $limit", [$rand]);
        if($res){
            if($limit == 1){
                return $res[0];
            }
            return $res;
        }
        return false;
    }

    function exportCsv($filename,$str){
        header("Content-type:application/vnd.ms-excel");   
        header("Content-Disposition:attachment;filename=".$filename);   
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
        header('Expires:0');   
        header('Pragma:public');   
        echo $str; 
        exit;   
    }

    function makeHtml($temp, $html_file, $data){
        $html_str = view($temp, $data)->__toString();
        $html_fp = fopen($html_file, 'w');
        fwrite($html_fp, $html_str);
        fclose($html_fp);
    }

    function checkLink($link){
        $result = ['status'=>0,'info'=>'原文链接无效'];
        try {
            $array = get_headers($link, 1); 
        } catch (\Exception $e) {
            return $result;
        }
        if(preg_match('/200/',$array[0])){ 
            return ['status'=>1];
        }else{
            return $result;
        }
    }
}
