<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Mixplant,
    App\Models\Project\Section,
    App\Models\Device\Collection;
use Input;

class MixplantController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.mixplant';
    protected $url = '';
    protected $cat_id=1;
    protected $column = 'bhz_num';
    protected $act_info = '拌合站';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Mixplant;
        $this->field = ['id','name','database_type','status','product_rate','factory','capacity','fzr','phone','created_at'];
        $this->url = url('manage/mixplant');
    }

    public function index(){
    	return $this->mixplantIndex();
    }

    //拌合站 梁场 隧道 已添加就增加对应项目 标段 对应分类的采集点
    //拌合站   拌合设备   梁场 张拉设备 梁场设备  隧道 隧道设备
    public function store()
    {
        $data = $this->model->checkData();

        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }
        $data['created_at'] = time();

        try {
            $res = $this->model->create($data);
            //添加对应采集点
            foreach (Config()->get('common.device_type.'.$this->cat_id) as $key => $value) {
                $info = [
                    'project_id'=>$data['project_id'],
                    'section_id'=>$data['section_id'],
                    'cat_id'=>$value,
                    'foreign_key'=>$res->id,
                    'name'=>$data['name']
                    ];
                (new Collection)->create($info);
            }
            //更新对应标段的拌合站数量
            (new Section)->updateColumn($data['section_id'], $this->column, 'add');
            $result = ['status'=>1,'info'=>'添加成功'];

            $this->addLog('添加新'.$this->act_info.'：'.$data['name'], 'a');
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
        try {
            if($data['name'] != $device->name){
                foreach (Config()->get('common.device_type.'.$this->cat_id) as $key => $value) {
                    (new Collection)->where('foreign_key', $data['id'])
                                    ->where('cat_id',$value)
                                    ->update(['name'=>$data['name']]);
                }
            }

            $device->fill($data);
            $device->save();
            
            $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];

            $this->addLog('修改'.$this->act_info.'：'.$data['name'], 'm');
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'修改失败,请检查是否重复'];
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

        $info = $this->model->find($id);
        $data = $this->model->destroy($id);
        
        if($data){
            //删除对应采集点
            foreach (Config()->get('common.device_type.'.$this->cat_id) as $key => $value) {
                (new Collection)->where('foreign_key', $id)
                                ->where('cat_id', $value)
                                ->delete();
            }
            //删除对应标段的拌合站数量
            (new Section)->updateColumn($info->section_id, $this->column, 'del');
            $result = ['status'=>1,'info'=>'删除成功', 'data'=>$id];

            $this->addLog('删除'.$this->act_info.'：'.$info->name, 'd');
        }else{
            $result = ['status'=>0,'info'=>'删除失败'];
        }
        return Response()->json($result);
    }
}