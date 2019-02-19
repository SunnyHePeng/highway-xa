<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Beamfield;
use Input;

/**
 * 梁场（弃）
 * Class BeamfieldController
 * @package App\Http\Controllers\Admin
 */
class BeamfieldController extends MixplantController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.beamfield';
    protected $url = '';
    protected $cat_id = 2;
    protected $column = 'lc_num';
    protected $act_info = '梁场';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Beamfield;
        $this->field = ['id','name','tz_num','status','created_at'];
        $this->url = url('manage/beamfield');
    }

    public function index(){
    	return $this->mixplantIndex();
    }
}