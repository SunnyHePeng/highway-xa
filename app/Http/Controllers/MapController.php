<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Project\Map,
    App\Models\Project\Project;
use Input;
class MapController extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
    	$tree = $this->getTreeProjectData();
        
        if($this->user->role == 1 || $this->user->role == 2){
            array_unshift($tree, ['id'=>0, 'name'=>'全部']);
            $pro_id = Input::get('pro_id') ? Input::get('pro_id') : 0;
        }else{
            $pro_id = $this->user->project[0];
        }
        $list['ztree_data'] = json_encode($tree);
        $list['ztree_url'] = url('map/index');
        if(!$pro_id){
            $list['ztree_name'] = '全部';
        }else{
            $list['ztree_name'] = Project::where('id', $pro_id)->pluck('name'); 
        }
            
            
        //获取对应项目地图信息
        //覆盖物信息
        $list['fgw'] = $this->getFgw($pro_id);
        //线路信息
        $list['line'] = $this->getLine($pro_id);
        return view('map.index', $list);
    }

    protected function getFgw($pro_id){
        $query = new Map;
        if($pro_id){
            $query = $query->where('project_id', $pro_id);
        }
        $list = $query->where('type', '!=', 9)
                        ->get()
                        ->toArray();
        $icon = [
            '1'=>['w'=>23, 'h'=>25, 'l'=>41, 't'=>42, 'x'=>9, 'lb'=>12],
            '2'=>['w'=>23, 'h'=>25, 'l'=>0, 't'=>68, 'x'=>9, 'lb'=>12],
            '3'=>['w'=>23, 'h'=>25, 'l'=>115, 't'=>68, 'x'=>9, 'lb'=>12],
            '4'=>['w'=>23, 'h'=>25, 'l'=>92, 't'=>68, 'x'=>9, 'lb'=>12],
            '5'=>['w'=>23, 'h'=>25, 'l'=>46, 't'=>-4, 'x'=>9, 'lb'=>12],
            '6'=>['w'=>23, 'h'=>25, 'l'=>69, 't'=>68, 'x'=>9, 'lb'=>12],
            '7'=>['w'=>23, 'h'=>25, 'l'=>46, 't'=>68, 'x'=>9, 'lb'=>12],
            '8'=>['w'=>23, 'h'=>25, 'l'=>23, 't'=>68, 'x'=>9, 'lb'=>12],
            '10'=>['w'=>23, 'h'=>25, 'l'=>0, 't'=>90, 'x'=>9, 'lb'=>12],
            ];
        /*{ 
            title: 'LJ-01拌合站',
             content: '监理：监理名称<br/>起始桩号<br/>承包商', 
             point: '109.235361,34.67471', 
             isOpen: 0, 
             icon: {w: 23, h: 25, l: 0, t:63, x: 9, lb: 12}
        }*/
        $info = [];
        foreach ($list as $key => $value) {
            $info[$key] = [
                    'title'=>$value['name'],
                    'content'=>$value['content'],
                    'point'=>$value['jwd'],
                    'isOpen'=>0,
                    'icon'=>$icon[$value['type']]
                ];
        }
        return json_encode($info);
    }

    protected function getLine($pro_id){
        $query = new Map;
        if($pro_id){
            $query = $query->where('project_id', $pro_id);
        }
        $list = $query->where('type', '=', 9)
                       ->orderByRaw('project_id asc, sort asc')
                       ->get()
                       ->toArray();
        /*{ 
            style: "solid", 
            weight: 4, 
            color: "red", 
            opacity: 0.6, 
            points: ["108.945604,34.382789", "109.235361,34.67471"]}
        */
        $color = [
                'red',
                'blue',
                'green',
                'yellow',
                '#f0f',
                '#0075c7',
                '#FFB90F',
                '#FF8247',
                '#FF34B3',
                '#FF0000',
                '#F4A460',
                '#EE30A7',
                '#8470FF',
                '#7A378B',
                '#DB7093',
                '#D15FEE',
                '#9ACD32',
                '#4876FF',
                '#00CD00',
                '#0000EE'
            ];
        $num = count($list);
        $info = [];
        $i = 0;
        foreach ($list as $key => $value) {
            if($i == 20){
                $i = 0;
            }
            if((isset($list[$key+1]['project_id']) && $value['project_id'] != $list[$key+1]['project_id']) || $key == $num-1){
                $points=[$value['jwd'], $value['jwd']];
            }else{
                $points=[$value['jwd'], $list[$key+1]['jwd']];
            }
            $info[$value['project_id']][] = [
                    'style'=>'solid',
                    'weight'=>4,
                    'color'=>$color[$i],
                    'opacity'=>'0.6',
                    'points'=>$points
                ];
            $i++;
        }
        sort($info);
        return json_encode($info);
    }
}
