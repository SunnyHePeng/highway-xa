<?php

namespace App\Http\Controllers\Remove;

/**
 * 征地拆迁
 */
use App\Models\Project\Project;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Remove\BaseController;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, Auth, DB, Cache, Log;
use Mockery\Exception;

use App\Models\Project\Supervision,
    App\Models\Project\Section,
    App\Models\Remove\RemoveDay,
    App\Models\Remove\RemoveTotal;

class RemoveController extends BaseController
{

    protected $page=20;

    protected $supervision_model;
    protected $section_model;
    protected $remove_total_model;
    protected $remove_day_model;

    public function __construct()
    {
        parent::__construct();
        view()->share(['module' => '征地拆迁']);

        $this->supervision_model=new Supervision();
        $this->section_model=new Section();
        $this->remove_total_model=new RemoveTotal();
        $this->remove_day_model=new RemoveDay();
    }


    /**
     * 征地拆迁设置
     */
    public function removeSet()
    {

        $remove_total_data=$this->remove_total_model->with('section','supervision')
                                                    ->get();

//        dd($remove_total_data);

        return view('remove.remove_stat.remove_set',compact('remove_total_data'));

    }

    /**
     * 添加征地拆迁总量信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function removeTotalAdd(Request $request)
    {
        if ($request->isMethod('get')) {

            //获取监理信息
            $supervision_data=$this->getSupervision($this->supervision_model);
//            dd($supervision_data);
            return view('remove.remove_stat.remove_total_add',compact('supervision_data'));
        }

        if ($request->isMethod('post')) {
             $input_data=$request->all();

             if($input_data['supervision_id']==0){
                 $result['status']=0;
                 $result['mess']='请选择监理';
                 return $result;
             }

             if($input_data['section_id']==0){
                 $result['status']=0;
                 $result['mess']='请选择合同段';
                 return $result;
             }

             try{
              $this->remove_total_model->create($input_data);

              $result['status']=1;
              $result['mess']='添加总量信息成功';
              return $result;

             }catch (\Exception $e){
                 $result['status']=0;
                 $result['mess']='添加总量信息失败';
                 return $result;
             }




        }
    }

    /**
     * 删除征地拆迁总量信息
     */
    public function removeTotalDel($id)
    {

        try {
            $this->remove_total_model->where('id',$id)
                                     ->delete();

            $result['status'] = 1;
            $result['info'] = '删除成功';
            $result['id'] = $id;
            return $result;
        } catch (\Exception $e) {

            $result['status'] = 0;
            $result['info'] = '删除失败';
            return $result;
        }

    }

    /**
     * 根据监理id获取合同段信息
     */
    public function getSectionBySupervision(Request $request)
    {
        $supervision_id = $request->get('supervision_id');

        $section = DB::table('supervision_section')->select('section_id')
            ->where('supervision_id', $supervision_id)
            ->lists('section_id');

        $section_data = $this->section_model->select('id', 'name')
            ->whereIn('id', $section)
            ->get()
            ->toArray();

        if (!$section_data) {

            $result['status'] = 1;
            $result['info'] = '未获取到该监理所管理的合同段';
            return Response()->json($result);

        } else {

            $result['status'] = 0;
            $result['info'] = '获取成功';
            $result['data'] = $section_data;
            return Response()->json($result);

        }

    }


    /**
     *征地拆迁日统计
     */
    public function removeDay()
    {

        $role = $this->user->role;
        $url=url('stat/remove_day');

        if ($role == 1 || $role == 3) {
            $removeDayData=$this->getRemoveDayDataToPro($this->remove_total_model,$this->remove_day_model);
//            dd($removeDayData);
            //超级管理员或项目管理处用户获取到的征地拆迁数据
            $view = 'remove.remove_stat.remove_day_project';

        } else {
            $field=[
                'supervision_id',
                'section_id',
                'occupation_day',
                'house_day',
                'pylon_day',
                'parallels_day',
                'optical_cable_day',
                'water_pipe_day',
                'natural_gas_pipeline_day',
                'special_remove_day',
                'time'
            ];
            //监理或合同段用户获取到的征地拆迁数据
            $removeDayData=$this->getRemoveDayDataToSec($this->remove_day_model,$role,$field,$this->page,$url);

            $view = 'remove.remove_stat.remove_day';
        }

        return view($view,$removeDayData);
    }

    /**
     * 征地拆迁日数据录入
     */
    public function removeDayAdd(Request $request)
    {
        if ($request->isMethod('get')) {

            $role = $this->user->role;

            if ($role == 1 || $role == 3 || $role == 4) {
                $info = '您没有对应的权限,该数据由合同段用户录入';

                return view('admin.error.iframe_no_info',compact('info'));

            }else{
                //所属监理id
                $supervision_id=$this->user->supervision->id;
                //所属合同段id
                $section_id=$this->user->section->id;

                return view('remove.remove_stat.remove_day_add',compact('supervision_id','section_id'));

            }

        }

        if ($request->isMethod('post')) {

            $input_data = $request->all();

            //判断今日数据是不是已经录入过
            $today_data = $this->remove_day_model->whereBetween('time', [strtotime(date('Y-m-d',time())) , strtotime(date('Y-m-d',time()+86400))])
                                                 ->where('section_id', $input_data['section_id'])
                                                 ->first();

            if ($today_data) {
                $result['status'] = 1;
                $result['mess'] = '今日数据已录入，请勿重复录入';

                return $result;
            }

            if ($input_data['occupation_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入用地今日交付数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['house_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入房屋今日拆迁数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['pylon_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入铁塔今日迁改数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['parallels_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入双杆今日迁改数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['optical_cable_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入地埋电缆今日迁改数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['water_pipe_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入输水管道今日迁改数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['natural_gas_pipeline_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入天然气管道今日迁改数据，如今日无该数据填写0';
                return $result;
            }

            if ($input_data['special_remove_day'] == '') {
                $result['status'] = 1;
                $result['mess'] = '请输入特殊拆除物今日拆除数据，如今日无该数据填写0';
                return $result;
            }

            $input_data['time']=time();

            try {
                $this->remove_day_model->create($input_data);
                $result['status'] = 0;
                $result['mess'] = '今日数据录入成功';
                return $result;
            } catch (\Exception $e) {

                Log::info($e);
                $result['status'] = 1;
                $result['mess'] = '数据录入失败，请重试或联系管理员';
                return $result;
            }

        }


    }

    /**
     * 按时间范围统计
     */
    public function removeStatByTimeHorizon(Request $request)
    {
        $role = $this->user->role;

        if ($role == 1 || $role == 3) {

            $removeStatData=$this->getRemoveStatData($request,$this->remove_total_model,$this->remove_day_model);
            return view('remove.remove_stat.remove_stat_by_time_horizon',$removeStatData);
        } else {

            $info = '您没有该功能对应的权限';

            return view('admin.error.no_info', compact('info'));
        }


    }





}
