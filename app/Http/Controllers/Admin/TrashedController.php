<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Soft\Channel, App\Models\Soft\Category, App\Models\Soft\Tag, App\Models\Soft\Soft;
use Redirect, Input, Validator;

class TrashedController extends BaseController
{
    protected $class;
    public function __construct()
    {
        $controller = Input::get('act');
        if($controller){
            if($controller == 'unedits'){
                $controller = 'game';
            }
            switch ($controller) {
                case 'device': $class = new DeviceController; break;
                case 'accessip': $class = new AccessipController; break;
                case 'app': $class = new AppController; break;
                default: return view('errors.admin_error',['error'=>'参数错误']); break;
            }
            $this->class = $class;
        }else{
            return view('errors.admin_error',['error'=>'参数错误']);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response category
     */
    public function index()
    {
    	return $this->class->index(1);
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        return $this->class->restore($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->class->delete($id);
    }
}
