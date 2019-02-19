<?php

namespace App\Http\Controllers\Remove;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\IndexController;
use App\Models\Project\Section;
use DB;

/**
 * 征地拆迁RemoveController父类
 */
class BaseController extends IndexController
{

    /**
     * 获取监理信息(除去试验检测中心)
     * @param $supervision_model
     * @return mixed
     */
   protected function getSupervision($supervision_model)
   {
       $supervision_data=$supervision_model->select('id','name')
                                           ->whereNotIn('id',[6])
                                           ->get();

       return $supervision_data;
   }

    /**
     * 监理或合同段用户获取日统计数据
     * @param $model
     * @param $role
     * @param $field
     * @param $page
     * @param $url
     * @return mixed
     */
   protected function getRemoveDayDataToSec($model,$role,$field,$page,$url)
   {
       //监理
       if ($role == 4) {
           $supervision_id = $this->user->supervision->id;

           $model = $model->where('supervision_id', $supervision_id);
       }

       if ($role == 5) {
           $section_id = $this->user->section->id;
           $model = $model->where('section_id', $section_id);
       }

       $remove_day_data=$model->select($field)
                              ->with('section')
                              ->orderBy('id','desc')
                              ->paginate($page)
                              ->toArray();
       $remove_day_data['url']=$url;

       return $remove_day_data;

   }

    /**
     * 超级管理员或项目管理处用户获取日统计数据
     * @param $total_model
     * @param $day_model
     * @return array
     */
    protected function getRemoveDayDataToPro($total_model, $day_model)
    {
        $start_time = strtotime(date('Y-m-d', time()));
        $end_time = strtotime(date('Y-m-d', time() + 86400));


        $total_data = $total_model->with('section')
                                  ->orderBy('id','asc')
                                  ->get()
                                  ->keyBy('section_id')
                                  ->toArray();

        $day_data = $day_model->whereBetween('time', [$start_time, $end_time])
            ->get()
            ->keyBy('section_id')
            ->toArray();

        $num_field = 'sum(occupation_day) as occupation_sum,sum(house_day) as house_sum,sum(pylon_day) as pylon_sum,
       sum(parallels_day) as parallels_sum,sum(optical_cable_day) as optical_cable_sum,sum(water_pipe_day) as water_pipe_sum,
       sum(natural_gas_pipeline_day) as natural_gas_pipeline_sum,sum(special_remove_day) as special_remove_sum,section_id';

        $finish_data = $day_model->select(DB::raw($num_field))
            ->groupBy('section_id')
            ->get()
            ->keyBy('section_id')
            ->toArray();
//       dd($finish_data);

//       $finish_data=$day_model->

        $data = [];
        $m = [];

        $gaoxin_data = [];
        $changan_data = [];
        $lantian_data = [];

        $amount=[];

        //总量的合计数据
        //所有标段用地量合计
        $amount['occupation_total_amount'] = 0;
        //所有标段拆迁房屋合计
        $amount['house_total_amount'] = 0;
        //所有标段铁塔总计
        $amount['pylon_total_amount'] = 0;
        //所有标段双杆合计
        $amount['parallels_total_amount'] = 0;
        //所有标段地埋电缆合计
        $amount['optical_cable_total_amount'] = 0;
        //所有标段输水管道合计
        $amount['water_pipe_total_amount'] = 0;
        //所有标段天然气管道合计
        $amount['natural_gas_pipeline_total_amount'] = 0;
        //所有的标段特殊拆除物的合计
        $amount['special_remove_total_amount'] = 0;


        //当日完成量的合计数据
        //所有标段当日交付用地合计
        $amount['occupation_day_amount'] = 0;
        //所有标段当日拆迁房屋合计
        $amount['house_day_amount'] = 0;
        //所有标段当日铁塔迁改总计
        $amount['pylon_day_amount'] = 0;
        //所有标段当日双杆迁改合计
        $amount['parallels_day_amount'] = 0;
        //所有标段当日地埋电缆迁改合计
        $amount['optical_cable_day_amount'] = 0;
        //所有标段当日输水管道合计
        $amount['water_pipe_day_amount'] = 0;
        //所有标段当日天然气管道合计
        $amount['natural_gas_pipeline_day_amount'] = 0;
        //所有标段当日特殊拆除物合计
        $amount['special_remove_day_amount'] = 0;

        //累计完成量合计数据
        //所有标段累计交付用地合计
        $amount['occupation_finish_amount'] = 0;
        //所有标段累计拆迁房屋合计
        $amount['house_finish_amount'] = 0;
        //所有标段累计迁改铁塔总计
        $amount['pylon_finish_amount'] = 0;
        //所有标段累计迁改双杆合计
        $amount['parallels_finish_amount'] = 0;
        //所有标段累计迁改地埋电缆合计
        $amount['optical_cable_finish_amount'] = 0;
        //所有标段累计迁改输水管道合计
        $amount['water_pipe_finish_amount'] = 0;
        //所有标段累计迁改天然气管道合计
        $amount['natural_gas_pipeline_finish_amount'] = 0;
        //所有标段累计拆除特殊拆除物合计
        $amount['special_remove_finish_amount'] = 0;



        //当日数据合计
        foreach ($day_data as $v) {
            $amount['occupation_day_amount'] += $v['occupation_day'];
            $amount['house_day_amount'] += $v['house_day'];
            $amount['pylon_day_amount'] += $v['pylon_day'];
            $amount['parallels_day_amount'] += $v['parallels_day'];
            $amount['optical_cable_day_amount'] += $v['optical_cable_day'];
            $amount['water_pipe_day_amount'] += $v['water_pipe_day'];
            $amount['natural_gas_pipeline_day_amount'] += $v['natural_gas_pipeline_day'];
            $amount['special_remove_day_amount'] += $v['special_remove_day'];
        }

        //累计完成数据合计
        foreach ($finish_data as $v) {
            $amount['occupation_finish_amount'] += $v['occupation_sum'];
            $amount['house_finish_amount'] += $v['house_sum'];
            $amount['pylon_finish_amount'] += $v['pylon_sum'];
            $amount['parallels_finish_amount'] += $v['parallels_sum'];
            $amount['optical_cable_finish_amount'] += $v['optical_cable_sum'];
            $amount['water_pipe_finish_amount'] += $v['water_pipe_sum'];
            $amount['natural_gas_pipeline_finish_amount'] += $v['natural_gas_pipeline_sum'];
            $amount['special_remove_finish_amount'] += $v['natural_gas_pipeline_sum'];
        }



        foreach ($total_data as $k => $v) {

            //合计数据
            $amount['occupation_total_amount'] += $v['occupation_total'];
            $amount['house_total_amount'] += $v['house_total'];
            $amount['pylon_total_amount'] += $v['pylon_total'];
            $amount['parallels_total_amount'] += $v['parallels_total'];
            $amount['optical_cable_total_amount'] += $v['optical_cable_total'];
            $amount['water_pipe_total_amount'] += $v['water_pipe_total'];
            $amount['natural_gas_pipeline_total_amount'] += $v['natural_gas_pipeline_total'];
            $amount['special_remove_total_amount'] += $v['special_remove_total'];

            if ($v['district_name'] == '高新区') {
                if (array_key_exists($k, $day_data)) {
                    $m = $v;
                    $m['now'] = $day_data[$k];

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }
                } else {

                    $m = $v;

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }

                }
                $gaoxin_data[] = $m;
            }

            if ($v['district_name'] == '长安区') {
                if (array_key_exists($k, $day_data)) {
                    $m = $v;
                    $m['now'] = $day_data[$k];

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }
                } else {

                    $m = $v;

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }

                }
                $changan_data[] = $m;
            }
            if ($v['district_name'] == '蓝田县') {
                if (array_key_exists($k, $day_data)) {
                    $m = $v;
                    $m['now'] = $day_data[$k];

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }
                } else {

                    $m = $v;

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }

                }
                $lantian_data[] = $m;
            }

        }
        $data['gaoxin_data'] = $gaoxin_data;
        $data['changan_data'] = $changan_data;
        $data['lantian_data'] = $lantian_data;
        $data['amount'] = $amount;
//       dd($data);

        return $data;
    }

    /**
     * 按时间范围统计
     * @param $request
     * @param $total_model
     * @param $day_model
     */
    protected function getRemoveStatData($request, $total_model, $day_model)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $start_time = $start_date ? strtotime($start_date) : strtotime(date('Y-m-d', time() - 7 * 86400));
        $end_time = $end_date ? strtotime($end_date) + 86400 : strtotime(date('Y-m-d', time() + 86400));

        $search['start_date']=date('Y-m-d',$start_time);
        $search['end_date']=date('Y-m-d',$end_time-86400);



        $total_data = $total_model->with('section')
                                  ->orderBy('id','asc')
                                  ->get()
                                  ->keyBy('section_id')
                                  ->toArray();



        $num_field = 'sum(occupation_day) as occupation_sum,sum(house_day) as house_sum,sum(pylon_day) as pylon_sum,
       sum(parallels_day) as parallels_sum,sum(optical_cable_day) as optical_cable_sum,sum(water_pipe_day) as water_pipe_sum,
       sum(natural_gas_pipeline_day) as natural_gas_pipeline_sum,sum(special_remove_day) as special_remove_sum,section_id';

        //时间范围内完成的数据
        $horizon_data = $day_model->select(DB::raw($num_field))
                                  ->whereBetween('time', [$start_time, $end_time])
                                  ->groupBy('section_id')
                                  ->get()
                                  ->keyBy('section_id')
                                  ->toArray();

        $finish_data = $day_model->select(DB::raw($num_field))
            ->groupBy('section_id')
            ->get()
            ->keyBy('section_id')
            ->toArray();


        $data = [];
        $m = [];

        $gaoxin_data = [];
        $changan_data = [];
        $lantian_data = [];

        $amount=[];

        //总量的合计数据
        //所有标段用地量合计
        $amount['occupation_total_amount'] = 0;
        //所有标段拆迁房屋合计
        $amount['house_total_amount'] = 0;
        //所有标段铁塔总计
        $amount['pylon_total_amount'] = 0;
        //所有标段双杆合计
        $amount['parallels_total_amount'] = 0;
        //所有标段地埋电缆合计
        $amount['optical_cable_total_amount'] = 0;
        //所有标段输水管道合计
        $amount['water_pipe_total_amount'] = 0;
        //所有标段天然气管道合计
        $amount['natural_gas_pipeline_total_amount'] = 0;
        //所有的标段特殊拆除物的合计
        $amount['special_remove_total_amount'] = 0;


        //当日完成量的合计数据
        //所有标段当日交付用地合计
        $amount['occupation_horizon_amount'] = 0;
        //所有标段当日拆迁房屋合计
        $amount['house_horizon_amount'] = 0;
        //所有标段当日铁塔迁改总计
        $amount['pylon_horizon_amount'] = 0;
        //所有标段当日双杆迁改合计
        $amount['parallels_horizon_amount'] = 0;
        //所有标段当日地埋电缆迁改合计
        $amount['optical_cable_horizon_amount'] = 0;
        //所有标段当日输水管道合计
        $amount['water_pipe_horizon_amount'] = 0;
        //所有标段当日天然气管道合计
        $amount['natural_gas_pipeline_horizon_amount'] = 0;
        //所有标段当日特殊拆除物合计
        $amount['special_remove_horizon_amount'] = 0;

        //累计完成量合计数据
        //所有标段累计交付用地合计
        $amount['occupation_finish_amount'] = 0;
        //所有标段累计拆迁房屋合计
        $amount['house_finish_amount'] = 0;
        //所有标段累计迁改铁塔总计
        $amount['pylon_finish_amount'] = 0;
        //所有标段累计迁改双杆合计
        $amount['parallels_finish_amount'] = 0;
        //所有标段累计迁改地埋电缆合计
        $amount['optical_cable_finish_amount'] = 0;
        //所有标段累计迁改输水管道合计
        $amount['water_pipe_finish_amount'] = 0;
        //所有标段累计迁改天然气管道合计
        $amount['natural_gas_pipeline_finish_amount'] = 0;
        //所有标段累计拆除特殊拆除物合计
        $amount['special_remove_finish_amount'] = 0;



        //当日数据合计
        foreach ($horizon_data as $v) {
            $amount['occupation_horizon_amount'] += $v['occupation_sum'];
            $amount['house_horizon_amount'] += $v['house_sum'];
            $amount['pylon_horizon_amount'] += $v['pylon_sum'];
            $amount['parallels_horizon_amount'] += $v['parallels_sum'];
            $amount['optical_cable_horizon_amount'] += $v['optical_cable_sum'];
            $amount['water_pipe_horizon_amount'] += $v['water_pipe_sum'];
            $amount['natural_gas_pipeline_horizon_amount'] += $v['natural_gas_pipeline_sum'];
            $amount['special_remove_horizon_amount'] += $v['special_remove_sum'];
        }

        //累计完成数据合计
        foreach ($finish_data as $v) {
            $amount['occupation_finish_amount'] += $v['occupation_sum'];
            $amount['house_finish_amount'] += $v['house_sum'];
            $amount['pylon_finish_amount'] += $v['pylon_sum'];
            $amount['parallels_finish_amount'] += $v['parallels_sum'];
            $amount['optical_cable_finish_amount'] += $v['optical_cable_sum'];
            $amount['water_pipe_finish_amount'] += $v['water_pipe_sum'];
            $amount['natural_gas_pipeline_finish_amount'] += $v['natural_gas_pipeline_sum'];
            $amount['special_remove_finish_amount'] += $v['natural_gas_pipeline_sum'];
        }



        foreach ($total_data as $k => $v) {

            //合计数据
            $amount['occupation_total_amount'] += $v['occupation_total'];
            $amount['house_total_amount'] += $v['house_total'];
            $amount['pylon_total_amount'] += $v['pylon_total'];
            $amount['parallels_total_amount'] += $v['parallels_total'];
            $amount['optical_cable_total_amount'] += $v['optical_cable_total'];
            $amount['water_pipe_total_amount'] += $v['water_pipe_total'];
            $amount['natural_gas_pipeline_total_amount'] += $v['natural_gas_pipeline_total'];
            $amount['special_remove_total_amount'] += $v['special_remove_total'];

            if ($v['district_name'] == '高新区') {
                if (array_key_exists($k, $horizon_data)) {
                    $m = $v;
                    $m['horizon'] = $horizon_data[$k];

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }
                } else {

                    $m = $v;

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }

                }
                $gaoxin_data[] = $m;
            }

            if ($v['district_name'] == '长安区') {
                if (array_key_exists($k, $horizon_data)) {
                    $m = $v;
                    $m['horizon'] = $horizon_data[$k];

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }
                } else {

                    $m = $v;

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }

                }
                $changan_data[] = $m;
            }
            if ($v['district_name'] == '蓝田县') {
                if (array_key_exists($k, $horizon_data)) {
                    $m = $v;
                    $m['horizon'] = $horizon_data[$k];

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }
                } else {

                    $m = $v;

                    if (array_key_exists($k, $finish_data)) {
                        $m['finish'] = $finish_data[$k];
                    }

                }
                $lantian_data[] = $m;
            }

        }
        $data['gaoxin_data'] = $gaoxin_data;
        $data['changan_data'] = $changan_data;
        $data['lantian_data'] = $lantian_data;
        $data['amount'] = $amount;
        $data['search']=$search;

//       dd($data);

        return $data;


    }




}
