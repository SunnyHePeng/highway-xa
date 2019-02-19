<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bhz\Snbhz_info,
    App\Models\Lab\Lab_info,
    App\Models\System\Stat_day,
    App\Models\System\Stat_week,
    App\Models\System\Stat_month,
    App\Models\Device\Device;
use DB, Log, Cache;
use App\Send\SendSms;

/**
 * 统计（执行拌合站与试验室的日统计，周统计，月统计）
 */
class Stat extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'stat bhz and lab data';
    protected $time, $date;
   
    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626 
     */
    public function handle()
    {
    	Log::info('stat start');
    	$this->date = date('Y-m-d',strtotime('-1 day'));//
        $this->time = strtotime($this->date);

    	$this->stat();

    	Log::info('stat end');
    }

    protected function stat(){
        //日统计 生产量、生产次数，报警次数，不合格率
        $this->statBhzDay();
        $this->statLabDay();
        //周和月统计 生产量、生产次数，报警次数，不合格率
        $this->statWeekAndMonth();
    }

    /*日统计 生产量、生产次数，报警次数，不合格率
	* 按照生产时间 非上传时间
    */
    protected function statBhzDay(){
    	$start = $this->time;
    	$end = $start+86400;
    	$date = $this->date;
     	//拌和站 生产次数
        $field = 'count(*) as sc_num, project_id, supervision_id, section_id, device_id';
        $info = $this->getDayScNum((new Snbhz_info), $field, $start, $end);
        //处理数  包括之前(3天内)和当天报警的数据今天处理的
        $field = 'count(snbhz_info.id) as cl_num, snbhz_info.project_id, snbhz_info.supervision_id, snbhz_info.section_id, snbhz_info.device_id';
        $cl_info = $this->getDayClNum((new Snbhz_info), $field, 'snbhz_info', $start, $end);
        
        if($info){
            foreach ($info as $key => $value) {
                //生产量
                $scl = $this->getDayScl('snbhz_product_total', $value['device_id']);
                //报警数
                $bj_num = $this->getDayBjNum((new Snbhz_info), $value['device_id'], $start, $end);
                
                //处理数
                $cl_num = 0;
                foreach ($cl_info as $k => $v) {
                    if($v['device_id'] == $value['device_id']){
                        $cl_num = $v['cl_num'];
                        break;
                    }
                }
                $data = [
                    'date'=>$this->date,
                    'scl'=> $scl ? $scl : 0,
                    'bj_num'=>$bj_num ? $bj_num : 0,
                    'cl_num'=>$cl_num ? $cl_num : 0,
                    'module_id'=>4
                    ];
                $data['bhgl'] = round($data['bj_num']/$value['sc_num'], 3)*100;
                $da[] = array_merge($value, $data);
            }
            if(isset($da) && is_array($da)){
                DB::table('stat_day')->insert($da);
            }
        }

        //没有生产数有处理数
        if(!$info && $cl_info){
            //处理数
            foreach ($cl_info as $k => $v) {
                $data = [
                    'date'=>$this->date,
                    'scl'=> 0,
                    'bj_num'=>0,
                    'bhgl'=>0,
                    'sc_num'=>0,
                    'module_id'=>4
                    ];
                $da[] = array_merge($v, $data);
            }
            if(isset($da) && is_array($da)){
                DB::table('stat_day')->insert($da);
            }
        }
    }

    protected function statLabDay(){
    	$start = $this->time;
    	$end = $start+86400;
    	$date = $this->date;
     	//试验次数
        $field = 'count(*) as sc_num, project_id, supervision_id, section_id, device_id';
     	$info = $this->getDayScNum((new Lab_info), $field, $start, $end);

        //处理数  包括之前(3天内)和当天报警的数据今天处理的
        $field = 'count(lab_info.id) as cl_num, lab_info.project_id, lab_info.supervision_id, lab_info.section_id, lab_info.device_id';
        $cl_info = $this->getDayClNum((new Lab_info), $field, 'lab_info', $start, $end);

    	if($info){
            foreach ($info as $key => $value) {
                $bj_num = $this->getDayBjNum((new Lab_info), $value['device_id'], $start, $end);
                //处理数
                $cl_num = 0;
                foreach ($cl_info as $k => $v) {
                    if($v['device_id'] == $value['device_id']){
                        $cl_num = $v['cl_num'];
                        break;
                    }
                }
                $data = [
                    'date'=>$this->date,
                    'bj_num'=>$bj_num ? $bj_num : 0,
                    'cl_num'=>$cl_num ? $cl_num : 0,
                    'module_id'=>3
                    ];
                $data['bhgl'] = round($data['bj_num']/$value['sc_num'], 3)*100;
                $da[] = array_merge($value, $data);
            }
            if(isset($da) && is_array($da)){
                DB::table('stat_day')->insert($da);
            }
        }

        //没有试验数有处理数
        if(!$info && $cl_info){
            //处理数
            foreach ($cl_info as $k => $v) {
                $data = [
                    'date'=>$this->date,
                    'bj_num'=>0,
                    'bhgl'=>0,
                    'sy_num'=>0,
                    'module_id'=>3
                    ];
                $da[] = array_merge($v, $data);
            }
            if(isset($da) && is_array($da)){
                DB::table('stat_day')->insert($da);
            }
        }
    }

    /*根据日统计计算 周和月统计  每天统计*/
    protected function statWeekAndMonth(){
        //$week_day = date("w", $this->time);
        //var_dump($week_day);
        //if($week_day == 1){  //周一 添加新行  否则更新该周数据
        //更新统计日期 或添加新设备的统计
        $this->addNewWeekAndMonthData();
        //}
        //$month_day = date("d", $this->time);
        //if($month_day == '01'){  //每月1号 添加新行  否则更新该月数据
        //$this->addNewMonthData();
        //}

        $week = date('W', $this->time);
        $month = date('m', $this->time);
        $year=date('Y',$this->time);
        //当天统计数据
        $field = 'sc_num,scl,bj_num,cl_num,device_id';
        $data = $this->getStatData((new stat_day), $field);
        //更新统计数据
        $this->updateData((new stat_week), $data, 'week', $week,$year);
        $this->updateData((new stat_month), $data, 'month', $month,$year);
    }

    protected function getStatData($model, $field){
        $list = $model->select(DB::raw($field))
                      ->where('date', '=', $this->date)
                      ->orderByRaw('id asc')
                      ->get()
                      ->toArray();
        return $list;
    }

    /*生产 试验数*/
    protected function getDayScNum($model, $field, $start, $end){
        $info = $model->select(DB::raw($field))
                        ->where('time', '>=', $start)
                        ->where('time', '<', $end)
                        ->groupBy('device_id')
                        ->orderByRaw('device_id asc')
                        ->get()
                        ->toArray();

        return $info;
    }

    /*生产量*/
    protected function getDayScl($table, $device_id){
        $num = DB::table($table)
                    ->where('device_id', $device_id)
                    ->where('date', $this->date)
                    ->pluck('num');

        return $num;
    }

    /*报警数*/
    protected function getDayBjNum($model, $device_id, $start, $end){
        $num = $model->where('time', '>=', $start)
                        ->where('time', '<', $end)
                        ->where('device_id', $device_id)
                        ->where('is_warn', 1)
                        ->count();
        return $num;
    }

    /*处理数*/
    protected function getDayClNum($model, $field, $table, $start, $end){
        //DB::connection()->enableQueryLog();
        //处理数  包括之前(3天内)和当天报警的数据今天处理的
        $cl_info = $model->select(DB::raw($field))
                        ->leftJoin('warn_deal_info', function($join) use($table, $start, $end){
                            $join->on($table.'.id', '=', 'warn_deal_info.info_id')
                                 ->on($table.'.device_id', '=', 'warn_deal_info.device_id')
                                 ->where(function($query) use($start, $end){
                                    $query->where(function($query) use($start, $end){
                                            $query->where('warn_deal_info.sec_time', '>=', $start)
                                                  ->where('warn_deal_info.sec_time', '<', $end);
                                        })
                                        ->orWhere(function($query) use($start, $end){
                                            $query->where('warn_deal_info.sup_time', '>=', $start)
                                                  ->where('warn_deal_info.sup_time', '<', $end);
                                        });
                                 });
                        })
                        ->whereNotNull('warn_deal_info.id')
                        ->where($table.'.time', '>=', $start-86400*3)
                        ->where($table.'.time', '<', $end)
                        ->where($table.'.is_sec_deal', '=', 1)
                        ->where($table.'.is_sup_deal', '=', 1)
                        ->groupBy($table.'.device_id')
                        ->orderByRaw($table.'.device_id asc')
                        ->get()
                        ->toArray();
        //var_dump($cl_info);
        //print_r(DB::getQueryLog());

        return $cl_info;
    }

    /*获取所有设备*/
    protected function getDevice($cat_id){
        $query = Device::select(['id', 'project_id', 'section_id', 'supervision_id']);
        if(is_array($cat_id)){
            $query = $query->whereIn('cat_id', $cat_id);
        }else{
            $query = $query->where('cat_id', $cat_id);
        }
        $list = $query->orderByRaw('id asc')->get()->toArray();
        return $list;
    }

    /*添加/更新统计新数据*/
    protected function addNewWeekAndMonthData(){
        //计算第几周 几月
        $data['week'] = date('W', $this->time);
        $data['month'] = date('m', $this->time);
        $data['year']=date('Y',$this->time);
        $data['created_at'] = time();
        
        //拌和站
        $data['module_id'] = 4;
        $device = $this->getDevice(4);
        $this->addNewData('stat_week', $device, $data);
        $this->addNewData('stat_month', $device, $data);
        //试验室
        $data['module_id'] = 3;
        $device = $this->getDevice([1,2,3]);
        $this->addNewData('stat_week', $device, $data);
        $this->addNewData('stat_month', $device, $data);
    }

    /*添加数据*/
    protected function addNewData($table, $device, $data){
        foreach($device as $key=>$value){
            $data['device_id'] = $value['id'];
            unset($value['id']);
            $info = array_merge($value, $data);
            if($table == 'stat_week'){
                unset($info['month']);
                $column = 'week';
                $column_value = $info['week'];
            }else{
                unset($info['week']);
                $column = 'month';
                $column_value = $info['month'];
            }
            //判断该周/月 此设备是否已经添加统计  添加则更新 否则添加
            $res = DB::table($table)
                        ->where('device_id', $info['device_id'])
                        ->where($column, $column_value)
                        ->where('year',$info['year'])
                        ->first();
            if($res){
                DB::table($table)->where('id', $res->id)->update(['created_at'=>$info['created_at']]);
            }else{
                DB::table($table)->insert($info);
            }
        }
    }

    /*更新统计数据*/
    protected function updateData($model, $data, $column, $column_data,$year){
        $time = time();
        foreach ($data as $key => $value) {
            $info = $model->where($column, $column_data)
                            ->where('device_id', $value['device_id'])
                            ->where('year',$year)
                            ->orderByRaw('id desc')
                            ->first();
            $new_info = [
                    'sc_num' => $info['sc_num'] + $value['sc_num'],
                    'scl'=> $info['scl'] + $value['scl'],
                    'bj_num'=> $info['bj_num'] + $value['bj_num'],
                    'cl_num'=> $info['cl_num'] + $value['cl_num'],
                    ];
            if($new_info['sc_num']){
                $new_info['bhgl'] = round($new_info['bj_num']/$new_info['sc_num'], 3)*100;
            }
            if($new_info['bj_num']){
                $new_info['cll'] = round($new_info['cl_num']/$new_info['bj_num'], 3)*100;
            }
            $new_info['created_at'] = $time;
            $model->where('id', $info['id'])->update($new_info);
        }
    }

    /*添加本月统计新数据*/
    protected function addNewMonthData(){
        //计算第几月
        $data['month'] = date('m', $this->time);
        $data['created_at'] = time();
        //为所有设备添加新数据
        //拌和站
        $device = $this->getDevice(1);
        $this->addNewData('snbhz_stat_month', $device, $data);
        //试验室
        $device = $this->getDevice([1,2,3]);
        $this->addNewData('lab_stat_month', $device, $data);
    }
}