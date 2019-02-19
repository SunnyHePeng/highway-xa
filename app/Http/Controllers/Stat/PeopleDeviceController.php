<?php

namespace App\Http\Controllers\Stat;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Mixplant\IndexController;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, Auth, DB, Cache,Log;
use App\Models\Sdtj\SdtjPeopleDevice\PeopleDevice;
use App\Models\Sdtj\SdtjPeopleDevice\PeopleDeviceMonthStatistics;
use Mockery\Exception;


/**
 * 进度统计中隧道人员设备统计类
 * Class PeopleDeviceController
 * @package App\Http\Controllers\Stat
 */
class PeopleDeviceController extends IndexController
{
   protected $peopleDeviceModel;
   protected $peopleDeviceMonthModel;
    public function __construct(PeopleDevice $peopleDevice,PeopleDeviceMonthStatistics $stattisics)
    {
        parent::__construct();
        view()->share(['module' => '进度统计']);
        $this->peopleDeviceModel=$peopleDevice;
        $this->peopleDeviceMonthModel=$stattisics;
    }

    /**
     *日统计
     */
    public function peopleDeviceDay()
    {

        $date=date('Y-m-d',time());
        $day_data=$this->peopleDeviceModel->where('date',$date)->first();

        return view('stat.peopleDevice.people_device_day',compact('day_data'));
    }

    /**
     * 月统计
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function peopleDeviceMonth(Request $request)
    {
        $search_month=$request->input('month');
        $search_year=$request->input('year');
        $query=$this->peopleDeviceMonthModel;
        $search=[];
        if($search_month){
              $query=$query->where('month',$search_month);
              $search['month']=$search_month;
        }
        if($search_year){
            $query=$query->where('year',$search_year);
            $search['year']=$search_year;
        }
        $month_data=$query->orderBy('id','desc')
                          ->get();
//        dd($month_data);
         return view('stat.peopleDevice.people_device_month',compact('month_data','search'));
    }

    /**
     * 添加日统计数据
     */
    public function peopleDeviceAdd(Request $request)
    {

       if($request->isMethod('get')){
           $date=date('Y-m-d',time());
           $res=$this->peopleDeviceModel->select('id')->where('date',$date)->first();
           if(!$res){
               return view('stat.peopleDevice.people_device_day_add');
           }else{
               $info='今日数据已经录入，无法重复录入';
               return view('admin.error.iframe_no_info',compact('info'));
           }


       }

       if($request->isMethod('post')){
           $input_data=$request->all();
           if($input_data['l_people_13']==''){
               $error_result['status']=1;
               $error_result['info']='13标左洞人员数不能为空，如没有请输入0';
               return $error_result;
           }
           if($input_data['r_people_13']==''){
               $error_result['status']=1;
               $error_result['info']='13标右洞人员数不能为空，如没有请输入0';
               return $error_result;
           }
           if($input_data['reinforcement_yard_13']==''){
               $error_result['status']=1;
               $error_result['info']='13标钢筋加工厂人员数不能为空，如没有请输入0';
               return $error_result;
           }

           if($input_data['roadbed_construction_13']==''){
               $error_result['status']=1;
               $error_result['info']='13标挖方段路基设备，不能为空，如没有请输入0或无';
               return $error_result;
           }
           if($input_data['l_people_14']==''){
               $error_result['status']=1;
               $error_result['info']='14标左洞人员数不能为空，如没有请输入0';
           }
           if($input_data['r_people_14']==''){
               $error_result['status']=1;
               $error_result['info']='14标右洞人员数不能为空，如没有请输入0';
           }
           if($input_data['reinforcement_yard_14']==''){
               $error_result['status']=1;
               $error_result['info']='14标钢筋加工厂人员数不能为空，如没有请输入0';
               return $error_result;
           }
           if($input_data['roadbed_construction_14']==''){
               $error_result['status']=1;
               $error_result['info']='14标挖方段路基设备，不能为空，如没有请输入0或无';
               return $error_result;
           }

           $input_data['date']=date('Y-m-d',time());
           $input_data['created_at']=time();

//           dd($input_data);


           try{
               //插入日统计数据
               $this->peopleDeviceModel->create($input_data);

//               //月统计数据
               $year=date('Y',time());
               $month=date('m',time());

               $month_data=$this->peopleDeviceMonthModel->where('month',$month)->where('year',$year)->first();

               if($month_data){
                   //更新数据
//                   13标数据
                   $month_data->l_people_month_13=$month_data->l_people_month_13+$input_data['l_people_13'];
                   $month_data->r_people_month_13=$month_data->r_people_month_13+$input_data['r_people_13'];

                   $month_data->l_construction_duration_month_13=$month_data->l_construction_duration_month_13+(int)$input_data['l_construction_duration_13'];
                   $month_data->r_construction_duration_month_13=$month_data->r_construction_duration_month_13+(int)$input_data['r_construction_duration_13'];

                   $month_data->reinforcement_yard_month_13=$month_data->reinforcement_yard_month_13+(int)$input_data['reinforcement_yard_13'];
                   $month_data->reinforcement_yard_construction_duration_month_13=$month_data->reinforcement_yard_construction_duration_month_13+(int)$input_data['reinforcement_yard_construction_duration_13'];
//                   14标数据
                   $month_data->l_people_month_14=$month_data->l_people_month_14+$input_data['l_people_14'];
                   $month_data->r_people_month_14=$month_data->r_people_month_14+$input_data['r_people_14'];

                   $month_data->l_construction_duration_month_14=$month_data->l_construction_duration_month_14+(int)$input_data['l_construction_duration_14'];
                   $month_data->r_construction_duration_month_14=$month_data->r_construction_duration_month_14+(int)$input_data['r_construction_duration_14'];

                   $month_data->reinforcement_yard_month_14=$month_data->reinforcement_yard_month_14+(int)$input_data['reinforcement_yard_14'];
                   $month_data->reinforcement_yard_construction_duration_month_14=$month_data->reinforcement_yard_construction_duration_month_14+(int)$input_data['reinforcement_yard_construction_duration_14'];
                   $month_data->number_of_days=$month_data->number_of_days+1;
                   $month_data->save();

               }else{
                   //插入月统计数据
                   $data=[
                       'l_people_month_13'=>$input_data['l_people_13'],
                       'r_people_month_13'=>$input_data['r_people_13'],
                       'l_construction_duration_month_13'=>$input_data['l_construction_duration_13'],
                       'r_construction_duration_month_13'=>$input_data['r_construction_duration_13'],

                       'reinforcement_yard_month_13'=>$input_data['reinforcement_yard_13'],
                       'reinforcement_yard_construction_duration_month_13'=>$input_data['reinforcement_yard_construction_duration_13'],


                       'l_people_month_14'=>$input_data['l_people_14'],
                       'r_people_month_14'=>$input_data['r_people_14'],
                       'l_construction_duration_month_14'=>$input_data['l_construction_duration_14'],
                       'r_construction_duration_month_14'=>$input_data['r_construction_duration_14'],

                       'reinforcement_yard_month_14'=>$input_data['reinforcement_yard_14'],
                       'reinforcement_yard_construction_duration_month_14'=>$input_data['reinforcement_yard_construction_duration_14'],

                       'number_of_days'=>1,
                       'month'=>$month,
                       'year'=>$year,
                   ];
                   $this->peopleDeviceMonthModel->create($data);

               }

               $result['status']=0;
               $result['info']='添加成功';
               return $result;
           }catch (Exception $e){

               $result['status']=0;
               $result['info']='添加成功';
               return $result;
           }

       }
    }

    /**
     * 修改日统计数据
     * @param Request $request
     */
    public function peopleDeviceEdit(Request $request)
    {
        //获取当天的数据信息
        $date=date('Y-m-d',time());

        $day_data=$this->peopleDeviceModel->where('date',$date)
                                          ->first();

//        dd($day_data);
        return view('stat.peopleDevice.people_device_day_edit',compact('day_data'));
    }

    /**
     * 更新日统计数据
     */
    public function peopleDeviceUpdate(Request $request)
    {
        $input_data=$request->all();

        if(!array_key_exists('id',$input_data)){
            $error_result['status']=1;
            $error_result['info']='发生未知错误，请联系管理员';
            return $error_result;
        }

        $id=$input_data['id'];
        if($input_data['l_people_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标左洞人员数不能为空，如没有请输入0';
            return $error_result;
        }
        if($input_data['l_construction_duration_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标左洞施工时长不能为空，如没有请输入0';
            return $error_result;
        }

        if($input_data['reinforcement_yard_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标钢筋加工厂人数不能为空，如没有请输入0';
            return $error_result;
        }

        if($input_data['reinforcement_yard_construction_duration_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标钢筋加工厂施工时长不能为空，如没有请输入0';
            return $error_result;
        }

        if($input_data['r_people_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标右洞人员数不能为空，如没有请输入0';
            return $error_result;
        }
        if($input_data['r_construction_duration_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标右洞施工时长，如没有请输入0';
            return $error_result;
        }
        if($input_data['roadbed_construction_13']==''){
            $error_result['status']=1;
            $error_result['info']='13标路基施工情况，不能为空，如没有请输入0或无';
            return $error_result;
        }

        if($input_data['l_people_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标左洞人员数不能为空，如没有请输入0';
        }
        if($input_data['l_construction_duration_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标左洞施工时长，如没有请输入0';
        }

        if($input_data['r_people_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标右洞人员数不能为空，如没有请输入0';
        }
        if($input_data['r_people_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标右洞人员数不能为空，如没有请输入0';
        }
        if($input_data['r_construction_duration_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标右洞施工时长不能为空，如没有请输入0';
            return $error_result;
        }
        if($input_data['reinforcement_yard_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标钢筋加工厂人数不能为空，如没有请输入0';
        }
        if($input_data['reinforcement_yard_construction_duration_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标钢筋加工厂施工时长不能为空，如没有请输入0';
            return $error_result;
        }

        if($input_data['roadbed_construction_14']==''){
            $error_result['status']=1;
            $error_result['info']='14标路基施工情况，不能为空，如没有请输入0或无';
            return $error_result;
        }

        $input_data['updated_at']=time();

        unset($input_data['id']);
        unset($input_data['_token']);

        //更新月统计数据
        $month=date('m',time());
        $year=date('Y',time());

        //获取已经填写的日统计数据
        $old_day_data=$this->peopleDeviceModel->where('id',$id)
                                              ->first();

        //获取当月的统计数据
        $month_data=$this->peopleDeviceMonthModel->where('month',$month)
                                                 ->where('year',$year)
                                                 ->first();

//        dd($month_data);


        try{
            //更新日统计数据
            $this->peopleDeviceModel->where('id',$id)
                                    ->update($input_data);
            //更新月统计数据
            //13标左洞月数据
            $month_data->l_people_month_13=$month_data->l_people_month_13-$old_day_data->l_people_13+(int)$input_data['l_people_13'];
            $month_data->l_construction_duration_month_13=$month_data->l_construction_duration_month_13-$old_day_data->l_construction_duration_13+(int)$input_data['l_construction_duration_13'];

            //13标右洞月数据
            $month_data->r_people_month_13=$month_data->r_people_month_13-$old_day_data->r_people_13+$input_data['r_people_13'];
            $month_data->r_construction_duration_month_13=$month_data->r_construction_duration_month_13-$old_day_data->r_construction_duration_13+$input_data['r_construction_duration_13'];

            //13标钢筋加工厂月数据
            $month_data->reinforcement_yard_month_13=$month_data->reinforcement_yard_month_13-$old_day_data->reinforcement_yard_13+$input_data['reinforcement_yard_13'];
            $month_data->reinforcement_yard_construction_duration_month_13=$month_data->reinforcement_yard_construction_duration_month_13-$old_day_data->reinforcement_yard_construction_duration_13+$input_data['reinforcement_yard_construction_duration_13'];

            //14标左洞月数据
            $month_data->l_people_month_14=$month_data->l_people_month_14-$old_day_data->l_people_14+$input_data['l_people_14'];
            $month_data->l_construction_duration_month_14=$month_data->l_construction_duration_month_14-$old_day_data->l_construction_duration_14+$input_data['l_construction_duration_14'];

            //14标右洞月数据
            $month_data->r_people_month_14=$month_data->r_people_month_14-$old_day_data->r_people_14+$input_data['r_people_14'];
            $month_data->r_construction_duration_month_14=$month_data->r_construction_duration_month_14-$old_day_data->r_construction_duration_14+$input_data['r_construction_duration_14'];

            //14标钢筋加工厂月数据
            $month_data->reinforcement_yard_month_14=$month_data->reinforcement_yard_month_14-$old_day_data->reinforcement_yard_14+$input_data['reinforcement_yard_14'];
            $month_data->reinforcement_yard_construction_duration_month_14=$month_data->reinforcement_yard_construction_duration_month_14-$old_day_data->reinforcement_yard_construction_duration_14+$input_data['reinforcement_yard_construction_duration_14'];
//                Log::info($month_data);
            $month_data->save();


            $result['status']=0;
            $result['info']='数据更新成功';
             return $result;

        }catch (Exception $e){
            $result['status']=1;
            $result['info']='数据修改失败';
            return $result;

        }

    }



}
