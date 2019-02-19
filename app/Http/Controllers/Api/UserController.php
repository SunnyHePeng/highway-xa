<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Project\Project;
use App\Models\Project\Section;
use App\Models\User\Log;
use Input,DB;

class UserController extends BaseController
{
    protected $request;
    protected $model;
    public function __construct()
    {
        parent::__construct();
        $this->request = new Request;
        $this->model = new User;
    }

    /*获取所有的用户*/
    public function getUserAll()
   {
//        //安全验证
//        $code=$this->verify();
//        if($code==1){
//            //验证成功,获取数据
//            $data=$this->getUserMess();
//            $res['status']=1;
//            $res['mess']='success';
//            $res['data']=$data;
//
//            return json_encode($res);
//
//        }else{
//            //验证失败
//            $list['status']=0;
//            $list['mess']= 'Unauthorized';
//            return json_encode($list);
//        }
        if(Input::get('id')){
           $res['status']=0;
           $res['mess']='请求不正确';
           return json_encode($res);
        }
        $data=$this->getUserMess();
        $res['status']=1;
        $res['mess']='success';
        $res['data']=$data;

        return json_encode($res);

    }

   /*获取单个用户信息*/
   public function getOneUser()
   {

//       //安全验证
//        $code=$this->verify();
//        if($code==1){
//            //验证成功,获取数据
//       $id=Input::get('id');
//       if(!$id){
//           $res['status']=0;
//           $res['mess']='请求参数不正确';
//           return json_encode($res);
//       }else{
//           $data=$this->getUserMess();
//           if(!$data){
//               $res['status']=0;
//               $res['mess']='该用户信息不存在';
//               return json_encode($res);
//           }
//           $res['status']=1;
//           $res['mess']='success';
//           $res['data']=$data;
//
//           return json_encode($res);
//         }
//
//        }else{
//            //验证失败
//            $list['status']=0;
//            $list['mess']= 'Unauthorized';
//            return json_encode($list);
//        }
       $id=Input::get('id');
       if(!$id){
           $res['status']=0;
           $res['mess']='请求参数不正确';
           return json_encode($res);
       }else{
           $data=$this->getUserMess();
           if(!$data){
               $res['status']=0;
               $res['mess']='该用户信息不存在';
               return json_encode($res);
           }
           $res['status']=1;
           $res['mess']='success';
           $res['data']=$data;

           return json_encode($res);
       }


   }
   /*获取项目信息*/

   public function getProject()
   {
//       安全验证
//       $code=$this->verify();
//       if($code==1){
//           //验证成功
//           $res['status']=1;
//           $res['mess']='获取成功';
//           $res['data']=$this->project();
//           return json_encode($res);
//       }else{
//           //验证失败
//           $res['status']=0;
//           $res['mess']='认证失败';
//           return json_encode($res);
//       }
       $res['status']=1;
       $res['mess']='获取成功';
       $res['data']=$this->project();
       return json_encode($res);

   }

   /*获取标段信息*/
   public function getSection()
   {
       //安全验证
//       $code=$this->verify();
//       if($code==1){
//           //验证成功
//           $res['status']=1;
//           $res['mess']='获取成功';
//           $res['data']=$this->section();
//
//           return json_encode($res);
//
//       }else{
//           //验证失败
//           $res['status']=0;
//           $res['mess']='认证失败';
//
//           return json_encode($res);
//       }

       $res['status']=1;
       $res['mess']='获取成功';
       $res['data']=$this->section();

       return json_encode($res);


   }

   /*获取登陆状态*/

   public function getLoginStatus()
   {
       //安全验证
       $code=$this->verify();
       if($code==1){
          //验证成功
          $res=$this->login();
          return json_encode($res);


       }else{
           //验证失败
           $res['status']=0;
           $res['mess']='认证失败';

           return json_encode($res);

       }
   }



   /*安全验证*/
   protected function verify()
   {
       $ip=Config()->get('common.getuser_ip');
//       dd($ip);
       //获取请求ip
       $req_ip=$_SERVER['REMOTE_ADDR'];
       if($req_ip == $ip){
          return 1;
       }else{
           return 0;
       }
   }
/*获取用户数据*/
   protected function getUserMess()
 {
      $field='user.id as user_id,user.username,user.name,
              user.project_id,project.name as project_name,user.role as role_id,roles.display_name as role_name,
              user.supervision_id,supervision.name as supervision_name,
              user.section_id,section.name as section_name,user.phone,user.IDNumber,user.has_sh';

      $id=Input::get('id');
      if($id){
          $this->model=$this->model->where('user.id',$id);
      }

        $data=$this->model
              ->select(DB::raw($field))
              ->orderBy('user_id','asc')
              ->leftJoin('project','user.project_id','=','project.id')
              ->leftJoin('role as roles','user.role','=','roles.id')
              ->leftJoin('supervision','user.supervision_id','=','supervision.id')
              ->leftJoin('section','user.section_id','=','section.id')
              ->where('user.status','1')
              ->get()
              ->toArray();

    return $data;
 }

/*项目信息*/
   protected function project()
  {
    $field=[
        'id',
        'name',
        'mileage',
        'section_num',
        'supervision_num'
    ];
    $model=new Project();

    $data=$model->select($field)
                ->get()
                ->toArray();
    return $data;
   }

/*标段信息*/

   protected function section()
    {
    $field=[
        'id',
        'project_id',
        'name',
        'begin_position',
        'end_position',
        'cbs_name',
        'fzr',
        'phone'
    ];

    $model= new Section();

    $data=$model->select($field)
                ->get()
                ->toArray();
    return $data;
   }

/*用户是否登陆*/
  protected function login()
  {
      $id=Input::get('id');
      if(!$id){
          $res['status']=0;
          $res['mess']='请求参数不正确';
          return $res;
      }
      //用户是否存在
      $user=$this->getPeople($id);
      if(!$user){
          $res['status']=0;
          $res['mess']='该用户信息不存在';
          return $res;
      }
      $model= new Log();
      $time=time()-14400;
//      return $time;
      $data=$model->select('id','user_id','name','created_at')
                  ->where('user_id',$id)
                  ->where('created_at','>',$time)
                  ->first();
      if($data){
          $res['status']=1;
          $res['mess']='该用户已登陆';
          return $res;
        }else{
          $res['status']=2;
          $res['mess']='该用户未登陆';
          return $res;
        }

  }

  /*用户信息*/
  protected function getPeople($id)
  {
      $data=$this->model
             ->select('id','username','name')
             ->where('id',$id)
             ->first();
      return $data;


  }



}