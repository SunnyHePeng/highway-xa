<?php

namespace App\Http\Controllers\Stretch;

use App\Models\Device\Device;
use App\Models\Stretch\StretchInfo;
use App\Models\Stretch\StretchInfoDetail;
use App\Models\Stretch\StretchMudjackStatWarn;
use App\Models\Stretch\StretchMudjackWarnDealInfo;
use App\Models\Stretch\StretchMudjackWarnUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use Input, Auth, DB, Cache,Log;
use Mockery\Exception;

/**
 * 张拉
 * Class StretchController
 * @package App\Http\Controllers\Stretch
 */
class StretchController extends BaseController
{

    protected $ispage=20;
    protected $device_cat=8;
    protected $device_model;
    protected $stretch_info_model;
    protected $stretch_info_detail_model;
    protected $module_name='stretch';


    public function __construct(Device $device,StretchInfo $info,StretchInfoDetail $detail)
    {
        parent::__construct();
        view()->share(['module' => '智能张拉']);

        $this->device_model=$device;
        $this->stretch_info_model=$info;
        $this->stretch_info_detail_model=$detail;

    }

    /**
     * 设备信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function deviceIndex(Request $request)
    {

        $role = $this->user->role;

        $view='stretch.device_index';

        $device_field = [
                         'id',
                         'project_id',
                         'supervision_id',
                         'section_id',
                         'beam_site_id',
                         'name',
                         'cat_id',
                         'last_time'
                        ];

        $data = $this->getDeviceData($this->device_model, $this->device_cat, $device_field, $role,$this->stretch_info_model,$this->stretch_info_detail_model);
//        dd($data);
        return view($view,$data);
    }

    /**
     * 设备列表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function deviceList(Request $request)
    {
        $role = $this->user->role;

        $device_field = [
                      'id',
                      'project_id',
                      'supervision_id',
                      'section_id',
                      'beam_site_id',
                      'name',
                      'dcode',
                      'model',
                      'factory_name',
                      'factory_date',
                      'fzr',
                      'phone'
        ];

        $device_data = $this->getDeviceList($this->device_model, $device_field, $this->device_cat,$role);

       return view('stretch.device_list',compact('device_data'));
    }

    /**
     * 根据设备获取张拉数据
     */
    public function stretchDataByDevice(Request $request,$device_id)
    {

        $field = [
                'id',
                'girder_number',
                'time',
                'stretch_unit',
                'supervisor_unit',
                'concrete_design_intensity',
                'concrete_reality_intensity',
                'stretch_order',
                'engineering_name',
                'precasting_yard',
                'stretch_craft',
                'component_type'
        ];

        $url=url('stretch/stretch_data_by_device').'/'.$device_id;

        $data=$this->getInfoDataByDevice($request,$this->stretch_info_model,$field,$device_id,$this->ispage,$url);

        return view('stretch.stretch_data_by_device',$data);
    }

    /**
     * 张拉详细数据
     */
    public function stretchDetail($info_id)
    {
        $info_data = $this->stretch_info_model->with('device')
                                              ->find($info_id);
//        dd($info_data);

        $detail_data = $this->stretch_info_detail_model->where('info_id', $info_id)
                                                       ->get();

        return view('stretch.stretch_detail',compact('info_data','detail_data'));
    }

    /**
     * 实时视频
     */
    public function realTimeVideo($device_id)
    {
       $device=$this->device_model->find($device_id);
//       dd($device);
       return view('stretch.real_time_video',compact('device'));
    }

    /**
     * 实时视频中获取数据
     */
    public function getDataAtRealVideo(Request $request,$device_id)
    {
        $field = [
                'id',
                'girder_number',
                'stretch_unit',
                'stretch_order',
                'precasting_yard',
                'component_type',
                'time'
        ];

        $url=url('stretch/get_data_at_real_video').'/'.$device_id;

        $data = $this->getDataInRealVideo($request,$this->stretch_info_model, $device_id, $this->ispage,$field,$url);
//        dd($data);
        return view('stretch.get_data_at_real_video',$data);
    }

    /**
     * 张拉数据
     */
    public function stretchData(Request $request)
    {
        $role= $this->user->role;

        $url = url('stretch/stretch_data');

        $view = 'stretch.stretch_data';

        $field = [
            'id',
            'project_id',
            'supervision_id',
            'section_id',
            'device_id',
            'beam_site_id',
            'girder_number',
            'time',
            'stretch_unit',
            'supervisor_unit',
            'concrete_design_intensity',
            'concrete_reality_intensity',
            'stretch_order',
            'engineering_name',
            'stretch_craft',
            'component_type'
        ];
        $data = $this->getData($request, $this->stretch_info_model, $role, $url, $this->ispage, $field);
//        dd($data);

        return view($view,$data);
    }

    /**
     * 报警信息
     */
    public function warnInfo(Request $request)
    {
        $role=$this->user->role;

        $view = 'stretch.warn_info';

        $detail_name='stretch_info_detail';

        $info_name='stretch_info';

        $select_raw='stretch_info_detail.id as detail_id,
                     stretch_info_detail.stretch_time,
                     stretch_info_detail.is_warn,
                     stretch_info_detail.warn_info,
                     stretch_info_detail.is_sec_deal,
                     stretch_info_detail.is_sup_deal,
                     stretch_info_detail.pore_canal_name,
                     stretch_info.id as sinfo_id,
                     stretch_info.project_id as project_id,
                     stretch_info.supervision_id as supervision_id,
                     stretch_info.section_id as section_id,
                     stretch_info.device_id as device_id,
                     stretch_info.beam_site_id as beam_site_id,
                     stretch_info.girder_number,
                     stretch_info.time,
                     project.name as project_name,
                     supervision.name as supervision_name,
                     section.name as section_name,
                     beam_site.name as beam_site_name,
                     device.name as device_name';

        $data = $this->getWarnInfo($request,$role,$detail_name,$info_name,$select_raw,$this->ispage);
        $data['role']=$role;
//        dd($data);
        return view($view,$data);
    }

    /**
     * 获取报警信息选项
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelectVal($type)
    {
        $data = $this->getCommonSelectVal($type);
        return $data;
    }

    /**
     * 报警信息中获取该数据详细信息
     */
    public function detailInWarn($detail_id)
    {

      $view='stretch.detail_in_warn';


      $detail=$this->getDetailInWarn($this->stretch_info_detail_model,$detail_id);

      $warn_deal_data=StretchMudjackWarnDealInfo::where('module_name',$this->module_name)
                                                 ->where('detail_id',$detail->id)
                                                 ->where('info_id',$detail->info_id)
                                                 ->first();
//      dd($warn_deal_data);
      return view($view,compact('detail','warn_deal_data'));

    }

    /**
     * 报警处理
     */
    public function warnDeal(Request $request,$detail_id)
    {
        $view='stretch.warn_deal';
        $time_field='stretch_time';
        $role=$this->user->role;
//        dd($role);
        $data=$this->doDeal($view,$request,$this->stretch_info_detail_model,$role,$detail_id,$time_field);

        return $data;
    }


    /**
     *处理报告
     */
    public function warnDealReport($detail_id)
    {

        $detail=$this->stretch_info_detail_model->with(['info'=>function($query){
                                                   $query->with('section','device');
                                                }])
                                                ->find($detail_id);
        $info_id=$detail->info_id;

        $warn_deal_info=StretchMudjackWarnDealInfo::where('module_name',$this->module_name)
                                                   ->where('detail_id',$detail_id)
                                                   ->where('info_id',$info_id)
                                                   ->first();
        return view('stretch.warn_deal_report',compact('detail','warn_deal_info'));
    }

    /**
     * 报警设置
     */
    public function warnSet(Request $request)
    {
        //设置报警信息只有具有审核权限的用户才可查看，设置，删除，超级管理员不设限
        $view='stretch.warn_set';
        $WarnUserModel=new StretchMudjackWarnUser();
        $data=$this->doWarnSet($WarnUserModel,$view,$request);

        return $data;
//        return view('stretch.warn_set');
    }

    /**
     * 添加报警通知人员
     */
    public function warnUserAdd(Request $request,User $user, StretchMudjackWarnUser $stretchMudjackWarnUser)
    {

       $view='stretch.warn_user_add';

       $user_field=[
           'id',
           'username',
           'name',
           'position_id',
           'role',
           'phone'
       ];

       $data=$this->doWarnUserAdd($request,$view,$user,$stretchMudjackWarnUser,$user_field);

       return $data;

    }

    /**
     * 删除报警通知人员
     */
    public function warnUserDel($id,StretchMudjackWarnUser $stretchMudjackWarnUser)
    {
        $role=$this->user->role;
        $hasSh=$this->user->has_sh;

        if ($role == 1 || $hasSh == 1) {
            try{
             $stretchMudjackWarnUser->where('id',$id)->delete();
             return ['status'=>1,'info'=>'删除成功','id'=>$id];

            }catch (Exception $e){
                return ['status'=>0,'info'=>'删除失败'];
            }

        }else{
            return ['status'=>0,'info'=>'您没有删除权限'];
        }

    }


   /**
    * 报警信息统计
    */
   public function statWarn(Request $request,StretchMudjackStatWarn $stretchMudjackStatWarn)
   {
       $url=url('stretch/stat_warn');
       $role=$this->user->role;

       $section_data=$this->getSectionByRole($role);

       $data=$this->getStatWarn($request,$this->module_name,$stretchMudjackStatWarn,$role,$url);
       $data['section_data']=$section_data;

       return view('stretch.stat_warn',$data);

   }




















}
