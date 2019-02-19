<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Device_category,
	App\Models\Device\Device,
	App\Models\Project\Project,
    App\Models\Project\Supervision,
    App\Models\Project\BeamSite,
	App\Models\Project\Section;
use Input, DB;

class DeviceController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.device.device';
    protected $url = '';
    protected $act_info = '设备';
    protected $act_name = 'model';
    protected $para = [  //M³
                '1'=>['最大试验力','试验力测量范围','液压泵额定压力','加荷速率'],
                '2'=>['最大载荷','准确度等级','液压泵额定压力','加荷速率'],
                '3'=>['最大试验力','分辨率','液压泵额定压力','加荷速率'],
                '4'=>['出料容量','进料容量','生产率','整机功率'],
                '5'=>['最大输出扭矩(KN.m)','最大钻孔直径(M)','最大钻孔深度(M)'],
                '6'=>['工作质量(KG)','振动频率(HZ)','激振力(KN)','振动轮宽度(mm)'],
                '7'=>['角度测量精度','距离测量范围(棱镜)','测距精度(棱镜)','距离测量范围(无棱镜)','测距精度(无棱镜)'],
                '8'=>['张拉力精度(MPa)','伸长量精度(mm)','额定压力(MPa)','额定功率(KW)'],
                '9'=>['压力测量精度(MPa)','浆液输出流量(M3/h)','安全保护压力(MPa)','总功率(KW)'],
                '10'=>['增压泵额定功率(KW)','增压泵扬程(M)','最大养生预制梁数量(个)','最长养生时间(D)'],
                '11'=>['温度控制范围(度)','湿度控制范围(M)','系统额定压力(MPa)','蒸汽产量(KG)'],
                '12'=>['允许载重(T)','行驶速度(米/分钟)','轴线(轴)','轮胎数量(个)'],
                '13'=>['额定起重量(T)','桥机跨度(米)','起升高度(轴)','过孔速度(米/分)','起升速度(米/分)','小车纵移速度(米/分)'],
                '14'=>['额定起重量(T)','桥机跨度(米)','起升高度(轴)','起升速度(米/分)','最大空载速度(KM/H)','最大重载速度(KM/H)'],
                '15'=>['测量范围(M)','最小读数(mm)'],
                '16' => ['测量范围'],
                '17'=>['测量范围'],
                '18'=>['最小读数'],
                ];

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Device;
        $this->field = [
						'id',
                        'project_id',
                        'supervision_id',
                        'section_id',
                        'cat_id',
                        'name',
                        'dcode',
                        'model',
                        'parame1',
                        'parame2',
                        'parame3',
                        'parame4',
                        'parame5',
                        'parame6',
                        'factory_name',
                        'factory_date',
                        'fzr',
                        'phone',
                        'camera1',
                        'camera2',
                        'camera1_encoderuuid',
                        'camera2_encoderuuid'
					];
        $this->url = url('manage/device');
    }

    /*public function index(){
        return view('admin.error.no_info', ['info'=>'参数错误']);
    }*/

    public function kzyDev(){
        $this->url = url('manage/kzy_dev');
        return $this->getIndex(1);
    }

    public function ylDev(){
        $this->url = url('manage/yl_dev');
        return $this->getIndex(2);
    }

    public function wnDev(){
        $this->url = url('manage/wn_dev');
        return $this->getIndex(3);
    }

    public function bhDev(){
        $this->url = url('manage/bh_dev');
        return $this->getIndex(4);
    }

    public function zjDev(){
        $this->url = url('manage/zj_dev');
        return $this->getIndex(5);
    }

    public function yljDev(){
        $this->url = url('manage/ylj_dev');
        return $this->getIndex(6);
    }

    public function qzyDev(){
        $this->url = url('manage/qzy_dev');
        return $this->getIndex(7);
    }

    public function zlDev(){
        $this->url = url('manage/zl_dev');
        return $this->getIndex(8);
    }

    public function yjDev(){
        $this->url = url('manage/yj_dev');
        return $this->getIndex(9);
    }

    public function znplDev(){
        $this->url = url('manage/znpl_dev');
        return $this->getIndex(10);
    }
    public function znysDev(){
        $this->url = url('manage/znys_dev');
        return $this->getIndex(11);    
    }

    public function ylcDev(){
        $this->url = url('manage/ylc_dev');
        return $this->getIndex(12);    
    }

    public function jqjDev(){
        $this->url = url('manage/jqj_dev');
        return $this->getIndex(13);    
    }

    public function yjytDev(){
        $this->url = url('manage/yjyt_dev');
        return $this->getIndex(14);    
    }

    public function szyDev(){
        $this->url = url('manage/szy_dev');
        return $this->getIndex(15);    
    }

    /**
     * 环境监测设备
     * 
     * @return \Illuminate\Http\Response
     */
    public function environmentDev(){
        $this->url = url('manage/environment_dev');
        return $this->getIndex(16);    
    }

    /**
     * 筛分试验设备
     */
    public function sieveDev()
    {
        $this->url = url('manage/sieve_dev');
        return $this->getIndex(17);
    }

    /**
     * 污水处理设备
     */
    public function wastewaterTreatmentDev()
    {
        $this->url = url('manage/sieve_dev');
        return $this->getIndex(18);
    }

    public function getIndex($cat_id){
        if(!$cat_id){
            return view('admin.error.no_info', ['info'=>'参数错误']);
        }
        
        $res = $this->judgePermission();
        if($res !== true){
            return $res;
        }
        $data['project'] = $this->getProjectList();

        //设备搜索
        $device_query = Device::select($this->field);
        //获取项目，监理，标段
        //筛选项目信息
        if($this->user->role != 1 && $this->user->role != 2){
            $device_query = $device_query->where('project_id', $this->user->project[0]);
        }
        //筛选监理
        if($this->user->role == 4 || $this->user->role == 5){
            $device_query = $device_query->where('supervision_id', $this->user->supervision_id);
        }
        //筛选标段
        if($this->user->role == 5){
            $device_query = $device_query->where('section_id', $this->user->section_id);
        }
        
        $data['ztree_data'] = json_encode($this->getTreeAllData());
        $data['ztree_url'] = $this->url;

        //设备列表
        $device_query = $device_query->where('cat_id', $cat_id);
        $data['url'] = $this->url.'?cat='.$cat_id;

        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $pro_id = Input::get('pro_id');
        if(!$sup_id && !$sec_id){
            $pro_id = $pro_id ? $pro_id : $data['project'][0]['id'];
        }
        if($pro_id){
            $device_query = $device_query->where('project_id', $pro_id);
            $data['ztree_name'] = Project::where('id', $pro_id)->pluck('name');
            $data['url'] .= '&pro_id='.$pro_id;
        }
        
        if($sup_id){
            $device_query = $device_query->where('supervision_id', $sup_id);
            $data['ztree_name'] = Supervision::where('id', $sup_id)->pluck('name');
            $data['url'] .= '&sup_id='.$sup_id;
        }

        if($sec_id){
            $device_query = $device_query->where('section_id', $sec_id);
            $data['ztree_name'] = Section::where('id', $sec_id)->pluck('name');
            $data['url'] .= '&sec_id='.$sec_id;
        }
        $list = $device_query->with(['project'=>function($query){
                                $query->select(['id','name']);
                            },'sup'=>function($query){
                                $query->select(['id','name']);
                            },'section'=>function($query){
                                $query->select(['id','name']);
                            }])
                            ->orderByRaw($this->order)
                            ->paginate($this->ispage)
                            ->toArray();
        
        $data['cat_id'] = $cat_id;
        $data['para'] = $this->para[$cat_id];
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        if(!isset($data['ztree_name']) || !$data['ztree_name']){
            $data['ztree_name'] = $data['project'][0]['name'];
        }
        return view($this->list_view, array_merge($list, $data));
    }


    public function store()
    {
        $data = $this->model->checkData();

        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }
        //生成设备编码
        //$data['dcode'] = $this->makeCode($data);
        //检查设备编码是否重复
        if($this->model->where('dcode', $data['dcode'])->first()){
            $result = ['status'=>0,'info'=>'设备编码已存在'];
            return Response()->json($result);
        }
        $data['created_at'] = time();
        //$this->model->create($data);
        try {
            $this->model->create($data);
            $result = ['status'=>1,'info'=>'添加成功'];
            
            $this->log($this->user->username.'添加新', isset($this->act_name) ? $data[$this->act_name] : $data['name'], 'a');
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'添加失败,请检查是否重复'];
        }

        return Response()->json($result);
    }

    public function update($id)
    {
        if(!$this->user->has_sh){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        
        $data = $this->model->checkData();
        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }
        
        $device = $this->model->find($id);
        //检查设备编码是否重复
        /*if($data['dcode'] != $device['dcode']){
            $result = ['status'=>0,'info'=>'设备编码已存在'];
            return Response()->json($result);
        }*/
        if($this->model->where('id', '!=', $id)->where('dcode', $data['dcode'])->first()){
            $result = ['status'=>0,'info'=>'设备编码已存在'];
            return Response()->json($result);
        }
        try {
            $device->fill($data);
            $device->save();
            $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];
        
            $this->log($this->user->username.'修改', isset($this->act_name) ? $device[$this->act_name] : $device['name'], 'm');
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'修改失败,请检查是否重复'];
        }
        return Response()->json($result);
    }

    public function edit($id)
    {
        $data = $this->model->find($id);
        if($data){
            $data['supervision'] = Supervision::select(['id','name'])->where('project_id', $data['project_id'])->get()->toArray();
            
            $sec = Supervision::where('id',$data['supervision_id'])
                              ->with(['sec'=>function($query){
                                    $query->select(['id','name']);
                              }])
                              ->first();
            //设备类型
            $data['section'] = $sec['sec'];
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }

    public function destroy($id)
    {
        //只有项目和集团用户可以删除
        if(!$this->user->has_sh){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        if($this->user->role ==4 || $this->user->role ==5){
            $result = ['status'=>0,'info'=>'您没有权限,请联系项目用户或集团用户'];
            return Response()->json($result);
        }
        //设备有数据上传后不可随意删除，只有集团用户可以删除
        //设备数据对应表名
        $device_table = [
            '1'=>'lab_info',
            '2'=>'lab_info',
            '3'=>'lab_info',
            '4'=>'snbhz_info',
            '5'=>'zj_info',
            '6'=>'ylj_info',
            '7'=>'qzy_info',
            '8'=>'zl_info',
            '9'=>'yj_info',
            '10'=>'znpl_info',
            '11'=>'znys_info',
            '12'=>'ylc_info',
            '13'=>'jqj_info',
            '14'=>'yjyt_info',
            '15'=>'szy_info',
            ];
        $info = $this->model->find($id);
        $table = $device_table[$info->cat_id];
        $detail_info = DB::table($table)->where('device_id', $id)->first();
        if($detail_info && $this->user->role !=2 && $this->user->role !=1){
            $result = ['status'=>0,'info'=>'您没有权限,请联系集团用户'];
            return Response()->json($result);
        }
        $data = $this->model->destroy($id);
        
        if($data){
            $result = ['status'=>1,'info'=>'删除成功', 'data'=>$id, 'is_reload'=>$this->is_reload];
        
            $this->log($this->user->username.'删除', isset($this->act_name) ? $data[$this->act_name] : $data['name'], 'd');
        }else{
            $result = ['status'=>0,'info'=>'删除失败'];
        }
        return Response()->json($result);
    }

    protected function makeCode($data){
        $str = '01';  //铁路集团编码
        $project_str = dechex($data['project_id']);  //根据项目id的十六进制获取项目编码
        if(strlen($project_str) == 1){
            $project_str = '0'.$project_str;
        }
        $section_str = '0A'; //标段0a, 监理0b，施工单位0c 
        $cat_str = dechex($data['cat_id']);  //根据设备分类id的十六进制获取设备分类编码
        if(strlen($cat_str) == 1){
            $cat_str = '0'.$cat_str;
        }
        //测点编码  不是很懂 应该是区分设备唯一性的 
        //暂时用设备id的十六进制代替  但是这个最终会超过2位字符的吧
        //获取上一设备的测点编码  然后根据其设置本次的
        $info = $this->model->select(['dcode'])
                            ->where('section_id', $data['section_id'])
                            ->where('cat_id', $data['cat_id'])
                            ->orderByRaw('id desc')
                            ->first();
        $cd_str = 1;
        if($info){
            $cd_str = hexdec(substr($info['dcode'], -4, 2))+1;
        }
        $cd_str = dechex($cd_str);
        if(strlen($cd_str) == 1){
            $cd_str = '0'.$cd_str;
        }
        //扩展编码
        $kz_str = '00';
        return strtoupper($str.$project_str.$section_str.$cat_str.$cd_str.$kz_str);
    }
    /**
     * 喷淋养生设备
     */
    public function steamSprayDevice(Project $project,Device $device,Request $request)
    {

        $user_act=$this->user_is_act;

        $field=[
            'id',
            'cat_id',
            'project_id',
            'supervision_id',
            'section_id',
            'dcode',
            'name',
            'model',
            'parame1',
            'parame2',
            'parame3',
            'parame4',
            'factory_name',
            'factory_date',
            'fzr',
            'phone',
            'camera1',
            'camera2',
            'beam_site_id'
        ];
        $cat_id=10;
        $project_data=$this->getProject($project);
//        dd($project_data);
        $device_data=$this->getSteamSprayDevice($device,$request,$field,$cat_id);
//        dd($device_data);
        return view('admin.device.device_steam_spray_device',compact('project_data','device_data','user_act','cat_id'));
    }

    /**
     * 获取所有项目信息
     */
    protected function getProject($project_model)
    {

        $role=$this->user->role;
        $query=$project_model;
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
    /**
     * 根据标段id获取梁场信息
     */
    public function getBeamSiteBySection(Request $request,BeamSite $beamSite)
    {
        $section_id=$request->get('sec_id');

        $beam_site_data=$beamSite->select(['id','name'])
            ->where('section_id',$section_id)
            ->get()
            ->toArray();
        if(count($beam_site_data)==0){
            $result['status']=1;
            $result['mess']='获取该合同段对应的梁场信息失败，请添加';
            return $result;
        }

        $result['status']=0;
        $result['mess']=$beam_site_data;
        return Response()->json($result);
    }

    /**添加喷淋养生设备
     * @param Request $request
     * @param Device $device
     * @return \Illuminate\Http\JsonResponse
     */
    public function steamSprayDeviceAdd(Request $request,Device $device)
    {
        $input_data=$request->all();

        if($input_data['project_id']==0){
            $list['status']=0;
            $list['info']='请选择项目';
            return Response()->json($list);
        }

        if($input_data['supervision_id']==0){
            $list['status']=0;
            $list['info']='请选择监理';

            return Response()->json($list);
        }

        if($input_data['section_id']==0){
            $list['status']=0;
            $list['info']='请选择合同段';
            return Response()->json($list);
        }

        if($input_data['beam_site_id']==0){
            $list['status']=0;
            $list['info']='请选择梁场';

            return Response()->json($list);
        }

        if($input_data['dcode']==''){
            $list['status']=0;
            $list['info']='请输入设备编码';
            return Response()->json($list);
        }else{
            $device_id=$device->select('id')
                ->where('dcode',$input_data['dcode'])
                ->first();
            if($device_id){
                $list['status']=0;
                $list['info']='设备编码已存在,请输入正确的设备编码';
                return Response()->json($list);
            }
        }

        try{
            $device::create($input_data);
            $list['status']=1;
            $list['info']='添加成功';
            return Response()->json($list);
        }catch (\Exception $e){
            $list['status']=0;
            $list['info']='添加失败';
            return Response()->json($list);
        }



    }

    /**
     * 获取喷淋养生设备信息
     */
    protected function getSteamSprayDevice($query,$request,$field,$cat_id)
    {
        $pro_id=$request->get('pro_id');
        $sup_id=$request->get('sup_id');
        $sec_id=$request->get('sec_id');

        if($pro_id){
            $query=$query->where('project_id',$pro_id);
        }
        if($sup_id){
            $query=$query->where('supervision_id',$sup_id);
        }
        if($sec_id){
            $query=$query->where('section_id',$sec_id);
        }

        $role=$this->user->role;

        //超级管理员或集团用户
        if($role==1 || $role==2){
            //获取所管理的项目
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
            $supervision_id=$this->user->supervision->id;
            $query=$query->where('supervision_id',$supervision_id);
        }
        //合同段用户
        if($role==5){
            $section_id=$this->user->section->id;

            $query=$query->where('section_id',$section_id);
        }

        $steamSprayDevice_data=$query->select($field)
            ->with('project','sup','section','beam_site')
            ->where('cat_id',$cat_id)
            ->get()
            ->toArray();

        return $steamSprayDevice_data;


    }

    /**
     * 喷淋养生设备信息修改
     */
    public function steamSprayDeviceEdit(Request $request,Project $project,Device $device_model,Supervision $supervision,Section $section)
    {
        $role=$this->user->role;
        if($request->isMethod('get')){
            $device_id=$request->get('id');

            $project_data=$this->getProject($project);

            //获取当前的设备信息
            $device_data=$device_model->where('id',$device_id)
                                      ->with('project','sup','section','beam_site')
                                      ->first();
            $now_project_id=$device_data->project_id;

            if($role==1 || $role==2 || $role==3){
                $sup_data=$supervision->where('project_id',$now_project_id)
                    ->get()
                    ->toArray();
            }else{
                //获取监理id
                $sup_id=$this->user->supervision->id;
                $sup_data=$supervision->where('project_id',$now_project_id)
                    ->where('id',$sup_id)
                    ->get()
                    ->toArray();
            }

            //获取合同段信息
            if ($role==1 || $role==2 || $role==3){
                $sec_data=$section->where('project_id',$now_project_id)
                    ->get()
                    ->toArray();
            }elseif($role==4){
                //监理用户
                $sup_id=$this->user->supervision->id;
                $sec_id=DB::table('supervision_section')->select(['section_id'])
                    ->where('supervision_id',$sup_id)
                    ->first()->section_id;
                $sec_data=$section->where('id',$sec_id)
                    ->get()
                    ->toArray();

            }else{
                //合同段用户
                $sec_id=$this->user->section->id;
                $sec_data=$section->where('id',$sec_id)
                    ->get()
                    ->toArray();
            }
            //获取；梁场信息
            $section_id=$device_data['section_id'];
            $beam_site_model=new BeamSite();
            $beam_site_data=$beam_site_model->select(['id','name'])
                ->where('section_id',$section_id)
                ->get()
                ->toArray();

            return view('admin.device.device_steam_spray_edit',compact('project_data','device_data','sup_data','sec_data','beam_site_data'));

        }

        if($request->isMethod('post')){
            $input_data=$request->all();
            //查询设备编码是否有重复
            $dcode=$input_data['dcode'];
            $id=$input_data['id'];
            $is_has_dcode=$device_model->where('dcode',$dcode)
                ->where('id','!=',$id)
                ->first();
            if($is_has_dcode){
                $result['status']=0;
                $result['info']='该设备编码已经存在，请填写正确的设备编码';
                return Response()->json($result);
            }
            if($input_data['project_id']==0 || $input_data['project_id']==''){
                $result['status']=0;
                $result['info']='请选择项目公司';
                return Response()->json($result);
            }
            if($input_data['supervision_id']==0 || $input_data['project_id']==''){
                $result['status']=0;
                $result['info']='请选择监理单位';
                return Response()->json($result);
            }
            if($input_data['section_id'] == 0 || $input_data['section_id']==''){
                $result['status']=0;
                $result['info']='请选择所属合同段';
                return Response()->json($result);
            }
            if($input_data['beam_site_id']==0 || $input_data['beam_site_id']==''){
                $result['status']=0;
                $result['info']='请选择对应的梁场';
                return Response()->json($result);
            }
            unset($input_data['_token']);
            unset($input_data['id']);

            try{

                $device_model->where('id',$id)
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


    /**喷淋养生设备信息删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function steamSprayDeviceDel(Device $device,$id)
    {
        $this_device=$device->find($id);

        try{
            $this_device->delete();
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


    /**
     * 张拉设备
     */
    public function stretchDevice(Project $project,Device $device,Request $request)
    {
        //设备分类id
        $cat_id = 8;
        $user_act=$this->user_is_act;

        $field=[
            'id',
            'cat_id',
            'project_id',
            'supervision_id',
            'section_id',
            'dcode',
            'name',
            'model',
            'parame1',
            'parame2',
            'parame3',
            'parame4',
            'factory_name',
            'factory_date',
            'fzr',
            'phone',
            'camera1',
            'camera2',
            'beam_site_id'
        ];
        $project_data=$this->getProject($project);
//        dd($project_data);
        $device_data=$this->getSteamSprayDevice($device,$request,$field,$cat_id);
//        dd($device_data);
        return view('admin.device.beam_yard_device',compact('project_data','device_data','user_act','cat_id'));

    }

    /**
     * 压浆设备
     */
    public function mudjackDevice(Project $project,Device $device,Request $request)
    {
         //设备分类id
        $cat_id = 9;
        $user_act=$this->user_is_act;

        $field=[
            'id',
            'cat_id',
            'project_id',
            'supervision_id',
            'section_id',
            'dcode',
            'name',
            'model',
            'parame1',
            'parame2',
            'parame3',
            'parame4',
            'factory_name',
            'factory_date',
            'fzr',
            'phone',
            'camera1',
            'camera2',
            'beam_site_id'
        ];
        $project_data=$this->getProject($project);
//        dd($project_data);
        $device_data=$this->getSteamSprayDevice($device,$request,$field,$cat_id);
//        dd($device_data);
        return view('admin.device.beam_yard_device',compact('project_data','device_data','user_act','cat_id'));

    }

    /**
     * 归属梁场的设备信息修改
     * @param Request $request
     */
    public function beamYardDeviceEdit(Request $request,Device $device,$device_id)
    {
          if($request->isMethod('get')){

              $role=$this->user->role;
              $supervision=new Supervision();
              $section=new Section();
              $project=new Project();

              $project_data=$this->getProject($project);

             $device_data=$device->find($device_id);

              $now_project_id=$device_data->project_id;

              if($role==1 || $role==2 || $role==3){
                  $sup_data=$supervision->where('project_id',$now_project_id)
                      ->get()
                      ->toArray();
              }else{
                  //获取监理id
                  $sup_id=$this->user->supervision->id;
                  $sup_data=$supervision->where('project_id',$now_project_id)
                      ->where('id',$sup_id)
                      ->get()
                      ->toArray();
              }

              //获取合同段信息
              if ($role==1 || $role==2 || $role==3){
                  $sec_data=$section->where('project_id',$now_project_id)
                      ->get()
                      ->toArray();
              }elseif($role==4){
                  //监理用户
                  $sup_id=$this->user->supervision->id;
                  $sec_id=DB::table('supervision_section')->select(['section_id'])
                      ->where('supervision_id',$sup_id)
                      ->first()->section_id;
                  $sec_data=$section->where('id',$sec_id)
                      ->get()
                      ->toArray();

              }else{
                  //合同段用户
                  $sec_id=$this->user->section->id;
                  $sec_data=$section->where('id',$sec_id)
                      ->get()
                      ->toArray();
              }
              //获取；梁场信息
              $section_id=$device_data['section_id'];
              $beam_site_model=new BeamSite();
              $beam_site_data=$beam_site_model->select(['id','name'])
                  ->where('section_id',$section_id)
                  ->get()
                  ->toArray();

//              dd($device_data);

              return view('admin.device.beam_yard_device_edit',compact('project_data','device_data','sup_data','sec_data','beam_site_data'));

          }

          if($request->isMethod('post')){

              $input_data=$request->all();
//              dd($input_data);
              //查询设备编码是否有重复
              $dcode=$input_data['dcode'];
              $id=$input_data['id'];
              $is_has_dcode=$device->where('dcode',$dcode)
                  ->where('id','!=',$id)
                  ->first();
              if($is_has_dcode){
                  $result['status']=0;
                  $result['info']='该设备编码已经存在，请填写正确的设备编码';
                  return Response()->json($result);
              }

              if($input_data['project_id']==0 || $input_data['project_id']==''){
                  $result['status']=0;
                  $result['info']='请选择项目公司';
                  return Response()->json($result);
              }
              if($input_data['supervision_id']==0 || $input_data['project_id']==''){
                  $result['status']=0;
                  $result['info']='请选择监理单位';
                  return Response()->json($result);
              }
              if($input_data['section_id'] == 0 || $input_data['section_id']==''){
                  $result['status']=0;
                  $result['info']='请选择所属合同段';
                  return Response()->json($result);
              }
              if($input_data['beam_site_id']==0 || $input_data['beam_site_id']==''){
                  $result['status']=0;
                  $result['info']='请选择对应的梁场';
                  return Response()->json($result);
              }
              unset($input_data['_token']);
              unset($input_data['id']);
              try{

                  $device->where('id',$id)
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


    /**
     * 智能蒸汽养生设备
     */
    public function steamKeepDevice(Project $project,Device $device,Request $request)
    {
        $user_act=$this->user_is_act;

        $field=[
            'id',
            'cat_id',
            'project_id',
            'supervision_id',
            'section_id',
            'dcode',
            'name',
            'model',
            'parame1',
            'parame2',
            'parame3',
            'parame4',
            'factory_name',
            'factory_date',
            'fzr',
            'phone',
            'camera1',
            'camera2',
            'beam_site_id'
        ];
        $cat_id=11;
        $project_data=$this->getProject($project);
//        dd($project_data);
        $device_data=$this->getSteamSprayDevice($device,$request,$field,$cat_id);
//        dd($device_data);
        return view('admin.device.device_steam_spray_device',compact('project_data','device_data','user_act','cat_id'));
    }




}