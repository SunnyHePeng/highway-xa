<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Section,
    App\Models\Project\Supervision,
    App\Models\Project\BeamSite,
    App\Models\Project\Project;
use Input,DB;
use Mockery\Exception;
use Qiniu\Http\Response;

class BeamSiteController extends BaseController
{
    protected $url = '';
    protected $act_info = '梁场';
    protected $is_reload = 1;
    protected $project_model;
    protected $supervision_model;

    public function __construct(Project $project)
    {
        parent::__construct();

//        $this->request = new Request;
        $this->model = new Section();
//        $this->url = url('manage/section');
        $this->project_model=$project;
//        $this->supervision_model=$supervision;

    }

    public function beamSite(Request $request,BeamSite $beamSite)
    {
        //获取所有的项目公司信息
        $project_data=$this->getProject();

        $field=['id',
            'project_id',
            'section_id',
            'supervision_id',
            'name',
            'area',
            'pedestal_number',
            'device_number',
            'device_type',
            'fzr',
            'phone',
            'beam_type',
            'beam_number'
        ];

        //获取梁场信息
        $beam_site_data=$this->getBeamSite($request,$beamSite,$field);
        //用户的增删改权限
        $user_act=$this->user_is_act;
        $role=$this->user->role;
        if($role==1 || $role==2 || $role==3){
            $view='admin.project.beam_site_project';
        }else{
            $view='admin.project.beam_site';
        }

        return view('admin.project.beam_site',compact('project_data','beam_site_data','user_act'));
    }

    /**
     * 梁场信息修改
     */
    public function beamSiteEdit(Request $request,BeamSite $beamSite,Supervision $supervision,Section $section)
    {

        if($request->isMethod('get')){

            $id=$request->get('id');
            $beam=$beamSite->find($id);
            $project_id=$beam->project_id;
            $supervision_data=$supervision->select(['id','name'])
                ->where('project_id',$project_id)
                ->get()
                ->toArray();
            $section_data=$section->select(['id','name'])
                ->where('project_id',$project_id)
                ->get()
                ->toArray();
            $project_data=$this->getProject();

            return view('admin.project.beam_site_edit',compact('project_data','supervision_data','section_data','beam'));
        }

        if($request->isMethod('post')){
            $input_data=$request->all();
            if($input_data['project_id']==0){
                $result['status']=0;
                $result['info']='请选择项目公司';
                return Response()->json($result);
            }

            if($input_data['supervision_id']==0){
                $result['status']=0;
                $result['info']='请选择监理';
                return Responese()->json($result);
            }

            if($input_data['section_id']==0){
                $result['status']=0;
                $result['info']='请选择合同段';
                return Response()->json($result);
            }

            if($input_data['name']==''){
                $result['status']=0;
                $result['info']='请输入梁场名称';
                return Response()->json($result);
            }

            $id=$input_data['id'];
            unset($input_data['id']);
            unset($input_data['_token']);

            try{
                $beamSite->where('id',$id)
                    ->update($input_data);
                $result['status']=1;
                $result['info']='修改成功';
                return Response()->json($result);

            }catch (\Exception $e){

                $result['status']=0;
                $result['info']='修改失败';
                return Response()->json($result);
            }
        }

    }
    //添加梁场
    public function add(Request $request,BeamSite $beamSite)
    {
        if($request->isMethod('post')){
            $input_data=$request->all();
            if($input_data['project_id']==0){
                $result['status']=0;
                $result['info']='请选择项目公司';
                return Response()->json($result);
            }

            if($input_data['supervision_id']==0){
                $result['status']=0;
                $result['info']='请选择监理';
                return Responese()->json($result);
            }

            if($input_data['section_id']==0){
                $result['status']=0;
                $result['info']='请选择合同段';
                return Response()->json($result);
            }

            if($input_data['name']==''){
                $result['status']=0;
                $result['info']='请输入梁场名称';
                return Response()->json($result);
            }

            try{
                $beamSite::create($input_data);
                $result['status']=1;
                $result['info']='添加成功';
                return Response()->json($result);

            }catch (\Exception $e){

                $result['status']=0;
                $result['info']='添加失败';
                return Response()->json($result);
            }

        }




    }

    /**删除梁场信息
     * @param Request $request
     * @param BeamSite $beamSite
     */
    public function beamSiteDel(BeamSite $beamSite,$id)
    {
        try{
            $beam=$beamSite::find($id);
            $beam->delete();
            $result['status']=1;
            $result['info']='删除成功';
            $result['id']=$id;
            return Response()->json($result);

        }catch (\Exception $e){
            $result['status']=0;
            $result['info']='删除失败';
            return Response()->json($result);

        }

    }

    //获取该用户所属项目公司，超级管理员或集团用户获取多个项目公司
    protected function getProject()
    {
        $role=$this->user->role;
        $query=$this->project_model;
        //超级管理员或集团用户
        if($role==1||$role==2){
            //获取所管理的项目公司id
            $project=$this->user->project->toArray();

            $query=$query->whereIn('id',$project);
        }else{
            //非超级管理员或项目管理处用户获取所属项目公司id
            $project=$this->user->project['0'];

            $query=$query->where('id',$project);
        }


        $project_data=$query->select(['id','name'])
            ->orderBy('id','asc')
            ->get()
            ->toArray();
        return $project_data;
    }

    //通过项目公司获取监理信息
    public function getSupervisionByProject(Request $request,Supervision $supervision)
    {

        $project_id= $request->get('pro_id');
        $role=$this->user->role;
        $query=$supervision;

        if(!$project_id){
            $result['status']=1;
            $result['mess']='监理信息获取出错';
            return $result;
        }

        //超级管理员，集团用户，项目公司用户，获取该projet_id中所有的监理
        if($role==1 ||$role==2 || $role==3){
            $query=$query->where([]);
        }else{
            //监理或者合同段用户
            $supervision_id=$this->user->supervision['id'];
            $query=$query->where('id',$supervision_id);
        }
        //查询项目公司对应的监理信息
        $sup_data=$query->select(['id','name'])
            ->where('project_id',$project_id)
            ->get()
            ->toArray();

        if(count($sup_data)==0){
            $result['status']=1;
            $result['mess']='获取该项目公司对应的监理信息失败，请联系管理员添加';
            return $result;
        }
        $result['status']=0;
        $result['mess']=$sup_data;
        return Response()->json($result);

    }

    /**
     * 根据监理id获取对应的合同段
     */
    public function getSectionBySup(Request $request,Section $section)
    {

        $supervision_id=$request->get('sup_id');

        $section_id=DB::table('supervision_section')->select(['section_id'])
            ->where('supervision_id',$supervision_id)
            ->first()->section_id;
        $query=$section;

        //获取合同段信息
        $section_data=$query->select(['id','name'])
            ->where('id',$section_id)
            ->first();
        $result['status']=0;
        $result['mess']=$section_data;
        return Response()->json($result);
    }

    /**
     * 获取梁场信息
     */
    protected function getBeamSite($request,$beamSite,$field)
    {
        $query=$beamSite;
        $role=$this->user->role;

        $project_id=$request->get('pro_id');
        $supervision_id=$request->get('sup_id');
        $section_id=$request->get('sec_id');

        if($project_id){
            $query=$query->where('project_id',$project_id);
        }

        if($supervision_id){
            $query=$query->where('supervision_id',$supervision_id);
        }

        if($section_id){
            $query=$query->where('section_id',$section_id);
        }


        //超级管理员或集团用户
        if($role==1 || $role==2){
            //获取所管理的项目公司
            $projects=$this->user->project->toArray();
            $query=$query->whereIn('project_id',$projects);
        }
        //项目公司用户
        if($role==3){
            $project=$this->user->project[0];

            $query=$query->where('project_id',$project);
        }
        //监理用户
        if($role==4){
            //获取对应的监理id
            $supervision=$this->user->supervision->id;

            $query=$query->where('supervision_id',$supervision);
        }

        //合同段用户
        if($role==5){
            //获取所对应的合同段id
            $section=$this->user->section->id;

            $query=$query->where('section_id',$section);
        }

        $beam_site_data=$query->select($field)
            ->with('project','supervision','section')
            ->get()
            ->toArray();
//        dd($beam_site_data);
        return $beam_site_data;



    }




}