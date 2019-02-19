<?php

namespace App\Http\Controllers\Lab;
/**
 * 集料试验
 */
use App\Models\Lab\Lab_info_detail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, DB, Cache;
use App\Http\Controllers\Mixplant\IndexController;
use App\Models\Device\Device;
use App\Models\Lab\Lab_info;
use App\Models\Project\Section;

use Qiniu\Config;
use Carbon\Carbon;
use App\Soap\LabStatistic;
use App\Extend\Helpers;
use Illuminate\Pagination\LengthAwarePaginator;

class AggregateController extends IndexController
{
    protected $order = 'id desc';
    protected $device_cat = 17;
    protected $ispage = 20;
    protected $module = 3;
    protected $sylx=[14,15];
    protected $device_model;
    protected $lab_info_model;
    public function __construct(Device $device,Lab_info $lab_info)
    {
        parent::__construct();
        view()->share(['module'=>'试验室']);
        $this->device_model=$device;
        $this->lab_info_model=$lab_info;

    }

    /**
     * 集料试验数据
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function aggregateIndex()
    {
        $section_model=new Section();
       //获取
       $device_data=$this->getAggregateDevice($section_model);
//        dd($device_data);
       return view('lab.aggregate.aggregate_index',compact('device_data'));
    }
    //获取集料试验设备信息
    protected function getAggregateDevice($section_model)
    {
        $role=$this->user->role;


        //超级管理员或项目管理处用户
        if($role==1 || $role==3){

            //获取合同段信息
            $section_data=$section_model->select('id')->get()->toArray();

            $device_data=[];

            foreach($section_data as $v){
                $section_id=$v['id'];
                $device=$this->device_model->where('cat_id',$this->device_cat)
                                                    ->where('section_id',$section_id)
                                                    ->orderBy('section_id','asc')
                                                    ->with('sup','section')
                                                    ->first();
                if($device){
                    array_push($device_data,$device);
                }

            }

        }

        //监理用户
        if($role==4){
             //获取监理id
            $supervision_id=$this->user->supervision->id;
            $device_data=[];
            $device=$this->device_model->where('cat_id',$this->device_cat)
                                       ->where('supervision_id',$supervision_id)
                                       ->with('sup','section')
                                       ->orderBy('section_id','asc')
                                       ->first();
            if($device){
                array_push($device_data,$device);
            }

        }

        //合同段用户
        if($role==5){
            //获取所属合同段id
            $section_id=$this->user->section->id;

            $device_data=[];
            $device=$this->device_model->where('cat_id',$this->device_cat)
                                       ->where('section_id',$section_id)
                                       ->with('sup','section')
                                       ->first();
            if($device){
                array_push($device_data,$device);
            }

        }

        return $device_data;
    }

    /**
     * 实时视频
     */
    public function aggregateVideo($device_id)
    {

      $device=$this->device_model->find($device_id);

      return view('lab.aggregate.aggregate_video',compact('device'));

    }

    /**
     * 实时视频中获取数据
     */
    public function getAggregateDataAtVideo(Request $request,$device_id)
    {

        $url=url('lab/get_aggregate_data_at_video').'/'.$device_id;
        $search=[];

        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');
        $search_sylx=$request->get('search_sylx');

        $start_time=$start_date ? strtotime($start_date) : time()-10*86400;
        $end_time=$end_date ? strtotime($end_date)+86400 : time()+86400;

        $search['start_date']=date('Y-m-d',$start_time);
        $search['end_date']=date('Y-m-d',$end_time-86400);

        $url=$url.'?start_date='.date('Y-m-d',$start_time).'&end_date='.date('Y-m-d',$end_time-86400);
        $query=$this->lab_info_model;

        $query=$query->whereBetween('time',[$start_time,$end_time]);

        if($search_sylx){
            $query=$query->where('sylx',$search_sylx);
            $url=$url.'&search_sylx='.$search_sylx;
            $search['search_sylx']=$search_sylx;
        }else{
            $query=$query->where([]);
            $search['search_sylx']=0;
        }


        $device=$this->device_model->find($device_id);

        $aggregate_sylx=Config()->get('common.aggregate_sylx');
        $sylx=array_keys($aggregate_sylx);
//      dd($sylx);

        $project_id=$device->project_id;
        $supervision_id=$device->supervision_id;
        $section_id=$device->section_id;

        //试验数据列表
        $data=$query->where('project_id',$project_id)
                    ->where('supervision_id',$supervision_id)
                    ->where('section_id',$section_id)
                    ->whereIn('sylx',$sylx)
                    ->orderBy('id','desc')
                    ->paginate(3)
                    ->toArray();

        $data['url']=$url;


        //获取最新一条的粗集料筛分数据或细集料筛分数据
        $sf_lab_info_data=$this->lab_info_model
            ->where('project_id',$project_id)
            ->where('supervision_id',$supervision_id)
            ->where('section_id',$section_id)
            ->whereIn('sylx',$sylx)
            ->orderBy('id','desc')
            ->first();


        //获取最新一条的粗集料压碎值数据
        $ys_lab_info_data=$this->lab_info_model
            ->where('project_id',$project_id)
            ->where('supervision_id',$supervision_id)
            ->where('section_id',$section_id)
            ->where('sylx',14)
            ->orderBy('id','desc')
            ->first();
//      dd($ys_lab_info_data);

        if($ys_lab_info_data){

            $sysprimarykey=$ys_lab_info_data->sysprimarykey;
//         dd($sysprimarykey);
            $ys_data=DB::table('cjl01004')->where('SysPrimaryKey',$sysprimarykey)
                ->first();

        }else{

            $ys_data='';
        }


//      dd($lab_info_data);
        if ($sf_lab_info_data) {
            $now_sylx = $sf_lab_info_data->sylx;
            $sysprimarykey = $sf_lab_info_data->sysprimarykey;

            /**
             *筛分类型
             *    干筛法-》gsf
             *    水筛法-》ssf
             */
            $sf_type='';

            if ($now_sylx == 14) {

                //粗集料
                $cjl_1=DB::table('cjl01008')->where('sysprimarykey',$sysprimarykey)
                    ->first();

                if ($cjl_1){
                    $sf_data=$cjl_1;
                    $sf_type='ssf';

                }else{
                    $cjl_2=DB::table('cjl01009')->where('sysprimarykey',$sysprimarykey)
                        ->first();
                    $sf_data=$cjl_2;
                    $sf_type='gsf';
                }

            } else {
                //细集料
                $xjl_1 = DB::table('xjl01003')->where('sysprimarykey', $sysprimarykey)
                    ->first();

                if($xjl_1){
                    $sf_data=$xjl_1;
                    $sf_type='gsf';
                }else{
                    $xjl_2 = DB::table('xjl02013')->where('sysprimarykey', $sysprimarykey)
                        ->first();
                    $sf_data=$xjl_2;
                    $sf_type='ssf';
                }

            }

        } else {
            $sf_data='';
            $sf_type='';
        }

        $data['search']=$search;
        $data['sf_lab_info_data']=$sf_lab_info_data;
        $data['ys_lab_info_data']=$ys_lab_info_data;
        $data['sf_data']=$sf_data;
        $data['ys_data']=$ys_data;
        $data['sf_type']=$sf_type;

        return view('lab.aggregate.get_aggregate_data_at_video',$data);

    }

    /**
     * 试验数据
     */
    public function aggregateData(Request $request,$device_id)
    {

        $url=url('lab/get_aggregate_data').'/'.$device_id;
        $aggregate_sylx=Config()->get('common.aggregate_sylx');

        //获取试验数据
        $aggregate_data=$this->getAggregateData($device_id,$request,$aggregate_sylx,$url);
        $aggregate_data['aggregate_sylx']=$aggregate_sylx;
//        dd($aggregate_data);

        return view('lab.aggregate.get_aggregate_data',$aggregate_data);
    }

    /**
     * 视频回放
     */
    public function videoPlayback($info_id,Lab_info_detail $lab_info_detail)
    {
        $detail=$lab_info_detail->where('lab_info_id',$info_id)
                                ->orderByRaw('type asc')
                                ->get()
                                ->toArray();
//        dd($detail);
        $videoPath=Config()->get('common.videoPath');
        $videoUrl=Config()->get('common.videoUrl');

//        dd($path);
        if(empty($detail)){
            $info='暂时还没有视频回放数据';
            return view('admin.error.iframe_no_info',compact('info'));
        }else{
            $videoFile=[];
//            dd($detail);
            foreach($detail as $k=>$v){
                if(file_exists(iconv('UTF-8','GB2312',$videoPath."\\".$v['videoName'].'.mp4'))){
                    $file=$videoUrl.$v['videoName'].'.mp4';
                       array_push($videoFile,$file);
                } elseif(file_exists(iconv('UTF-8','GB2312',$videoPath."\\".$v['videoName'].'.flv'))) {
                    $file=$videoUrl.$v['videoName'].'.flv';
                    array_push($videoFile,$file);
                }
            }
//            dd($videoFile);
            return view("lab.aggregate.video_playback",compact('videoFile'));
//            return view("lab.aggregate.video_playback",compact('detail'));
        }

//        dd($detail);


    }

    /**
     * 获取试验数据
     * @param $device_id
     */
    protected function getAggregateData($device_id,$request,$aggregate_sylx,$url)
    {
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');
        $now_sylx=$request->get('sylx');

        $search=[];
        if($start_date){
           $start_time=strtotime($start_date);
            $search['start_date']=date('Y-m-d',$start_time);
            $url=$url.'?start_date='.$start_date;
        }else{
           $start_time=time()-86400*30;
           $search['start_date']=date('Y-m-d',$start_time);
           $url=$url.'?start_date='.date('Y-m-d',$start_time);
        }

        if($end_date){
            $end_time=strtotime($end_date)+86400;
            $search['end_date']=date('Y-m-d',$end_time-86400);
            $url=$url.'&end_date='.date('Y-m-d',$end_time-86400);
        }else{
            $end_time=time()+86400;
            $search['end_date']=date('Y-m-d',$end_time-86400);
            $url=$url.'&end_date='.date('Y-m-d',$end_time-86400);
        }

        $query=$this->lab_info_model;
        $query=$query->whereBetween('time',[$start_time,$end_time]);

        if($now_sylx){
            $query=$query->where('sylx',$now_sylx);
            $search['now_sylx']=$now_sylx;
            $url=$url.'&sylx='.$now_sylx;
        }else{
            $search['now_sylx']=0;
            $url=$url.'&sylx='.'0';
        }

        $device_data=$this->device_model->find($device_id);

        $supervision_id=$device_data->supervision_id;
        $section_id=$device_data->section_id;

        $sylx=array_keys($aggregate_sylx);

        $aggregate_data=$query->whereIn('sylx',$sylx)
                              ->where('supervision_id',$supervision_id)
//        $aggregate_data=$query->where('supervision_id',$supervision_id)
                              ->where('section_id',$section_id)
                              ->orderBy('id','desc')
                              ->paginate($this->ispage)
                              ->toArray();
        $aggregate_data['search']=$search;
        $aggregate_data['url']=$url;
//        dd($aggregate_data);
        return $aggregate_data;


    }



}
