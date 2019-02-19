<?php

namespace App\Http\Controllers\Smog;

use App\Models\Project\Section;
use App\Models\Smog\Environment;
use Illuminate\Http\Request;

use App\Http\Requests;


/**
 * 环境监测
 */
class EnvironmentMonitorController extends BaseController
{
    protected $order = 'id desc';
    protected $ispage = 20;
    protected $module = 31;
    protected $environment_info_model;
    protected $section_model;
    public function __construct(Section $section)
    {
        parent::__construct();
        view()->share(['module' => '环境监测']);
        $this->environment_info_model=new Environment();
        $this->section_model=$section;

    }

    /**
     * 现场环境监测仪
     */
    public function environmentScene(Request $request)
    {
        $now_environment_data=$this->getNowEnvironmentData($this->environment_info_model);
//        dd($now_environment_data);
        return view('smog.environment_monitor.environment_scene',compact('now_environment_data'));
    }

    /**
     * 洞内气体监测仪（待开发）
     */
    public function tunnelGas()
    {
        return view('smog.environment_monitor.tunnel_gas');
    }

    /**
     * 环境监测历史
     */
    public function environmentHistory(Request $request)
    {
        $section_field=[
            'id',
            'name',
        ];
        //获取合同段信息
        $section_data=$this->getSection($this->section_model,$section_field);

        $environment_field=[
            'project_id',
            'supervision_id',
            'section_id',
            'device_id',
            'pm25',
            'pm10',
            'moisture',
            'temperature',
            'noise',
            'place',
            'datetime',
            'time',
            'created_at'
        ];

        $url=url('smog/environment_history');

//        dd($this->environment_info_model);
        //获取监测数据
        if ($request->get('d')) {

            $view = 'smog.environment_monitor.environment_history_export';

        } else {

            $view = 'smog.environment_monitor.environment_history';
        }

        $environment_history_data=$this->getEnvironmentHistoryData($request,$environment_field,$this->environment_info_model,$this->ispage,$url);
        $environment_history_data['section_data']=$section_data;
//        dd($environment_history_data);
        return view($view,$environment_history_data);
//           return 1;
    }




}
