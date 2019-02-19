<?php

namespace App\Http\Controllers\Mudjack;

use App\Models\Device\Device;
use App\Models\Mudjack\MudjackInfo;
use App\Models\Mudjack\MudjackInfoDetail;
use App\Http\Controllers\Stretch\BaseController;
use App\Models\Stretch\StretchMudjackStatWarn;
use App\Models\Stretch\StretchMudjackWarnDealInfo;
use App\Models\Stretch\StretchMudjackWarnUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use Input, Auth, DB, Cache,Log;
use Mockery\Exception;

/**
 * 压浆
 * Class MudjackController
 * @package App\Http\Controllers\Mudjack
 */
class MudjackController extends BaseController
{

    protected $ispage=20;
    protected $device_cat=9;
    protected $device_model;
    protected $mudjack_info_model;
    protected $mudjack_info_detail_model;
    protected $module_name='mudjack';


    public function __construct(Device $device,MudjackInfo $mudjackInfo ,MudjackInfoDetail $mudjackInfoDetail)
    {
        parent::__construct();
        view()->share(['module' => '智能压浆']);

        $this->device_model=$device;
        $this->mudjack_info_model=$mudjackInfo;
        $this->mudjack_info_detail_model=$mudjackInfoDetail;

    }

    /**
     * 设备状态
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function deviceIndex(Request $request)
    {
        $role = $this->user->role;

        $view='mudjack.device_index';

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

        $data = $this->getDeviceData($this->device_model, $this->device_cat, $device_field, $role,$this->mudjack_info_model,$this->mudjack_info_detail_model);
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

        return view('mudjack.device_list',compact('device_data'));
    }

    /**
     * 根据设备获取压浆数据
     */
    public function mudjackDataByDevice(Request $request,$device_id)
    {

        $field = [
            'id',
            'girder_number',
            'girdertype',
            'mudjackdirect',
            'concretename',
            'mudjackagent',
            'mobility',
            'stirtime',
            'environment_temperature',
            'seriflux_temperature',
            'mudjackmode',
            'stepparam',
            'stretchdate',
            'time'
        ];

        $url=url('mudjack/mudjack_data_by_device').'/'.$device_id;

        $data=$this->getInfoDataByDevice($request,$this->mudjack_info_model,$field,$device_id,$this->ispage,$url);
//        dd($data);
        return view('mudjack.mudjack_data_by_device',$data);
    }

    /**
     *压浆详情数据
     * @param $info_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function mudjackDetail($info_id)
    {
        $info_data = $this->mudjack_info_model->find($info_id);


        $detail_data = $this->mudjack_info_detail_model->where('info_id', $info_id)
                                                       ->get();

        return view('mudjack.mudjack_detail',compact('info_data','detail_data'));

    }

    /**
     * 实时视频
     * @param $device_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function realTimeVideo($device_id)
    {
        $device=$this->device_model->find($device_id);
//       dd($device);
        return view('mudjack.real_time_video',compact('device'));
    }

    /**
     * 实时视频中获取数据
     */
    public function getDataAtRealVideo(Request $request,$device_id)
    {
        $field = [
            'id',
            'girder_number',
            'girdertype',
            'mudjackdirect',
            'mudjackagent',
            'groutingagent',
            'mobility',
            'time'
        ];

        $url=url('mudjack/get_data_at_real_video').'/'.$device_id;

        $data = $this->getDataInRealVideo($request,$this->mudjack_info_model, $device_id, $this->ispage,$field,$url);
//        dd($data);
        return view('mudjack.get_data_at_real_video',$data);
    }

    /**
     * 压浆数据
     */
    public function mudjackData(Request $request)
    {

        $role= $this->user->role;

        $url = url('mudjack/mudjack_data');

        $view = 'mudjack.mudjack_data';

        $field = [
            'id',
            'project_id',
            'supervision_id',
            'section_id',
            'device_id',
            'beam_site_id',
            'girder_number',
            'time',
            'girdertype',
            'mudjackdirect',
            'concretename',
            'mudjackagent',
            'groutingagent',
            'mobility',
            'stirtime',
            'environment_temperature',
            'seriflux_temperature',
            'operating_personnel',
            'mudjackmode',
            'stepparam',
            'stretchdate'
        ];
        $data = $this->getData($request, $this->mudjack_info_model, $role, $url, $this->ispage, $field);
//        dd($data);

        return view($view,$data);
//        return view('mudjack.mudjack_data');
    }

    /**
     * 报警数据
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */

    public function warnInfo(Request $request)
    {
        $role=$this->user->role;

        $view = 'mudjack.warn_info';

        $detail_name='mudjack_info_detail';

        $info_name='mudjack_info';

        $select_raw='mudjack_info_detail.id as detail_id,
                     mudjack_info_detail.start_time,
                     mudjack_info_detail.is_warn,
                     mudjack_info_detail.warn_info,
                     mudjack_info_detail.is_sec_deal,
                     mudjack_info_detail.is_sup_deal,
                     mudjack_info_detail.pore_canal_name,
                     mudjack_info.id as sinfo_id,
                     mudjack_info.project_id as project_id,
                     mudjack_info.supervision_id as supervision_id,
                     mudjack_info.section_id as section_id,
                     mudjack_info.device_id as device_id,
                     mudjack_info.beam_site_id as beam_site_id,
                     mudjack_info.girder_number,
                     mudjack_info.time,
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
     * 报警信息中获取详情信息
     * @param $detail_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailInWarn($detail_id)
    {

        $view='mudjack.detail_in_warn';

        $detail=$this->getDetailInWarn($this->mudjack_info_detail_model,$detail_id);
//
        $warn_deal_data=StretchMudjackWarnDealInfo::where('module_name',$this->module_name)
                                                   ->where('detail_id',$detail->id)
                                                   ->where('info_id',$detail->info_id)
                                                   ->first();

        return view($view,compact('detail','warn_deal_data'));
    }

    /**
     * 报警处理
     * @param Request $request
     * @param $detail_id
     * @return array
     */
    public function warnDeal(Request $request,$detail_id)
    {
        $view='mudjack.warn_deal';
        $time_field='start_time';

        $role=$this->user->role;
//        dd($role);
        $data=$this->doDeal($view,$request,$this->mudjack_info_detail_model,$role,$detail_id,$time_field);

        return $data;
    }

    /**
     * 处理报告
     * @param $detail_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function warnDealReport($detail_id)
    {

        $detail=$this->mudjack_info_detail_model->with(['info'=>function($query){
            $query->with('section','device');
        }])
            ->find($detail_id);
        $info_id=$detail->info_id;

        $warn_deal_info=StretchMudjackWarnDealInfo::where('module_name',$this->module_name)
            ->where('detail_id',$detail_id)
            ->where('info_id',$info_id)
            ->first();
        return view('mudjack.warn_deal_report',compact('detail','warn_deal_info'));
    }

    /**
     * 报警设置
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function warnSet(Request $request)
    {
        //设置报警信息只有具有审核权限的用户才可查看，设置，删除，超级管理员不设限
        $view='mudjack.warn_set';
        $WarnUserModel=new StretchMudjackWarnUser();
        $data=$this->doWarnSet($WarnUserModel,$view,$request);

        return $data;
//        return view('stretch.warn_set');
    }


    /**
     * 添加报警通知人员
     * @param Request $request
     * @param User $user
     * @param StretchMudjackWarnUser $stretchMudjackWarnUser
     * @return array
     */
    public function warnUserAdd(Request $request,User $user, StretchMudjackWarnUser $stretchMudjackWarnUser)
    {

        $view='mudjack.warn_user_add';

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
     * @param $id
     * @param StretchMudjackWarnUser $stretchMudjackWarnUser
     * @return array
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
     * @param Request $request
     * @param StretchMudjackStatWarn $stretchMudjackStatWarn
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function statWarn(Request $request,StretchMudjackStatWarn $stretchMudjackStatWarn)
    {
        $url=url('mudjack/stat_warn');
        $role=$this->user->role;

        $section_data=$this->getSectionByRole($role);

        $data=$this->getStatWarn($request,$this->module_name,$stretchMudjackStatWarn,$role,$url);
        $data['section_data']=$section_data;

        return view('mudjack.stat_warn',$data);

    }









}
