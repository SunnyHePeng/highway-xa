<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Log,
    App\Models\User\Module,
    App\Models\User\User,    
    App\Models\Project\Project,
    App\Models\Project\Section;

use Input, DB;

class LogController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id desc';
    protected $list_view = 'admin.admin.log';
    protected $url = '';
    protected $type = [
                    'l'=>'登录',
                    'r'=>'注册',
                    'a'=>'新增',
                    'm'=>'修改',
                    'd'=>'删除',
                    'c'=>'审核',
                ];

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Log;
        $this->field = ['id','name','ip','addr','act','created_at'];
        $this->url = url('manage/log');
    }

     public function index(){
        $where = $search = [];
        $project_list = $factory_list = $material_list = $url_para = '';

        //集团用户可以查看所有日志，项目用户可以查看自己项目的日志
        if($this->user->role != 1 && $this->user->role != 2){
            $where[] = ['project_id', '=', $this->user->project[0]];
        }
        $keyword = str_replace('+', '', trim(Input::get('keyword')));
        if($keyword){
            $search['keyword'] = $keyword;
            $where[] = ['name', 'like', '%'.$keyword.'%'];
            $url_para = 'keyword='.$keyword;
        }

        $type = Input::get('type');
        if($type){
            $search['type'] = $type;
            $where[] = ['type', '=', $type];
            $url_para .= $url_para ? '&type='.$type : 'type='.$type;
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $where[] = ['created_at', '>=', strtotime($start_date)];
            $url_para .= $url_para ? '&start_date='.$start_date : 'start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $where[] = ['created_at', '<=', strtotime($end_date)+86400];
            $url_para .= $url_para ? '&end_date='.$end_date : 'end_data='.$end_date;
        }

        $this->order = isset($this->order) ? $this->order : 'id desc';
        $d = Input::get('d');
        if($d == 'all'){
            $list['data'] = $this->model->getList($this->model, $where, $this->field, $this->order, '');
        }else{
            $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);
        }
        $list['search'] = $search;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        if($d){
            return view('admin.admin.log_export', $list);
        }
        $list['type'] = $this->type;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        

        return view($this->list_view, $list);
    }

    /*
    *（2）合同段可以统计自己合同段不同负责模块信息化人员的登陆次数
    *（3）监理可以统计自己所辖合同段不同负责模块信息化人员的登陆次数
    *（4）项目公司用户应该增加不同合同段人员登陆次数统计  模块可以勾选  可以选择日期段 可以打印报表
    *（5）针对集团用户  统计各个项目公司用户登陆次数  及施工单位总次数就可以了
    */
    public function statLogin(){
        $url_para = '';
        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $url_para .= '?start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $url_para .= '&end_date='.$end_date;
        }
        //项目公司和施工单位登录次数
        if($this->user->role == 1 or $this->user->role == 2){
            $query = (new Project)->select(DB::raw('project.id,project.name,count(log.id) as num,user_project.project_id'));
            $query = $query->leftJoin('user_project', function($query){
                                $query->on('user_project.project_id', '=', 'project.id');
                            })
                            ->leftJoin('user', function($query){
                                $query->on('user_project.user_id', '=', 'user.id')
                                      ->where('user.role', '>', 2)
                                      ->where('user.status', '=', 1);
                            })  
                            ->leftJoin('log', function($query) use ($start_date, $end_date){
                                $query->on('user.id', '=', 'log.user_id')
                                      ->where('log.type', '=', 'l')
                                      ->where('log.created_at', '>=', strtotime($start_date))
                                      ->where('log.created_at', '<=', strtotime($end_date)+86400);
                            })
                            ->groupBy('project.id')
                            ->orderByRaw('project.id asc');
                            /*->paginate($this->ispage)
                            ->toArray();*/
        }else{
            $pro_id = $this->user->project[0];
            $sup_id = $sec_id = '';
            $query = User::select(DB::raw('user.supervision_id,user.section_id,user.role,user_project.project_id, count(log.id) as num, role.display_name'))
                            ->leftJoin('user_project', function($query) use($pro_id){
                                $query->on('user.id', '=', 'user_project.user_id')
                                      ->on('user_project.project_id', '=', DB::raw($pro_id));
                            });
            //不同合同段人员登陆次数统计  模块可以勾选
            if($this->user->role == 3){
                $role = 3;
            }
            //监理可以统计自己所辖合同段不同负责模块信息化人员的登陆次数
            if($this->user->role == 4){
                $role = 4;
                $sup_id = $this->user->supervision_id;
            }
            $sec_id = Input::get('sec_id') ? Input::get('sec_id') : '';
            if($sec_id){
                $search['sec_id'] = $sec_id;
                $url_para .= '&sec_id='.$sec_id;
            }
            $m_id = Input::get('m_id') ? Input::get('m_id') : '';
            if($m_id){
                $search['m_id'] = $m_id;
                $url_para .= '&m_id='.$m_id;
            }
            //合同段可以统计自己合同段不同负责模块信息化人员的登陆次数
            if($this->user->role == 5){
                $role = 5;
                $sup_id = $this->user->supervision_id;
                $sec_id = $this->user->section_id;
            }
            if($m_id){
                $query = $query ->leftJoin('user_module', function($query) use ($m_id){
                                    $query->on('user.id', '=', 'user_module.user_id')
                                          ->on('user_module.module_id', '=', DB::raw($m_id));
                                })
                                ->whereNotNull('user_module.module_id');
            }                          
            $query = $query ->leftJoin('log', function($query) use ($start_date, $end_date){
                                $query->on('user.id', '=', 'log.user_id')
                                      ->where('log.type', '=', 'l')
                                      ->where('log.created_at', '>=', strtotime($start_date))
                                      ->where('log.created_at', '<=', strtotime($end_date)+86400);
                            })
                            ->leftJoin('role', 'user.role', '=', 'role.id')
                            ->whereNotNull('user_project.project_id')
                            ->where('user.role', '>=', $role)
                            ->where('user.status', '=', 1);
            if($sup_id){
                $query = $query->where('user.supervision_id', '=', $sup_id);
            }
            if($sec_id){
                $query = $query->where('user.section_id', '=', $sec_id);
            }
            $query = $query ->with(['module'=>function($query) use($m_id){
                                if($m_id){
                                    $query->where('module_id', $m_id);
                                }  
                            },'supervision','section'])
                            ->groupBy(['user.role', 'user.section_id'])
                            ->orderByRaw('user.role asc');
                            /*->paginate($this->ispage)
                            ->toArray();*/
        }
        
        $d = Input::get('d');
        if($d == 'all'){
            $list['data'] = $query->get()->toArray();
        }else{
            $list = $query->paginate($this->ispage)->toArray();
        }
        //var_dump($list);
        $url = url('manage/stat_login');
        $list['search'] = $search;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        
        if($d){
            $list['module'] = $list['section'] = '';
            if(isset($m_id) && $m_id){
                $list['module'] = Module::where('id', $m_id)->pluck('name');
            }
            if(isset($sec_id) && $sec_id){
                $list['section'] = Section::where('id', $sec_id)->pluck('name');
            }
            return view('admin.admin.stat_login_export', $list);
        }

        $list['url'] = $url_para ? $url.'?'.$url_para : $url;
        $list['section'] = $this->user_section['section_list'];
        $list['module'] = Module::orderByRaw('sort desc, id asc')->get()->toArray();
        return view('admin.admin.stat_login', $list);
    }
}