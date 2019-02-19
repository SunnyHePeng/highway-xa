<?php

namespace App\Http\Controllers\Stat;

/**
 * 进度统计历史数据
 */
use App\Models\Project\Project;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Mixplant\IndexController;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, Auth, DB, Cache, Log;
use App\Models\Project\Section;
use App\Models\Project\Supervision;
use App\Models\Sdtj\SdtjDaliyTfkw;
use App\Models\Sdtj\SdtjDaliyLf;
use App\Models\Sdtj\ResourceSgry;
use App\Models\Sdtj\ResourceJxsb;
use App\Models\Sdtj\StatWarnMess;
use App\Models\Sdtj\SdtjMonitor;
use Mockery\Exception;


class StatHistoryController extends IndexController
{
    protected $order = 'id desc';
    protected $device_cat = 4;
    protected $ispage = 20;
    protected $module = 4;
    protected $sdtj_daliy_lf_model;
    protected $resource_sgry_model;
    protected $resource_jxsb_model;
    protected $warn_mess_model;
    protected $monitor_model;

    public function __construct(SdtjDaliyLf $daliyLf, ResourceSgry $resourceSgry, ResourceJxsb $resourceJxsb)
    {
        parent::__construct();
        view()->share(['module' => '进度统计']);
        $this->sdtj_daliy_lf_model = $daliyLf;
        $this->resource_sgry_model = $resourceSgry;
        $this->resource_jxsb_model = $resourceJxsb;
        $this->warn_mess_model = new StatWarnMess();
        $this->monitor_model = new SdtjMonitor();
    }

    /**
     * 隧道工程历史数据
     */
    public function tunnelProjectHistory(Request $request)
    {
        $role = $this->user->role;
        $url = url('stat/tunnel_project_history');
        $history_data = $this->getTunnelProjectHistory($request, $role, $url);
//        dd($history_data);
        if ($request->get('d')) {

            $view = 'stat.statHistory.tunnel_project_history_project_export';

        } else {

            $view = 'stat.statHistory.tunnel_project_history_project';
        }

        return view($view, $history_data);
    }

    protected function getTunnelProjectHistory($request, $role, $url)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $search = [];

        $query = $this->sdtj_daliy_lf_model;

        if ($start_date) {

            $start_time = strtotime($start_date);
            $search['start_date'] = $start_date;

        } else {

            $start_time = time() - 30 * 86400;
            $search['start_date'] = date('Y-m-d', time() - 30 * 86400);
        }

        if ($end_date) {

            $end_time = strtotime($end_date);

            $search['end_date'] = $end_date;
        } else {

            $end_time = time() + 86400;
            $search['end_date'] = date('Y-m-d', time());
        }

        if ($request->get('page')) {
            $search['page'] = $request->get('page');
        } else {
            $search['page'] = 1;
        }


        $url = $url . '?start_date=' . $search['start_date'] . '&end_date=' . $search['end_date'];

        $query = $query->whereBetween('time', [$start_time, $end_time]);

        //超级管理员或项目管理处用户
        if ($role == 1 || $role == 3) {
            $query = $query->where([]);
        }

        //监理用户
        if ($role == 4) {

            $supervision_id = $this->user->supervision->id;

            $section = DB::table('supervision_section')->where('supervision_id', $supervision_id)
                ->first();
            $section_id = $section->section_id;

            $query = $query->where('section_id', $section_id);
        }

        //合同段用户
        if ($role == 5) {
            $section_id = $this->user->section->id;
            $query = $query->where('section_id', $section_id);
        }

        if ($request->get('d')) {

            $search['d'] = $request->get('d');

            if ($request->get('d') == 'all') {

                $history_data['data'] = $query->with('section')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->toArray();

            } else {

                $history_data = $query->with('section')
                    ->orderBy('id', 'desc')
                    ->paginate($this->ispage)
                    ->toArray();
            }


        } else {

            $history_data = $query->with('section')
                ->orderBy('id', 'desc')
                ->paginate($this->ispage)
                ->toArray();

        }

//        $history_data = $query->with('section')
//            ->orderBy('id', 'desc')
//            ->paginate($this->ispage)
//            ->toArray();

        $history_data['url'] = $url;
        $history_data['search'] = $search;

        return $history_data;

    }

    /**
     * 资源配置统计历史数据
     */
    public function resourceHistory(Request $request)
    {
        if ($request->get('d')) {
            $view = 'stat.statHistory.resource_history_export';
        } else {
            $view = 'stat.statHistory.resource_history';
        }

        $url = url('stat/resource_history');
        $resource_history_data = $this->getResourceHistory($request, $url);
//        dd($resource_history_data);
        return view($view, $resource_history_data);
    }

    /**
     * 报警信息统计历史数据
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function warnMessHistory(Request $request)
    {
        $url = url('stat/warn_mess_history');

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $module_id = $request->get('module_id');
        $section_id = $request->get('section_id');

        $role = $this->user->role;
        $search = [];

        $query = $this->warn_mess_model;



        if ($start_date) {
            $start_time = strtotime($start_date);
        } else {
            $start_time = time() - 86400 * 30;
        }

        if ($end_date) {
            $end_time = strtotime($end_date) + 86400;
        } else {
            $end_time = time() + 86400;
        }


        if ($request->get('page')) {
            $search['page'] = $request->get('page');
        } else {
            $search['page'] = 1;
        }


        $search['start_date'] = date('Y-m-d', $start_time);
        $search['end_date'] = date('Y-m-d', $end_time - 86400);

        $url = $url . '?start_date=' . date('Y-m-d', $start_time) . '&end_date=' . date('Y-m-d', $end_time - 86400);

        if ($module_id) {
            $query = $query->where('module_id', $module_id);
            $search['module_id'] = $module_id;
            $url=$url.'&module_id='.$module_id;

        } else {
            $search['module_id'] = 0;
        }

        if ($section_id) {
            $query = $query->where('section_id', $section_id);
            $search['section_id'] = $section_id;
            $url=$url.'&section_id='.$section_id;

        } else {
            $search['section_id'] = 0;
        }

        $query = $query->whereBetween('time', [$start_time, $end_time]);

        if ($role == 1 || $role == 3) {
            $query = $query->where([]);
        }

        if ($role == 4) {

            $supervision_id = $this->user->supervision->id;
            $section = DB::table('supervision_section')->select('section_id')
                ->where('supervision_id', $supervision_id)
                ->first();
            $section_id = $section->section_id;

            $query = $query->where('section_id', $section_id);
        }

        if ($role == 5) {
            $section_id = $this->user->section->id;

            $query = $query->where('section_id', $section_id);
        }

        if ($request->get('d')) {

            $view = 'stat.statHistory.warn_mess_history_export';
            $search['d'] = $request->get('d');

            if ($request->get('d') == 'all') {

                $warn_mess_data['data'] = $query->orderBy('id', 'desc')
                                                ->get()
                                                ->toArray();

            } else {

                $warn_mess_data = $query->orderBy('id', 'desc')
                                        ->paginate($this->ispage)
                                        ->toArray();
            }

        } else {
            $view = 'stat.statHistory.warn_mess_history';
            $warn_mess_data = $query->orderBy('id', 'desc')
                                    ->paginate($this->ispage)
                                    ->toArray();
        }

        $section_data = $this->getSection($role);
//        dd($section_data);
        $warn_mess_data['url'] = $url;
        $warn_mess_data['search'] = $search;
        $warn_mess_data['section_data'] = $section_data;
//        dd($warn_mess_data);

        return view($view, $warn_mess_data);

    }

    /**
     * 隧道监控量测统计历史
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function monitorHistory(Request $request)
    {
        $role = $this->user->role;
        $url = url('stat/monitor_history');

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $search = [];

        $query = $this->monitor_model;

        if ($start_date) {
            $start_time = strtotime($start_date);
        } else {
            $start_time = time() - 86400 * 30;
        }

        if ($end_date) {
            $end_time = strtotime($end_date) + 86400;
        } else {
            $end_time = time() + 86400;
        }

        $search['start_date'] = date('Y-m-d', $start_time);
        $search['end_date'] = date('Y-m-d', $end_time - 86400);
        $url = $url . '?start_date=' . date('Y-m-d', $start_time) . '&end_date=' . date('Y-m-d', $end_time - 86400);

        $query = $query->whereBetween('time', [$start_time, $end_time]);

        if ($request->get('page')) {
            $search['page'] = $request->get('page');
        } else {
            $search['page'] = 1;
        }


        if ($role == 1 || $role == 3) {
            $query = $query->where([]);
        }

        if ($role == 4) {

            $supervision_id = $this->user->supervision->id;
            $section = DB::table('supervision_section')->select('section_id')
                ->where('supervision_id', $supervision_id)
                ->first();
            $section_id = $section->section_id;

            $query = $query->where('section_id', $section_id);
        }

        if ($role == 5) {
            $section_id = $this->user->section->id;

            $query = $query->where('section_id', $section_id);
        }

        if ($request->get('d')) {

            $view='stat.statHistory.monitor_history_export';
            $search['d'] = $request->get('d');

            if ($request->get('d') == 'all') {

                $monitor_data['data'] = $query->with('section')
                                              ->orderBy('id', 'desc')
                                              ->get()
                                              ->toArray();

            } else {

                $monitor_data = $query->with('section')
                                      ->orderBy('id', 'desc')
                                      ->paginate($this->ispage)
                                      ->toArray();
            }

        } else {

            $view='stat.statHistory.monitor_history';

            $monitor_data = $query->with('section')
                                  ->orderBy('id', 'desc')
                                  ->paginate($this->ispage)
                                  ->toArray();
        }


        $monitor_data['url'] = $url;
        $monitor_data['search'] = $search;

        return view($view, $monitor_data);
    }

    /*获取资源配置统计历史数据*/
    public function getResourceHistory($request, $url)
    {
//        $type=$request->input('data_type');
//        dd($type);
        $type = $request->input('data_type', 1);
        $search = [];
        $search['type'] = $type;
        $url = $url . '?data_type=' . $type;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($type == 1) {
            $query = $this->resource_sgry_model;
        }

        if ($type == 2) {
            $query = $this->resource_jxsb_model;
        }

        if ($start_date) {
            $start_time = strtotime($start_date);
            $url = $url . '&start_date=' . date('Y-m-d', $start_time);

        } else {
            $start_time = time() - 30 * 86400;
            $url = $url . '&start_date=' . date('Y-m-d', $start_time);
        }

        if ($end_date) {
            $end_time = strtotime($end_date) + 86400;
            $url = $url . '&end_date=' . date('Y-m-d', $end_time - 86400);

        } else {
            $end_time = time() + 86400;
            $url = $url . '&end_date=' . date('Y-m-d', $end_time - 86400);
        }


        $search['start_date'] = date('Y-m-d', $start_time);
        $search['end_date'] = date('Y-m-d', $end_time - 86400);

        if ($request->get('page')) {
            $search['page'] = $request->get('page');
        } else {
            $search['page'] = 1;
        }

        $query = $query->whereBetween('time', [$start_time, $end_time]);

        $role = $this->user->role;

        //超级管理员或项目管理处用户
        if ($role == 1 || $role == 3) {
            $query = $query->where([]);
        }

        //监理用户
        if ($role == 4) {
            $supervision_id = $this->user->supervision->id;
            $section = DB::table('supervision_section')->select('section_id')
                ->where('supervision_id', $supervision_id)
                ->first();
            $section_id = $section->section_id;
//           dd($section_id);
            $query = $query->where('section_id', $section_id);
        }

        //合同段用户
        if ($role == 5) {
            $section_id = $this->user->section->id;

            $query = $query->where('section_id', $section_id);
        }

        if ($request->get('d')) {

            $search['d'] = $request->get('d');

            if ($request->get('d') == 'all') {

                $resource_data['data'] = $query->with('section')
                                         ->orderBy('id', 'desc')
                                         ->get()
                                         ->toArray();

            } else {

                $resource_data = $query->with('section')
                    ->orderBy('id', 'desc')
                    ->paginate($this->ispage)
                    ->toArray();

            }

        } else {

            $resource_data = $query->with('section')
                ->orderBy('id', 'desc')
                ->paginate($this->ispage)
                ->toArray();
        }



        $resource_data['search'] = $search;
        $resource_data['url'] = $url;
        return $resource_data;
//        dd($resource_data);

    }

    /*获取标段信息*/
    public function getSection($role)
    {
        $section_model = new Section();
        //超级管理员或项目管理处用户
        if ($role == 1 || $role == 3) {
            $section_model = $section_model->where([]);
        }
        //监理用户
        if ($role == 4) {
            $supervision_id = $this->user->supervision->id;

            $section = DB::table('supervision_section')->select('section_id')
                ->where('supervision_id', $supervision_id)
                ->first();

            $section_id = $section->section_id;
            $section_model = $section_model->where('id', $section_id);
        }

        //合同段用户
        if ($role == 5) {
            $section_id = $this->user->section_id;

            $section_model = $section_model->where('id', $section_id);
        }

        $section_data = $section_model->select('id', 'name')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return $section_data;


    }


}
