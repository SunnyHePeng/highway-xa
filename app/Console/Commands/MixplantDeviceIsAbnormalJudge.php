<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB, Log, Cache;
use App\Send\SendSms;
use App\Send\SendWechat;

use App\Models\Bhz\SnbhzDeviceWarnNumber;
use App\Models\Project\Project;
use App\Models\Project\Supervision;
use App\Models\Project\Section;
use App\Models\Device\Device;
use App\Models\System\Warn_user_set;


/**
 * 拌合站设备状态异常检测，
 * （该command暂时未使用，按照外环高速信息化系统管理办法20181116文档，编写了该方法，后来修改好代码运行时，
 * 下面施工单位反映项目管理处未下发该文档，给长安大学那边反馈，结果是取消报警升级，其他问题暂时保持原有的不变）
 *
 * 当本月该设备初级报警超过当月总生产盘数3%或高级报警超过总生产盘数1%时，触发报警
 * 拌和设备进行数据上传时，在api中记录下生产次数及初高级报警次数
 * 之后通过该command执行对生产次数与报警次数之间的判断
 * 该command每5分钟执行一次
 *
 *
 */
class MixplantDeviceIsAbnormalJudge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * 该command名字，也就是artisan list中的名字
     */
    protected $signature = 'mixplant_device_is_abnormal_judge';

    /**
     * The console command description.
     *
     * @var string
     *该command描述
     */
    protected $description = 'mixplant_device_is_abnormal_judge';
    protected $time, $date;

    /**
     * Execute the console command.
     *
     * @return mixed   xianjw6288   zhanze1979   zhunan6274  lonjm19970626
     */
    public function handle()
    {
        Log::info('mixplant_device_is_abnormal_judge start');

        $this->abnormalJudge(new SnbhzDeviceWarnNumber);

        Log::info('mixplant_device_is_abnormal_judge end');
    }

    /**
     * 执行拌和设备的异常判断
     */
    protected function abnormalJudge($model)
    {
         //拌和设备当月初级报警与生产次数比值的上限值
        $snbhz_device_cj_prodruction_retio_max=Config()->get('common.snbhz_device_cj_prodruction_retio_max');

        //拌和设备当月高级报警与生产次数比值的上限值
        $snbhz_device_gj_prodruction_retio_max=Config()->get('common.snbhz_device_gj_prodruction_retio_max');


        //拌和设备分类id
        $device_cat=4;
        //获取当月所有拌和设备的数据
        $month = date('m', time());
        $year = date('Y', time());

        /*获取所有记录的拌和设备的生产次数与初级，高级报警次数*/
        $deviceWarnNumberData = $model->where('month', $month)
                                      ->where('year', $year)
                                      ->where('device_cat',$device_cat)
                                      ->get();

        //异常设备id
        $abnormalDeviceIds = [];

        foreach ($deviceWarnNumberData as $v) {
            //判断初级报警次数与生产次数的比值是否超过最大上限值
            if ($v->cj_warn_num / $v->production_num > $snbhz_device_cj_prodruction_retio_max) {

                $abnormalDeviceIds[$v->device_id] = [
                    'project_id' => $v->project_id,
                    'supervision_id' => $v->supervision_id,
                    'section_id' => $v->section_id,
                    'device_id'=>$v->device_id,
                    'warn_info'=>'当月初级报警次数超过本月生产总盘数的3%',
                ];

            }

            //判断初级报警次数与生产次数的比值是否超过最大上限值
            if ($v->gj_warn_num / $v->production_num > $snbhz_device_gj_prodruction_retio_max) {

                //该deviceid是否已经存在异常设备id数组中
                if (!array_key_exists($v->device_id, $abnormalDeviceIds)) {
                    $abnormalDeviceIds[$v->device_id] = [
                        'project_id' => $v->project_id,
                        'supervision_id' => $v->supervision_id,
                        'section_id' => $v->section_id,
                        'device_id'=>$v->device_id,
                        'warn_info'=>'当月高级报警次数超过本月生产总盘数的1%',
                    ];
                }
            }
        }
        Log::info($abnormalDeviceIds);

    }


}