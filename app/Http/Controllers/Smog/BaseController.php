<?php

namespace App\Http\Controllers\Smog;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\IndexController;
use App\Models\Project\Section;
use DB;

/**
 * 治污减霾模块父类（环境监测类和污水处理类继承此类）
 */
class BaseController extends IndexController
{

    /**
     * 获取当前最新的监测数据
     */
    protected function getNowEnvironmentData($model)
    {
        $role=$this->user->role;
        $section_model=new Section();

        /*超级管理员或项目管理处用户*/
        if($role==1 || $role==3){
            //获取除试验检测中心外的所有合同段
            $section_model=$section_model->whereNotIn('id',[18]);
        }

        /*监理用户*/
        if($role==4){
            //获取该监理单位所监管的合同段
            $supervision_id=$this->user->supervision->id;
            $section=DB::table('supervision_section')->select('section_id')
                                                     ->where('supervision_id',$supervision_id)
                                                     ->lists('section_id');
//            dd($section);

//            $section_id=$section->section_id;

            $section_model=$section_model->whereIn('id',$section);
        }

        /*合同段用户*/
        if($role==5){
            $section_id=$this->user->section->id;
            $section_model=$section_model->where('id',$section_id);
        }


        //获取所管理的合同段id集合或所属合同段id
        $section_ids=$section_model->select('id')
                                   ->get()
                                   ->toArray();

        $now_environment_data=[];

        foreach($section_ids as $v){

             $now_section_id=$v['id'];
             $now_section_data=$model->where('section_id',$now_section_id)
                                     ->with('section')
                                     ->orderBy('id','desc')
                                     ->first();

             if($now_section_data){
                 array_push($now_environment_data,$now_section_data);
             }

        }

        return $now_environment_data;




    }


    /**
     * 获取监测历史数据
     * @param $request
     * @param $model
     * @param $field
     * @param $page
     * @param $url
     */
    protected function getEnvironmentHistoryData($request,$field,$model,$page,$url)
    {

        $section_id=$request->get('section_id');
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');
//        dd($model);
        $search=[];

        $start_time=$start_date ? strtotime($start_date) :time()-86400*3;
        $end_time=$end_date ? strtotime($end_date)+86400 : time()+86400;

         $search['start_date']=date('Y-m-d',$start_time);
         $search['end_date']=date('Y-m-d',$end_time-86400);

         $url=$url.'?start_date='.date('Y-m-d',$start_time).'&end_date='.date('Y-m-d',$end_time-86400);

         $role=$this->user->role;
//         dd($model);
         $model=$this->getModelByRole($role,$model);
//         dd($model);
         $model=$model->where('time','>=',$start_time)
                      ->where('time','<',$end_time);

         if($section_id!=0){
             $model=$model->where('section_id',$section_id);
             $search['section_id']=$section_id;
             $url=$url.'&section_id='.$section_id;
         }else{
             $model=$model;
             $search['section_id']=0;
             $url=$url.'&section_id=0';
         }

         if($request->get('page')){
             $search['page']=$request->get('page');
         }else{
             $search['page']=1;
         }

         if($request->get('d')){

             $search['d']=$request->get('d');

            if($request->get('d') == 'all'){
                //查询时间间隔限定
                if($end_time-$start_time > 86400){
                    $history_data['data']='';
                    $history_data['hint']='因数据量过大,为了避免导出文件时导致电脑死机，系统只允许导出时间间隔在1天内的全部数据，请您调整查询开始结束时间';
                }else{
                    $history_data['data']=$model->select($field)
                        ->orderBy('id','desc')
                        ->with('section','device')
                        ->get()
                        ->toArray();
                }

            }else{

                $history_data=$model->select($field)
                    ->orderBy('id','desc')
                    ->with('section','device')
                    ->paginate($page)
                    ->toArray();
             }

         }else{
             $history_data=$model->select($field)
                 ->orderBy('id','desc')
                 ->with('section','device')
                 ->paginate($page)
                 ->toArray();
         }

         $history_data['url']=$url;
         $history_data['search']=$search;
//          dd($history_data);
         return $history_data;
    }


    /**
     * 获取合同段信息
     * @param $model
     * @param $field
     * @return mixed
     */
    protected function getSection($model,$field)
    {
         $role=$this->user->role;
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
            $section=DB::table('supervision_section')->select('section_id')
                ->where('supervision_id',$supervision_id)
                ->lists('section_id');
//            dd($section);
//            $section_id=$section->section_id;
            $model=$model->whereIn('id',$section);
        }

        //合同段用户
        if($role==5){
            $section_id=$this->user->section->id;
            $model=$model->where('id',$section_id);
        }


         $section_data=$model->select($field)
                             ->where('id','!=',18)
                             ->orderBy('id','asc')
                             ->get()
                             ->toArray();

         return $section_data;
    }

    /**
     * 获取设备信息
     */
    protected function getDevice($role,$model,$device_cat,$field)
    {

       $query=$this->getModelByRole($role,$model);


        $device_data=$query->select($field)
                           ->where('cat_id',$device_cat)
                           ->with('sup','section')
                           ->get();
        return $device_data;
    }


    /**
     * 获取设备对应的数据
     */
    protected function getDataByDevice($request,$data_model,$device_id,$page,$url,$data_field,$time_field)
    {
          $start_date=$request->get('start_date');
          $end_date=$request->get('end_date');

          $search=[];

          $start_time=$start_date ? strtotime($start_date) : time()-86400*7;
          $end_time=$end_date ? strtotime($end_date)+86400 : time()+86400;

          $url=$url.'?start_date='.date('Y-m-d',$start_time);
          $url=$url.'&end_date='.date('Y-m-d',$end_time-86400);

          $search['start_date']=date('Y-m-d',$start_time);
          $search['end_date']=date('Y-m-d',$end_time-86400);

          $query=$data_model->whereBetween($time_field,[$start_time,$end_time]);



          $data=$query->select($data_field)
                      ->where('device_id',$device_id)
                      ->orderBy('id','desc')
                      ->paginate($page)
                      ->toArray();

          $data['url']=$url;
          $data['search']=$search;

          return $data;

    }

    /**
     * 根据role给model加where条件
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
//            $section=DB::table('supervision_section')->select('section_id')
//                                                     ->where('supervision_id',$supervision_id)
//                                                     ->first();
//            $section_id=$section->section_id;
            $model=$model->where('supervision_id',$supervision_id);
        }

        //合同段用户
        if($role==5){
            $section_id=$this->user->section->id;
            $model=$model->where('section_id',$section_id);
        }

        return $model;

    }




}
