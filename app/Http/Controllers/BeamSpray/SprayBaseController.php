<?php

namespace App\Http\Controllers\BeamSpray;

use App\Http\Controllers\IndexController;
use Illuminate\Http\Request;


/**
 * 喷淋
 */
class SprayBaseController extends IndexController
{

    /**
     * 获取设备信息
     */
    protected function getDeviceList($request,$role,$device_cat,$page,$model,$field,$url)
    {


        //根据角色来限定获取数据的范围
        $query=$this->getModelByRole($role,$model);
//        dd();

//        根据项目树中的参数对获取的数据进行筛选

        $queryAndUrl=$this->getQueryAndUrlByTreeParame($request,$query,$url);

        $url=$queryAndUrl['url'];

        $query=$queryAndUrl['query'];

        $query=$query->where('cat_id',$device_cat);

        $device_data=$query->select($field)
            ->with('project','sup','section','beam_site')
            ->orderBy('project_id','asc')
            ->paginate($page)
            ->toArray();
        $device_data['url']=$url;

        return $device_data;


    }

    /**
     * 获取预制梁信息
     */
    protected function getBeamData($request,$role,$model,$field,$url,$page,$type)
    {

        $pro_id = $request->get('pro_id');
        $sup_id = $request->get('sup_id');
        $sec_id = $request->get('sec_id');
        $finish = $request->get('finish');
        $beam_num = $request->get('beam_num');

        $search = [];

        $search['pro_id'] = $pro_id ? $pro_id : 0;
        $search['sup_id'] = $sup_id ? $sup_id : 0;
        $search['sec_id'] = $sec_id ? $sec_id : 0;
        $search['finish'] = $finish ? $finish : 0;
        $search['beam_num'] = $beam_num ? $beam_num : '';



        //根据角色来限定获取数据的范围
        $query=$this->getModelByRole($role,$model);
//        根据项目树中的参数对获取的数据进行筛选
        $queryAndUrl=$this->getQueryAndUrlByTreeParame($request,$query,$url);

        $query=$queryAndUrl['query'];

        $url=$queryAndUrl['url'];

        if($finish){
            if($finish == 1){
                $query=$query->where('is_finish',0);
            }

            if($finish == 2){
                $query=$query->where('is_finish',1);
            }
        }

        if($beam_num){
            $query=$query->where('beam_num','like','%'.$beam_num.'%');
        }

        $data=$query->select($field)
            ->where('type',$type)
            ->with('project','sup','section','beam_site')
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
     * 根据项目树中的参数对获取的数据进行筛选
     */
    protected function getQueryAndUrlByTreeParame($request,$query,$url)
    {
        $pro_id=$request->get('pro_id');
        $sup_id=$request->get('sup_id');
        $sec_id=$request->get('sec_id');

        if($pro_id){
            $query=$query->where('project_id',$pro_id);
            $url=$url.'?pro_id='.$pro_id;
        }
        if($sup_id){
            $query=$query->where('supervision_id',$sup_id);
            $url=$url.'?sup_id='.$sup_id;
        }
        if($sec_id){
            $query=$query->where('section_id',$sec_id);
            $url=$url.'?sec_id='.$sec_id;
        }
//        dd($query);

        $data['query']=$query;
        $data['url']=$url;

        return $data;
    }
}
