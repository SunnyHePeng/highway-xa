<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'IndexController@index');
Route::match(['get', 'post'], 'index', 'IndexController@index');

/**
 * 能够更方便的获取一些平台需要的数据
 * 该部分不会在系统菜单导航上显示，用户登陆系统后在地址栏输入对应的url访问
 */
   Route::group(['middleware'=>'airtual'],function(){
       //获取试验室摄像头数据(摄像头编号，摄像头对应的编码设备uuid)
       /*外环高速-试验室监控服务器由建研提供维护*/
       Route::get('camera',function(){
           return view('camera');
       });
       /*获取农民工工资系统id信息*/
       Route::get('salary',function(){
           return view('salary');
       });
       /*获取拌合站，污水处理摄像头编号*/
       Route::get('mixplant_camera',function(){
           return view('mixplant_camera');
       });
   });

/*admin后台管理  之后加一个登录验证 中间件*/
Route::group(['namespace' => 'Admin'], function()
{

    Route::group(['prefix' => 'manage'], function()
    {
        //后台根目录
        Route::match(['get', 'post'],'login', 'AuthController@login');
        Route::match(['get', 'post'], 'register', 'AuthController@register');
        Route::match(['get', 'post'], 'verifyinfo', 'AuthController@verifyInfo');
        Route::match(['get', 'post'], 'findpass', 'AuthController@findPass');
        Route::match(['get', 'post'], 'changepass', 'AuthController@changePass');
        Route::match(['get', 'post'], 'verifyip', 'AuthController@verifyIp');
        Route::get('logout', 'AuthController@logout');
        Route::match(['get', 'post'],'pass', 'AuthController@changePass');
        Route::post('get_sup', 'SupervisionController@getSupByProject');
        Route::post('get_sec', 'SupervisionController@getSecBySup');
        Route::post('get_pos', 'PositionController@getPositionByRole');

        Route::post('getcom','RelationController@getCompanyByRole');
        Route::post('getdepart','RelationController@getDepartmentByCom');
        Route::group(['middleware'=>'admin'], function(){
            Route::get('/', function(){
                return redirect('manage/project');
            });
            Route::any('error', function(){
                return view('errors.error');
            });
            Route::match(['get', 'put'], 'index', function(){
                return redirect('manage/project');
            });
            Route::resource('admin', 'AdminController');
            Route::any('addUser', 'AdminController@addUser');
            Route::get('admin/{uid?}', 'AdminController@index');
            Route::match(['get', 'post'], 'user_mod', 'AdminController@userMod');
            Route::match(['get', 'post'], 'user_info', 'AdminController@userInfo');
            Route::resource('role', 'RoleController');
            Route::resource('permission', 'PermissionController');
            Route::resource('module', 'ModuleController');
            Route::resource('pda', 'PdaController');
            Route::resource('log', 'LogController');
            Route::resource('stat_login', 'LogController@statLogin');
            Route::resource('position', 'PositionController');
            Route::resource('company', 'CompanyController');
            Route::resource('project', 'ProjectController');
            Route::resource('department','DepartmentController');
            Route::resource('psection', 'ProjectSectionController');
            Route::post('get_psec', 'ProjectSectionController@getSecByPro');
            Route::resource('map', 'MapController');
            Route::resource('section', 'SectionController');
            Route::resource('mixplant', 'MixplantController');
            Route::resource('beamfield', 'BeamfieldController');
            //梁场信息
            Route::get('beam_site','BeamSiteController@beamSite');
            Route::post('beam_site_add','BeamSiteController@add');
            Route::Match(['get','post'],'beam_site_edit','BeamSiteController@beamSiteEdit');
            Route::get('beam_site_del/{id}','BeamSiteController@beamSiteDel');

            //梁场信息中根据项目公司获取监理信息
            Route::get('get_sup_by_pro','BeamSiteController@getSupervisionByProject');
            //梁场信息中根据监理获取合同段信息
            Route::get('get_sec_by_sup','BeamSiteController@getSectionBySup');
//            Route::resource('tunnel', 'TunnelController');
            Route::resource('supervision', 'SupervisionController');
            Route::match(['get', 'post'], 'sup_section', 'SupervisionController@supSection');
            
            
            Route::resource('factory', 'FactoryController');
            Route::resource('factory_detail', 'FactoryDetailController');
            Route::resource('material', 'MaterialController');

            Route::resource('device', 'DeviceController');
            Route::resource('dev_category', 'DeviceCategoryController');
            Route::resource('dev_type', 'DeviceTypeController');
            Route::resource('truck', 'TruckController');
            Route::resource('truck_category', 'TruckCategoryController');
            Route::resource('detection_device', 'DetectionDeviceController');
            Route::resource('collection', 'CollectionController');
            Route::get('get_collection', 'CollectionController@getCollectionBySec');
            Route::get('get_device_type', 'DeviceTypeController@getDeviceTypeByCat');
            Route::match(['get', 'post'], 'collection_cat', 'CollectionController@collectionCat');
            
            //Route::resource('dev_user', 'DeviceUserController');
            Route::get('kzy_dev', 'DeviceController@kzyDev');
            Route::get('yl_dev', 'DeviceController@ylDev');
            Route::get('wn_dev', 'DeviceController@wnDev');
            Route::get('bh_dev', 'DeviceController@bhDev');
            Route::get('zj_dev', 'DeviceController@zjDev');
            Route::get('ylj_dev', 'DeviceController@yljDev');
            Route::get('qzy_dev', 'DeviceController@qzyDev');
            Route::get('zl_dev', 'DeviceController@zlDev');
            Route::get('yj_dev', 'DeviceController@yjDev');
            Route::get('znpl_dev', 'DeviceController@znplDev');
            Route::get('znys_dev', 'DeviceController@znysDev');
            Route::get('ylc_dev', 'DeviceController@ylcDev');
            Route::get('jqj_dev', 'DeviceController@jqjDev');
            Route::get('yjyt_dev', 'DeviceController@yjytDev');
            Route::get('szy_dev', 'DeviceController@szyDev');
            Route::get('environment_dev', 'DeviceController@environmentDev');//环境监测设备
            //筛分试验设备
            Route::get('sieve_dev','DeviceController@sieveDev');
            //污水处理设备
            Route::get('wastewater_treatment_dev','DeviceController@wastewaterTreatmentDev');
            //喷淋养生设备
            Route::get('steam_spray_device','DeviceController@steamSprayDevice');
            //根据section_id获取梁场信息
            Route::get('get_beam_site_by_sec','DeviceController@getBeamSiteBySection');
            //添加喷淋养生设备信息
            Route::post('steam_spray_device_add','DeviceController@steamSprayDeviceAdd');
            //编辑喷淋养生设备
            Route::Match(['get','post'],'steam_spray_device_edit','DeviceController@steamSprayDeviceEdit');
            Route::get('steam_spray_device_del/{id}','DeviceController@steamSprayDeviceDel');
            /*智能蒸汽养生设备*/
            Route::get('steam_keep_device','DeviceController@steamKeepDevice');

            /*张拉设备*/
            Route::get('stretch_device','DeviceController@stretchDevice');

            /*梁场中的设备信息修改*/
            Route::Match(['get','post'],'beam_yard_device_edit/{device_id}','DeviceController@beamYardDeviceEdit');

            /*压浆设备*/
            Route::get('mudjack_device','DeviceController@mudjackDevice');



            //文件处理apkupload
            Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
            Route::post('file/del_file', 'FileController@delFile');
            Route::post('file/apkinfo', 'FileController@apkInfo');
            //回收站路由
            /*Route::resource('trashed', 'TrashedController');*/
            /*Route::get('abnormal', 'AbnormalController@index');
            Route::delete('abnormal/{id}', 'AbnormalController@destroy');*/
        });     
    });
});

Route::group(['prefix' => 'map'], function()
{
    Route::group(['middleware'=>'admin'], function(){
        Route::get('/', 'MapController@index');
        Route::get('index', 'MapController@index');
    });     
});
/*拌合站*/
Route::group(['namespace' => 'Mixplant'], function()
{    
    Route::group(['prefix' => 'mixplant'], function()
    {
        Route::group(['middleware'=>'admin'], function(){
            Route::get('/', 'IndexController@index');
            Route::get('index', 'IndexController@index');
            Route::any('error', function(){
                return view('errors.error');
            });
        });     
    });

    Route::group(['prefix' => 'snbhz'], function()
    {


        Route::group(['middleware'=>'admin'], function(){
            Route::get('index', 'SnbhzController@index');
            Route::get('device', 'SnbhzController@device');
            Route::get('product_data', 'SnbhzController@productData');
            Route::get('product_data_info/{d_id}', 'SnbhzController@productInfo');
            Route::get('video_preview/{device_id}', 'SnbhzController@videoPreview');
            Route::get('product_data_at_video/{device_id}', 'SnbhzController@getDataAtVideo');
            Route::match(['get','post'], 'deal/{id?}', 'SnbhzController@deal');
            Route::get('detail_info', 'SnbhzController@getDetailInfo');
            Route::get('data_report', 'SnbhzController@dataReport');
            Route::get('data_curve', 'SnbhzController@dataCurve');
            Route::get('deviation_curve', 'SnbhzController@deviationCurve');
            Route::get('warn_info', 'SnbhzController@warnInfo');
            Route::get('warn_report', 'SnbhzController@warnReport');
            Route::get('warn_compare', 'SnbhzController@warnCompare');
            Route::match(['get','post'],'warn_set', 'SnbhzController@warnSet');
            Route::get('product_report', 'SnbhzController@productReport');
            Route::get('product_report_week', 'SnbhzController@productReportWeek');
            Route::get('product_compare', 'SnbhzController@productCompare');
            Route::get('clbg', 'SnbhzController@clbg');
            Route::get('tzlb', 'SnbhzController@tzlb');
            Route::get('stat_login', 'SnbhzController@statLogin');
            Route::get('stat_week', 'SnbhzController@statWeek');
            Route::get('stat_month', 'SnbhzController@statMonth');
            Route::get('get_select_val/{type}', 'SnbhzController@getSelectVal');

            Route::get('get_new_info_at_video','SnbhzController@getNewInfoAtVideo');

            Route::get('get_today_warn','SnbhzController@getTodayWarnBhz');
            /*实时视频(新，从萤石云上调用实时视频，而不是海康8700平台【拌合站设备监控】,该功能代码暂时不用)*/
            Route::get('video_live/{device_id}','SnbhzController@videoLive');
            //文件处理
            Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
            Route::post('file/del_file', 'FileController@delFile');
            //上传超时通知人员设置
            Route::get('notice/index', 'NoticeController@index');
            Route::get('notice/create', 'NoticeController@create');
            Route::post('notice/store', 'NoticeController@store');
            Route::get('notice/show', 'NoticeController@show');
            Route::get('notice/del/{id}','NoticeController@delete');

            /*拌合站设备与采集端连接异常时通知人员信息*/
            Route::get('device_failure_push_user','SnbhzController@deviceFailurePushUser');
            /*添加拌合站设备与采集端连接异常时通知人员*/
            Route::match(['get','post'],'add_device_failure_push_user','SnbhzController@addDeviceFailurePushUser');
            /*删除拌合站设备与采集端连接异常时通知人员*/
            Route::delete('del_device_failure_push_user/{id}','SnbhzController@delDeviceFailurePushUser');


        });     
    });
});
/*试验室*/
Route::group(['namespace' => 'Lab'], function()
{
    Route::group(['prefix' => 'lab'], function()
    {



        Route::group(['middleware'=>'admin'], function(){
            Route::get('/', 'LabController@index');
            Route::get('index', 'LabController@index');
            Route::any('error', function(){
                return view('errors.error');
            });
            Route::get('device', 'LabController@device');
            Route::get('lab_data', 'LabController@labData');
            Route::get('lab_data_info', 'LabController@labInfo');
            Route::match(['get','post'], 'deal/{id?}', 'LabController@deal');
            Route::get('lab_data_detail', 'LabController@getDetailInfo');
            Route::get('getVideo', 'LabController@getVideo');
            Route::get('section_report', 'LabController@sectionReport');
            Route::get('type_report', 'LabController@typeReport');
            Route::get('warn_data', 'LabController@warnInfo');
            Route::get('getDataAtVideo/{device_id}','LabController@getDataAtVideo');

            Route::get('get_today_warn','LabController@getTodayWarnLab');

            Route::get('warn_report', 'LabController@warnReport');
            Route::get('section_warn_report', 'LabController@sectionWarnReport');
            Route::get('type_warn_report', 'LabController@typeWarnReport');
            Route::get('olab', 'LabController@olab');
            Route::match(['get','post'],'warn_set', 'LabController@warnSet');
            Route::get('clbg', 'LabController@clbg');
            Route::get('tzlb', 'LabController@tzlb');
            Route::get('stat_login', 'LabController@statLogin');
            Route::get('stat_week', 'LabController@statWeek');
            Route::get('stat_month', 'LabController@statMonth');
            Route::get('video/{device_id}', 'LabController@video');
            Route::get('get_select_val/{type}', 'LabController@getSelectVal');
            //其他试验
            Route::get('other_lab','LabController@otherLab');
            Route::get('other_lab_tj','LabController@otherLabTj');
            Route::get('other_lab_data_info','LabController@otherLabInfo');
            Route::get('other_section_report','LabController@otherSectionReport');
            Route::get('other_type_report','LabController@otherTypeReport');

            //试验数据（手动）
            Route::get('statistic_by_item', 'LabController@statisticByItem');
            Route::get('statistic_by_section', 'LabController@statisticBySection');
            Route::get('lab_report', 'LabController@labReport');

            /**
             * 集料试验
             */
              /*集料试验数据*/
            Route::get('aggregate_index','AggregateController@aggregateIndex');
            //实时视频
            Route::get('aggregate_video/{device_id}','AggregateController@aggregateVideo');
            //实时视频中获取数据
            Route::get('get_aggregate_data_at_video/{device_id}','AggregateController@getAggregateDataAtVideo');
            //试验数据
            Route::get('get_aggregate_data/{device_id}','AggregateController@aggregateData');
            /*视频回放*/
            Route::get('aggregate_video_playback/{info_id}','AggregateController@videoPlayback');

            //文件处理
            Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
            Route::post('file/del_file', 'FileController@delFile');
        });     
    });
});


Route::group(['namespace' => 'Ycjc'], function()
{    
    Route::group(['prefix' => 'ycjc'], function()
    {
        Route::group(['middleware'=>'admin'], function(){
            Route::get('/', 'YcjcController@index');
            Route::get('index', 'YcjcController@index');
            Route::any('error', function(){
                return view('errors.error');
            });
            Route::get('history_data', 'YcjcController@historyData');
            Route::get('warn', 'YcjcController@warn');
        });     
    });
});


Route::group(['namespace' => 'Zjzl'], function()
{    
    Route::group(['prefix' => 'zjzl'], function()
    {
        Route::group(['middleware'=>'admin'], function(){
            Route::get('/', 'ZjzlController@index');
            Route::get('index', 'ZjzlController@index');
            Route::any('error', function(){
                return view('errors.error');
            });
            Route::get('device', 'ZjzlController@device');
            Route::get('zj_data', 'ZjzlController@zjData');
            Route::get('zj_data_info/{d_id}', 'ZjzlController@zjInfo');
            Route::match(['get','post'], 'deal/{id?}', 'ZjzlController@deal');
            Route::get('warn_info', 'ZjzlController@warnInfo');
            Route::match(['get','post'],'warn_set', 'ZjzlController@warnSet');
            Route::get('clbg', 'ZjzlController@clbg');
            Route::get('clbg', 'ZjzlController@clbg');
            Route::get('tzlb', 'ZjzlController@tzlb');
            Route::get('stat_zjsd', 'ZjzlController@zjsd');
            Route::get('stat_zjgrl', 'ZjzlController@zjgrl');
            Route::get('stat_login', 'ZjzlController@statLogin');
            Route::get('stat_week', 'ZjzlController@statWeek');
            Route::get('stat_month', 'ZjzlController@statMonth');
            Route::get('get_select_val/{type}', 'ZjzlController@getSelectVal');
            //文件处理
            Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
            Route::post('file/del_file', 'FileController@delFile');
        });     
    });
});

/*进度统计*/
Route::group(['namespace'=>'Stat'],function()
{
  Route::group(['prefix'=>'stat'],function()
  {
      Route::group(['middleware'=>'admin'],function()
      {
          Route::get('/','StatController@index');
          Route::get('index','StatController@index');
          Route::match(['get','post'],'today_add','StatController@todayAdd');
          Route::get('get_today_report','StatController@getTodayReport');
          Route::get('project_set','StatController@sdSet');
          Route::get('sd_set','StatController@sdSet');
          Route::match(['get','post'],'sd_set_add','StatController@sdSetAdd');
          Route::get('sd_inform_people','StatController@sdInformPeople');
          Route::Match(['get','post'],'sd_inform_add','StatController@sdInformAdd');
          Route::get('sdtj_inform_del','StatController@sdInformDel');
          /*资源配置统计*/
          Route::get('resource','StatController@resource');
          /*资源配置信息录入*/
          Route::match(['get','post'],'resource_add','StatController@resourceAdd');
          /*报警信息统计*/
          Route::get('warn_mess','StatController@warnMess');
          /*隧道监控量测*/
          Route::get('monitor','StatController@monitor');
          /*隧道监控量测监理录入*/
          Route::Match(['get','post'],'monitor_add','StatController@monitorAdd');
          //天气信息统计
          Route::get('weather_stat','StatController@weatherStat');
          //历史天气信息
          Route::get('weather_history','StatController@weatherHistory');
          //系统进度统计
          Route::get('system_stat','StatController@systemStat');
          //系统进度录入(信息化各系统运行情况录入)
          Route::Match(['get','post'],'system_run_add','StatController@systemRunAdd');
          /**
           * 隧道人员设备统计
           */
          /*日统计*/
          Route::get('people_device_day','PeopleDeviceController@peopleDeviceDay');
          /*月统计*/
          Route::get('people_device_month','PeopleDeviceController@peopleDeviceMonth');
          /*录入日统计数据*/
          Route::Match(['get','post'],'people_device_day_add','PeopleDeviceController@peopleDeviceAdd');
          /*修改日统计数据*/
          Route::get('people_device_edit','PeopleDeviceController@peopleDeviceEdit');
          /*更新日统计数据*/
          Route::post('people_device_update','PeopleDeviceController@peopleDeviceUpdate');

          /**
           * 进度统计历史数据
           */
          /*隧道工程历史*/
          Route::get('tunnel_project_history','StatHistoryController@tunnelProjectHistory');
          /*资源配置历史*/
          Route::get('resource_history','StatHistoryController@resourceHistory');
          /*报警信息统计历史*/
          Route::get('warn_mess_history','StatHistoryController@warnMessHistory');
          /*隧道监控量测历史*/
          Route::get('monitor_history','StatHistoryController@monitorHistory');

          /**
           * 隧道洞顶房屋沉降观测
           */
          /*工程设置--隧道洞顶房屋沉降观测初始高程*/
         Route::get('tunnel_house_init','StatController@tunnelHouseInit');
         /*添加隧道洞顶房屋沉降观测初始高程*/
         Route::match(['get','post'],'tunnel_house_init_add','StatController@tunnelHouseInitAdd');
         /*编辑隧道洞顶房屋沉降观测初始高程*/
         Route::match(['get','post'],'tunnel_house_init_edit','StatController@tunnelHouseInitEdit');
         /*隧道洞顶房屋沉降观测*/
         Route::get('tunnel_house_monitor','StatController@tunnelHouseMonitor');
         /*添加隧道洞顶房屋沉降观测数据*/
         Route::match(['get','post'],'tunnel_house_monitor_add','StatController@tunnelHouseMonitorAdd');
         /*隧道洞顶房屋沉降监测详细数据*/
         Route::get('tunnel_house_monitor_detail/{id}','StatController@tunnelHouseMonitorDetail');
         /*审核*/
         Route::get('tunnel_house_monitor_check/{id}','StatController@tunnelHouseMonitorCheck');
         /*修改*/
         Route::match(['get','post'],'tunnel_house_monitor_edit/{id}','StatController@tunnelHouseMonitorEdit');

      });
  });
});

/**
 * 重要通知公告
 */
Route::group(['namespace'=>'Notice'],function(){
   Route::group(['prefix'=>'notice'],function(){
       Route::group(['middleware'=>'admin'],function(){

           /*公告列表*/
          Route::get('index','NoticeController@noticeList');
          /*我的发布*/
          Route::get('my_publish','NoticeController@myPublish');
          /*发布新公告*/
          Route::Match(['get','post'],'publish_new_notice','NoticeController@publishNewNotice');
          /*查看公告详细信息*/
          Route::get('notice_details/{notice_id}','NoticeController@noticeDetails');
          /*下载附件*/
          Route::get('download/{id}','NoticeController@downloadAccessory');
          /*下载附件时记录增加下载次数*/
          Route::get('download_number_augment/{id}','NoticeController@downloadNumberAugment');
          /*查看我发布的公告的详细信息*/
          Route::get('my_publish_notice_details/{id}','NoticeController@myPublishNoticeDetails');
          Route::Match(['get','post'],'notice_edit','NoticeController@noticeEdit');

          /*公告查看和下载情况*/
          Route::get('read_download_condition','NoticeController@readDownloadCondition');

          /*查看已阅读公告人员信息*/
          Route::get('get_already_read_user/{id}','NoticeController@getAlreadyReadUser');

          /*查看已下载附件人员信息*/
          Route::get('get_already_download_user/{id}','NoticeController@getAlreadyDownloadUser');

          /*获取最新的一条公告信息*/
          Route::get('get_new_notice','NoticeController@getNewNotice');

          /*公告审核及修改*/
          Route::get('notice_approval_and_edit','NoticeController@noticeApprovalAndEdit');

          /*公告审核微信推送人员*/
          Route::get('notice_approval_user_set','NoticeController@noticeApprovalUserSet');

          /*添加公告审核微信推送人员*/
          Route::match(['get','post'],'add_user','NoticeController@addUser');

          /*删除公告审核微信推送人员*/
          Route::get('del_user','NoticeController@delUser');

          //审核
          Route::get('approval_publish','NoticeController@approvalPublish');


           //文件处理
           Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
           Route::post('file/del_file', 'FileController@delFile');

       });
   });
});

/**
 * 治污减霾
 */
Route::group(['prefix' => 'smog', 'namespace' => 'Smog', 'middleware' => 'admin'], function () {
    //文档
    Route::get('document/index', 'DocumentController@index');
    Route::get('document/create', 'DocumentController@create');
    Route::post('document/upload', 'DocumentController@upload');
    Route::post('document/store', 'DocumentController@store');
    Route::delete('document/destroy/{id}', 'DocumentController@destroy');
    //视频
    Route::get('video/index', 'VideoController@index');
    Route::get('video/create', 'VideoController@create');
    Route::post('video/upload', 'VideoController@upload');
    Route::post('video/store', 'VideoController@store');
    Route::get('video/show/{id}', 'VideoController@show');
    Route::delete('video/destroy/{id}', 'VideoController@destroy');

    /**
     * 环境监测
     *
     */
//    /*现场环境监测仪*/
    Route::get('environment_scene','EnvironmentMonitorController@environmentScene');
    /*洞内气体监测仪*/
    Route::get('tunnel_gas','EnvironmentMonitorController@tunnelGas');
    /*环境监测历史*/
    Route::get('environment_history','EnvironmentMonitorController@environmentHistory');
    /**
     * 污水处理
     */
    /*污水处理实时监测数据*/
    Route::get('waste_water_newest','WasteWaterTreatmentController@wastewaterNewest');
    /*污水处理数据*/
    Route::get('waste_water_index','WastewaterTreatmentController@wastewaterIndex');
    /*获取污水处理数据*/
    Route::get('get_waste_water_data/{device_id}','WastewaterTreatmentController@getWastewaterData');
    /*污水处理监测历史*/
    Route::get('waste_water_history','WastewaterTreatmentController@wastewaterHistory');
    /*污水处理实时视频*/
    Route::get('real_time_video_by_device/{device_id}','WastewaterTreatmentController@realTimeVideoByDevice');
    /*污水处理实时视频中获取数据*/
    Route::get('data_in_real_time_video_by_device/{device_id}','WastewaterTreatmentController@DataInRealTimeVideoByDevice');
});

/**
 * 喷淋养生
 */
Route::group(['namespace'=>'BeamSpray', 'prefix' => 'beam_spray', 'middleware' => 'admin'],function()
{
    /*设备列表*/
    Route::get('devices', 'SprayKeepController@device');
    /*墩身养护*/
    Route::get('beams', 'SprayKeepController@beamList');
    /*喷淋养生记录*/
    Route::get('spray_detail/{id}', 'SprayKeepController@sprayDetail');
    /*根据设备查看预制梁信息*/
    Route::get('beam_info_by_device/{device_id}','SprayKeepController@beamInfoByDevice');
    /*梁场养生*/
    Route::get('beam_field_keep','SprayKeepController@beamFieldKeep');
    /*蒸汽养生*/
    Route::get('steam_keep','SprayKeepController@steamKeep');

});

/**
 * 征地拆迁
 */
Route::group(['namespace'=>'Remove', 'prefix' => 'remove', 'middleware' => 'admin'],function()
{

    /*征地拆迁设置*/
    Route::get('remove_set','RemoveController@removeSet');

    /*添加征地拆迁总量*/
    Route::Match(['get','post'],'remove_total_add','RemoveController@removeTotalAdd');

    /*删除征地拆迁总量*/
    Route::delete('remove_total_del/{id}','RemoveController@removeTotalDel');

    /*根据监理id获取合同段信息*/
    Route::get('get_section_by_supervision','RemoveController@getSectionBySupervision');

    /*征地拆迁日统计*/
    Route::get('remove_day','RemoveController@removeDay');

    /*征地拆迁今日数据录入*/
    Route::Match(['get','post'],'remove_day_add','RemoveController@removeDayAdd');

    /*按时间范围统计*/
    Route::get('remove_stat_by_time','RemoveController@removeStatByTimeHorizon');

});


/**
 * 智能张拉
 */
Route::group(['namespace'=>'Stretch', 'prefix' => 'stretch', 'middleware' => 'admin'],function()
{

    /*设备状态*/
    Route::get('device_index','StretchController@deviceIndex');
    /*设备列表*/
    Route::get('device_list','StretchController@deviceList');
    /*根据设备获取张拉数据*/
    Route::get('stretch_data_by_device/{device_id}','StretchController@stretchDataByDevice');
    /*张拉详细数据*/
    Route::get('stretch_detail/{info_id}','StretchController@stretchDetail');
    /*实时视频*/
    Route::get('real_time_video/{device_id}','StretchController@realTimeVideo');
    /*实时视频中获取数据*/
    Route::get('get_data_at_real_video/{device_id}','StretchController@getDataAtRealVideo');
    /*张拉数据*/
    Route::get('stretch_data','StretchController@stretchData');

    /*报警信息*/
    Route::get('warn_info','StretchController@warnInfo');
    /*获取报警信息选项*/
    Route::get('get_select_val/{type}', 'StretchController@getSelectVal');
    /*报警数据中的详细数据*/
    Route::get('detail_in_warn/{detail_id}','StretchController@detailInWarn');
    /*报警处理*/
    Route::match(['get','post'],'warn_deal/{detail_id}','StretchController@warnDeal');
    /*处理报告*/
    Route::get('warn_deal_report/{detail_id}','StretchController@warnDealReport');
    /*报警设置*/
    Route::get('warn_set','StretchController@warnSet');
    /*添加报警通知人员*/
    Route::match(['get','post'],'warn_user_add','StretchController@warnUserAdd');
    /*删除报警通知人员*/
    Route::delete('warn_user_del/{id}','StretchController@warnUserDel');
    /*报警信息统计*/
    Route::get('stat_warn','StretchController@statWarn');

    //文件处理
    Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
    Route::post('file/del_file', 'FileController@delFile');

});

/**
 * 智能压浆
 */
Route::group(['namespace'=>'Mudjack','prefix'=>'mudjack','middleware'=>'admin'],function(){

    /*设备状态*/
    Route::get('device_index','MudjackController@deviceIndex');
    /*设备列表*/
    Route::get('device_list','MudjackController@deviceList');
    /*根据设备获取压浆数据*/
    Route::get('mudjack_data_by_device/{device_id}','MudjackController@mudjackDataByDevice');
    /*压浆详情数据*/
    Route::get('mudjack_detail/{info_id}','MudjackController@mudjackDetail');
    /*实时视频*/
    Route::get('real_time_video/{device_id}','MudjackController@realTimeVideo');
    /*实时视频中获取数据*/
    Route::get('get_data_at_real_video/{device_id}','MudjackController@getDataAtRealVideo');
    /*压浆数据*/
    Route::get('mudjack_data','MudjackController@mudjackData');

    /*报警信息*/
    Route::get('warn_info','MudjackController@warnInfo');
    /*获取报警信息选项*/
    Route::get('get_select_val/{type}', 'MudjackController@getSelectVal');
    /*报警数据中的详细数据*/
    Route::get('detail_in_warn/{detail_id}','MudjackController@detailInWarn');
    /*报警处理*/
    Route::match(['get','post'],'warn_deal/{detail_id}','MudjackController@warnDeal');
    /*处理报告*/
    Route::get('warn_deal_report/{detail_id}','MudjackController@warnDealReport');
    /*报警设置*/
    Route::get('warn_set','MudjackController@warnSet');
    /*添加报警通知人员*/
    Route::match(['get','post'],'warn_user_add','MudjackController@warnUserAdd');
    /*删除报警通知人员*/
    Route::delete('warn_user_del/{id}','MudjackController@warnUserDel');
    /*报警信息统计*/
    Route::get('stat_warn','MudjackController@statWarn');

    //文件处理
    Route::match(['get', 'post'], 'file/upload', 'FileController@upload');
    Route::post('file/del_file', 'FileController@delFile');

});
/*其他未开发模块首页*/
Route::get('jcsj/index', 'IndexController@other');
Route::get('lxys/index', 'IndexController@other');
Route::get('wyjk/index', 'IndexController@other');
Route::get('cjjc/index', 'IndexController@other');

Route::get('yjl/index', 'IndexController@other');
Route::get('jdgl/index', 'IndexController@other');

Route::get('jlzf/index', 'IndexController@other');
Route::get('sgrz/index', 'IndexController@other');
Route::get('dzda/index', 'IndexController@other');
Route::get('plzqys/index', 'IndexController@other');
Route::get('pressure/index', 'IndexController@other');

/*验证码*/
Route::get('code/{rdm?}', 'CodeController@code');
Route::match(['get','post'], 'pcode/{rdm?}', 'CodeController@pcode');
/*微信*/
Route::group(['prefix' => 'wechat', 'namespace' => 'Wechat'], function()
{
    Route::get('/', 'IndexController@index');
    Route::get('index', 'IndexController@index');
    Route::get('openid', 'IndexController@openid');
    Route::get('login', 'IndexController@LoginProject');
    Route::get('checkt', 'IndexController@checkToken');
    Route::get('snbhz_detail_info', 'IndexController@getSnbhzDetailInfo');
    Route::get('lab_detail_info', 'IndexController@getLabDetailInfo');
});
/*外部链接(向第三方系统跳转)*/
Route::group(['middleware'=>'admin'], function()
{
    /*视频监控*/
    Route::get('spjk/index', 'IndexController@video');
    /*前期手续*/
    Route::match(['get','post'], 'qqsx/index', 'IndexController@qqsx');
    /*农民工资*/
    Route::get('nmggz/index', 'IndexController@getFarmerWages');
    /*边坡监测*/
    Route::get('side/index','IndexController@side');
    /*隧道监控量测*/
    Route::get('tunnel_monitor/index','IndexController@tunnelMonitor');
    /*BIM*/
    Route::get('bim/index','IndexController@bim');
    /*隧道安全*/
    Route::get('tunnel/index','IndexController@tunnel');
});

/*电子档案链接*/
Route::get('recode/index','IndexController@electronic');


/*client v1.0客户端api*/
Route::group(['prefix' => 'api/v1.0', 'namespace' => 'Api'], function()
{
        Route::post('/', 'ApiController@index');
        Route::match(['get','post'], 'index', 'ApiController@index');


    Route::get('tpost', 'TestController@testpost');
    Route::get('tget', 'TestController@testget');
    /*第三方系统获取一级平台用户信息*/
    Route::get('get_user_all','UserController@getUserAll');
    /*第三方系统获取一级平台的单个用户信息*/
    Route::get('get_user_one','UserController@getOneUser');
    /*第三方系统获取一级平台项目公司信息*/
    Route::get('get_project','UserController@getProject');
    /*第三方系统获取一级平台标段信息*/
    Route::get('get_section','UserController@getSection');
    /*第三方系统获取用户登陆状态*/
    Route::get('get_login_status','UserController@getLoginStatus');

    /*拌和设备与采集端连接异常时推送通知*/
    Route::post('device_failure_push','MixplantController@deviceFailurePush');

});

