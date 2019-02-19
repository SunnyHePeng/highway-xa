<?php 
return [
	'app_name' => '陕西交建长安工程管理系统',
	'app_url' => 'http://www.xawhgs.com',
	'lab_url'=>'http://sys.xawhgs.com',
	'app_title' => '陕西交建长安工程管理系统',
	'keyword' => '',
	'description' => '',
	'copyright' => 'Copyright © 2017 陕西交建长安工程管理系统. All Rights Reserved',
	'upload_path' => './uploads/',
	'show_path' => '/uploads/',
	'cdn_link' => '',
	'expire'=>'24', //以小时计算重置密码邮件过期时间
	'cache_expire'=>120,   //缓存2个小时 单位分钟
	'vpn_expire'=>20,	//缓存20分钟 单位分钟
	'access_ip'=>'',	//任务列表缓存90分钟 单位分钟
	'version' => '1.0',
	'time_difference' => '300',  //客户端和服务器时间差  秒为单位（5分钟）
	'email'=>'151@qq.com',
	'device_type'=>[
		'1'=>[1],
		'2'=>[2,3],
		'3'=>[4]
		],     //拌合站1 梁场2 隧道3对应的设备类型
	'snbhz_info'=>[      //水泥拌合站物料偏差最大值 %
		'1'=>'2',  //粗细集料
		'2'=>'1',  //粉料
		'3'=>'1',  //液料
		],
	/*'snbhz_info_detail'=>[
		'1'=>['name'=>'大石','pcl'=>2, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'2'=>['name'=>'中石','pcl'=>2, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'3'=>['name'=>'小石','pcl'=>2, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'4'=>['name'=>'砂1','pcl'=>2, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'5'=>['name'=>'砂2','pcl'=>2, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'6'=>['name'=>'水泥1','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'7'=>['name'=>'水泥2','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'8'=>['name'=>'水泥3','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'9'=>['name'=>'水泥4','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'10'=>['name'=>'粉煤灰','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'11'=>['name'=>'粉煤灰','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'12'=>['name'=>'粉煤灰3','pcl'=>1, 'cj'=>5, 'zj'=>10, 'gj'=>10],
		'13'=>['name'=>'水','pcl'=>1, 'cj'=>1, 'zj'=>5, 'gj'=>5],
		'14'=>['name'=>'水2','pcl'=>1, 'cj'=>1, 'zj'=>5, 'gj'=>5],
		'15'=>['name'=>'水3','pcl'=>1, 'cj'=>1, 'zj'=>5, 'gj'=>5],
		'16'=>['name'=>'外加剂1','pcl'=>1, 'cj'=>1, 'zj'=>5, 'gj'=>5],
		'17'=>['name'=>'外加剂2','pcl'=>1, 'cj'=>1, 'zj'=>5, 'gj'=>5],
		'18'=>['name'=>'引气剂3','pcl'=>1, 'cj'=>1, 'zj'=>5, 'gj'=>5],
		],*/
	/*拌合站报警等级标准*/
	/*12月27日修改，将拌合站报警等级初，中，高级修改为初，高级*/
	/*pcl为偏差率，cj为初级报警上限，gj为高级报警下限*/
	/*偏差率<=cj时为初级报警，偏差率>gj时为高级报警*/
	'snbhz_info_detail'=>[
		'1'=>['name'=>'碎石(16-31.5mm)','pcl'=>2, 'cj'=>4, 'gj'=>4],
		'2'=>['name'=>'碎石(10-20mm)','pcl'=>2, 'cj'=>4, 'gj'=>4],
		'3'=>['name'=>'碎石(5-10mm)','pcl'=>2, 'cj'=>4, 'gj'=>4],
		'4'=>['name'=>'河砂','pcl'=>2, 'cj'=>4, 'gj'=>4],
		'5'=>['name'=>'水泥','pcl'=>1, 'cj'=>3, 'gj'=>3],
		'6'=>['name'=>'粉煤灰','pcl'=>1, 'cj'=>3,'gj'=>3],
		'7'=>['name'=>'水','pcl'=>1, 'cj'=>3, 'gj'=>3],
		'8'=>['name'=>'减水剂','pcl'=>1, 'cj'=>3, 'gj'=>3],
		'9'=>['name'=>'引气剂','pcl'=>1, 'cj'=>3, 'gj'=>3]
		],
    /*拌和站设备生产时最小搅拌时长*/
    /*拌合站规定的搅拌时间规定为120s，当小于120s的80%时，需报警，也就是最小搅拌时间为90s*/
    'snbhz_device_mix_stir_time'=>90,

    /*拌和站设备当月初级报警与生产次数比值的上限值*/
    'snbhz_device_cj_prodruction_retio_max'=>0.03,

    /*拌合站设备当月高级报警与生产次数比值的上限值*/
    'snbhz_device_gj_prodruction_retio_max'=>0.01,

    /*试验类型*/
	'sylx'=>[
		1=>'钢筋焊接接头',
		2=>'钢筋机械连接',
		3=>'钢筋原材',
		4=>'碳素钢结构',    //暂时不展示
		5=>'混凝土弹性模量',//暂时不展示
		6=>'混凝土抗压',
		7=>'混凝土抗折',
		8=>'混凝土收缩',//暂时不展示
		9=>'砂浆抗压',
		10=>'水泥胶砂抗折抗压',
        11=>'钢筋焊搂复检',
        12=>'钢筋机械连接复检',
        13=>'钢筋原材复检',
		],
    /*集料试验类型*/
    'aggregate_sylx'=>[
        14=>'粗集料试验',
        15=>'细集料试验',
    ],
    /*
     * 实验室设备分类和试验类型对应关系
     *
     *  键 对应  设备分类id  1==>'抗折压一体机' 2=>'压力试验机' 3=>'万能试验机'
     *  值 对应  实验类型中的编号
     *
     */
    'category_sylx'=>[
         1=>[9,10],
         2=>[6],
         3=>[1,2,3,6,9],
    ],
	'video'=>[
		'00000000202100'=>['cam_num'=>'2624','obj'=>'syjczx'],
		'00000000203100'=>['cam_num'=>'2625','obj'=>'syjczx'],
		'00000000203200'=>['cam_num'=>'2623','obj'=>'syjczx'],
		'00000000203300'=>['cam_num'=>'2621','obj'=>'syjczx'],
        '00000000201100'=>['cam_num'=>'2622','obj'=>'syjczx'],
        '0000040D203200'=>['cam_num'=>'2663','obj'=>'LJ-13'],
        '0000040D201100'=>['cam_num'=>'2664','obj'=>'LJ-13'],
        '0000040D202100'=>['cam_num'=>'2665','obj'=>'LJ-13'],
        '0000040D203100'=>['cam_num'=>'2666','obj'=>'LJ-13'],
        '0000040D203300'=>['cam_num'=>'2667','obj'=>'LJ-13'],
        '0000050E203100'=>['cam_num'=>'2679','obj'=>'LJ-14'],
        '0000050E203300'=>['cam_num'=>'2676','obj'=>'LJ-14'],
        '0000050E203200'=>['cam_num'=>'2675','obj'=>'LJ-14'],
        '0000050E202100'=>['cam_num'=>'2677','obj'=>'LJ-14'],
        '0000050E201100'=>['cam_num'=>'2678','obj'=>'LJ-14']
		],
        'videoUrl'=>"http://v.xawhgs.com/",
        //试验室视频回放视频文件在服务器中的绝对路径
        'videoPath'=>'D:\pkpm\网站\UploadVideo',
    'getuser_ip'=>'::1',
    /*拌合站配比编号*/
    'bhz_pbbh'=>[
        '1'=>'c20',
        '2'=>'c25',
        '3'=>'c30',
        '4'=>'c40',
        '5'=>'c50',
        '6'=>'c25'
    ],
    /**
     * 压浆数据报警限值
     */
    'mudjack_warn_standard'=>[
        /*水胶比最小值*/
        'wcratio_min'=>'0.26',
        /*水胶比最大值*/
        'wcratio_max'=>'0.28',
        /*压浆过程中压力最大值,单位为(mpa)*/
        'pressure_max'=>'1.0',
        /*进浆压力最小值*/
        'enter_pressure_min'=>'0.5',
        /*进浆压力最大值*/
        'enter_pressure_max'=>'0.7',
        /*稳压压力正常值(稳压期间的压力值为0.5mpa)*/
        'stabilize_pressure'=>'0.5',
        /*保压时间最小值(保压时间不小于5分钟)*/
        'duration_time_min'=>'300',
    ],

    /**
     * 张拉数据报警限值
     */
    'stretch_warn_standard'=>[
        /*延伸量偏差率限值，在±6%内*/
        'elongation_max'=>6,
        /*持荷时间限值，持荷时间不低于300s*/
        'hold_time_min'=>300,
    ],

    /*
     * 天气信息与天气分类
     *  '天气代码code'=>'天气分类'
     */
     'weather_contrast'=>[
         '0'=>'晴',
         '1'=>'晴',
         '2'=>'晴',
         '3'=>'晴',
         '4'=>'晴',
         '5'=>'晴',
         '6'=>'晴',
         '7'=>'晴',
         '8'=>'晴',
         '9'=>'晴',
         '10'=>'雨',
         '11'=>'雨',
         '12'=>'雨',
         '13'=>'雨',
         '14'=>'雨',
         '15'=>'雨',
         '16'=>'雨',
         '17'=>'雨',
         '18'=>'雨',
         '19'=>'雨',
         '20'=>'雨',
         '21'=>'雪',
         '22'=>'雪',
         '23'=>'雪',
         '24'=>'雪',
         '25'=>'雪',
         '26'=>'晴',
         '27'=>'沙尘大风',
         '28'=>'沙尘大风',
         '29'=>'沙尘大风',
         '30'=>'晴',
         '31'=>'晴',
         '32'=>'晴',
         '33'=>'沙尘大风',
         '34'=>'沙尘大风',
         '35'=>'沙尘大风',
         '36'=>'沙尘大风',
         '37'=>'晴',
         '38'=>'晴',
     ],
    /*
     * 天气分类与施工情况
     * '天气分类'=>'施工情况'
     */
    'weather_construction'=>[
        '晴'=>'施工',
        '雨'=>'停工',
        '沙尘大风'=>'停工',
        '雪'=>'停工',
    ],
    /**
     * 新监控平台控件下载地址
     */
    'ocx_download_url'=>'http://175.6.228.72/views/home/file/cmsocx.exe',

];