<?php

namespace App\Http\Controllers\BeamSpray;

use App\Models\BeamSpray\BeamInfo;
use App\Models\BeamSpray\BeamSprayRecord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Device\Device;
use Cache;

use App\Http\Controllers\BeamSpray\SprayBaseController;
/**
 *  喷淋养生
 */
class SprayKeepController extends SprayBaseController
{
    protected $order = 'id desc';
    protected $device_cat = 10;
    protected $ispage = 20;
    protected $module = 19;
    protected $device_model;
    protected $beam_info_model;
    protected $beam_spray_record_model;
    public function __construct(Device $device,BeamInfo $beamInfo,BeamSprayRecord $beamSprayRecord)
    {
        parent::__construct();
        view()->share(['module' => '喷淋养生']);
        $this->device_model=$device;
        $this->beam_info_model=$beamInfo;
        $this->beam_spray_record_model=$beamSprayRecord;
    }

    /**
     * 设备列表
     */
    public function device(Request $request)
    {

        $url=url('beam_spray/devices');

        $role=$this->user->role;
//        dd($role);
        //超级管理员或集团用户
        if($role==1 || $role==3){
            $view='beamSpray.device.index_project';
        }else{
            $view='beamSpray.device.index';
        }

        $device_field=[
            'id',
            'project_id',
            'supervision_id',
            'section_id',
            'cat_id',
            'name',
            'model',
            'factory_name',
            'beam_site_id',
        ];

        $device_data=$this->getDeviceList($request,$role,$this->device_cat,$this->ispage,$this->device_model,$device_field,$url);

//        dd($device_data);
        return view($view,$device_data);

    }

    /**
     * 墩身养护
     */
    public function beamList(Request $request)
    {
        $beam_type=1;

        $role=$this->user->role;
        $url=url('beam_spray/beams');

        if($role==1 || $role==2){

            $view='beamSpray.beam.beam_index_project';
        }else{

            $view='beamSpray.beam.beam_index';
        }

        $field=[
            'id',
            'beam_site_id',
            'project_id',
            'section_id',
            'supervision_id',
            'device_id',
            'project_name',
            'project_place',
            'beam_num',
            'start_time',
            'end_time',
            'days_spend',
            'time_count',
            'is_finish',
            'type'
        ];

        $beam_data=$this->getBeamData($request,$role,$this->beam_info_model,$field,$url,$this->ispage,$beam_type);

//        dd($beam_data);

        return view($view,$beam_data);
    }

    /**
     * 喷淋养生记录
     */
    public function sprayDetail($id)
    {
        $url=url('beam_spray/spray_detail').'/'.$id;

        $model=$this->beam_spray_record_model;

        $field=[
            'start_time',
            'end_time',
            'time_count',
            'time_interval',
            'temperature',
            'moisture'
        ];

        $detail_data=$model->select($field)
            ->where('beam_info_id',$id)
            ->paginate($this->ispage)
            ->toArray();

        $detail_data['url']=$url;
//        dd($detail_data);
        return view('beamSpray.beam.spray_detail',$detail_data);


    }

    /**
     * 根据设备查看预制梁信息
     */
    public function beamInfoByDevice(Request $request,$device_id)
    {
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');


        $url=url('beam_spray/beam_info_by_device').'/'.$device_id;
//       dd($id);
        $beam_info_data=$this->beam_info_model->where('device_id',$device_id)
            ->paginate()
            ->toArray();
//        dd($beam_info_data);
        $beam_info_data['url']=$url;

        return view('beamSpray.beam.beam_info_by_device',$beam_info_data);

    }

    /**
     * 梁场养生
     */
    public function beamFieldKeep(Request $request)
    {
        $beam_type=2;

        $role=$this->user->role;
        $url=url('beam_spray/beams');

        if($role==1 || $role==2){

            $view='beamSpray.beam.beam_index_project';
        }else{

            $view='beamSpray.beam.beam_index';
        }

        $field=[
            'id',
            'beam_site_id',
            'project_id',
            'section_id',
            'supervision_id',
            'device_id',
            'project_name',
            'project_place',
            'beam_num',
            'start_time',
            'end_time',
            'days_spend',
            'time_count',
            'is_finish',
            'type'
        ];

        $beam_data=$this->getBeamData($request,$role,$this->beam_info_model,$field,$url,$this->ispage,$beam_type);

//        dd($beam_data);

        return view($view,$beam_data);
    }

    /**
     * 蒸汽养生
     */
    public function steamKeep(Request $request)
    {
        $beam_type=3;

        $role=$this->user->role;
        $url=url('beam_spray/beams');

        if($role==1 || $role==2){

            $view='beamSpray.beam.beam_index_project';
        }else{

            $view='beamSpray.beam.beam_index';
        }

        $field=[
            'id',
            'beam_site_id',
            'project_id',
            'section_id',
            'supervision_id',
            'device_id',
            'project_name',
            'project_place',
            'beam_num',
            'start_time',
            'end_time',
            'days_spend',
            'time_count',
            'is_finish',
            'type'
        ];

        $beam_data=$this->getBeamData($request,$role,$this->beam_info_model,$field,$url,$this->ispage,$beam_type);

//        dd($beam_data);

        return view($view,$beam_data);
    }



}
