<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use DB, Input, Auth;
use App\Models\User\User,
    App\Models\User\Role,
    App\Models\System\Code_type,
    App\Models\Code\Code_sale,
    App\Models\Code\Code,
    App\Models\Pay\Pay,
    App\Models\Pay\Consume,
    App\Models\Pay\Back,
    App\Models\Stat\Stat_code;

class IndexController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response 1466510446
     */
    public function index($is_trashed=0)
    {   
        parent::__construct();
        
        $role = $this->user->roles->toArray();
        
        return view('admin.index.'.$role[0]['name']);
    }

    /*超级管理信息*/
    protected function super_admin(){
        $start_time = strtotime(date('Y-m-d'));
        $end_time = $start_time + 86400;
        
        //代理总数
        $list['agent'] = DB::table('user')
                            ->where('status', '=', 1)
                            ->where('role', '=', 5)
                            ->count();

        //今日出卡
        $info = User::select(DB::raw('sum(code_sale.num) as count'))
                     ->leftJoin('code_sale', 'user.id', '=', 'code_sale.from_id')
                     ->where('user.role', 5)
                     ->where('code_sale.created_at', '>=', $start_time)
                     ->where('code_sale.created_at', '<', $end_time)
                     ->first()
                     ->toArray();
        $info2 = User::leftJoin('code', 'user.id', '=', 'code.user_id')
                     ->where('user.role', 5)
                     ->where('code.created_at', '>=', $start_time)
                     ->where('code.created_at', '<', $end_time)
                     ->count();
        $list['card'] = $info['count'] + $info2;                 
        
        //今日充值(审核通过)
        $list['pay'] = (new Pay)->payByTime($start_time, $end_time);
        
        //消费
        $list['consume'] = (new Consume)->consumeByTime($start_time, $end_time);
        
        //返现
        $list['back'] = (new Back)->backByTime($start_time, $end_time);

        $code = new Code;
        //今日生成码数
        $list['make_code'] = $code->makeCodeByTime($start_time, $end_time);
        
        //今日激活码数
        $list['active_code'] = $code->activeCodeByTime($start_time, $end_time);
        
        //待处理事项
        $list['pay'] = Pay::where('status', '=', 0)->count();
        
        //近七日各类型卡出售情况
        $list['chart'] = $this->trendDate();
        return $list;
    }

    /*普通管理信息*/
    protected function admin(){
        //公告
        $list['notice'] = $this->getNotice(Auth::user()->role);

        //待处理事项
        $list['pay'] = Pay::where('status', '=', 0)->count();
        
        return $list;
    }

    /*代理信息*/
    protected function agent(){
        $id = Auth::user()->id;
        //子代理数
        $list['agent'] = User::where('pid', $id)->where('role', '5')->count();
        
        //返现合计
        $info = DB::table('back')
                    ->select(DB::raw('sum(amount) as count'))
                    ->where('user_id', $id)
                    ->first();
        $list['back'] = $info ? $info->count : 0;
        $start_time = strtotime(date('Y-m-d'));
        $end_time = $start_time + 86400;
        
        //今日消费
        $info = DB::table('consume')
                    ->select(DB::raw('sum(amount) as count'))
                    ->where('user_id', $id)
                    ->where('created_at', '>=', $start_time)
                    ->where('created_at', '<=', $end_time)
                    ->first();
        $list['consume'] = $info->count;
        
        //今日出卡
        $card1 = DB::table('code_sale')
                    ->select(DB::raw('sum(num) as count'))
                    ->where('from_id', $id)
                    ->where('created_at', '>=', $start_time)
                    ->where('created_at', '<=', $end_time)
                    ->first();
        $card2 = DB::table('code')
                    ->where('user_id', $id)
                    ->where('created_at', '>=', $start_time)
                    ->where('created_at', '<=', $end_time)
                    ->count();
        $list['card'] = $card1->count + $card2;
        
        //余额
        $list['balance'] = Auth::user()->balance;
        
        //公告
        $list['notice'] = $this->getNotice(Auth::user()->role);
        return $list;
    }

    /*分销商信息*/
    protected function distributor(){
        $id = Auth::user()->id;
        //各卡数量
        $list['card'] = Code_type::select(DB::raw('code_type.id, code_type.name, sum(code_sale.remain) as count'))
                         ->leftJoin('code_sale', function($join) use ($id)
                            {
                                $join->on('code_type.id', '=', 'code_sale.type')
                                     ->on('code_sale.user_id', '=', DB::raw($id))
                                     ->where('code_sale.remain', '>', 0);
                            })
                         ->groupBy('code_type.id')
                         ->orderByRaw('code_type.sort desc')
                         ->get()
                         ->toArray();
        //公告
        $list['notice'] = $this->getNotice(Auth::user()->role);
        return $list;
    }

    /*获取公告*/
    protected function getNotice($role_id){
        $model = new Role;
        $list = $model->getList($model, 
                                [['id', '=', $role_id]], 
                                ['id'], 
                                'id desc', 
                                '', 
                                '',
                                'notice', 
                                ['id','content','created_at'], 
                                [['status', '=', '1']], 
                                'sort desc, id desc'
                                );
        return $list[0]['notice'];
    }

    /*近七日各类型卡出售情况*/
    protected function trendDate(){
        $start_time = strtotime(date('Y-m-d', strtotime('-6 day')));
        $data = [];
        //获取前六天数据
        $list = Stat_code::select(['date','type','sale_count'])
                        ->where('created_at', '>=', $start_time)
                        ->where('created_at', '<', time())
                        ->orderByRaw('id asc')
                        ->get()
                        ->toArray();
        foreach ($list as $key => $value) {
            $data[$value['type']][] = $value['sale_count'];
        }

        for($i = 0; $i < 7; $i++){
            $categories[$i] = date('Y-m-d', $start_time);
            if($i == 6){
                //代理给分销商加卡
                $dis = (new Code_sale)->addCodeByTime($start_time, $start_time+86400, 'type');

                //代理直接提卡
                $agent = (new Code)->agentCodeByTime($start_time, $start_time+86400, 'type');
                
                foreach ($data as $key => $value) {
                    $data[$key][] = (isset($dis[$key]) ? $dis[$key] : 0) + (isset($agent[$key]) ? $agent[$key] : 0);
                }
            }
            $start_time = $start_time + 86400;
        }

        $code_type = Code_type::select(['id','name','price'])->orderByRaw('sort desc, id asc')->get()->toArray();
        foreach ($code_type as $key => $value) {
            $series[$key]['name'] = $value['name'];
            $series[$key]['data'] = isset($data[$value['id']]) ? $data[$value['id']] : 0;//(isset($data1[$value['id']]) ? $data1[$value['id']] : 0) + (isset($data2[$value['id']]) ? $data2[$value['id']] : 0);  
        }
        
        $chart = [
            'categories'=>$categories,
            'series'=>$series,
            'title_x'=>'最近七日出卡情况',
            'title_y'=>'数量'
            ];
        return json_encode($chart);
    }
}

