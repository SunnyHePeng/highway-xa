<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Response;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Device_ip;
use Redirect, Input, Auth, Validator, DB;
class AdminAbnormalController extends BaseController
{
    protected $request;
    protected $model;
    protected $rule;
    public function __construct(Request $request, Device_ip $model)
    {
        $this->request = $request;
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if(Input::get('did')){
            //三个月的登陆记录
            $start = strtotime(date('Y-m-d')) - 93*86400;
            $end = time();
            $where = [
                ['did','=',Input::get('did')],
                ['timestamp','>=',$start],
                ['timestamp','<=',$end],
            ];
        }else{
            $where = [
                ['is_abnormal','=',1]
            ];
        }
        $list = $this->model->getList($this->model, $where, '*', 'timestamp desc', $this->ispage);
        $list['url'] = url('manage/abnormal?did='.Input::get('did'));
        $list['did'] = Input::get('did');
        return view('admin.device.abnormal',$list);
    }

    public function destroy($id=0)
    {
        if(Input::get('type') == 'clear'){
            $str = 'truncate table device_ip';
            $res = DB::statement($str);
            if($res){
                $result = ['status'=>1,'info'=>'操作成功'];
            }else{
                $result = ['status'=>0,'info'=>'操作失败'];
            }
            return Response()->json($result);
        }

        $data = $this->model->find($id);
        if($data){
            $res = $this->model->where('id', $id)->update(['is_abnormal'=>2]);
            if($res){
                $result = ['status'=>1,'info'=>'处理成功', 'data'=>$id];
            }else{
                $result = ['status'=>0,'info'=>'处理失败'];
            }
        }else{
            $result = ['status'=>0,'info'=>'参数错误'];
        }
        return Response()->json($result);
    }
}
