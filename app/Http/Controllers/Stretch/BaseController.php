<?php

namespace App\Http\Controllers\Stretch;

use App\Models\Project\Supervision;
use App\Models\Stretch\StretchMudjackWarnDealInfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Cache;

use App\Models\Project\Section;
use App\Models\Project\Project;
use App\Models\Device\Device;
use App\Models\Project\Project_section;

use DB,Input;
use Mockery\Exception;

/**
 * 张拉和压浆模块父类控制器
 */
class BaseController extends IndexController
{


    /**
     * 获取设备信息
     */
 protected function getDeviceData($device_model,$device_cat,$field,$role,$info_model,$detail_model)
 {

     //设备各种状态
     $device_status_list = [
         'J1'=>['0'=>'离线','1'=>'在线'],
         'J2'=>['0'=>'断网','1'=>'良好'],
         'J3'=>['0'=>'断电','1'=>'良好'],
         'J4'=>['0'=>'是','1'=>'否'],
         'J5'=>['0'=>'不正常','1'=>'正常','2'=>'无'],
     ];

     //根据role给model加where条件
     $device_model=$this->getModelByRole($role,$device_model);

     //获取设备信息
     $device = $device_model->select($field)
                            ->with('sup','section','beam_site')
                            ->where('cat_id', $device_cat)
                            ->orderBy('section_id','desc')
                             ->get();

     //当日操作信息
     $that_day=[];

     $device_data=[];
     //设备在线总数
     $device_online_number=0;

     //暂时获取设备状态最新上报时间，（获取设备各个状态待后期完善）
     //设备最新上传时间，按照管理处以往比较奇葩的习惯，不允许有空白
     //所以，当从缓存中取不到设备状态最新上报时间时，将该设备的最新数据上报时间给设备状态最新上报时间
     foreach ($device as $k => $v) {
         if (Cache::get('device_status_time_' . $v->id)) {

             $status_time = Cache::get('device_status_time_' . $v->id);
         } else {
             $status_time = $v->last_time;
         }

         if (Cache::get('device_status_' . $v->id)) {

             $device_status = json_decode(Cache::get('device_status_' . $v->id), true);

             if ($device_status['J1'] == 1) {
                 $device_online_number++;
             }

         }

         $device_data[$k] = $v;
         $device_data[$k]->status_time = $status_time;
     }

     $data['device_data']=$device_data;
     $data['device_online_number']=$device_online_number;
     $data['device_number']=count($device_data);

     return $data;
 }



    /**
     * 获取设备列表
     */
 protected function getDeviceList($device_model,$field,$device_cat,$role)
 {
     $device_model = $this->getModelByRole($role, $device_model);

      $device_data=$device_model->select($field)
                                ->with('sup','section','beam_site')
                                ->where('cat_id',$device_cat)
                                ->orderBy('id','asc')
                                ->get();

      return $device_data;
 }


    /**
     *根据设备获取张拉数据
     */
    protected function getInfoDataByDevice($request,$info_model,$field,$device_id,$page,$url)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $start_time = $start_date ? strtotime($start_date) : strtotime(date('Y-m-d', time() - 86400 * 7));
        $end_time = $end_date ? strtotime($end_date)+86400 : strtotime(date('Y-m-d', time() + 86400));

        $search = [];

        $search['start_date'] = $start_date ? $start_date : date('Y-m-d', $start_time);
        $search['end_date'] = $end_date ? $end_date : date('Y-m-d', $end_time - 86400);

        $url=$url.'?start_date='.$search['start_date'].'&end_date'.$search['end_date'];

        $data = $info_model->select($field)
                                ->where('time', '>=', $start_time)
                                ->where('time', '<', $end_time)
                                ->where('device_id', $device_id)
                                ->paginate($page)
                                ->toArray();

        $data['search'] = $search;
        $data['url'] = $url;

        return $data;
    }


    /**
     *实时视频中获取数据
     */
    protected function getDataInRealVideo($request,$model,$device_id,$page,$field,$url)
    {
        $start_date = $request->get('start_date');

        $end_date = $request->get('end_date');

        $search=[];

        $start_time = $start_date ? strtotime($start_date) : strtotime(date('Y-m-d', time() - 86400 * 7));

        $end_time = $end_date ? strtotime($end_date) + 86400 : strtotime(date('Y-m-d', time() + 86400));

        $search['start_date']= $start_date ? $start_date : date('Y-m-d',$start_time);
        $search['end_date']= $end_date ? $end_date : date('Y-m-d',$end_time-86400);

        $url=$url.'?start_date='.$search['start_date'];
        $url=$url.'&end_date='.$search['end_date'];

        $data = $model->select($field)
                      ->where('device_id',$device_id)
                      ->where('time','>=',$start_time)
                      ->where('time','<',$end_time)
                      ->paginate($page)
                      ->toArray();

        $data['url']=$url;
        $data['search']=$search;
//        dd($data);

        return $data;
    }


    protected function getData($request, $model, $role, $url, $page, $field)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $section_id = $request->get('section_id');

        //根据role给model添加条件
        $model = $this->getModelByRole($role, $model);

        $search = [];

        $start_time = $start_date ? strtotime($start_date) : strtotime(date('Y-m-d', time() - 86400 * 30));

        $end_time = $end_date ? strtotime($end_date)+86400 : strtotime(date('Y-m-d', time() + 86400));

        $search['start_date'] = $start_date ? $start_date : date('Y-m-d', $start_time);

        $search['end_date'] = $end_date ? $end_date : date('Y-m-d', $end_time - 86400);

        $url=$url.'?start_date='.$search['start_date'].'&end_date='.$search['end_date'];

        if ($section_id) {

            $model=$model->where('section_id',$section_id);

            $url=$url.'&section_id='.$section_id;
            $search['section_id'] = $section_id;
        } else {
            $search['section_id'] = 0;

        }


        $data = $model->select($field)
                      ->with('section', 'beamSite', 'device')
                      ->where('time', '>=', $start_time)
                      ->where('time', '<', $end_time)
                      ->paginate($page)
                      ->toArray();

        $data['url'] = $url;

        //合同段信息
        $section_data = $this->getSectionByRole($role);

        $data['section_data'] = $section_data;
        $data['search'] = $search;

        return $data;
    }




    /**
     * 根据role给model加where条件
     * @param $role
     * @param $model
     * @return mixed
     */
    protected function getModelByRole($role,$model)
    {

        //超级管理员，集团用户，项目公司用户
        if($role==1 || $role==2 || $role==3){
            //获取所管理的项目公司
            $project=$this->user->project;
//            dd($model);
//            dd($project);
            $model=$model->whereIn('project_id',$project);
        }

        //监理用户
        if($role==4){
            $supervision_id=$this->user->supervision->id;

            $model=$model->where('supervision_id',$supervision_id);
        }

        //合同段用户
        if($role==5){
            $section_id=$this->user->section->id;
            $model=$model->where('section_id',$section_id);
        }

        return $model;

    }

    /**
     * 获取报警信息
     */
    protected function getWarnInfo($request,$role,$detail_name,$info_name,$select_raw,$page)
    {
        $url=$request->fullUrl();
        $url_page=$request->get('page');
        if($url_page){
           if(strpos($url,'&page='.$url_page)){
               $url=str_replace('&page='.$url_page,'',$url);
           }
            if(strpos($url,'?page='.$url_page)){
                $url=str_replace('?page='.$url_page,'',$url);
            }

        }

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $search_project_ids = $request->get('pro_id');
        $search_supervision_ids = $request->get('sup_id');
        $search_section_ids = $request->get('sec_id');

        $search_type = $request->get('type');


        $start_time = $start_date ? strtotime($start_date) : strtotime(date('Y-m-d', time() - 86400 * 30));

        $end_time = $end_date ? strtotime($end_date) + 86400 : strtotime(date('Y-m-d', time() + 86400));

        $search = [];

        $search['start_date'] = $start_date ? $start_date : date('Y-m-d', $start_time);
        $search['end_date'] = $end_date ? $end_date : date('Y-m-d', $end_time - 86400);
        $search['type'] = $search_type ? $search_type : 0;
        $search['pro_id'] = $search_project_ids ? json_encode($search_project_ids) : json_encode([0]);
        $search['sup_id'] = $search_supervision_ids ? json_encode($search_supervision_ids) : json_encode([0]);
        $search['sec_id'] = $search_section_ids ? json_encode($search_section_ids) : json_encode([0]);

        //处理状态
        $type = [
              0=>'全部',
              1=>'标段已处理',
              2=>'标段未处理',
              3=>'监理已处理',
              4=>'监理未处理'
        ];

        $condition=[];

        //超级管理员或集团用户(铁路)项目公司用户(铁路)或项目管理处用户(外环)
        if($role==1 || $role==2 || $role==3){
             $project_ids=$this->user->project->toArray();
//             dd($project_ids);
             $condition['project_ids']=$project_ids;
        }

        //监理用户
        if($role==4){

            $supervision_id=$this->user->supervision->id;
            $condition['supervision_id']=$supervision_id;
        }

        //合同段用户
        if($role==5){

            $section_id=$this->user->section->id;
            $condition['section_id']=$section_id;
        }




        //构建多表查询
        $query=DB::table($detail_name)
                  ->select(DB::raw($select_raw))
                  ->where('is_warn',1)
                  ->Join($info_name,function ($join) use ($condition,$detail_name,$info_name){
                         //超级管理员，集团用户(铁路)，项目公司（铁路），项目管理处(外环)
                         if(isset($condition['project_ids'])){
                             $join->on($detail_name.'.'.'info_id','=',$info_name.'.'.'id')
                                  ->whereIn($info_name.'.'.'project_id',$condition['project_ids']);
                         }
//                         $join->on($detail_name.'.'.'info_id','=',$info_name.'.'.'id');
//
                         //监理用户
                         if(isset($condition['supervision_id'])){

                             $join->on($detail_name.'.'.'info_id','=',$info_name.'.'.'id')
                                  ->where($info_name.'.'.'supervision_id','=',$condition['supervision_id']);
                         }

                         //合同段用户
                         if(isset($condition['section_id'])){

                          $join->on($detail_name.'.'.'info_id','=',$info_name.'.'.'id')
                              ->where($info_name.'.'.'section_id','=',$condition['section_id']);
                         }

                  })
                  ->leftJoin('section',function($join) use($info_name){
                       $join->on('section.id','=',$info_name.'.'.'section_id');
                  })
                  ->leftJoin('supervision',function($join) use($info_name){
                       $join->on('supervision.id','=',$info_name.'.'.'supervision_id');
                  })
                  ->leftJoin('project',function($join) use ($info_name){
                      $join->on('project.id','=',$info_name.'.'.'project_id');

                  })
                  ->leftJoin('beam_site',function($join) use ($info_name){
                      $join->on('beam_site.id','=',$info_name.'.'.'beam_site_id');

                  })
                  ->leftJoin('device',function($join) use ($info_name){
                      $join->on('device.id','=',$info_name.'.'.'device_id');

                  });
        //时间搜索条件
        $query=$query->where($info_name.'.'.'time','>=',$start_time)
                     ->where($info_name.'.'.'time','<',$end_time);
        //项目公司搜索条件
        if ($search_project_ids && $search_project_ids[0] != 0) {
            $query = $query->whereIn($info_name . '.' . 'project_id',  $search_project_ids);
        }
        //监理搜索条件
        if ($search_supervision_ids && $search_supervision_ids[0] != 0) {
            $query = $query->whereIn($info_name . '.' . 'supervision_id', $search_supervision_ids);
        }
        //合同段搜索条件
        if ($search_section_ids && $search_section_ids[0] != 0) {
            $query = $query->whereIn($info_name . '.' . 'section_id', $search_section_ids);
        }

        switch ($search_type) {
            //标段已处理
            case 1:
                $query=$query->where($detail_name.'.'.'is_sec_deal','=',1);
            break;
            //标段未处理
            case 2:
                $query=$query->where($detail_name.'.'.'is_sec_deal','=',0);
            break;
            //监理已处理
            case 3:
                $query=$query->where($detail_name.'.'.'is_sup_deal','=',1);
            break;
            case 4:
                $query=$query->where($detail_name.'.'.'is_sup_deal','=',0);
            break;
            default :
                break;
        }

        $data=$query->orderBy($detail_name.'.'.'id','desc')
                    ->paginate($page)
                    ->toArray();

        //项目公司信息
        $project_data = $this->getProjectByUser();
        //监理信息
        $supervision_data = $this->getSupervisionByRole($role);
        //合同段信息
        $section_data = $this->getSectionByRole($role);
        $data['url']=$url;
        $data['project_data'] = $project_data;
        $data['supervision_data'] = $supervision_data;
        $data['section_data'] = $section_data;
        $data['search'] = $search;
        $data['type'] = $type;

        return $data;

    }

    /**
     * 根据用户所管理的项目公司信息或所属项目公司信息
     * @return mixed
     */
    protected function getProjectByUser()
    {
        $project_ids=$this->user->project->toArray();

        $project_data=Project::select('id','name')
                               ->whereIn('id',$project_ids)
                               ->get();
        return $project_data;
    }

    /**
     * 根据role获取用户所关联的监理或所属监理
     * @param $role
     * @return mixed
     */
    protected function getSupervisionByRole($role)
    {
        $model=new Supervision();
        //超级管理员，集团用户，
        if($role==1 || $role==2 || $role==3){
            $project_ids=$this->user->project->toArray();
            $query=$model->whereIn('project_id',$project_ids);
        }

        //监理用户
        if($role==4){
            $supervision_id=$this->user->supervision->id;
//            dd($supervision_id);
            $query=$model->where('id',$supervision_id);
        }

        //合同段用户
        if($role==5){
            $supervision_id=$this->user->supervision->id;
            $query=$model->where('id',$supervision_id);
        }

        $supervision_data=$query->get();

        return $supervision_data;
    }

    /**
     * 根据role获取所管理的标段信息
     * @param $role
     * @return mixed
     */
    protected function getSectionByRole($role)
    {
        $section_model = new Section();
        $supervision_model = new Supervision();

        //超级管理员或集团用户
        if ($role == 1 || $role == 2) {
            //获取所有到所有标段
            $section_query = $section_model->where([]);
        }

        //项目公司用户(铁路)或项目管理处用户(外环)
        if ($role == 3) {
            //如果是铁路，则获取到该项目公司的标段信息，如果是外环，则能获取到全部信息
            $project_id = $this->user->project[0];


            $section_query = $section_model->where('project_id', $project_id);
        }

        //监理用户
        if ($role == 4) {
            //获取监理所管理的合同段信息
            $supervision_id = $this->user->supervision->id;

            $section_ids = DB::table('supervision_section')->where('supervision_id', $supervision_id)
                                                           ->lists('section_id');

            $section_query = $section_model->whereIn('id', $section_ids);


        }
        //合同段用户
        if ($role == 5) {
            $section_id = $this->user->section->id;

            $section_query = $section_model->where('id', $section_id);
        }


        $secion_data = $section_query->select('id', 'name')
                                     ->get();

        return $secion_data;
    }

    /**
     * 报警数据页面选择框改变获取对应数据
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommonSelectVal($type){
        if($type == 'pro'){ //获取对应监理 标段 设备
            $pro_id = Input::get('pro_id');
            if(count($pro_id)==1 && $pro_id[0]==0){
                $data['sup_id'] = Supervision::select(['id', 'name'])->orderByRaw('project_id asc, id asc')->get()->toArray();
                $data['sec_id'] = Section::select(['id', 'name'])->orderByRaw('project_id asc, id asc')->get()->toArray();
                $query = Device::select(['id', 'name', 'model','dcode','cat_id']);
            }else{
                $data['sup_id'] = Supervision::select(['id', 'name'])->whereIn('project_id', $pro_id)->orderByRaw('id asc')->get()->toArray();
                $data['sec_id'] = Section::select(['id', 'name'])->whereIn('project_id', $pro_id)->orderByRaw('id asc')->get()->toArray();
                $query = Device::select(['id', 'name', 'model','dcode','cat_id'])->whereIn('project_id', $pro_id);
            }
        }
        if($type == 'sup'){ //获取对应标段 设备
            $sup_id = Input::get('sup_id');
            if(count($sup_id)==1 && $sup_id[0]==0){
                $info = Supervision::select(['id', 'name']);
                $query = Device::select(['id', 'name', 'model','dcode','cat_id']);
                if($this->user->role == 3){
                    $info = $info->where('project_id', $this->user->project[0]);
                    $query = $query->where('project_id', $this->user->project[0]);
                }
                $info = $info->with(['sec'=>function($query){
                    $query->select(['id','name']);
                }])
                    ->get()
                    ->toArray();
            }else{
                $info = Supervision::whereIn('id', $sup_id)
                    ->with(['sec'=>function($query){
                        $query->select(['id','name']);
                    }])
                    ->get()
                    ->toArray();
                $query = Device::select(['id', 'name', 'model','dcode','cat_id'])->whereIn('supervision_id', $sup_id);
            }
            foreach ($info as $key => $value) {
                foreach ($value['sec'] as $k => $v) {
                    $data['sec_id'][] = $v;
                }
            }
        }
        if($type == 'sec'){ //获取对应设备
            $sec_id = Input::get('sec_id');
            $query = Device::select(['id', 'name', 'model','dcode','cat_id']);
            if(count($sec_id)==1 && $sec_id[0]==0){
                if($this->user->role == 3){
                    $query = $query->where('project_id', $this->user->project[0]);
                }
                if($this->user->role == 4){
                    $query = $query->where('supervision_id', $this->user->supervision_id);
                }
                if($this->user->role == 5){
                    $query = $query->where('section_id', $this->user->section_id);
                }
            }else{
                $query = $query->whereIn('section_id', $sec_id);
            }
        }
        if(is_array($this->device_cat)){
            $query = $query->whereIn('cat_id', $this->device_cat);
        }else{
            $query = $query->where('cat_id', $this->device_cat);
        }
        $data['dev_id'] = $query->with('category')->orderByRaw('id asc')->get()->toArray();
        $result = ['status'=>1, 'info'=>'获取成功', 'data'=>$data];
        return Response()->json($result);
    }

    /**
     * 获取报警信息中详细数据
     * @param $detail_model
     * @param $detail_id
     */
    protected function getDetailInWarn($detail_model,$detail_id)
    {
        $detail = $detail_model->where('id',$detail_id)
                               ->with('info')
                               ->first();

        return $detail;

    }

    /**
     * 报警信息处理
     * @param $view
     * @param $request
     * @param $detail_model
     */
    protected function doDeal($view,$request,$detail_model,$role,$detail_id,$time_field)
    {
        if ($request->isMethod('get')) {

           $detail=$detail_model->with('info')
                                ->find($detail_id);
//           dd($detail);
           $res=$this->isDeal($role,$detail,$time_field);

           if($res['status']==1){
               $info=$res['info'];
               return view('admin.error.iframe_no_info',compact('info'));
           }

            return view($view,compact('detail'));

        }

        if ($request->isMethod('post')) {

            $input_data=$request->all();
            $data = [];

            if($input_data['deal_info'] == ''){
                return ['status'=>0,'mess'=>'请填写处理意见'];
            }
//            dd($input_data);
            $data['device_id']=$input_data['device_id'];
            $data['info_id']=$input_data['info_id'];
            $data['detail_id']=$detail_id;
            $data['module_name']=$this->module_name;
            //合同段处理
            if ($role == 5) {
               $data['section_deal_time']=time();
               $data['section_deal_info']=$input_data['deal_info'];
               $data['section_user_id']=$this->user->id;
               $data['section_user_name']=$this->user->name;
               $data['section_img']=isset($input_data['thumb']) ? $input_data['thumb'] : '';
               $data['section_file']=isset($input_data['file']) ? $input_data['file'] : '';

               try{

                   \DB::beginTransaction();
                   StretchMudjackWarnDealInfo::create($data);
                   $detail=$detail_model->find($detail_id);
                   $detail->is_sec_deal=1;
                   $detail->save();
                   \DB::commit();

                   return ['status'=>0,'mess'=>'报警处理成功'];
               }catch (Exception $e){
                   \DB::rollBack();

                   return ['status'=>1,'mess'=>'报警处理失败'];
               }
            }

            //监理处理
            if($role == 4){

                try{


                    $deal=StretchMudjackWarnDealInfo::where('module_name',$this->module_name)
                                                     ->where('device_id',$input_data['device_id'])
                                                     ->where('info_id',$input_data['info_id'])
                                                     ->where('detail_id',$detail_id)
                                                     ->first();
                    if(!$deal){
                        return ['status'=>1,'mess'=>'合同段用户处理之后才能处理'];
                    }

                    \DB::beginTransaction();

                    $deal->supervision_deal_time=time();
                    $deal->supervision_user_id=$this->user->id;
                    $deal->supervision_user_name=$this->user->name;
                    $deal->supervision_deal_info=$input_data['deal_info'];
                    $deal->supervision_img=isset($input_data['thumb']) ? $input_data['thumb'] : '';
                    $deal->supervision_file=isset($input_data['file']) ? $input_data['file'] : '';
                    $deal->save();

                    $detail=$detail_model->find($detail_id);
                    $detail->is_sup_deal=1;
                    $detail->save();
                    \DB::commit();

                    return ['status'=>0,'mess'=>'报警处理成功'];

                }catch (Exception $e){

                    \DB::rollBack();

                    return ['status'=>1,'mess'=>'报警处理失败'];
                }

            }

        }
    }

    /**
     *判断用户是否有权限处理，及是否已经超过报警处理时间
     * 1，通过角色进行判断，看是否到了这一层级用户处理
     * 2，通知职位判断，该用户是否有处理权限(该需求暂留)
     * 3，判断是否超过了最长报警处理时间
     */
    protected function isDeal($role,$detail_data,$time_field)
    {
        $result['status']=0;
        $result['info']='放行';

        //目前是合同段和监理用户能够处理
        if ($role == 1 || $role == 3) {
            $result['status'] = 1;
            $result['info'] = '您没有权限处理报警';
        }

        //监理用户在合同段用户没有处理之前不能进行报警处理
        if ($role == 4 && $detail_data->is_sec_deal == 0) {
            $result['status']=1;
            $result['info']='合同段用户还未处理该报警，您暂时还不能处理';
        }

        //报警处理时限在3天内，超过3天就不能再处理
        if (time() > $detail_data->$time_field + 86400 * 3) {
            $result['status']=1;
            $result['info']='该报警信息超过3天，不能进行处理';
        }

        return $result;
    }


    /**
     * 获取张拉，压浆模块报警推送人员信息
     * 该功能只有具有审核权限的用户才能查看及添加删除人员信息
     */
    protected function doWarnSet($warnUserModel, $view, $request)
    {
        $role = $this->user->role;

        $hasSh = $this->user->has_sh;

        if ($role != 1 && $hasSh == 0) {
            $info = '您没有该权限功能权限，只有有审核权限用户才可操作';
            return view('admin.error.no_info', compact('info'));
        }
        $section_model=new Section();
         //根据角色获取所属合同段或所管理合同段（除去试验检测中心合同段）
        if($role==1 || $role==3){
            $section_model=$section_model->where([]);
        }

        if($role==4){
            $supervision_id=$this->user->supervision->id;

            $section_ids=DB::table('supervision_section')->where('supervision_id',$supervision_id)
                                                     ->lists('section_id');
            $section_model=$section_model->whereIn('id',$section_ids);
        }

        if($role==5){
            $section_id=$this->user->section->id;
            $section_model=$section_model->where('id',$section_id);
        }

        $section_data = $section_model->select('id','name')
                                      ->whereNotIn('id',[18])
                                      ->orderBy('name','asc')
                                      ->get();
//        dd($section_data);

        $search=[];
        $search_sec_id=$request->get('sec_id');

        if($search_sec_id){
            $warnUserModel=$warnUserModel->where('section_id',$search_sec_id);
            $search['search_sec_id']=$search_sec_id;
        }else{
            $warnUserModel=$warnUserModel->where('section_id',$section_data[0]['id']);
            $search['search_sec_id']=$section_data[0]['id'];
        }
//        DB::connection()->enableQueryLog();
        $warn_users=$warnUserModel->with(['user'=>function($query){
                                       $query->with('posi','roled','section');
                                  }])
                                  ->where('module_name',$this->module_name)
                                  ->get();

        return view($view,compact('section_data','warn_users','search'));

    }


    /**
     * 添加报警人员信息
     */
    protected function doWarnUserAdd($request,$view,$user_model,$stretchMudjackWarnUser,$user_field)
    {
        if ($request->isMethod('get')) {

            //判断用户是否有审核权限
            $hasSh = $this->user->has_sh;

            $role = $this->user->role;

            if ($role != 1 && $hasSh != 1) {
                $info = '您无该功能操作权限，只有具有审核权限用户才可操作';
                return view('admin.error.iframe_no_info', compact('info'));
            }

            $section_id = $request->get('sec_id');

            $user_data = $this->getUser($user_model, $section_id, $stretchMudjackWarnUser,$user_field);

            return view($view,compact('user_data','section_id'));

        }

        if ($request->isMethod('post')) {

                $input_data=$request->all();
//                dd($input_data);
            if(!$input_data['user_id']) {
                return ['status'=>1,'mess'=>'请选择要添加的用户'];
            }

            $supervision_id=DB::table('supervision_section')->where('section_id',$input_data['section_id'])
                                                            ->value('supervision_id');

            $project_id=DB::table('section')->where('id',$input_data['section_id'])
                                                    ->value('project_id');

            $input_data['supervision_id'] = $supervision_id;
            $input_data['project_id'] = $project_id;
            $input_data['module_name'] = $this->module_name;

            try{

               $stretchMudjackWarnUser->create($input_data);
               return ['status'=>0,'mess'=>'添加成功'];
            }catch (Exception $e){
               return ['status'=>1,'mess'=>'添加失败'];
            }
        }

    }

    /**
     * 获取除了已经添加过的报警通知人员外的可添加人员
     * @param $user_model
     * @param $section_id
     * @param $stretchMudjackWarnUser
     * @param $user_field
     * @return mixed
     */
    protected function getUser($user_model,$section_id,$stretchMudjackWarnUser,$user_field)
    {
        $alreadyUserIds=$stretchMudjackWarnUser->select('user_id')
                                                ->where('module_name',$this->module_name)
                                                ->where('section_id',$section_id)
                                                ->lists('user_id');

        $supervision_id=DB::table('supervision_section')->select('supervision_id')
                                                        ->where('section_id',$section_id)
                                                        ->value('supervision_id');

        $role=$this->user->role;

        /*如果是超级管理员，或项目管理处用户
         *就可获取到项目管理处，监理，合同段三类角色用户
         * 如果是监理，就可获取到，监理用户
         * 如果是合同段，就可获取到合同段用户
        */
        if ($role == 1 || $role == 3) {
            $user_model = $user_model->where('role',3)
                                     ->orWhere(function($query) use($supervision_id){
                                     $query->where('role',4)
                                     ->where('supervision_id',$supervision_id);
                                       })
                                      ->orWhere(function($query) use($section_id){
                                       $query->where('role',5)
                                         ->where('section_id',$section_id);
                });
        }

        if ($role == 4) {
            $user_model = $user_model->where('role',4)
                                     ->where('supervision_id',$supervision_id);
        }

        if ($role == 5) {
            $user_model = $user_model->where('role',5)
                                     ->where('section_id',$section_id);
        }

        DB::enableQueryLog();
        $userData=$user_model->select($user_field)
                             ->with('roled','posi','department')
                             ->whereNotIn('id',$alreadyUserIds)
                             ->get();
//        dd(DB::getQueryLog());

        return $userData;
    }

    /**
     * 获取报警信息统计数据
     */
    protected function getStatWarn($request,$module_name,$model,$role,$url)
    {
        $section_id = $request->get('section_id');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $search=[];

        $start_time = $start_date ? strtotime($start_date) : strtotime(date('Y-m-d', time() - 86400 * 7));
        $end_time = $end_date ? strtotime($end_date) + 86400 : strtotime(date('Y-m-d', time() + 86400));

        $search['start_date'] = $start_date ? $start_date : date('Y-m-d',$start_time);
        $search['end_date'] = $end_date ? $end_date : date('Y-m-d',$end_time-86400);

        $url = $start_date ? $url.'?start_date='.$start_date : $url.'?start_date='.date('Y-m-d',$start_time);
        $url = $end_date ? $url.'&end_date='.$end_date : $url.'&end_date='.date('Y-m-d',$end_time-86400);

        $query = $this->getModelByRole($role,$model);

        if ($section_id) {
            $query = $query->where('section_id', $section_id);
            $search['section_id'] = $section_id;
            $url = $url . '&section_id=' . $section_id;
        } else {
            $search['section_id'] = 0;
        }

        $data=$query->where('module_name',$module_name)
                    ->where('created_at','>=',$start_time)
                    ->where('created_at','<',$end_time)
                    ->with('supervision','section','device','beam_site')
                    ->orderBy('created_at','desc')
                    ->paginate($this->ispage)
                    ->toArray();

        $data['search'] = $search;
        $data['url'] = $url;

        return $data;
    }


}
