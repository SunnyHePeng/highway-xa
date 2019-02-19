<?php

namespace App\Http\Controllers\Notice;

use App\Models\Notice\NoticeApprovalUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Input, Auth, DB, Cache,Log;
use App\Models\Notice\NoticeInfo;
use App\Models\Notice\NoticeInfoUserRecord;
use Mockery\Exception;
use App\Send\SendWechat;

/**
 * 通知公告
 * Class NoticeController
 * @package App\Http\Controllers\Notice
 */
class NoticeController extends BaseController
{
    protected $ispage = 10;
    protected $module = 4;
    protected $noticeInfoModel;
    public function __construct(NoticeInfo $noticeInfo)
    {
        parent::__construct();
        view()->share(['module' => '重要通知公告']);
        $this->noticeInfoModel=$noticeInfo;
    }


    /**
     * 公告列表
     */
    public function noticeList(Request $request,NoticeInfo $noticeInfo)
    {
        $field=[
            'id',
            'publish_time',
            'publish_user_id',
            'title',
            'content',
            'read_number',
            'download_number'
        ];
        $url=url('notice/index');

        $notice_data=$this->getNoticeList($request,$noticeInfo,$field,$url);

        return view('notice.notice_list',$notice_data);
    }

    /**
     * 我的发布
     */
    public function myPublish(NoticeInfo $noticeInfo,Request $request)
    {
        $role=$this->user->role;
        //超级管理员或项目管理处用户
        if($role==1 || $role==3){
          $field=[
              'id',
              'publish_time',
              'title',
              'content',
              'read_number',
              'download_number',
              'status',
          ];
            $url=url('notice/my_publish');
          $my_notice_data=$this->getMyNotice($request,$noticeInfo,$field,$url);

          return view('notice.my_publish',$my_notice_data);
        }else{

            return view('notice.error');
        }

    }

    /**
     * 发布新公告
     */
    public function publishNewNotice(Request $request,NoticeInfo $noticeInfo)
    {

       if($request->isMethod('get')){

           $user_id=$this->user->id;

           return view('notice.publish_new_notice',compact('user_id'));

       }


       if($request->isMethod('post')){
           $input_data=$request->all();
           if($input_data['publish_user_id']==''){
               $result['status']=1;
               $result['info']='发布公告出错';
               return $result;
           }

           if($input_data['title']==''){
               $result['status']=1;
               $result['info']='请输入公告标题';
               return $result;
           }

           $data=[
               'publish_user_id'=>$input_data['publish_user_id'],
               'title'=>isset($input_data['title']) ? $input_data['title'] : '',
               'content'=>isset($input_data['content']) ? $input_data['content'] : '',
               'accessory_addr'=>isset($input_data['file']) ? $input_data['file'] : '',
               'publish_time'=>time(),
               'status'=>0,
               'created_at'=>time(),
           ];

           $user=User::find($input_data['publish_user_id']);
           $name=$user->name;
           $time=date('Y-m-d H:i:s',time());
           try{
               $noticeInfo::create($data);
               /*公告审核微信推送提醒*/
               $info=[
                   'first'=>'审核提醒',
                   'url'=>url('notice/notice_approval_and_edit'),
                   'word1'=>'重要通知公告审核',
                   'word2'=>$name,
                   'remark'=>'请您尽快审核，谢谢。'.$time
               ];
               /*获取需要推送的人员信息*/
               $NoticeApprovalUserModel=new NoticeApprovalUser();

               $push_user_data=$NoticeApprovalUserModel->with('user')->get();

//               dd($push_user_data);
                foreach($push_user_data as $v){
                    $res=(new SendWechat)->sendApprovalNotice($v->user->openid,$info);
                    Log::info('sendPushApprovalNotice start');
                    Log::info($v->user->opendid);
                    Log::info($res);
                    Log::info('sendPushApprovalNotice end');
                }

               $result['status']=0;
               $result['info']='发布公告成功';
               return $result;
           }catch (\Exception $e){
               $result['status']=1;
               $result['info']='出现未知错误，发布公告失败';
               Log::info($e);
               Log::info($input_data);
               return $result;
           }



       }
    }

    /**
     * 查看公告详细信息
     * @param Request $request
     * @param NoticeInfo $noticeInfo
     * @param $notice_id
     */
    public function noticeDetails(Request $request,NoticeInfo $noticeInfo,$notice_id,NoticeInfoUserRecord $infoUserRecord)
    {
       $notice=$noticeInfo::find($notice_id);
//       dd($notice);


       /*记录查看这条公告的人员信息和查看时间*/
       $user_id=$this->user->id;
       $type='s';
       try{
          $infoUserRecord->createRecord($type,$user_id,$notice_id);
          //这条公告的阅读次数增加
          $notice->increment('read_number',1);

       }catch (\Exception $e){

          return view('errors.503');
       }

       return view('notice.notice_details',compact('notice'));
    }

    /**
     * 查看我发布的公告的详细信息
     * @param $id
     * @param NoticeInfo $noticeInfo
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string
     */
    public function myPublishNoticeDetails($id,NoticeInfo $noticeInfo)
    {
        $role=$this->user->role;

        if($role==1 || $role==3){

            $notice=$noticeInfo::find($id);

             return view('notice.my_publish_notice_details',compact('notice'));
        }else{
           return '您没有发布公告和管理公告的权限';
        }
    }

    /**
     * 编辑公告信息
     */
    public function noticeEdit(Request $request,NoticeInfo $noticeInfo)
    {
       if($request->isMethod('get')){

         $id=$request->get('id');
         $notice=$noticeInfo::find($id);

         return view('notice.my_notice_edit',compact('notice'));
       }


       if($request->isMethod('post')){

           $input_data=$request->all();

           if($input_data['title']==''){
                  $result['status']=1;
                  $result['info']='请输入公告标题';
                  return Response()->json($result);
           }


           $id=$input_data['id'];
           $notice_data=$noticeInfo::find($id);
           $old_file_path=$notice_data->accessory_addr;
           $title=$input_data['title'];
           $content=$input_data['content'];

           if(array_key_exists('file',$input_data)){
              $data=[
                  'title'=>$title,
                  'content'=>$content,
                  'accessory_addr'=>$input_data['file'],
                  'updated_at'=>time(),
              ];
           }else{
               $data=[
                   'title'=>$title,
                   'content'=>$content,
                   'updated_at'=>time(),
               ];
           }

           try{
               $noticeInfo->where('id',$id)
                          ->update($data);

               $result['status']=0;
               $result['info']='修改成功';
               return Response()->json($result);

           }catch (\Exception $e){
               Log::info($data);
               $result['status']=1;
               $result['info']='发生未知错误,修改失败';
               return Response()->json($result);
           }
       }
    }

    /**
     * 下载附件
     */
    public function downloadAccessory($id,NoticeInfo $noticeInfo,NoticeInfoUserRecord $infoUserRecord)
    {
       $notice=$noticeInfo::find($id);

        //这条公告的下载次数增加
//        $notice->increment('download_number');

       $user_id=$this->user->id;
       $notice_info_id=$id;
       $type='d';
       //记录下载这条公告附件的人员信息及下载时间
       try{

           $infoUserRecord->createRecord($type,$user_id,$notice_info_id);


       } catch (\Exception $e){

           return view('errors.503');
       }


       $extend_name=strstr($notice->accessory_addr,'.',false);


       $file_name=$notice->title.'附件';

       $path='uploads/'.$notice->accessory_addr;

       $header=['Content-type'=>'application/octet-stream'];

       return response()->download(public_path($path),$file_name.$extend_name,$header);

    }

    /**
     * 下载次数增加
     * @param $id
     * @param NoticeInfo $noticeInfo
     */
    public function downloadNumberAugment($id,NoticeInfo $noticeInfo)
    {
        $notice=$noticeInfo::find($id);

        try{
            $notice->increment('download_number');
            $result['status']=0;
            $result['info']='下载次数记录成功';
            return $result;
        }catch (Exception $e){
            Log::info('notice accessory download error');
            $result['status']=0;
            $result['info']='下载次数记录失败';
            return $result;
        }

    }


    /**
     * 公告查看和下载情况
     * @param Request $request
     * @param NoticeInfo $noticeInfo
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function readDownloadCondition(Request $request,NoticeInfo $noticeInfo)
    {
         $role=$this->user->role;
        $field=[
            'id',
            'publish_time',
            'publish_user_id',
            'title',
            'content',
            'read_number',
            'download_number'
        ];
        $url=url('notice/read_download_condition');


         if($role==1 || $role==3){

             $notice_data=$this->getNoticeList($request,$noticeInfo,$field,$url);
//              dd($notice_data);
            return view('notice.notice_read_download_condition',$notice_data);

         }else{

             return view('notice.error_read_download_condition');
         }
    }


    /**
     *公告审核及修改
     * 该功能为处长专用(这个操作是不是很666)
     */
    public function noticeApprovalAndEdit()
    {

        $url=url('notice/notice_approval_and_edit');
        $role=$this->user->role;

        $user_position=$this->user->position_id;
//        dd($user_position);

        if($role==1 || $user_position==58){

            $notice=$this->noticeInfoModel->with('publish_user')
                                        ->orderBy('id','desc')
                                        ->paginate(5)
                                        ->toArray();

            $notice['url']=$url;

//        dd($notice_data);

            return view('notice.notice_approval_and_edit',$notice);
        }else{
            $info='您没有公告审核及修改权限';
            return view('admin.error.no_info',compact('info'));
        }

    }

    /**
     * 审核同意发布公告
     */
    public function approvalPublish(Request $request)
    {
       $id=$request->get('id');

       $approval_user_id=$this->user->id;

       if(!$id){
           $result['status']=1;
           $result['info']='出现未知错误，请联系管理员';
           return Response()->json($result);
       }

       $approvalNotice=$this->noticeInfoModel->find($id);

       try{
           $approvalNotice->status=1;
           $approvalNotice->approval_user_id=$approval_user_id;
           $approvalNotice->approval_time=time();
           $approvalNotice->save();
           $result['status']=0;
           $result['info']='审核成功';

           return Response()->json($result);

       }catch (\Exception $e){

           $result['status']=1;
           $result['info']='审核失败,请重试或联系管理员';
           return Response()->json($result);
       }
    }

    /**
     * 公告审核微信推送人员
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string
     */
    public function noticeApprovalUserSet()
    {
        $role = Auth::user()->role;
        if ($role==3 || $role == 4 || $role == 5) {
            return '您没有相关权限';
        }


        $model = new NoticeApprovalUser();

        $data = $model->select('id', 'user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'company_id', 'username', 'phone')
                    ->with('company');
            }])
            ->get()
            ->toArray();

//        dd($data);

        return view('notice.notice_approval_user_set',compact('data'));
    }

    /**
     * 添加公告审核微信推送人员
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function addUser(Request $request)
    {
      if($request->isMethod('get')){
          $model = new User();
          $role_id = [1, 3];
          $status = 1;
          $field = ['id', 'role', 'company_id', 'name', 'position_id'];
          $user = $model->select($field)
              ->whereBetween('role', $role_id)
              ->where('status', $status)
              ->with('company')
              ->with('posi')
              ->get()
              ->toArray();
//          dd($user);

          return view('notice.add_user',compact('user'));
      }

      if($request->isMethod('post')){
          $user_model = new NoticeApprovalUser();
          $userData = $request->all();
//       $userData['user_id']=(int)$userData['user_id'];
//       dd($userData);
          if ($userData['user_id'] == 0) {
              $list['status'] = 0;
              $list['mess'] = '请选择人员';
              return $list;
          }
          if ($user_model->select('user_id')->where('user_id', $userData['user_id'])->first()) {
              $list['status'] = 0;
              $list['mess'] = '该人员已经添加过，请勿重复添加';
              return $list;
          }


          try {
              $user_model::create($userData);
              $list['status'] = 1;
              $list['mess'] = '添加成功';
              return $list;
          } catch (Exception $e) {
              $list['status'] = 0;
              $list['mess'] = '添加出错';
              return $list;
          }

      }
    }


    /**
     * 删除公告审核微信推送人员
     */
    public function delUser(Request $request)
    {
        $role=Auth::user()->role;

        if ($role != 1){
            $list['status'] = 0;
            $list['mess'] = '用户没有权限';
            return $list;
        }

        $id = $request->get('id');

        $model = new NoticeApprovalUser();

        try {
            $model->where('id', $id)->delete();
            $list['status'] = 1;
            $list['mess'] = '删除成功';
            return $list;
        } catch (Exception $e) {
            $list['status'] = 0;
            $list['mess'] = '删除出错';
        }

    }

    /**
     * 查看已阅读人员信息
     */
    public function getAlreadyReadUser($id,NoticeInfoUserRecord $noticeInfoUserRecord)
    {

        $type='s';
        $notice_info_id=$id;


        $data=$noticeInfoUserRecord->where('type',$type)
                                   ->where('notice_info_id',$notice_info_id)
                                   ->with('user')
                                   ->get()
                                   ->toArray();

//        dd($data);
        return view('notice.already_read_user',compact('data'));
    }



    /**
     * 查看已下载附件人员信息
     * @param $id
     * @param NoticeInfoUserRecord $noticeInfoUserRecord
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */

    public function getAlreadyDownloadUser($id,NoticeInfoUserRecord $noticeInfoUserRecord)
    {

        $type='d';
        $notice_info_id=$id;


        $data=$noticeInfoUserRecord->where('type',$type)
            ->where('notice_info_id',$notice_info_id)
            ->with('user')
            ->get()
            ->toArray();

//        dd($data);
        return view('notice.already_download_user',compact('data'));
    }

    /**
     * 获取最新的一条公告信息
     *
     */
    public function getNewNotice(NoticeInfo $noticeInfo,NoticeInfoUserRecord $noticeInfoUserRecord)
    {
        $user_id=$this->user->id;

       $notice=$noticeInfo->orderBy('id','desc')
                          ->where('status',1)
                          ->first();

       if($notice){
           $id=$notice->id;
           $read=$noticeInfoUserRecord->where('notice_info_id',$id)
               ->where('user_id',$user_id)
               ->where('type','s')
               ->first();
           if($read){
               //已经阅读
               $is_read=0;
           }else{
               //还没有阅读这条公告
               $is_read=1;
           }


           $title=$notice->title;

           $result['status']=0;
           $result['is_read']=$is_read;
           $result['title']=$title;

           return $result;

       }else{

           $result=[];
           $result['status']=0;
           $result['is_read']=0;
           $result['title']='暂时还没有公告信息';

           return $result;
       }

    }

    /**
     * 获取我发布过的公告信息
     */
    protected function getMyNotice($request,$noticeInfoModel,$field,$url)
    {
        $user_id=$this->user->id;

        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');

        $title_antistop=$request->get('title_antistop');

        $search=[];

        if($start_date){
            $start_time=strtotime($start_date);

            $url=$url.'?start_date='.date('Y-m-d',$start_time);

            $search['start_date']=trim($start_date);
        }else{

            $start_time=strtotime(date('Y-m-d',time()-30*86400));

            $url=$url.'?start_date='.date('Y-m-d',$start_time);

            $search['start_date']=trim(date('Y-m-d',$start_time));
        }

        if($end_date){

            $end_time=strtotime($end_date)+86400;

            $url=$url.'&end_time='.date('Y-m-d',$end_time+86400);

            $search['end_date']=trim($end_date);
        }else{

            $end_time=time()+86400;

            $url=$url.'&end_time='.date('Y-m-d',time());

            $search['end_date']=trim(date('Y-m-d',time()));
        }


        if(isset($title_antistop)){
            $noticeInfoModel=$noticeInfoModel->where('title','like','%'.$title_antistop.'%');

            $url=$url.'&title_antistop='.$title_antistop;

            $search['title_antistop']=trim($title_antistop);
        }else{

            $search['title_antistop']='';
        }

        $noticeInfoModel=$noticeInfoModel->whereBetween('publish_time',[$start_time,$end_time]);


        $data=$noticeInfoModel->select($field)
                              ->where('publish_user_id',$user_id)
                              ->orderBy('publish_time','desc')
                              ->paginate($this->ispage)
                              ->toArray();
        $data['url']=$url;
        $data['search']=$search;

        return $data;


    }

    /**
     * 获取公告列表
     */
    protected function getNoticeList($request,$notice_model,$field,$url)
    {
       $start_date=$request->get('start_date');
       $end_date=$request->get('end_date');

       $title_antistop=$request->get('title_antistop');

       $search=[];

       if($start_date){
           $start_time=strtotime($start_date);

           $url=$url.'?start_date='.date('Y-m-d',$start_time);

           $search['start_date']=trim($start_date);
       }else{

           $start_time=strtotime(date('Y-m-d',time()-30*86400));

           $url=$url.'?start_date='.date('Y-m-d',$start_time);

           $search['start_date']=trim(date('Y-m-d',$start_time));
       }

       if($end_date){

           $end_time=strtotime($end_date)+86400;

           $url=$url.'&end_time='.date('Y-m-d',$end_time+86400);

           $search['end_date']=trim($end_date);
       }else{

           $end_time=time()+86400;

           $url=$url.'&end_time='.date('Y-m-d',time());

           $search['end_date']=trim(date('Y-m-d',time()));
       }


       if(isset($title_antistop)){
           $notice_model=$notice_model->where('title','like','%'.$title_antistop.'%');

           $url=$url.'&title_antistop='.$title_antistop;

           $search['title_antistop']=trim($title_antistop);
       }else{

           $search['title_antistop']='';
       }

       $notice_model=$notice_model->whereBetween('publish_time',[$start_time,$end_time]);

        $data=$notice_model->select($field)
                           ->where('status',1)
                           ->with('publish_user')
                           ->orderBy('publish_time','desc')
                           ->paginate($this->ispage)
                           ->toArray();

        $data['url']=$url;
        $data['search']=$search;

//        dd($data);

        return $data;
    }









}
