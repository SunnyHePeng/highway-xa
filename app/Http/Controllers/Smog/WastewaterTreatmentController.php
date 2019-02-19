<?php

namespace App\Http\Controllers\Smog;

use App\Models\Device\Device;
use App\Models\Project\Section;
use App\Models\Smog\WasteWaterTreatmentInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\IndexController;
use DB,Auth;

/**
 * 污水处理
 */
class WastewaterTreatmentController extends BaseController
{
    protected $order = 'id desc';
    protected $device_cat = 18;
    protected $ispage = 20;
    protected $device_model;
    protected $water_model;
    protected $section_model;
    public function __construct(Device $device,WasteWaterTreatmentInfo $wasteWaterTreatmentInfo)
    {
        parent::__construct();
        view()->share(['module'=>'治污减霾']);
        $this->device_model=$device;
        $this->water_model=$wasteWaterTreatmentInfo;
        $this->section_model=new Section();
    }

    /**
     * 污水处理实时监测数据
     */
    public function wastewaterNewest()
    {
//
        $role=$this->user->role;

        $device_query=$this->device_model;

        if($role==1 || $role==3){
            $device_query=$device_query->where([]);
        }

        if($role==4){
            $supervision_id=$this->user->supervision->id;
            $device_query=$device_query->where('supervision_id',$supervision_id);
        }

        if($role==5){
            $section_id=$this->user->section->id;
            $device_query=$device_query->where('section_id',$section_id);
        }

        $device_data=$device_query->where('cat_id',$this->device_cat)
                                  ->orderBy('section_id','asc')
                                  ->get()
                                  ->toArray();

        $now_data=[];

        foreach($device_data as $v){

            $now_device_id=$v['id'];
            $now_section_data=$this->water_model->where('device_id',$now_device_id)
                ->with('section','supervision','device')
                ->orderBy('id','desc')
                ->first();

            if($now_section_data){
                array_push($now_data,$now_section_data);
            }

        }

//        dd($device_data);

        return view('smog.waste_water.waste_water_newest',compact('now_data'));
    }

    /**
     * 污水处理数据
     */
    public function wastewaterIndex()
    {
        $role=$this->user->role;
        $device_model=$this->device_model;
        $device_field=[
            'id',
            'project_id',
            'supervision_id',
            'section_id',
            'name',
            'model'
        ];

        $device_data=$this->getDevice($role,$device_model,$this->device_cat,$device_field);

       return view('smog.waste_water.waste_water_index',compact('device_data'));
    }

    /**
     * 污水处理数据
     * @return mixed
     */
    public function getWastewaterData(Request $request,$device_id)
    {
        $url=url('smog/get_waste_water_data').'/'.$device_id;

         $device=$this->device_model->find($device_id);

         $time_field='time';

         $data_field=[
             'id',
             'time',
             'enter_instantaneous_flow',
             'exit_instantaneous_flow',
             'voltage',
             'water_temperature',
             'enter_BOD',
             'exit_BOD',
             'pH',
             'chrominance'
         ];

        $water_data=$this->getDataByDevice($request,$this->water_model,$this->device_model,$device_id,$this->ispage,$url,$data_field,$time_field);


        return view('smog.waste_water.data_list',$water_data);


    }

    /**
     * 污水处理监测历史
     * @param Request $request
     */
    public function wastewaterHistory(Request $request)
    {
        $section_field=[
            'id',
            'name',
        ];
        //获取合同段信息
        $section_data=$this->getSection($this->section_model,$section_field);

        $wastewater_field=[
            'supervision_id',
            'section_id',
            'device_id',
            'time',
            'enter_instantaneous_flow',
            'exit_instantaneous_flow',
            'voltage',
            'water_temperature',
            'enter_BOD',
            'exit_BOD',
            'pH',
            'chrominance',
            'place',
            'created_at'
        ];

        if($request->get('d')){

            $view='smog.waste_water.waste_water_history_export';

        }else{

           $view='smog.waste_water.waste_water_history';
        }

        $url=url('smog/waste_water_history');
//        dd($this->environment_info_model);
        //获取监测数据

        $wastewater_history_data=$this->getEnvironmentHistoryData($request,$wastewater_field,$this->water_model,$this->ispage,$url);
        $wastewater_history_data['section_data']=$section_data;
//        dd($wastewater_history_data);
        return view($view,$wastewater_history_data);
    }

    /**
     *实时视频
     */
    public function realTimeVideoByDevice($device_id)
    {


        $device=$this->device_model->find($device_id);

        return view('smog.waste_water.real_video_by_device',compact('device'));

    }

    /**
     * 实时视频中获取数据
     */
    public function DataInRealTimeVideoByDevice(Request $request,$device_id)
    {
        $url=url('smog/data_in_real_time_video_by_device').'/'.$device_id;

        $data_field=[
            'time',
            'enter_instantaneous_flow',
            'exit_instantaneous_flow',
            'voltage',
            'water_temperature',
            'enter_BOD',
            'exit_BOD',
            'pH',
            'chrominance',
            'place',
            'created_at'
        ];

        $time_field='time';

        $data=$this->getDataByDevice($request,$this->water_model,$device_id,$this->ispage,$url,$data_field,$time_field);

        return view('smog.waste_water.data_in_real_time_video_by_device',$data);
    }








}
