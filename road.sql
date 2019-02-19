-- --------------------------------------------------------
-- 主机:                           localhost
-- 服务器版本:                        5.7.19 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 导出 road 的数据库结构
CREATE DATABASE IF NOT EXISTS `road` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `road`;

-- 导出  表 road.beamfield 结构
CREATE TABLE IF NOT EXISTS `beamfield` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `name` varchar(60) NOT NULL COMMENT '梁场名称',
  `tz_num` smallint(3) NOT NULL COMMENT '台座数量',
  `status` varchar(20) NOT NULL COMMENT '状态',
  `created_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projetc_section_name` (`project_id`,`section_id`,`name`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- 正在导出表  road.beamfield 的数据：~4 rows (大约)
DELETE FROM `beamfield`;
/*!40000 ALTER TABLE `beamfield` DISABLE KEYS */;
INSERT INTO `beamfield` (`id`, `project_id`, `section_id`, `name`, `tz_num`, `status`, `created_at`) VALUES
	(1, 4, 2, '梁场1', 1, '1', 1500551743),
	(2, 4, 2, '梁场2', 1, '1', 1501516077),
	(3, 4, 2, '梁场3', 1, '1', 1501516101),
	(4, 4, 2, '梁场4', 1, '1', 1501516126);
/*!40000 ALTER TABLE `beamfield` ENABLE KEYS */;

-- 导出  表 road.collection 结构
CREATE TABLE IF NOT EXISTS `collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `foreign_key` int(10) unsigned NOT NULL COMMENT '对应采集点外键',
  `cat_id` tinyint(2) unsigned NOT NULL COMMENT '设备分类id',
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`project_id`,`section_id`,`name`,`cat_id`) USING BTREE,
  KEY `col` (`project_id`,`section_id`,`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- 正在导出表  road.collection 的数据：~7 rows (大约)
DELETE FROM `collection`;
/*!40000 ALTER TABLE `collection` DISABLE KEYS */;
INSERT INTO `collection` (`id`, `project_id`, `section_id`, `foreign_key`, `cat_id`, `name`) VALUES
	(1, 4, 3, 0, 0, '采集点1'),
	(2, 4, 2, 0, 0, '采集点3'),
	(3, 4, 2, 6, 1, '拌合站45'),
	(6, 4, 2, 4, 3, '隧道3'),
	(7, 4, 2, 3, 2, '梁场3'),
	(9, 4, 2, 4, 2, '梁场4'),
	(10, 4, 2, 7, 1, '拌合站3');
/*!40000 ALTER TABLE `collection` ENABLE KEYS */;

-- 导出  表 road.collection_category 结构
CREATE TABLE IF NOT EXISTS `collection_category` (
  `collection_id` int(10) unsigned NOT NULL,
  `category_id` smallint(3) unsigned NOT NULL,
  KEY `cat` (`category_id`),
  KEY `collection_id` (`collection_id`),
  CONSTRAINT `cat` FOREIGN KEY (`category_id`) REFERENCES `device_category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `coll` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.collection_category 的数据：~0 rows (大约)
DELETE FROM `collection_category`;
/*!40000 ALTER TABLE `collection_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_category` ENABLE KEYS */;

-- 导出  表 road.company 结构
CREATE TABLE IF NOT EXISTS `company` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `sort` smallint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- 正在导出表  road.company 的数据：~4 rows (大约)
DELETE FROM `company`;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` (`id`, `name`, `sort`) VALUES
	(1, '项目管理处', 1),
	(2, '中心实验室', 0),
	(9, '总工办', 0),
	(10, '长安大学', 0);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;

-- 导出  表 road.detection_device 结构
CREATE TABLE IF NOT EXISTS `detection_device` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '设备名称',
  `type` varchar(60) NOT NULL COMMENT '设备类型',
  `factory_name` varchar(60) NOT NULL COMMENT '生产厂家',
  `fzr` varchar(60) NOT NULL COMMENT '负责人',
  `phone` varchar(15) NOT NULL COMMENT '联系方式',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在导出表  road.detection_device 的数据：~0 rows (大约)
DELETE FROM `detection_device`;
/*!40000 ALTER TABLE `detection_device` DISABLE KEYS */;
INSERT INTO `detection_device` (`id`, `name`, `type`, `factory_name`, `fzr`, `phone`) VALUES
	(1, '1', '1', '1', '1', '11');
/*!40000 ALTER TABLE `detection_device` ENABLE KEYS */;

-- 导出  表 road.device 结构
CREATE TABLE IF NOT EXISTS `device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `cat_id` varchar(10) NOT NULL COMMENT '设备分类',
  `name` varchar(60) DEFAULT NULL COMMENT '设备名称',
  `dcode` varchar(30) NOT NULL COMMENT '设备编号',
  `model` varchar(60) NOT NULL COMMENT '设备型号',
  `parame1` varchar(60) NOT NULL COMMENT '精度等级',
  `parame2` varchar(60) NOT NULL COMMENT '最大负荷',
  `parame3` varchar(60) DEFAULT NULL,
  `parame4` varchar(60) DEFAULT NULL,
  `parame5` varchar(60) DEFAULT NULL,
  `parame6` varchar(60) DEFAULT NULL,
  `factory_name` varchar(60) NOT NULL COMMENT '生产厂家',
  `factory_date` varchar(18) DEFAULT NULL COMMENT '生产日期',
  `fzr` varchar(60) NOT NULL COMMENT '负责人',
  `phone` varchar(15) NOT NULL COMMENT '联系方式',
  `is_online` tinyint(1) unsigned DEFAULT '0' COMMENT '是否在线0 不在线 1在线',
  `last_time` int(11) unsigned DEFAULT NULL COMMENT '最新上报时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`dcode`) USING BTREE,
  KEY `project` (`project_id`),
  KEY `section` (`section_id`),
  KEY `cat` (`cat_id`) USING BTREE,
  KEY `supervision_id` (`supervision_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

-- 正在导出表  road.device 的数据：~6 rows (大约)
DELETE FROM `device`;
/*!40000 ALTER TABLE `device` DISABLE KEYS */;
INSERT INTO `device` (`id`, `project_id`, `supervision_id`, `section_id`, `cat_id`, `name`, `dcode`, `model`, `parame1`, `parame2`, `parame3`, `parame4`, `parame5`, `parame6`, `factory_name`, `factory_date`, `fzr`, `phone`, `is_online`, `last_time`) VALUES
	(71, 4, 6, 18, '1', 'TYA-300B型微机控制恒加载抗折抗压试验机', '00000000201100', 'TYA-300B型', '10KN、300KN', '/', '/', '/', NULL, NULL, '无锡新路达仪器设备有限公司', '2018-02-02', '益紫林', '15332580433', 0, NULL),
	(73, 4, 6, 18, '3', '电液伺服万能试验机（微机控制）', '00000000203300', 'WA-100C', '100KN', '/', '/', '/', NULL, NULL, '无锡新路达仪器设备有限公司', '2017-02-01', '益紫林', '15332580433', 0, NULL),
	(74, 4, 6, 18, '3', 'WA-300C电液伺服万能试验机（微机控制）', '00000000203200', 'WA-300C', '300KN', '/', '/', '/', NULL, NULL, '无锡新路达仪器设备有限公司', '2017-12-01', '益紫林', '15332580433', 0, 1521579688),
	(75, 4, 6, 18, '3', 'WA-1000C电液伺服万能试验机（微机控制）', '00000000203100', 'WA-1000C', '1000KN', '/', '/', '/', NULL, NULL, '无锡新路达仪器设备有限公司', '2018-01-01', '益紫林', '15332580433', 0, 1521583070),
	(76, 4, 6, 18, '2', 'TYA-2000A型微机控制恒加载试验机', '00000000202100', 'TYA-2000A型', '2000KN', '/', '/', '/', NULL, NULL, '无锡新路达仪器设备有限公司', '2018-02-01', '益紫林', '15332580433', 0, 1521613689),
	(77, 4, 6, 18, '4', '设备1', '000000120013', 'abc', '2', '2', '2', '2', NULL, NULL, 'abc', '2018-03-24', '2', '2', 0, NULL);
/*!40000 ALTER TABLE `device` ENABLE KEYS */;

-- 导出  表 road.device_category 结构
CREATE TABLE IF NOT EXISTS `device_category` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- 正在导出表  road.device_category 的数据：~14 rows (大约)
DELETE FROM `device_category`;
/*!40000 ALTER TABLE `device_category` DISABLE KEYS */;
INSERT INTO `device_category` (`id`, `name`) VALUES
	(3, '万能试验机'),
	(7, '全站仪'),
	(2, '压力试验机'),
	(9, '压浆设备'),
	(6, '压路机设备'),
	(8, '张拉设备'),
	(1, '抗折压一体机'),
	(4, '拌合设备'),
	(10, '智能喷淋养生设备'),
	(11, '智能蒸汽养生设备'),
	(13, '架桥机'),
	(15, '水准仪'),
	(14, '运架一体机'),
	(12, '运梁车');
/*!40000 ALTER TABLE `device_category` ENABLE KEYS */;

-- 导出  表 road.device_type 结构
CREATE TABLE IF NOT EXISTS `device_type` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(3) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `caategory_name` (`category_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.device_type 的数据：~0 rows (大约)
DELETE FROM `device_type`;
/*!40000 ALTER TABLE `device_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_type` ENABLE KEYS */;

-- 导出  表 road.device_user 结构
CREATE TABLE IF NOT EXISTS `device_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `name` varchar(45) NOT NULL COMMENT '名称',
  `phone` varchar(15) NOT NULL COMMENT '电话',
  `position` varchar(45) NOT NULL COMMENT '职位',
  `email` varchar(60) DEFAULT NULL COMMENT '邮箱',
  `type` tinyint(1) unsigned NOT NULL COMMENT '用户类型1试验2张拉压浆3拌合',
  `created_at` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `p` (`project_id`),
  KEY `s` (`supervision_id`),
  KEY `se` (`section_id`),
  KEY `t` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.device_user 的数据：~0 rows (大约)
DELETE FROM `device_user`;
/*!40000 ALTER TABLE `device_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_user` ENABLE KEYS */;

-- 导出  表 road.factory 结构
CREATE TABLE IF NOT EXISTS `factory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '厂家名称',
  `data_row` varchar(10) DEFAULT NULL COMMENT '数据总行数',
  `data_analyse_type` varchar(10) DEFAULT NULL COMMENT '数据分析类型',
  `date_position_row` varchar(10) DEFAULT NULL COMMENT '日期位置行',
  `date_position_col` varchar(10) DEFAULT NULL COMMENT '日期位置列',
  `date_formate_type` varchar(10) DEFAULT NULL COMMENT '日期格式',
  `time_position_row` varchar(10) DEFAULT NULL COMMENT '时间位置行',
  `time_position_col` varchar(10) DEFAULT NULL COMMENT '时间位置列',
  `time_formate_type` varchar(10) DEFAULT NULL COMMENT '时间格式',
  `pb_number_position_row` varchar(10) DEFAULT NULL COMMENT '配比编号位置行',
  `pb_number_position_col` varchar(10) DEFAULT NULL COMMENT '配比编号位置列',
  `design_ysb_position_row` varchar(10) DEFAULT NULL COMMENT '设计油石比位置行',
  `design_ysb_position_col` varchar(10) DEFAULT NULL COMMENT '设计油石比位置列',
  `design_ysb_standard` varchar(10) DEFAULT NULL COMMENT '设计油石比标准',
  `fact_ysb_position_row` varchar(10) DEFAULT NULL COMMENT '实际油石比位置行',
  `fact_ysb_position_col` varchar(10) DEFAULT NULL COMMENT '实际油石比位置列',
  `hhlwd_position_row` varchar(10) DEFAULT NULL COMMENT '混合料温度位置行',
  `hhlwd_postion_col` varchar(10) DEFAULT NULL COMMENT '混合料温度位置列',
  `hhlwd_standard` varchar(10) DEFAULT NULL COMMENT '混合料温度标准',
  `hhlwd_bj` varchar(10) DEFAULT NULL COMMENT '开启混合料温度报警',
  `hhlwd_bj_pc` varchar(10) DEFAULT NULL COMMENT '混合料温度报警偏差',
  `lcwd_position_row` varchar(10) DEFAULT NULL COMMENT '溜槽温度位置行',
  `lcwd_position_col` varchar(10) DEFAULT NULL COMMENT '溜槽温度位置列',
  `lcwd_standard` varchar(10) DEFAULT NULL COMMENT '溜槽温度标准',
  `lcwd_bj` varchar(10) DEFAULT NULL COMMENT '开启溜槽温度报警',
  `lcwd_bj_pc` varchar(10) DEFAULT NULL COMMENT '溜槽温度报警偏差',
  `lqwd_position_row` varchar(10) DEFAULT NULL COMMENT '沥青温度位置行',
  `lqwd_position_col` varchar(10) DEFAULT NULL COMMENT '沥青温度位置列',
  `lqwd_standard` varchar(10) DEFAULT NULL COMMENT '沥青温度标准',
  `lqwd_bj` varchar(10) DEFAULT NULL COMMENT '开启沥青温度报警',
  `lqwd_bj_pc` varchar(10) DEFAULT NULL COMMENT '沥青温度报警偏差',
  `cl_position_row` varchar(10) DEFAULT NULL COMMENT '产量位置行',
  `cl_position_col` varchar(10) DEFAULT NULL COMMENT '产量位置列',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在导出表  road.factory 的数据：~2 rows (大约)
DELETE FROM `factory`;
/*!40000 ALTER TABLE `factory` DISABLE KEYS */;
INSERT INTO `factory` (`id`, `name`, `data_row`, `data_analyse_type`, `date_position_row`, `date_position_col`, `date_formate_type`, `time_position_row`, `time_position_col`, `time_formate_type`, `pb_number_position_row`, `pb_number_position_col`, `design_ysb_position_row`, `design_ysb_position_col`, `design_ysb_standard`, `fact_ysb_position_row`, `fact_ysb_position_col`, `hhlwd_position_row`, `hhlwd_postion_col`, `hhlwd_standard`, `hhlwd_bj`, `hhlwd_bj_pc`, `lcwd_position_row`, `lcwd_position_col`, `lcwd_standard`, `lcwd_bj`, `lcwd_bj_pc`, `lqwd_position_row`, `lqwd_position_col`, `lqwd_standard`, `lqwd_bj`, `lqwd_bj_pc`, `cl_position_row`, `cl_position_col`) VALUES
	(1, '西咸3标102', '1', '1', '', '', '', '', '', '1', '1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
	(2, '西咸2标', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
/*!40000 ALTER TABLE `factory` ENABLE KEYS */;

-- 导出  表 road.factory_detail 结构
CREATE TABLE IF NOT EXISTS `factory_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `factory_id` int(10) unsigned NOT NULL COMMENT '厂家id/编号',
  `material_id` mediumint(5) unsigned NOT NULL COMMENT '材料id/编号',
  `order_num` int(10) unsigned NOT NULL COMMENT '顺序号',
  `cl_position_row` varchar(8) NOT NULL COMMENT '材料位置行',
  `cl_position_col` varchar(8) NOT NULL COMMENT '材料位置列',
  `fact_z_cjjs` varchar(8) DEFAULT NULL COMMENT '实际值采集计算',
  `fact_z_position_row` varchar(8) DEFAULT NULL COMMENT '实际值位置行',
  `fact_z_position_col` varchar(8) DEFAULT NULL COMMENT '实际值位置列',
  `design_z_cjjs` varchar(8) DEFAULT NULL COMMENT '设计值采集计算',
  `design_z_position_row` varchar(8) DEFAULT NULL COMMENT '设计值位置行',
  `design_z_position_col` varchar(8) DEFAULT NULL COMMENT '设计值位置列',
  PRIMARY KEY (`id`),
  UNIQUE KEY `factory_material` (`factory_id`,`material_id`),
  KEY `factory_id` (`factory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在导出表  road.factory_detail 的数据：~0 rows (大约)
DELETE FROM `factory_detail`;
/*!40000 ALTER TABLE `factory_detail` DISABLE KEYS */;
INSERT INTO `factory_detail` (`id`, `factory_id`, `material_id`, `order_num`, `cl_position_row`, `cl_position_col`, `fact_z_cjjs`, `fact_z_position_row`, `fact_z_position_col`, `design_z_cjjs`, `design_z_position_row`, `design_z_position_col`) VALUES
	(1, 2, 2, 1, '1', '1', '1', '1', '1', '1', '1', '1');
/*!40000 ALTER TABLE `factory_detail` ENABLE KEYS */;

-- 导出  表 road.lab_info 结构
CREATE TABLE IF NOT EXISTS `lab_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL COMMENT '所属项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '所属标段id',
  `device_id` int(10) unsigned NOT NULL COMMENT '设备id',
  `device_type` tinyint(1) unsigned DEFAULT NULL COMMENT '设备类型',
  `device_cat` tinyint(1) unsigned NOT NULL COMMENT '设备分类',
  `time` int(11) unsigned NOT NULL COMMENT '实验时间',
  `sybh` varchar(30) DEFAULT NULL COMMENT '实验编号',
  `sydw` varchar(90) DEFAULT NULL COMMENT '实验单位',
  `jldw` varchar(90) DEFAULT NULL COMMENT '监理单位',
  `wtdw` varchar(90) DEFAULT NULL COMMENT '委托单位',
  `sylx` varchar(30) DEFAULT NULL COMMENT '实验类型',
  `syzh` varchar(30) DEFAULT NULL COMMENT '实验组号',
  `sypz` varchar(30) DEFAULT NULL COMMENT '试验品种',
  `sylq` varchar(20) DEFAULT NULL COMMENT '实验龄期(天)',
  `qddj` varchar(20) DEFAULT NULL COMMENT '强度等级',
  `syry` varchar(45) DEFAULT NULL COMMENT '实验人员',
  `lbph` varchar(30) DEFAULT NULL COMMENT '类别牌号',
  `sjgg` varchar(45) DEFAULT NULL COMMENT '试件规格',
  `sjgs` varchar(30) DEFAULT NULL COMMENT '试件个数',
  `jzsl` varchar(30) DEFAULT NULL COMMENT '加载速率',
  `yxlz` varchar(30) DEFAULT NULL COMMENT '有效力值(KN)',
  `yxqd` varchar(30) DEFAULT NULL COMMENT '有效强度(m pa)',
  `xqfqd` varchar(30) DEFAULT NULL COMMENT '下屈服强度',
  `klqd` varchar(30) DEFAULT NULL COMMENT '抗拉强度',
  `xqflz` varchar(30) DEFAULT NULL COMMENT '下屈服力值',
  `jxzh` varchar(30) DEFAULT NULL COMMENT '极限载荷',
  `jxqd` varchar(30) DEFAULT NULL COMMENT '极限强度',
  `image` text COMMENT '图片',
  `is_warn` tinyint(1) unsigned DEFAULT NULL COMMENT '1不合格0合格',
  `warn_info` varchar(100) DEFAULT NULL COMMENT '报警信息',
  `warn_level` varchar(100) DEFAULT NULL COMMENT '报警级别',
  `is_warn_para1` tinyint(1) unsigned DEFAULT NULL COMMENT '1不合格0合格',
  `is_warn_para2` tinyint(1) unsigned DEFAULT NULL COMMENT '1不合格0合格',
  `is_warn_para3` tinyint(1) unsigned DEFAULT NULL COMMENT '1不合格0合格',
  `is_sup_deal` tinyint(1) unsigned DEFAULT '0' COMMENT '是否处理异常',
  `is_sec_deal` tinyint(1) unsigned DEFAULT '0',
  `is_pro_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '是否项目部处理 高级报警需要其处理',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '上传时间',
  `is_24_notice` tinyint(1) unsigned DEFAULT '0' COMMENT '是否发送24小通知 1是0否',
  `is_48_notice` tinyint(1) unsigned DEFAULT '0' COMMENT '是否发送48小通知 1是0否',
  `reportFile` varchar(500) DEFAULT NULL,
  `warn_sx_level` tinyint(1) unsigned DEFAULT NULL COMMENT '1初级2中级3高级 因时限导致的报警升级',
  `warn_sx_info` varchar(45) DEFAULT NULL COMMENT '时限升级原因',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `section_id` (`section_id`),
  KEY `device_id` (`device_id`),
  KEY `device_type` (`device_type`),
  KEY `device_cat` (`device_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_info 的数据：~3 rows (大约)
DELETE FROM `lab_info`;
/*!40000 ALTER TABLE `lab_info` DISABLE KEYS */;
INSERT INTO `lab_info` (`id`, `project_id`, `supervision_id`, `section_id`, `device_id`, `device_type`, `device_cat`, `time`, `sybh`, `sydw`, `jldw`, `wtdw`, `sylx`, `syzh`, `sypz`, `sylq`, `qddj`, `syry`, `lbph`, `sjgg`, `sjgs`, `jzsl`, `yxlz`, `yxqd`, `xqfqd`, `klqd`, `xqflz`, `jxzh`, `jxqd`, `image`, `is_warn`, `warn_info`, `warn_level`, `is_warn_para1`, `is_warn_para2`, `is_warn_para3`, `is_sup_deal`, `is_sec_deal`, `is_pro_deal`, `created_at`, `is_24_notice`, `is_48_notice`, `reportFile`, `warn_sx_level`, `warn_sx_info`) VALUES
	(199, 4, 6, 18, 74, 20, 3, 1521512940, 'TJ1-BG-2018-GJJ-0006', '西安外环高速公路南段试验检测中心', '陕西交建公路工程试验检测有限公司', '陕西交建公路工程试验检测有限公司', '3', 'GJ-2018-0004', '热轧带肋钢筋', NULL, NULL, '尹红梅', 'HRB400E', 'HRB400E12', '2', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1521579688, 0, 0, 'http://sys.xawhgs.com/Applications/TestFlow/ReportPrint/Ajax_reportPrint.aspx?itemCode=JT_GJCC&reportClass=%E6%8A%A5%E5%91%8A&reportNum=TJ1-BG-2018-GJJ-0006&regCode=00041&genType=html&printTypes=%E6%8A%A5%E5%91%8A&needGenRecord=no&printDirect=1', NULL, NULL),
	(200, 4, 6, 18, 75, 20, 3, 1511231799, 'TJ1-BG-2018-GJJ-0008', '西安外环高速公路南段试验检测中心', '陕西交建公路工程试验检测有限公司', '陕西交建公路工程试验检测有限公司', '6', '16-007111', NULL, '28', 'C10', '益紫林', NULL, '150×150×150', '3', '35.33', '2092.5', '9.3', NULL, NULL, NULL, NULL, NULL, NULL, 1, '抗压强度不合格！', NULL, NULL, NULL, NULL, 0, 0, NULL, 1521583070, 0, 0, 'http://sys.xawhgs.com/Applications/TestFlow/ReportPrint/Ajax_reportPrint.aspx?itemCode=JT_HNKY&reportClass=%E6%8A%A5%E5%91%8A&reportNum=TJ1-BG-2018-GJJ-0008&regCode=00041&genType=html&printTypes=%E6%8A%A5%E5%91%8A&needGenRecord=no&printDirect=1', NULL, NULL),
	(201, 4, 6, 18, 76, 20, 2, 1521612689, 'TJ1-BG-2018-GJJ-0013', '西安外环高速公路南段试验检测中心', '陕西交建公路工程试验检测有限公司', '陕西交建公路工程试验检测有限公司', '6', 'SH-2018-0004', NULL, '28', 'C30', '益紫林', NULL, '150×150×150', '3', '13.5', '13972.5', '62.1', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1521613689, 0, 0, 'http://sys.xawhgs.com/Applications/TestFlow/ReportPrint/Ajax_reportPrint.aspx?itemCode=JT_HNKY&reportClass=%E6%8A%A5%E5%91%8A&reportNum=TJ1-BG-2018-GJJ-0013&regCode=00041&genType=html&printTypes=%E6%8A%A5%E5%91%8A&needGenRecord=no&printDirect=1', NULL, NULL);
/*!40000 ALTER TABLE `lab_info` ENABLE KEYS */;

-- 导出  表 road.lab_info_deal 结构
CREATE TABLE IF NOT EXISTS `lab_info_deal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_info_id` int(10) unsigned NOT NULL COMMENT '水泥拌合站信息id',
  `sec_info` varchar(300) DEFAULT '' COMMENT '标段处理信息',
  `sec_name` varchar(60) DEFAULT '' COMMENT '标段处理人',
  `sec_time` int(11) unsigned DEFAULT NULL COMMENT '标段处理时间',
  `sup_info` varchar(300) DEFAULT '' COMMENT '监理处理信息',
  `sup_name` varchar(60) DEFAULT '' COMMENT '监理处理人',
  `sup_time` int(11) unsigned DEFAULT NULL COMMENT '监理处理时间',
  `pro_info` varchar(300) DEFAULT NULL,
  `pro_name` varchar(60) DEFAULT NULL,
  `pro_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lab_info_id` (`lab_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_info_deal 的数据：~4 rows (大约)
DELETE FROM `lab_info_deal`;
/*!40000 ALTER TABLE `lab_info_deal` DISABLE KEYS */;
INSERT INTO `lab_info_deal` (`id`, `lab_info_id`, `sec_info`, `sec_name`, `sec_time`, `sup_info`, `sup_name`, `sup_time`, `pro_info`, `pro_name`, `pro_time`) VALUES
	(1, 11, 'ui', 'yf2234', 1508228955, '', '', NULL, NULL, NULL, NULL),
	(2, 26, '', '', NULL, '1234', 'yf2234', 1510485835, NULL, NULL, NULL),
	(3, 17, '整改完成', '蔡柯萌', 1516104563, '', '', NULL, NULL, NULL, NULL),
	(4, 19, '已整改', '蔡柯萌', 1516105527, '', '', NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `lab_info_deal` ENABLE KEYS */;

-- 导出  表 road.lab_info_detail 结构
CREATE TABLE IF NOT EXISTS `lab_info_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_info_id` int(10) unsigned NOT NULL,
  `type` tinyint(2) unsigned NOT NULL COMMENT '物料类型',
  `lz` double(8,2) NOT NULL COMMENT '力值',
  `qd` double(8,2) NOT NULL COMMENT '强度',
  `image` mediumtext COMMENT '试验图片',
  `videoName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `snbhz_info_id` (`lab_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_info_detail 的数据：~4 rows (大约)
DELETE FROM `lab_info_detail`;
/*!40000 ALTER TABLE `lab_info_detail` DISABLE KEYS */;
INSERT INTO `lab_info_detail` (`id`, `lab_info_id`, `type`, `lz`, `qd`, `image`, `videoName`) VALUES
	(180, 200, 1, 274.08, 12.20, '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', '00088-00088CAJC18000000013-jixianghezai1-1'),
	(181, 201, 2, 210.11, 9.30, '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', '00088-00088CAJC18000000013-jixianghezai2-1'),
	(182, 201, 3, 193.45, 8.60, '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', '00088-00088CAJC18000000013-jixianghezai3-1'),
	(183, 201, 1, 1410.28, 62.70, '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', '00088-00088CAJC18000000013-jixianghezai1-1');
/*!40000 ALTER TABLE `lab_info_detail` ENABLE KEYS */;

-- 导出  表 road.lab_info_gjsy_detail 结构
CREATE TABLE IF NOT EXISTS `lab_info_gjsy_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_info_id` int(10) unsigned NOT NULL,
  `type` tinyint(2) unsigned NOT NULL COMMENT '试件序号',
  `lz` double(8,2) NOT NULL COMMENT '力值',
  `qd` double(8,2) NOT NULL COMMENT '强度',
  `is_qd_warn` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '下屈服强度是否报警',
  `jxhz` double(8,2) NOT NULL COMMENT '极限荷载',
  `jxqd` double(8,2) NOT NULL COMMENT '极限强度/抗拉强度',
  `is_jxqd_warn` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '抗拉强度是否报警',
  `image` text COMMENT '试验图片',
  `videoName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `snbhz_info_id` (`lab_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_info_gjsy_detail 的数据：~27 rows (大约)
DELETE FROM `lab_info_gjsy_detail`;
/*!40000 ALTER TABLE `lab_info_gjsy_detail` DISABLE KEYS */;
INSERT INTO `lab_info_gjsy_detail` (`id`, `lab_info_id`, `type`, `lz`, `qd`, `is_qd_warn`, `jxhz`, `jxqd`, `is_jxqd_warn`, `image`, `videoName`) VALUES
	(1, 25, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(2, 25, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(3, 25, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(4, 26, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(5, 26, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(6, 26, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(7, 27, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(8, 27, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(9, 27, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(10, 28, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(11, 28, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(12, 28, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(13, 29, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(14, 29, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(15, 29, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(16, 30, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(17, 30, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(18, 30, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(19, 31, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(20, 31, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(21, 31, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(22, 32, 1, 660.00, 400.00, 0, 120.00, 540.00, 0, NULL, NULL),
	(23, 32, 2, 650.00, 410.00, 0, 120.00, 550.00, 0, NULL, NULL),
	(24, 32, 3, 500.00, 390.00, 1, 120.00, 550.00, 0, NULL, NULL),
	(70, 176, 1, 211.15, 430.00, 0, 294.77, 600.00, 0, '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', NULL),
	(71, 199, 1, 48.53, 429.00, 0, 67.50, 597.00, 0, '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', '00088-00088CAJC18000000006-jxyl1,qfyl1-1'),
	(72, 199, 2, 47.91, 424.00, 0, 68.10, 602.00, 0, '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', '00088-00088CAJC18000000006-jxyl2,qfyl2-1');
/*!40000 ALTER TABLE `lab_info_gjsy_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report 结构
CREATE TABLE IF NOT EXISTS `lab_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_info_id` int(10) unsigned NOT NULL,
  `sylx` tinyint(2) unsigned NOT NULL COMMENT '试验类型',
  `wtdw` varchar(100) DEFAULT NULL COMMENT '委托单位',
  `gcmc` varchar(100) DEFAULT NULL COMMENT '工程名称',
  `sgbw` varchar(100) DEFAULT NULL COMMENT '施工部位',
  `cdcm` varchar(90) DEFAULT NULL COMMENT '产地厂名/母材产地/生产厂家/厂名牌号',
  `bmxz` varchar(60) DEFAULT NULL COMMENT '表面形状',
  `bgbh` varchar(60) DEFAULT NULL COMMENT '报告编号',
  `wtbh` varchar(60) DEFAULT NULL COMMENT '委托编号',
  `jlbh` varchar(60) DEFAULT NULL COMMENT '记录编号',
  `wtrq` int(11) DEFAULT NULL COMMENT '委托日期',
  `syrq` int(11) DEFAULT NULL COMMENT '试验日期',
  `ljfs` varchar(60) DEFAULT NULL COMMENT '连接方式',
  `qydd` varchar(100) DEFAULT NULL COMMENT '取样地点',
  `bzzl` varchar(30) DEFAULT NULL COMMENT '包装种类',
  `pzdj` varchar(30) DEFAULT NULL COMMENT '品种等级',
  `ccbh` varchar(60) DEFAULT NULL COMMENT '出厂编号',
  `bgrq` int(11) DEFAULT NULL COMMENT '报告日期',
  `dbsl` varchar(30) DEFAULT NULL COMMENT '代表数量',
  `mcqd` varchar(60) DEFAULT NULL COMMENT '母材强度',
  `gjph` varchar(30) DEFAULT NULL COMMENT '钢筋牌号',
  `jcpdyj` varchar(360) DEFAULT NULL COMMENT '检测评定依据',
  `syjl` varchar(360) DEFAULT NULL COMMENT '试验结论',
  `bz` varchar(360) DEFAULT NULL COMMENT '备注',
  `syr` varchar(30) DEFAULT NULL COMMENT '试验',
  `fh` varchar(30) DEFAULT NULL COMMENT '复核',
  `pz` varchar(30) DEFAULT NULL COMMENT '批准',
  PRIMARY KEY (`id`),
  KEY `lab_info_id` (`lab_info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report 的数据：~0 rows (大约)
DELETE FROM `lab_report`;
/*!40000 ALTER TABLE `lab_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report` ENABLE KEYS */;

-- 导出  表 road.lab_report_gjhj_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_gjhj_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjxh` tinyint(2) unsigned DEFAULT NULL COMMENT '试件序号，0为规定标准值',
  `qybw` varchar(100) DEFAULT NULL COMMENT '取样部位',
  `hjzl` varchar(30) DEFAULT NULL COMMENT '焊接种类',
  `hfcd` varchar(30) DEFAULT NULL COMMENT '焊缝长度Lh（mm）',
  `hgxm` varchar(60) DEFAULT NULL COMMENT '焊工姓名及上岗证编号',
  `gczj` varchar(30) DEFAULT NULL COMMENT '公称直径a（mm）',
  `gcjmj` varchar(30) DEFAULT NULL COMMENT '公称截面面积S0(mm2)',
  `ldzdl` varchar(30) DEFAULT NULL COMMENT '拉断最大力Fb（kN）',
  `klqd` varchar(30) DEFAULT NULL COMMENT '抗拉强度σb（MPa）',
  `dlwzms` varchar(100) DEFAULT NULL COMMENT '断裂位置描述',
  `dktzms` varchar(100) DEFAULT NULL COMMENT '断口特征描述',
  `wxjd` varchar(30) DEFAULT NULL COMMENT '弯心角度α（°）',
  `wqytzj` varchar(30) DEFAULT NULL COMMENT '弯曲压头直径d（mm）',
  `wqwbms` varchar(100) DEFAULT NULL COMMENT '弯曲外表面描述',
  `wqjg` varchar(100) DEFAULT NULL COMMENT '弯曲结果',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_id_gjhj` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_gjhj_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_gjhj_detail`;
/*!40000 ALTER TABLE `lab_report_gjhj_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_gjhj_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_gjjxlj_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_gjjxlj_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjxh` tinyint(2) unsigned DEFAULT NULL COMMENT '试件序号，0为规定标准值',
  `mcgczj` varchar(100) DEFAULT NULL COMMENT '母材公称直径a(mm)',
  `mcgcmj` varchar(30) DEFAULT NULL COMMENT '母材公称面积S（mm2）',
  `mczdl` varchar(30) DEFAULT NULL COMMENT '母材最大力Fb(kN)',
  `mcklqd` varchar(60) DEFAULT NULL COMMENT '母材抗拉强度σb(MPa)',
  `mcdltz` varchar(30) DEFAULT NULL COMMENT '母材断裂特征',
  `sjljcd` varchar(30) DEFAULT NULL COMMENT '试件连接长度L（mm）',
  `jtmj` varchar(30) DEFAULT NULL COMMENT '接头面积S（mm2）',
  `jtzdl` varchar(30) DEFAULT NULL COMMENT '接头最大力Fb(kN)',
  `jtklqd` varchar(100) DEFAULT NULL COMMENT '接头抗拉强度σb (MPa)',
  `jljcddjl` varchar(100) DEFAULT NULL COMMENT '距连接处端点距离（mm）',
  `jtdltz` varchar(30) DEFAULT NULL COMMENT '接头断裂特征',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_id_gjjxlj` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_gjjxlj_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_gjjxlj_detail`;
/*!40000 ALTER TABLE `lab_report_gjjxlj_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_gjjxlj_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_gjyc_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_gjyc_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjxh` tinyint(2) unsigned DEFAULT NULL COMMENT '试件序号，0为规定标准值',
  `gczj` varchar(30) DEFAULT NULL COMMENT '公称直径a（mm）',
  `gcjmj` varchar(30) DEFAULT NULL COMMENT '公称截面面积S0(mm2)',
  `ysbj` varchar(30) DEFAULT NULL COMMENT '原始标距L0（mm）',
  `jfd` varchar(30) DEFAULT NULL COMMENT '屈服点σ',
  `klqd` varchar(30) DEFAULT NULL COMMENT '抗拉强度σb（MPa）',
  `scl` varchar(30) DEFAULT NULL COMMENT '伸长率δ（%）',
  `zdlzscl` varchar(30) DEFAULT NULL COMMENT '最大力总伸长率A',
  `sclsqd` varchar(30) DEFAULT NULL COMMENT '实测拉伸强度与实测屈服强度之',
  `scqfqd` varchar(30) DEFAULT NULL COMMENT '实测屈服强度与规定的屈服强度',
  `wxjd` varchar(30) DEFAULT NULL COMMENT '弯心角度α（°）',
  `wqytzj` varchar(30) DEFAULT NULL COMMENT '弯曲压头直径d（mm）',
  `wqwbms` varchar(100) DEFAULT NULL COMMENT '弯曲外表面描述',
  `wqjg` varchar(100) DEFAULT NULL COMMENT '弯曲结果',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_id` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_gjyc_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_gjyc_detail`;
/*!40000 ALTER TABLE `lab_report_gjyc_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_gjyc_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_hntky_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_hntky_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjbh` varchar(90) DEFAULT NULL COMMENT '试件编号',
  `syrq` int(11) DEFAULT NULL COMMENT '试验日期',
  `lq` varchar(30) DEFAULT NULL COMMENT '龄期',
  `cymj1` varchar(30) DEFAULT NULL COMMENT '承压面积1',
  `cymj2` varchar(30) DEFAULT NULL COMMENT '承压面积2',
  `cymj3` varchar(30) DEFAULT NULL COMMENT '承压面积3',
  `kyqd1` varchar(30) DEFAULT NULL COMMENT '抗压强度1',
  `kyqd2` varchar(30) DEFAULT NULL COMMENT '抗压强度2',
  `kyqd3` varchar(30) DEFAULT NULL COMMENT '抗压强度3',
  `kyqd` varchar(30) DEFAULT NULL COMMENT '抗压强度组值',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_hntky_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_hntky_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_hntky_detail`;
/*!40000 ALTER TABLE `lab_report_hntky_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_hntky_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_hntkz_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_hntkz_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjbh` varchar(90) DEFAULT NULL COMMENT '试件编号',
  `syrq` int(11) DEFAULT NULL COMMENT '试验日期',
  `lq` varchar(30) DEFAULT NULL COMMENT '龄期',
  `zzjkd1` varchar(30) DEFAULT NULL COMMENT '支座间跨度1',
  `zzjkd2` varchar(30) DEFAULT NULL COMMENT '支座间跨度2',
  `zzjkd3` varchar(30) DEFAULT NULL COMMENT '支座间跨度3',
  `sjjmkd1` varchar(30) DEFAULT NULL COMMENT '试件截面宽度1',
  `sjjmkd2` varchar(30) DEFAULT NULL COMMENT '试件截面宽度2',
  `sjjmkd3` varchar(30) DEFAULT NULL COMMENT '试件截面宽度3',
  `sjjmgd1` varchar(30) DEFAULT NULL COMMENT '试件截面高度1',
  `sjjmgd2` varchar(30) DEFAULT NULL COMMENT '试件截面高度2',
  `sjjmgd3` varchar(30) DEFAULT NULL COMMENT '试件截面高度3',
  `kzqd1` varchar(30) DEFAULT NULL COMMENT '抗折强度1',
  `kzqd2` varchar(30) DEFAULT NULL COMMENT '抗折强度2',
  `kzqd3` varchar(30) DEFAULT NULL COMMENT '抗折强度3',
  `kzqd` varchar(30) DEFAULT NULL COMMENT '抗折强度组值',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_hntkz_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_hntkz_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_hntkz_detail`;
/*!40000 ALTER TABLE `lab_report_hntkz_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_hntkz_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_hntsjtj_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_hntsjtj_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjqddj` varchar(30) DEFAULT NULL COMMENT '设计强度等级',
  `sjssz` varchar(30) DEFAULT NULL COMMENT '设计收缩值',
  `sjtxml` varchar(30) DEFAULT NULL COMMENT '设计弹性模量(MPa)',
  `sjkzqd` varchar(30) DEFAULT NULL COMMENT '设计抗折强度',
  `llphbbgbh` varchar(90) DEFAULT NULL COMMENT '理论配合比报告编号',
  `llphb` varchar(90) DEFAULT NULL COMMENT '理论配合比',
  `sjb1` varchar(60) DEFAULT NULL COMMENT '水胶比1',
  `sjb2` varchar(60) DEFAULT NULL COMMENT '水胶比2',
  `sgphb` varchar(60) DEFAULT NULL COMMENT '施工配合比',
  `gdbhff` varchar(60) DEFAULT NULL COMMENT '工地拌和方法',
  `gddsff` varchar(60) DEFAULT NULL COMMENT '工地捣实方法',
  `zjdsff` varchar(60) DEFAULT NULL COMMENT '制件捣实方法',
  `zjstld` varchar(30) DEFAULT NULL COMMENT '制件时坍落度(mm)',
  `zjskzd` varchar(30) DEFAULT NULL COMMENT '制件时扩展度(mm)',
  `zjwbcd` varchar(30) DEFAULT NULL COMMENT '制件维勃稠度(s)',
  `zjrq` int(11) DEFAULT NULL COMMENT '制件日期',
  `sjcc` varchar(30) DEFAULT NULL COMMENT '试件尺寸(mm)',
  `yhff` varchar(60) DEFAULT NULL COMMENT '养护方法',
  `yhwd` varchar(30) DEFAULT NULL COMMENT '养护温度',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_hntsjtj` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_hntsjtj_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_hntsjtj_detail`;
/*!40000 ALTER TABLE `lab_report_hntsjtj_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_hntsjtj_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_hntss_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_hntss_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjbh1` varchar(90) DEFAULT NULL COMMENT '试件编号1',
  `sjbh2` varchar(90) DEFAULT NULL COMMENT '试件编号2',
  `sjbh3` varchar(90) DEFAULT NULL COMMENT '试件编号3',
  `cdrq` int(11) DEFAULT NULL COMMENT '测定日期',
  `lq` varchar(30) DEFAULT NULL COMMENT '龄期',
  `dkssz1` varchar(30) DEFAULT NULL COMMENT '单块收缩值1',
  `dkssz2` varchar(30) DEFAULT NULL COMMENT '单块收缩值2',
  `dkssz3` varchar(30) DEFAULT NULL COMMENT '单块收缩值3',
  `pjssz` varchar(30) DEFAULT NULL COMMENT '平均收缩值',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_hntss_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_hntss_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_hntss_detail`;
/*!40000 ALTER TABLE `lab_report_hntss_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_hntss_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_hntsycl_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_hntsycl_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sn_cd` varchar(60) DEFAULT NULL COMMENT '水泥产地',
  `sn_gg` varchar(30) DEFAULT NULL COMMENT '水泥规格',
  `sn_bgbh` varchar(90) DEFAULT NULL COMMENT '水泥报告编号',
  `sn_yl` varchar(30) DEFAULT NULL COMMENT '水泥用量',
  `chl1_cd` varchar(60) DEFAULT NULL COMMENT '掺和料1产地',
  `chl1_gg` varchar(30) DEFAULT NULL COMMENT '掺和料1规格',
  `chl1_bgbh` varchar(90) DEFAULT NULL COMMENT '掺和料1报告编号',
  `chl1_yl` varchar(30) DEFAULT NULL COMMENT '掺和料1用量',
  `chl2_cd` varchar(60) DEFAULT NULL COMMENT '掺和料2产地',
  `chl2_gg` varchar(30) DEFAULT NULL COMMENT '掺和料2规格',
  `chl2_bgbh` varchar(90) DEFAULT NULL COMMENT '掺和料2报告编号',
  `chl2_yl` varchar(30) DEFAULT NULL COMMENT '掺和料2用量',
  `xgl_cd` varchar(60) DEFAULT NULL COMMENT '细骨料产地',
  `xgl_gg` varchar(30) DEFAULT NULL COMMENT '细骨料规格',
  `xgl_bgbh` varchar(90) DEFAULT NULL COMMENT '细骨料报告编号',
  `xgl_yl` varchar(30) DEFAULT NULL COMMENT '细骨料用量',
  `cgl_cd` varchar(60) DEFAULT NULL COMMENT '粗骨料产地',
  `cgl_gg` varchar(30) DEFAULT NULL COMMENT '粗骨料规格',
  `cgl_bgbh` varchar(90) DEFAULT NULL COMMENT '粗骨料报告编号',
  `cgl_yl` varchar(30) DEFAULT NULL COMMENT '粗骨料用量',
  `wjj1_cd` varchar(60) DEFAULT NULL COMMENT '外加剂1产地',
  `wjj1_gg` varchar(30) DEFAULT NULL COMMENT '外加剂1规格',
  `wjj1_bgbh` varchar(90) DEFAULT NULL COMMENT '外加剂1报告编号',
  `wjj1_yl` varchar(30) DEFAULT NULL COMMENT '外加剂1用量',
  `wjj2_cd` varchar(60) DEFAULT NULL COMMENT '外加剂2产地',
  `wjj2_gg` varchar(30) DEFAULT NULL COMMENT '外加剂2规格',
  `wjj2_bgbh` varchar(90) DEFAULT NULL COMMENT '外加剂2报告编号',
  `wjj2_yl` varchar(30) DEFAULT NULL COMMENT '外加剂2用量',
  `bhs_cd` varchar(60) DEFAULT NULL COMMENT '拌合水材料产地',
  `bhs_gg` varchar(30) DEFAULT NULL COMMENT '拌合水品种规格',
  `bhs_bgbh` varchar(90) DEFAULT NULL COMMENT '拌合水报告编号',
  `bhs_yl` varchar(30) DEFAULT NULL COMMENT '拌合水用量',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_hntsycl_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_hntsycl_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_hntsycl_detail`;
/*!40000 ALTER TABLE `lab_report_hntsycl_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_hntsycl_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_hnttxml_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_hnttxml_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjbh` varchar(90) DEFAULT NULL COMMENT '试件编号',
  `syrq` int(11) DEFAULT NULL COMMENT '试验日期',
  `lq` varchar(30) DEFAULT NULL COMMENT '龄期',
  `zsxs` varchar(90) DEFAULT NULL COMMENT '折算系数',
  `phhz1` varchar(30) DEFAULT NULL COMMENT '破坏荷载1',
  `phhz2` varchar(30) DEFAULT NULL COMMENT '破坏荷载2',
  `phhz3` varchar(30) DEFAULT NULL COMMENT '破坏荷载3',
  `qzxkyqd1` varchar(30) DEFAULT NULL COMMENT '前轴心抗压强度1',
  `qzxkyqd2` varchar(30) DEFAULT NULL COMMENT '前轴心抗压强度2',
  `qzxkyqd3` varchar(30) DEFAULT NULL COMMENT '前轴心抗压强度3',
  `qzxkyqd` varchar(30) DEFAULT NULL COMMENT '前轴心抗压强度组值',
  `hzxkyqd1` varchar(30) DEFAULT NULL COMMENT '后轴心抗压强度1',
  `hzxkyqd2` varchar(30) DEFAULT NULL COMMENT '后轴心抗压强度2',
  `hzxkyqd3` varchar(30) DEFAULT NULL COMMENT '后轴心抗压强度3',
  `hzxkyqd` varchar(30) DEFAULT NULL COMMENT '后轴心抗压强度组值',
  `cshz` varchar(30) DEFAULT NULL COMMENT '初始荷载',
  `kzhz` varchar(30) DEFAULT NULL COMMENT '控制荷载',
  `clbj` varchar(30) DEFAULT NULL COMMENT '测量标距',
  `cymj` varchar(30) DEFAULT NULL COMMENT '承压面积',
  `jlsytxml1` varchar(30) DEFAULT NULL COMMENT '静力受压弹性模量1',
  `jlsytxml2` varchar(30) DEFAULT NULL COMMENT '静力受压弹性模量2',
  `jlsytxml3` varchar(30) DEFAULT NULL COMMENT '静力受压弹性模量3',
  `jlsytxml` varchar(30) DEFAULT NULL COMMENT '静力受压弹性模量组值',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_hnttxml_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_hnttxml_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_hnttxml_detail`;
/*!40000 ALTER TABLE `lab_report_hnttxml_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_hnttxml_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_sjkyjstj_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_sjkyjstj_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjqddj` varchar(30) DEFAULT NULL COMMENT '设计强度等级',
  `phbbgbh` varchar(90) DEFAULT NULL COMMENT '配合比报告编号',
  `llphb` varchar(90) DEFAULT NULL COMMENT '理论配合比',
  `sjb1` varchar(60) DEFAULT NULL COMMENT '水胶比1',
  `zjrq` int(11) DEFAULT NULL COMMENT '制件日期',
  `sgpltzdh` varchar(60) DEFAULT NULL COMMENT '施工配料通知单号',
  `sgphb` varchar(90) DEFAULT NULL COMMENT '施工配合比',
  `sjb2` varchar(60) DEFAULT NULL COMMENT '水胶比2',
  `zjsldd` varchar(30) DEFAULT NULL COMMENT '制件时流动度',
  `zjdsff` varchar(60) DEFAULT NULL COMMENT '制件捣实方法',
  `sjcc` varchar(30) DEFAULT NULL COMMENT '试件尺寸(mm)',
  `yhff` varchar(60) DEFAULT NULL COMMENT '养护方法',
  `yhwd` varchar(30) DEFAULT NULL COMMENT '养护温度',
  `sn_cd` varchar(60) DEFAULT NULL COMMENT '水泥产地',
  `sn_gg` varchar(30) DEFAULT NULL COMMENT '水泥规格',
  `sn_bgbh` varchar(90) DEFAULT NULL COMMENT '水泥报告编号',
  `sn_yl` varchar(30) DEFAULT NULL COMMENT '水泥用量',
  `yjl_cd` varchar(60) DEFAULT NULL COMMENT '压浆料产地',
  `yjl_gg` varchar(30) DEFAULT NULL COMMENT '压浆料规格',
  `yjl_bgbh` varchar(90) DEFAULT NULL COMMENT '压浆料报告编号',
  `yjl_yl` varchar(30) DEFAULT NULL COMMENT '压浆料用量',
  `wjj_cd` varchar(60) DEFAULT NULL COMMENT '外加剂产地',
  `wjj_gg` varchar(30) DEFAULT NULL COMMENT '外加剂规格',
  `wjj_bgbh` varchar(90) DEFAULT NULL COMMENT '外加剂报告编号',
  `wjj_yl` varchar(30) DEFAULT NULL COMMENT '外加剂用量',
  `bhs_cd` varchar(60) DEFAULT NULL COMMENT '拌和水产地',
  `bhs_gg` varchar(30) DEFAULT NULL COMMENT '拌和水规格',
  `bhs_bgbh` varchar(90) DEFAULT NULL COMMENT '拌和水报告编号',
  `bhs_yl` varchar(30) DEFAULT NULL COMMENT '拌和水用量',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`) USING BTREE,
  CONSTRAINT `lab_report_sjky_jstj_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_sjkyjstj_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_sjkyjstj_detail`;
/*!40000 ALTER TABLE `lab_report_sjkyjstj_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_sjkyjstj_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_sjkysyjg_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_sjkysyjg_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `sjbh1` varchar(90) DEFAULT NULL COMMENT '试件编号1',
  `syrq1` int(11) DEFAULT NULL COMMENT '试验日期1',
  `lq1` varchar(30) DEFAULT NULL COMMENT '龄期1',
  `zjj` varchar(30) DEFAULT NULL COMMENT '支间距',
  `kzqd1` varchar(30) DEFAULT NULL COMMENT '抗折强度1',
  `kzqd2` varchar(30) DEFAULT NULL COMMENT '抗折强度2',
  `kzqd3` varchar(30) DEFAULT NULL COMMENT '抗折强度3',
  `kzqd` varchar(30) DEFAULT NULL COMMENT '抗折强度均值',
  `sjbh2` varchar(90) DEFAULT NULL COMMENT '试件编号2',
  `syrq2` int(11) DEFAULT NULL COMMENT '试验日期2',
  `lq2` varchar(30) DEFAULT NULL COMMENT '龄期2',
  `cymj` varchar(30) DEFAULT NULL COMMENT '承压面积',
  `kyqd1` varchar(30) DEFAULT NULL COMMENT '抗压强度1',
  `kyqd2` varchar(30) DEFAULT NULL COMMENT '抗压强度2',
  `kyqd3` varchar(30) DEFAULT NULL COMMENT '抗压强度3',
  `kyqd4` varchar(30) DEFAULT NULL COMMENT '抗压强度4',
  `kyqd5` varchar(30) DEFAULT NULL COMMENT '抗压强度5',
  `kyqd6` varchar(30) DEFAULT NULL COMMENT '抗压强度6',
  `kyqd` varchar(30) DEFAULT NULL COMMENT '抗压强度均值',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`) USING BTREE,
  CONSTRAINT `lab_report_sjkysyjg_detail_ibfk_1` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_sjkysyjg_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_sjkysyjg_detail`;
/*!40000 ALTER TABLE `lab_report_sjkysyjg_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_sjkysyjg_detail` ENABLE KEYS */;

-- 导出  表 road.lab_report_sn_detail 结构
CREATE TABLE IF NOT EXISTS `lab_report_sn_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lab_report_id` int(10) unsigned NOT NULL,
  `xd_bz` varchar(100) DEFAULT NULL COMMENT '细度标准',
  `xd_syjg` varchar(30) DEFAULT NULL COMMENT '细度试验结果',
  `md_bz` varchar(30) DEFAULT NULL COMMENT '密度标准',
  `md_syjg` varchar(60) DEFAULT NULL COMMENT '密度试验结果',
  `bbmj_bz` varchar(30) DEFAULT NULL COMMENT '比表面积标准',
  `bbmj_syjg` varchar(30) DEFAULT NULL COMMENT '比表面积试验结果',
  `bzcdysl_bz` varchar(30) DEFAULT NULL COMMENT '标准稠度用水量标准',
  `bzcdysl_syjg` varchar(30) DEFAULT NULL COMMENT '标准稠度用水量试验结果',
  `cn_bz` varchar(100) DEFAULT NULL COMMENT '初凝标准',
  `cn_syjg` varchar(100) DEFAULT NULL COMMENT '初凝试验结果',
  `zn_bz` varchar(30) DEFAULT NULL COMMENT '终凝标准',
  `zn_syjg` varchar(30) DEFAULT NULL COMMENT '终凝试验结果',
  `adx_bz` varchar(100) DEFAULT NULL COMMENT '安定性标准',
  `adx_syjg` varchar(100) DEFAULT NULL COMMENT '安定性试验结果',
  `jsldd_bz` varchar(255) DEFAULT NULL COMMENT '胶砂流动度标准',
  `jsldd_syjg` varchar(255) DEFAULT NULL COMMENT '胶砂流动度试验结果',
  `ssl_bz` varchar(255) DEFAULT NULL COMMENT '烧失量标准',
  `ssl_syjg` varchar(255) DEFAULT NULL COMMENT '烧失量试验结果',
  `syhlhl_bz` varchar(255) DEFAULT NULL COMMENT '三氧化硫含量标准',
  `syhlhl_syjg` varchar(255) DEFAULT NULL COMMENT '三氧化硫含量试验结果',
  `yhm_bz` varchar(255) DEFAULT NULL COMMENT '氧化镁标准',
  `yhm_syjg` varchar(255) DEFAULT NULL COMMENT '氧化镁试验结果',
  `llzhl_bz` varchar(255) DEFAULT NULL COMMENT '氯离子含量标准',
  `llzhl_syjg` varchar(255) DEFAULT NULL COMMENT '氯离子含量试验结果',
  `ylyhg_bz` varchar(255) DEFAULT NULL COMMENT '游离氧化钙标准',
  `ylyhg_syjg` varchar(255) DEFAULT NULL COMMENT '游离氧化钙试验结果',
  `jhl_bz` varchar(255) DEFAULT NULL COMMENT '碱含量标准',
  `jhl_syjg` varchar(255) DEFAULT NULL COMMENT '碱含量试验结果',
  `lq1` varchar(255) DEFAULT NULL COMMENT '龄期1',
  `lq1_kzqd_bz` varchar(255) DEFAULT NULL COMMENT '龄期1对应抗折强度标准',
  `lq1_kzqd1` varchar(255) DEFAULT NULL COMMENT '龄期1对应抗折强度1',
  `lq1_kzqd2` varchar(255) DEFAULT NULL COMMENT '龄期1对应抗折强度2',
  `lq1_kzqd3` varchar(255) DEFAULT NULL COMMENT '龄期1对应抗折强度3',
  `lq1_kzqd_zz` varchar(255) DEFAULT NULL COMMENT '龄期1对应抗折强度组值',
  `lq2` varchar(255) DEFAULT NULL COMMENT '龄期2',
  `lq2_kzqd_bz` varchar(255) DEFAULT NULL COMMENT '龄期2对应抗折强度标准',
  `lq2_kzqd1` varchar(255) DEFAULT NULL COMMENT '龄期2对应抗折强度1',
  `lq2_kzqd2` varchar(255) DEFAULT NULL COMMENT '龄期2对应抗折强度2',
  `lq2_kzqd3` varchar(255) DEFAULT NULL COMMENT '龄期2对应抗折强度3',
  `lq2_kzqd_zz` varchar(255) DEFAULT NULL COMMENT '龄期2对应抗折强度组值',
  `lq3` varchar(255) DEFAULT NULL COMMENT '龄期3',
  `lq3_kyqd_bz` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度标准',
  `lq3_kyqd1` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度1',
  `lq3_kyqd2` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度2',
  `lq3_kyqd3` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度3',
  `lq3_kyqd4` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度4',
  `lq3_kyqd5` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度5',
  `lq3_kyqd6` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度6',
  `lq3_kyqd_zz` varchar(255) DEFAULT NULL COMMENT '龄期3对应抗压强度组值',
  `lq4` varchar(255) DEFAULT NULL COMMENT '龄期4',
  `lq4_kyqd_bz` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度标准',
  `lq4_kyqd1` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度1',
  `lq4_kyqd2` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度2',
  `lq4_kyqd3` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度3',
  `lq4_kyqd4` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度4',
  `lq4_kyqd5` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度5',
  `lq4_kyqd6` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度6',
  `lq4_kyqd_zz` varchar(255) DEFAULT NULL COMMENT '龄期4对应抗压强度组值',
  PRIMARY KEY (`id`),
  KEY `lab_report_id` (`lab_report_id`),
  CONSTRAINT `lab_report_sn_detail` FOREIGN KEY (`lab_report_id`) REFERENCES `lab_report` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_report_sn_detail 的数据：~0 rows (大约)
DELETE FROM `lab_report_sn_detail`;
/*!40000 ALTER TABLE `lab_report_sn_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_report_sn_detail` ENABLE KEYS */;

-- 导出  表 road.lab_stat_day 结构
CREATE TABLE IF NOT EXISTS `lab_stat_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sy_num` int(10) unsigned DEFAULT NULL COMMENT '试验次数',
  `bj_num` int(10) unsigned DEFAULT NULL COMMENT '报警次数',
  `cl_num` int(10) DEFAULT NULL,
  `bhgl` double DEFAULT NULL COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `date` varchar(15) DEFAULT NULL COMMENT '统计日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_stat_day 的数据：~0 rows (大约)
DELETE FROM `lab_stat_day`;
/*!40000 ALTER TABLE `lab_stat_day` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_stat_day` ENABLE KEYS */;

-- 导出  表 road.lab_stat_month 结构
CREATE TABLE IF NOT EXISTS `lab_stat_month` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sy_num` int(10) unsigned DEFAULT '0' COMMENT '生产次数',
  `bj_num` int(10) unsigned DEFAULT '0' COMMENT '报警次数',
  `cl_num` int(10) unsigned DEFAULT '0' COMMENT '处理数',
  `bhgl` double DEFAULT '0' COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `month` varchar(2) DEFAULT NULL COMMENT '月份',
  `created_at` int(11) DEFAULT NULL COMMENT '月份 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_stat_month 的数据：~23 rows (大约)
DELETE FROM `lab_stat_month`;
/*!40000 ALTER TABLE `lab_stat_month` DISABLE KEYS */;
INSERT INTO `lab_stat_month` (`id`, `project_id`, `supervision_id`, `section_id`, `device_id`, `sy_num`, `bj_num`, `cl_num`, `bhgl`, `cll`, `month`, `created_at`) VALUES
	(2, 4, 1, 4, 8, 0, 0, 0, 0, NULL, '01', 1514813837),
	(3, 4, 1, 4, 9, 0, 0, 0, 0, NULL, '01', 1514813837),
	(4, 4, 1, 4, 10, 0, 0, 0, 0, NULL, '01', 1514813837),
	(5, 4, 1, 4, 8, 0, 0, 0, 0, NULL, '01', 1514903665),
	(6, 4, 1, 4, 9, 0, 0, 0, 0, NULL, '01', 1514903665),
	(7, 4, 1, 4, 10, 0, 0, 0, 0, NULL, '01', 1514903665),
	(8, 4, 1, 4, 8, 0, 0, 0, 0, NULL, '02', 1517537664),
	(9, 4, 1, 4, 9, 0, 0, 0, 0, NULL, '02', 1517537664),
	(10, 4, 1, 4, 10, 0, 0, 0, 0, NULL, '02', 1517537664),
	(11, 1, 4, 1, 57, 7, 0, 0, 0, NULL, '01', 1516718858),
	(12, 1, 4, 1, 58, 0, 0, 0, 0, NULL, '01', 1516718858),
	(13, 4, 1, 2, 47, 0, 0, 0, 0, NULL, '02', 1517502603),
	(14, 4, 1, 2, 48, 0, 0, 0, 0, NULL, '02', 1517502603),
	(15, 4, 1, 2, 49, 0, 0, 0, 0, NULL, '02', 1517502603),
	(16, 4, 1, 2, 50, 0, 0, 0, 0, NULL, '02', 1517502603),
	(17, 2, 5, 16, 51, 0, 0, 0, 0, NULL, '02', 1517502603),
	(18, 2, 5, 16, 52, 0, 0, 0, 0, NULL, '02', 1517502603),
	(19, 2, 5, 16, 53, 0, 0, 0, 0, NULL, '02', 1517502603),
	(20, 2, 5, 16, 54, 0, 0, 0, 0, NULL, '02', 1517502603),
	(21, 1, 4, 1, 55, 0, 0, 0, 0, NULL, '02', 1517502603),
	(22, 1, 4, 1, 56, 0, 0, 0, 0, NULL, '02', 1517502603),
	(23, 1, 4, 1, 57, 0, 0, 0, 0, NULL, '02', 1517502603),
	(24, 1, 4, 1, 58, 0, 0, 0, 0, NULL, '02', 1517502603);
/*!40000 ALTER TABLE `lab_stat_month` ENABLE KEYS */;

-- 导出  表 road.lab_stat_week 结构
CREATE TABLE IF NOT EXISTS `lab_stat_week` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sy_num` int(10) unsigned DEFAULT '0' COMMENT '试验次数',
  `bj_num` int(10) unsigned DEFAULT '0' COMMENT '报警次数',
  `cl_num` int(10) unsigned DEFAULT '0' COMMENT '处理数',
  `bhgl` double DEFAULT '0' COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `week` varchar(2) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_stat_week 的数据：~24 rows (大约)
DELETE FROM `lab_stat_week`;
/*!40000 ALTER TABLE `lab_stat_week` DISABLE KEYS */;
INSERT INTO `lab_stat_week` (`id`, `project_id`, `supervision_id`, `section_id`, `device_id`, `sy_num`, `bj_num`, `cl_num`, `bhgl`, `cll`, `week`, `created_at`) VALUES
	(13, 4, 1, 2, 47, 1, 0, 0, 0, NULL, '04', 1516718945),
	(14, 4, 1, 2, 48, 1, 0, 0, 0, NULL, '04', 1516718945),
	(15, 4, 1, 2, 49, 2, 0, 0, 0, NULL, '04', 1516718945),
	(16, 4, 1, 2, 50, 3, 1, 0, 33.3, 0, '04', 1516984200),
	(17, 2, 5, 16, 51, 0, 0, 0, 0, NULL, '04', 1516718945),
	(18, 2, 5, 16, 52, 1, 0, 0, 0, NULL, '04', 1516897800),
	(19, 2, 5, 16, 53, 1, 0, 0, 0, NULL, '04', 1516718945),
	(20, 2, 5, 16, 54, 1, 0, 0, 0, NULL, '04', 1516897800),
	(21, 1, 4, 1, 55, 0, 0, 0, 0, NULL, '04', 1516718945),
	(22, 1, 4, 1, 56, 0, 0, 0, 0, NULL, '04', 1516718945),
	(23, 1, 4, 1, 57, 1, 0, 0, 0, NULL, '04', 1516718945),
	(24, 1, 4, 1, 58, 0, 0, 0, 0, NULL, '04', 1516718945),
	(25, 4, 1, 2, 47, 0, 0, 0, 0, NULL, '05', 1517243400),
	(26, 4, 1, 2, 48, 0, 0, 0, 0, NULL, '05', 1517243400),
	(27, 4, 1, 2, 49, 0, 0, 0, 0, NULL, '05', 1517243400),
	(28, 4, 1, 2, 50, 0, 0, 0, 0, NULL, '05', 1517243400),
	(29, 2, 5, 16, 51, 0, 0, 0, 0, NULL, '05', 1517243400),
	(30, 2, 5, 16, 52, 0, 0, 0, 0, NULL, '05', 1517243400),
	(31, 2, 5, 16, 53, 0, 0, 0, 0, NULL, '05', 1517243400),
	(32, 2, 5, 16, 54, 0, 0, 0, 0, NULL, '05', 1517243400),
	(33, 1, 4, 1, 55, 0, 0, 0, 0, NULL, '05', 1517243400),
	(34, 1, 4, 1, 56, 0, 0, 0, 0, NULL, '05', 1517243400),
	(35, 1, 4, 1, 57, 0, 0, 0, 0, NULL, '05', 1517243400),
	(36, 1, 4, 1, 58, 0, 0, 0, 0, NULL, '05', 1517243400);
/*!40000 ALTER TABLE `lab_stat_week` ENABLE KEYS */;

-- 导出  表 road.lab_user_warn 结构
CREATE TABLE IF NOT EXISTS `lab_user_warn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `cj_0` tinyint(1) unsigned DEFAULT NULL,
  `cj_24` tinyint(1) unsigned DEFAULT NULL,
  `cj_48` tinyint(1) unsigned DEFAULT NULL,
  `zj_0` tinyint(1) unsigned DEFAULT NULL,
  `zj_24` tinyint(1) unsigned DEFAULT NULL,
  `zj_48` tinyint(1) unsigned DEFAULT NULL,
  `gj_0` tinyint(1) unsigned DEFAULT NULL,
  `gj_24` tinyint(1) unsigned DEFAULT NULL,
  `gj_48` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `lab_user_warn` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_user_warn 的数据：~0 rows (大约)
DELETE FROM `lab_user_warn`;
/*!40000 ALTER TABLE `lab_user_warn` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_user_warn` ENABLE KEYS */;

-- 导出  表 road.lab_warn_info 结构
CREATE TABLE IF NOT EXISTS `lab_warn_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `device_id` int(10) unsigned NOT NULL COMMENT '设备id',
  `lab_info_id` int(10) unsigned NOT NULL,
  `warn_type` varchar(60) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sec_id` (`section_id`),
  KEY `dev_id` (`device_id`),
  KEY `lab_info_id` (`lab_info_id`) USING BTREE,
  KEY `supervision_id` (`supervision_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_warn_info 的数据：~5 rows (大约)
DELETE FROM `lab_warn_info`;
/*!40000 ALTER TABLE `lab_warn_info` DISABLE KEYS */;
INSERT INTO `lab_warn_info` (`id`, `project_id`, `supervision_id`, `section_id`, `device_id`, `lab_info_id`, `warn_type`, `time`) VALUES
	(1, 4, 1, 4, 8, 28, '下屈服强度,不合格', 1510748751),
	(2, 4, 1, 4, 8, 29, '下屈服强度,不合格', 1510748751),
	(3, 4, 1, 4, 8, 30, '下屈服强度不合格', 1510748751),
	(4, 4, 1, 4, 8, 31, '下屈服强度不合格', 1510748751),
	(5, 4, 1, 4, 8, 32, '下屈服强度不合格', 1510748751);
/*!40000 ALTER TABLE `lab_warn_info` ENABLE KEYS */;

-- 导出  表 road.lab_warn_total 结构
CREATE TABLE IF NOT EXISTS `lab_warn_total` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `device_id` int(10) unsigned NOT NULL COMMENT '设备id',
  `num` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '报警次数',
  `date` date NOT NULL COMMENT '报警日期',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `section_id` (`section_id`),
  KEY `supervision_id` (`supervision_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- 正在导出表  road.lab_warn_total 的数据：~4 rows (大约)
DELETE FROM `lab_warn_total`;
/*!40000 ALTER TABLE `lab_warn_total` DISABLE KEYS */;
INSERT INTO `lab_warn_total` (`id`, `project_id`, `supervision_id`, `section_id`, `device_id`, `num`, `date`) VALUES
	(1, 4, 1, 4, 8, 3, '2017-11-15'),
	(2, 4, 1, 2, 1, 1, '2018-01-12'),
	(3, 4, 1, 2, 1, 7, '2018-01-17'),
	(4, 4, 1, 2, 1, 1, '2018-01-18');
/*!40000 ALTER TABLE `lab_warn_total` ENABLE KEYS */;

-- 导出  表 road.log 结构
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` tinyint(1) DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `addr` varchar(60) NOT NULL,
  `act` varchar(150) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `type` varchar(6) NOT NULL COMMENT '操作类型l登录r注册a新增m修改d删除c审核',
  `created_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2706 DEFAULT CHARSET=utf8;

-- 正在导出表  road.log 的数据：~579 rows (大约)
DELETE FROM `log`;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` (`id`, `role`, `ip`, `addr`, `act`, `user_id`, `name`, `type`, `created_at`) VALUES
	(2053, NULL, '123.139.49.104', '陕西 西安', '梁箭登录成功', 86, '梁箭', 'l', 1519785585),
	(2054, NULL, '117.23.242.64', '陕西 宝鸡', '王永全登录成功', 58, '王永全', 'l', 1519785969),
	(2055, NULL, '123.139.48.155', '陕西 西安', '张建科登录成功', 85, '张建科', 'l', 1519785976),
	(2056, NULL, '123.139.48.155', '陕西 西安', '张建科登录成功', 85, '张建科', 'l', 1519785991),
	(2057, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519786938),
	(2058, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519787033),
	(2059, NULL, '117.136.50.28', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519787339),
	(2060, NULL, '117.136.50.28', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519787394),
	(2061, NULL, '113.200.49.214', '陕西 西安', '曹继伟登录成功', 87, '曹继伟', 'l', 1519787486),
	(2062, NULL, '117.136.50.207', '陕西 西安', 'cmc123修改用户：郭栋的信息', 31, '陈满仓', 'm', 1519787737),
	(2063, NULL, '117.136.50.28', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519787783),
	(2064, NULL, '36.46.212.11', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519787964),
	(2065, NULL, '113.143.111.216', '陕西 宝鸡', '李兵登录成功', 55, '李兵', 'l', 1519788302),
	(2066, NULL, '111.20.133.245', '陕西 宝鸡', '岳卫民登录成功', 69, '岳卫民', 'l', 1519788354),
	(2067, NULL, '111.20.133.245', '陕西 宝鸡', '岳卫民登录成功', 69, '岳卫民', 'l', 1519788399),
	(2068, NULL, '111.20.133.245', '陕西 宝鸡', '岳卫民登录成功', 69, '岳卫民', 'l', 1519788417),
	(2069, NULL, '61.158.146.67', '河南 洛阳', '张明登录成功', 54, '张明', 'l', 1519788778),
	(2070, NULL, '61.158.146.67', '河南 洛阳', '张明登录成功', 54, '张明', 'l', 1519788806),
	(2071, NULL, '61.158.146.67', '河南 洛阳', '张明登录成功', 54, '张明', 'l', 1519788835),
	(2072, NULL, '36.46.212.11', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519789682),
	(2073, NULL, '111.20.133.248', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519789744),
	(2074, NULL, '111.20.133.248', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519789771),
	(2075, NULL, '111.20.133.245', '陕西 宝鸡', '岳卫民登录成功', 69, '岳卫民', 'l', 1519790035),
	(2076, NULL, '61.158.146.67', '河南 洛阳', '张明登录成功', 54, '张明', 'l', 1519790057),
	(2077, NULL, '117.23.242.84', '陕西 宝鸡', '王永全登录成功', 58, '王永全', 'l', 1519790090),
	(2078, NULL, '123.139.49.104', '陕西 西安', '梁箭登录成功', 86, '梁箭', 'l', 1519790203),
	(2079, NULL, '113.143.111.216', '陕西 宝鸡', '李兵登录成功', 55, '李兵', 'l', 1519790218),
	(2080, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519792802),
	(2081, NULL, '1.85.11.243', '陕西 西安', '赵晓晓登录成功', 38, '赵晓晓', 'l', 1519793692),
	(2082, NULL, '117.136.50.28', '陕西 西安', '赵晓晓登录成功', 38, '赵晓晓', 'l', 1519794146),
	(2083, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519795830),
	(2084, NULL, '111.20.133.245', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519795894),
	(2085, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519795928),
	(2086, NULL, '111.20.133.245', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519795965),
	(2087, NULL, '111.20.133.245', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519795971),
	(2088, NULL, '219.145.173.73', '陕西 渭南', '刘代鹤登录成功', 30, '刘代鹤', 'l', 1519798793),
	(2089, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519799953),
	(2090, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519799975),
	(2091, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519800446),
	(2092, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519800719),
	(2093, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519801867),
	(2094, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519801888),
	(2095, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519802216),
	(2096, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519802439),
	(2097, NULL, '1.85.11.246', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519802525),
	(2098, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519802566),
	(2099, NULL, '111.21.82.250', '陕西 宝鸡', 'xtgly1登录成功', 5, 'xtgly1', 'l', 1519803259),
	(2100, NULL, '111.21.82.250', '陕西 宝鸡', 'xtgly1登录成功', 5, 'xtgly1', 'l', 1519803549),
	(2101, NULL, '111.21.82.250', '陕西 宝鸡', 'xtgly1登录成功', 5, 'xtgly1', 'l', 1519803589),
	(2102, NULL, '111.21.82.250', '陕西 宝鸡', 'xtgly1登录成功', 5, 'xtgly1', 'l', 1519803652),
	(2103, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519803685),
	(2104, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519803731),
	(2105, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519803746),
	(2106, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519803753),
	(2107, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519803783),
	(2108, NULL, '111.21.82.250', '陕西 宝鸡', 'xtgly1登录成功', 5, 'xtgly1', 'l', 1519803856),
	(2109, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519804237),
	(2110, NULL, '113.143.109.166', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519804242),
	(2111, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519804698),
	(2112, NULL, '113.143.109.166', '陕西 宝鸡', '李兵登录成功', 55, '李兵', 'l', 1519804897),
	(2113, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519805168),
	(2114, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519805184),
	(2115, NULL, '123.138.232.182', '陕西 西安', '靳永全登录成功', 75, '靳永全', 'l', 1519805597),
	(2116, NULL, '123.138.232.182', '陕西 西安', '靳永全登录成功', 75, '靳永全', 'l', 1519805692),
	(2117, NULL, '123.138.232.182', '陕西 西安', '靳永全登录成功', 75, '靳永全', 'l', 1519805714),
	(2118, NULL, '123.138.232.182', '陕西 西安', '靳永全登录成功', 75, '靳永全', 'l', 1519805749),
	(2119, NULL, '111.21.82.250', '陕西 宝鸡', 'xtgly1登录成功', 5, 'xtgly1', 'l', 1519805823),
	(2120, NULL, '111.19.92.236', '陕西 西安', 'yf添加新职位：专业工程师', 1, 'yf', 'a', 1519806573),
	(2121, NULL, '111.19.92.236', '陕西 西安', 'yf添加新职位：专业工程师', 1, 'yf', 'a', 1519806579),
	(2122, NULL, '111.19.92.236', '陕西 西安', 'yf添加新职位：专业工程师', 1, 'yf', 'a', 1519806586),
	(2123, NULL, '111.19.92.236', '陕西 西安', 'yf添加新职位：专业工程师', 1, 'yf', 'a', 1519806591),
	(2124, NULL, '111.21.82.250', '陕西 宝鸡', '田涛登录成功', 112, '田涛', 'l', 1519807203),
	(2125, NULL, '113.200.107.89', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519808617),
	(2126, NULL, '111.21.82.250', '陕西 宝鸡', '何小明登录成功', 79, '何小明', 'l', 1519808785),
	(2127, NULL, '61.185.60.234', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519810913),
	(2128, NULL, '117.136.50.207', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519811235),
	(2129, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519812450),
	(2130, NULL, '117.136.50.207', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519814196),
	(2131, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519814374),
	(2132, NULL, '117.136.50.207', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519814578),
	(2133, NULL, '113.200.204.243', '陕西 西安', '田涛登录成功', 112, '田涛', 'l', 1519814914),
	(2134, NULL, '113.200.204.243', '陕西 西安', '田涛登录成功', 112, '田涛', 'l', 1519814932),
	(2135, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519815199),
	(2136, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519815870),
	(2137, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519815902),
	(2138, NULL, '117.136.50.207', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519817266),
	(2139, NULL, '117.136.50.207', '陕西 西安', 'cmc123修改用户：董晓武的信息', 31, '陈满仓', 'm', 1519817386),
	(2140, NULL, '111.21.82.250', '陕西 宝鸡', '何小明登录成功', 79, '何小明', 'l', 1519817718),
	(2141, NULL, '111.19.92.238', '陕西 西安', 'yf修改用户：董晓武的信息', 1, 'yf', 'm', 1519818033),
	(2142, NULL, '219.145.173.70', '陕西 渭南', '王顺登录成功', 21, '王顺', 'l', 1519819481),
	(2143, NULL, '219.145.173.70', '陕西 渭南', '王顺登录成功', 21, '王顺', 'l', 1519820325),
	(2144, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1519820799),
	(2145, NULL, '113.200.141.108', '陕西 西安', '刘晓春登录成功', 83, '刘晓春', 'l', 1519821456),
	(2146, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519822883),
	(2147, NULL, '111.20.133.243', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519823083),
	(2148, NULL, '111.21.82.250', '陕西 宝鸡', '张明登录成功', 54, '张明', 'l', 1519823113),
	(2149, NULL, '61.158.149.126', '河南 郑州', '张明登录成功', 54, '张明', 'l', 1519823148),
	(2150, NULL, '111.19.92.238', '陕西 西安', 'yf删除单位：', 1, 'yf', 'd', 1519824654),
	(2151, NULL, '111.19.92.238', '陕西 西安', 'yf添加新单位：123', 1, 'yf', 'a', 1519824844),
	(2152, NULL, '111.19.92.238', '陕西 西安', 'yf删除单位：123', 1, 'yf', 'd', 1519824850),
	(2153, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519825497),
	(2154, NULL, '111.19.92.238', '陕西 西安', 'yf添加新单位：陕西西法（南线）城际铁路有限公司', 1, 'yf', 'a', 1519825658),
	(2155, NULL, '111.19.92.238', '陕西 西安', '测试888登录成功', 49, '测试888', 'l', 1519825915),
	(2156, NULL, '111.19.92.238', '陕西 西安', '注册新用户：test999', 113, 'test999', 'r', 1519826713),
	(2157, NULL, '111.19.92.238', '陕西 西安', '注册新用户：test333', 114, 'test333', 'r', 1519827013),
	(2158, NULL, '111.19.92.238', '陕西 西安', 'yf审核用户：test999的状态为正常', 1, 'yf', 'c', 1519827043),
	(2159, NULL, '111.19.92.238', '陕西 西安', 'yf删除用户：test333', 1, 'yf', 'd', 1519827059),
	(2160, NULL, '111.19.92.238', '陕西 西安', 'yf登录成功', 1, 'yf', 'l', 1519827660),
	(2161, NULL, '111.19.92.238', '陕西 西安', 'yf登录成功', 1, 'yf', 'l', 1519827793),
	(2162, NULL, '111.19.92.238', '陕西 西安', '测试888登录成功', 49, '测试888', 'l', 1519828119),
	(2163, NULL, '111.18.44.148', '陕西 西安', '张建科登录成功', 85, '张建科', 'l', 1519829951),
	(2164, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519831253),
	(2165, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519831304),
	(2166, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519831385),
	(2167, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519831445),
	(2168, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519831486),
	(2169, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519831842),
	(2170, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519832027),
	(2171, NULL, '117.23.242.84', '陕西 宝鸡', '张萍登录成功', 108, '张萍', 'l', 1519859148),
	(2172, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519859849),
	(2173, NULL, '36.43.163.50', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1519859886),
	(2174, NULL, '113.200.106.117', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519860367),
	(2175, NULL, '113.200.106.117', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519860402),
	(2176, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519860595),
	(2177, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519860595),
	(2178, NULL, '113.200.106.117', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519860831),
	(2179, NULL, '111.21.82.250', '陕西 宝鸡', '靳永全登录成功', 75, '靳永全', 'l', 1519861218),
	(2180, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519862107),
	(2181, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519862276),
	(2182, NULL, '36.43.219.36', '陕西 西安', '邢相青登录成功', 81, '邢相青', 'l', 1519862559),
	(2183, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519862589),
	(2184, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519862730),
	(2185, NULL, '111.20.133.243', '陕西 宝鸡', '梁璞登录成功', 14, '梁璞', 'l', 1519862744),
	(2186, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519863452),
	(2187, NULL, '123.139.49.104', '陕西 西安', '谢潇登录成功', 88, '谢潇', 'l', 1519863789),
	(2188, NULL, '36.43.163.50', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1519864043),
	(2189, NULL, '113.200.106.117', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519864644),
	(2190, NULL, '117.33.57.126', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519864863),
	(2191, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519865046),
	(2192, NULL, '123.139.49.190', '陕西 西安', '卢雪鸽登录成功', 95, '卢雪鸽', 'l', 1519866797),
	(2193, NULL, '123.139.49.190', '陕西 西安', '卢雪鸽登录成功', 95, '卢雪鸽', 'l', 1519867090),
	(2194, NULL, '123.139.49.190', '陕西 西安', '卢雪鸽登录成功', 95, '卢雪鸽', 'l', 1519867141),
	(2195, NULL, '113.200.205.59', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519867227),
	(2196, NULL, '113.200.205.59', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519867276),
	(2197, NULL, '1.85.19.170', '陕西 西安', '孟小辉登录成功', 53, '孟小辉', 'l', 1519867642),
	(2198, NULL, '113.140.127.157', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519867774),
	(2199, NULL, '113.140.127.157', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519867840),
	(2200, NULL, '123.139.48.155', '陕西 西安', '张建科登录成功', 85, '张建科', 'l', 1519868668),
	(2201, NULL, '117.23.242.84', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1519871092),
	(2202, NULL, '113.140.127.157', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519871118),
	(2203, NULL, '117.23.242.84', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1519871268),
	(2204, NULL, '117.23.242.84', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1519871513),
	(2205, NULL, '123.139.48.155', '陕西 西安', '张建科登录成功', 85, '张建科', 'l', 1519871543),
	(2206, NULL, '117.23.242.84', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1519871674),
	(2207, NULL, '117.23.242.84', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1519871875),
	(2208, NULL, '113.140.127.157', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519872141),
	(2209, NULL, '219.145.173.70', '陕西 渭南', '王顺登录成功', 21, '王顺', 'l', 1519873734),
	(2210, NULL, '123.139.49.104', '陕西 西安', '梁箭登录成功', 86, '梁箭', 'l', 1519873960),
	(2211, NULL, '219.145.173.70', '陕西 渭南', 'xhcgxxy修改用户：王顺的模块权限', 21, '王顺', 'm', 1519874191),
	(2212, NULL, '113.200.141.108', '陕西 西安', '刘晓春登录成功', 83, '刘晓春', 'l', 1519874392),
	(2213, NULL, '221.11.61.112', '陕西 西安', '后想林登录成功', 35, '后想林', 'l', 1519874532),
	(2214, NULL, '113.140.127.157', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519874546),
	(2215, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519875015),
	(2216, NULL, '111.19.92.225', '陕西 西安', '测试888登录成功', 49, '测试888', 'l', 1519875031),
	(2217, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519875059),
	(2218, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519875181),
	(2219, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519875304),
	(2220, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519875313),
	(2221, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519875337),
	(2222, NULL, '117.136.50.28', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519878111),
	(2223, NULL, '117.136.86.39', '陕西 西安', '张路路登录成功', 70, '张路路', 'l', 1519878122),
	(2224, NULL, '1.85.11.245', '陕西 西安', '王海英登录成功', 40, '王海英', 'l', 1519878124),
	(2225, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878131),
	(2226, NULL, '117.136.86.39', '陕西 西安', '张路路登录成功', 70, '张路路', 'l', 1519878133),
	(2227, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878186),
	(2228, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878381),
	(2229, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878382),
	(2230, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878405),
	(2231, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878421),
	(2232, NULL, '113.139.45.137', '陕西 西安', '测试122登录成功', 67, '测试122', 'l', 1519878471),
	(2233, NULL, '221.11.61.213', '陕西 西安', '梁霖浩登录成功', 73, '梁霖浩', 'l', 1519878472),
	(2234, NULL, '111.19.92.225', '陕西 西安', '测试888登录成功', 49, '测试888', 'l', 1519878498),
	(2235, NULL, '117.136.50.196', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519878633),
	(2236, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519883440),
	(2237, NULL, '113.200.141.108', '陕西 西安', '刘晓春登录成功', 83, '刘晓春', 'l', 1519884718),
	(2238, NULL, '123.139.220.116', '陕西 宝鸡', '杨洋登录成功', 102, '杨洋', 'l', 1519885383),
	(2239, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1519885935),
	(2240, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519886084),
	(2241, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519886084),
	(2242, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519886185),
	(2243, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519886193),
	(2244, NULL, '1.85.11.246', '陕西 西安', '赵晓晓登录成功', 38, '赵晓晓', 'l', 1519886508),
	(2245, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519886540),
	(2246, NULL, '111.19.92.225', '陕西 西安', 'yf登录成功', 1, 'yf', 'l', 1519887643),
	(2247, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：桥梁专业工程师', 1, 'yf', 'a', 1519887674),
	(2248, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：路基专业工程师', 1, 'yf', 'a', 1519887684),
	(2249, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：隧道专业工程师', 1, 'yf', 'a', 1519887695),
	(2250, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：轨道专业工程师', 1, 'yf', 'a', 1519887705),
	(2251, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：试验专业工程师', 1, 'yf', 'a', 1519887714),
	(2252, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：安全专业工程师', 1, 'yf', 'a', 1519887721),
	(2253, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：质量专业工程师', 1, 'yf', 'a', 1519887730),
	(2254, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：环水保专业工程师', 1, 'yf', 'a', 1519887740),
	(2255, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：桥梁专业工程师', 1, 'yf', 'a', 1519887769),
	(2256, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：路基专业工程师', 1, 'yf', 'a', 1519887778),
	(2257, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：隧道专业工程师', 1, 'yf', 'a', 1519887787),
	(2258, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：轨道专业工程师', 1, 'yf', 'a', 1519887795),
	(2259, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：试验专业工程师', 1, 'yf', 'a', 1519887803),
	(2260, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：安全专业工程师', 1, 'yf', 'a', 1519887811),
	(2261, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：质量专业工程师', 1, 'yf', 'a', 1519887819),
	(2262, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：环水保专业工程师', 1, 'yf', 'a', 1519887827),
	(2263, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：桥梁专业工程师', 1, 'yf', 'a', 1519887837),
	(2264, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：路基专业工程师', 1, 'yf', 'a', 1519887845),
	(2265, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：隧道专业工程师', 1, 'yf', 'a', 1519887852),
	(2266, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：轨道专业工程师', 1, 'yf', 'a', 1519887860),
	(2267, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：试验专业工程师', 1, 'yf', 'a', 1519887868),
	(2268, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：安全专业工程师', 1, 'yf', 'a', 1519887876),
	(2269, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：质量专业工程师', 1, 'yf', 'a', 1519887883),
	(2270, NULL, '111.19.92.225', '陕西 西安', 'yf添加新职位：环水保专业工程师', 1, 'yf', 'a', 1519887891),
	(2271, NULL, '1.85.11.246', '陕西 西安', '赵晓晓登录成功', 38, '赵晓晓', 'l', 1519889055),
	(2272, NULL, '117.136.86.151', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519890571),
	(2273, NULL, '113.200.205.221', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1519890571),
	(2274, NULL, '117.136.86.151', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519890625),
	(2275, NULL, '117.136.86.151', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519890749),
	(2276, NULL, '117.136.86.151', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519890764),
	(2277, NULL, '117.136.86.151', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519890792),
	(2278, NULL, '117.136.86.151', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519890798),
	(2279, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519891498),
	(2280, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519891597),
	(2281, NULL, '1.84.203.181', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519891676),
	(2282, NULL, '61.158.146.74', '河南 洛阳', '张明登录成功', 54, '张明', 'l', 1519893627),
	(2283, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1519897115),
	(2284, NULL, '124.89.34.254', '陕西 西安', '注册新用户：王镔', 115, '王镔', 'r', 1519900638),
	(2285, NULL, '124.89.34.254', '陕西 西安', '孟小辉登录成功', 53, '孟小辉', 'l', 1519900928),
	(2286, NULL, '124.89.34.254', '陕西 西安', 'xfngly审核用户：王镔的状态为正常', 53, '孟小辉', 'c', 1519900965),
	(2287, NULL, '117.136.50.21', '陕西 西安', '王镔登录成功', 115, '王镔', 'l', 1519901042),
	(2288, NULL, '117.136.50.21', '陕西 西安', '王镔登录成功', 115, '王镔', 'l', 1519901157),
	(2289, NULL, '124.89.34.254', '陕西 西安', '王镔登录成功', 115, '王镔', 'l', 1519901245),
	(2290, NULL, '124.89.51.123', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519903073),
	(2291, NULL, '117.23.242.84', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519905773),
	(2292, NULL, '117.136.50.197', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519907233),
	(2293, NULL, '117.136.86.39', '陕西 西安', '张路路登录成功', 70, '张路路', 'l', 1519907244),
	(2294, NULL, '117.136.50.197', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519908087),
	(2295, NULL, '113.200.66.147', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519912458),
	(2296, NULL, '113.200.66.147', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519913025),
	(2297, NULL, '1.85.38.12', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519916417),
	(2298, NULL, '1.85.38.12', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519916439),
	(2299, NULL, '1.85.38.12', '陕西 西安', '佘辉登录成功', 71, '佘辉', 'l', 1519916456),
	(2300, NULL, '113.200.66.147', '陕西 西安', '王馨萍登录成功', 56, '王馨萍', 'l', 1519917051),
	(2301, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1519948182),
	(2302, NULL, '1.85.19.170', '陕西 西安', '孟小辉登录成功', 53, '孟小辉', 'l', 1519951955),
	(2303, NULL, '117.136.50.21', '陕西 西安', '王镔登录成功', 115, '王镔', 'l', 1519954740),
	(2304, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1519956157),
	(2305, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1519958570),
	(2306, NULL, '219.145.173.70', '陕西 渭南', '王顺登录成功', 21, '王顺', 'l', 1519958648),
	(2307, NULL, '36.43.163.50', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1519961081),
	(2308, NULL, '36.43.163.50', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1519961149),
	(2309, NULL, '123.139.51.127', '陕西 西安', '张建科登录成功', 85, '张建科', 'l', 1519965574),
	(2310, NULL, '117.136.50.73', '陕西 西安', '刘晓春登录成功', 83, '刘晓春', 'l', 1519969609),
	(2311, NULL, '36.43.163.50', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1519973527),
	(2312, NULL, '117.23.243.242', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519973541),
	(2313, NULL, '117.23.243.242', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519973564),
	(2314, NULL, '117.23.243.242', '陕西 宝鸡', '王永全登录成功', 58, '王永全', 'l', 1519973582),
	(2315, NULL, '117.23.243.242', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519973737),
	(2316, NULL, '117.23.243.242', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519973753),
	(2317, NULL, '117.23.243.242', '陕西 宝鸡', '李少峰登录成功', 106, '李少峰', 'l', 1519973772),
	(2318, NULL, '36.43.163.50', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1519974002),
	(2319, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519974855),
	(2320, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975149),
	(2321, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975194),
	(2322, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975272),
	(2323, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975365),
	(2324, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975451),
	(2325, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975505),
	(2326, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519975854),
	(2327, NULL, '117.23.243.37', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519976159),
	(2328, NULL, '117.136.50.53', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1519976202),
	(2329, NULL, '117.23.243.37', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1519976629),
	(2330, NULL, '219.145.173.70', '陕西 渭南', '王顺登录成功', 21, '王顺', 'l', 1519977012),
	(2331, NULL, '36.43.221.101', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519996813),
	(2332, NULL, '36.43.221.101', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1519999071),
	(2333, NULL, '36.43.204.155', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520012106),
	(2334, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520029185),
	(2335, NULL, '36.43.204.155', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520046852),
	(2336, NULL, '117.23.243.29', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1520046894),
	(2337, NULL, '117.23.243.29', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1520046943),
	(2338, NULL, '117.23.243.29', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1520047027),
	(2339, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520047133),
	(2340, NULL, '36.43.204.155', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520047262),
	(2341, NULL, '113.134.137.210', '陕西 渭南', 'TLJTCS登录成功', 6, 'TLJTCS', 'l', 1520048056),
	(2342, NULL, '36.43.204.155', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520048443),
	(2343, NULL, '36.43.204.155', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520048456),
	(2344, NULL, '36.43.204.155', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520050138),
	(2345, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520051088),
	(2346, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520051463),
	(2347, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520051464),
	(2348, NULL, '1.85.11.243', '陕西 西安', '注册新用户：曹学鹏', 116, '曹学鹏', 'r', 1520053638),
	(2349, NULL, '1.85.11.243', '陕西 西安', '赵晓晓登录成功', 38, '赵晓晓', 'l', 1520053662),
	(2350, NULL, '1.85.11.243', '陕西 西安', 'zxx123审核用户：曹学鹏的状态为正常', 38, '赵晓晓', 'c', 1520053687),
	(2351, NULL, '1.85.11.243', '陕西 西安', '曹学鹏登录成功', 116, '曹学鹏', 'l', 1520053718),
	(2352, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520058719),
	(2353, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520058725),
	(2354, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520058931),
	(2355, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520059237),
	(2356, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520059887),
	(2357, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520059953),
	(2358, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520060318),
	(2359, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520060965),
	(2360, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520076864),
	(2361, NULL, '111.18.44.18', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1520112393),
	(2362, NULL, '111.18.44.18', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1520112520),
	(2363, NULL, '111.18.44.18', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1520113128),
	(2364, NULL, '111.18.44.18', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1520113304),
	(2365, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520118381),
	(2366, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520123383),
	(2367, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520123550),
	(2368, NULL, '1.84.202.97', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520123598),
	(2369, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520127977),
	(2370, NULL, '113.200.106.170', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1520129929),
	(2371, NULL, '113.200.106.170', '陕西 西安', '王永全登录成功', 58, '王永全', 'l', 1520129941),
	(2372, NULL, '1.86.145.90', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520132252),
	(2373, NULL, '123.138.233.146', '陕西 西安', '后想林登录成功', 35, '后想林', 'l', 1520158895),
	(2374, NULL, '113.140.120.105', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520160182),
	(2375, NULL, '117.23.243.29', '陕西 宝鸡', '注册新用户：常宝利', 117, '常宝利', 'r', 1520168595),
	(2376, NULL, '117.23.243.29', '陕西 宝鸡', '注册新用户：蔡亮', 118, '蔡亮', 'r', 1520168613),
	(2377, NULL, '117.23.243.29', '陕西 宝鸡', '王永全登录成功', 58, '王永全', 'l', 1520168739),
	(2378, NULL, '117.23.243.29', '陕西 宝鸡', 'wang813642594审核用户：常宝利的状态为正常', 58, '王永全', 'c', 1520168760),
	(2379, NULL, '117.23.243.29', '陕西 宝鸡', 'wang813642594审核用户：蔡亮的状态为正常', 58, '王永全', 'c', 1520168788),
	(2380, NULL, '117.23.243.29', '陕西 宝鸡', '蔡亮登录成功', 118, '蔡亮', 'l', 1520168840),
	(2381, NULL, '111.21.82.250', '陕西 宝鸡', '注册新用户：范蒙蒙', 119, '范蒙蒙', 'r', 1520171645),
	(2382, NULL, '111.21.82.250', '陕西 宝鸡', '董晓武登录成功', 78, '董晓武', 'l', 1520171760),
	(2383, NULL, '111.21.82.250', '陕西 宝鸡', 'XFB-TJ01-DXW修改用户：范蒙蒙的模块权限', 78, '董晓武', 'm', 1520171817),
	(2384, NULL, '111.21.82.250', '陕西 宝鸡', 'XFB-TJ01-DXW修改用户：范蒙蒙的模块权限', 78, '董晓武', 'm', 1520171829),
	(2385, NULL, '113.140.120.105', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520171830),
	(2386, NULL, '111.21.82.250', '陕西 宝鸡', 'XFB-TJ01-DXW审核用户：范蒙蒙的状态为禁用', 78, '董晓武', 'c', 1520171843),
	(2387, NULL, '111.21.82.250', '陕西 宝鸡', 'XFB-TJ01-DXW审核用户：范蒙蒙的状态为正常', 78, '董晓武', 'c', 1520171849),
	(2388, NULL, '117.23.243.206', '陕西 宝鸡', '张萍登录成功', 108, '张萍', 'l', 1520174162),
	(2389, NULL, '1.81.64.64', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1520186394),
	(2390, NULL, '61.185.204.119', '陕西 西安', 'TLJTCS登录成功', 6, 'TLJTCS', 'l', 1520205056),
	(2391, NULL, '1.81.64.64', '陕西 西安', '惠三宝登录成功', 59, '惠三宝', 'l', 1520205895),
	(2392, NULL, '113.140.120.105', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520207093),
	(2393, NULL, '117.23.243.206', '陕西 宝鸡', '惠三宝登录成功', 59, '惠三宝', 'l', 1520207110),
	(2394, NULL, '113.140.120.105', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520207269),
	(2395, NULL, '117.136.86.149', '陕西 西安', 'TLJTCS登录成功', 6, 'TLJTCS', 'l', 1520207954),
	(2396, NULL, '113.140.120.105', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520208617),
	(2397, NULL, '113.140.120.105', '陕西 西安', '李少峰登录成功', 106, '李少峰', 'l', 1520208931),
	(2398, NULL, '111.18.44.9', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1520209870),
	(2399, NULL, '111.18.44.9', '陕西 西安', '注册新用户：王利民', 120, '王利民', 'r', 1520210270),
	(2400, NULL, '111.18.44.9', '陕西 西安', '注册新用户：王新才', 121, '王新才', 'r', 1520210381),
	(2401, NULL, '111.18.44.9', '陕西 西安', '陈满仓登录成功', 31, '陈满仓', 'l', 1520210400),
	(2402, NULL, '111.18.44.9', '陕西 西安', 'cmc123审核用户：王利民的状态为正常', 31, '陈满仓', 'c', 1520210474),
	(2403, NULL, '111.18.44.9', '陕西 西安', 'cmc123审核用户：王利民的状态为正常', 31, '陈满仓', 'c', 1520210482),
	(2404, NULL, '111.18.44.9', '陕西 西安', 'cmc123审核用户：王新才的状态为正常', 31, '陈满仓', 'c', 1520210502),
	(2405, NULL, '117.23.242.74', '陕西 宝鸡', '邢相青登录成功', 81, '邢相青', 'l', 1520213294),
	(2406, NULL, '1.85.19.170', '陕西 西安', '孟小辉登录成功', 53, '孟小辉', 'l', 1520213935),
	(2407, NULL, '1.85.19.170', '陕西 西安', 'xfngly审核用户：王利民的状态为正常', 53, '孟小辉', 'c', 1520213957),
	(2408, NULL, '1.85.19.170', '陕西 西安', 'xfngly审核用户：王新才的状态为正常', 53, '孟小辉', 'c', 1520213982),
	(2409, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：yf1234', 18, 'yf1234', 'r', 1520754123),
	(2410, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520754278),
	(2411, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：yf的信息', 18, 'yf', 'm', 1520754303),
	(2412, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：test666', 19, 'test666', 'r', 1520754457),
	(2413, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：test668', 20, 'test668', 'r', 1520754530),
	(2414, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：test133', 21, 'test133', 'r', 1520754643),
	(2415, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：test888', 22, 'test888', 'r', 1520754880),
	(2416, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：test1234', 23, 'test1234', 'r', 1520755121),
	(2417, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：test2234', 24, 'test2234', 'r', 1520755248),
	(2418, NULL, '127.0.0.1', 'XX 内网IP', '注册新用户：1', 25, '1', 'r', 1520755443),
	(2419, NULL, '127.0.0.1', 'XX 内网IP', 'yf审核用户：test666的状态为正常', 18, 'yf', 'c', 1520755459),
	(2420, NULL, '127.0.0.1', 'XX 内网IP', 'yf审核用户：test668的状态为正常', 18, 'yf', 'c', 1520755466),
	(2421, NULL, '127.0.0.1', 'XX 内网IP', 'yf审核用户：test133的状态为正常', 18, 'yf', 'c', 1520755473),
	(2422, NULL, '127.0.0.1', 'XX 内网IP', 'yf审核用户：test888的状态为正常', 18, 'yf', 'c', 1520755482),
	(2423, NULL, '127.0.0.1', 'XX 内网IP', 'yf审核用户：test1234的状态为正常', 18, 'yf', 'c', 1520755492),
	(2424, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：test666的信息', 18, 'yf', 'm', 1520755502),
	(2425, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：test668的信息', 18, 'yf', 'm', 1520755503),
	(2426, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：test133的信息', 18, 'yf', 'm', 1520755505),
	(2427, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：test888的信息', 18, 'yf', 'm', 1520755510),
	(2428, NULL, '127.0.0.1', 'XX 内网IP', 'test888登录成功', 22, 'test888', 'l', 1520755697),
	(2429, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：test888的模块权限', 18, 'yf', 'm', 1520755723),
	(2430, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520822671),
	(2431, NULL, '127.0.0.1', 'XX 内网IP', 'test888登录成功', 22, 'test888', 'l', 1520828206),
	(2432, NULL, '127.0.0.1', 'XX 内网IP', 'test888修改用户：test888的信息', 22, 'test888', 'm', 1520828235),
	(2433, NULL, '127.0.0.1', 'XX 内网IP', 'test888修改用户：test888的信息', 22, 'test888', 'm', 1520828458),
	(2434, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520851518),
	(2435, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：添加用户', 18, 'yf', 'a', 1520851946),
	(2436, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加用户：testuser的信息', 18, 'yf', 'm', 1520852015),
	(2437, NULL, '127.0.0.1', 'XX 内网IP', 'testuser登录成功', 26, 'testuser', 'l', 1520852193),
	(2438, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改用户：test666的信息', 18, 'yf', 'm', 1520852346),
	(2439, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520863096),
	(2440, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520909649),
	(2441, NULL, '127.0.0.1', 'XX 内网IP', 'test888登录成功', 22, 'test888', 'l', 1520910048),
	(2442, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520921315),
	(2443, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改权限：设备信息', 18, 'yf', 'm', 1520921375),
	(2444, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改权限：设备状态', 18, 'yf', 'm', 1520921405),
	(2445, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：设备列表', 18, 'yf', 'a', 1520921423),
	(2446, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改权限：桩基数据', 18, 'yf', 'm', 1520921954),
	(2447, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：桩基数据', 18, 'yf', 'a', 1520921997),
	(2448, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改模块：桩基质量', 18, 'yf', 'm', 1520922071),
	(2449, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：桩基成桩深度统计', 18, 'yf', 'a', 1520922131),
	(2450, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：桩基灌入量统计', 18, 'yf', 'a', 1520922178),
	(2451, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：报警数据', 18, 'yf', 'a', 1520922700),
	(2452, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：报警信息', 18, 'yf', 'a', 1520922721),
	(2453, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：获取报警信息选择项', 18, 'yf', 'a', 1520922752),
	(2454, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改权限：桩基成桩深度统计', 18, 'yf', 'm', 1520922772),
	(2455, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改权限：桩基灌入量统计', 18, 'yf', 'm', 1520922778),
	(2456, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：报警处理', 18, 'yf', 'a', 1520922822),
	(2457, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：报警设置', 18, 'yf', 'a', 1520922861),
	(2458, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新权限：桩基数据详情', 18, 'yf', 'a', 1520922898),
	(2459, NULL, '127.0.0.1', 'XX 内网IP', 'yf添加新设备：1', 18, 'yf', 'a', 1520924657),
	(2460, NULL, '127.0.0.1', 'XX 内网IP', 'yf修改设备：1', 18, 'yf', 'm', 1520924720),
	(2461, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1520997123),
	(2462, NULL, '127.0.0.1', 'XX 内网IP', 'yf登录成功', 18, 'yf', 'l', 1521019768),
	(2463, NULL, '::1', 'XX 内网IP', 'jinfeihu添加新模块：张拉', 18, 'jinfeihu', 'a', 1521451523),
	(2464, NULL, '::1', 'XX 内网IP', 'jinfeihu修改模块：张拉', 18, 'jinfeihu', 'm', 1521451546),
	(2465, NULL, '::1', 'XX 内网IP', 'jinfeihu修改模块：张拉', 18, 'jinfeihu', 'm', 1521451579),
	(2466, NULL, '::1', 'XX 内网IP', 'jinfeihu删除模块：张拉', 18, 'jinfeihu', 'd', 1521452077),
	(2467, NULL, '::1', 'XX 内网IP', 'jinfeihu修改模块：张拉', 18, 'jinfeihu', 'm', 1521452085),
	(2468, NULL, '::1', 'XX 内网IP', 'jinfeihu删除监理：XH-JL01', 18, 'jinfeihu', 'd', 1521454076),
	(2469, NULL, '::1', 'XX 内网IP', 'jinfeihu删除标段：XH-TJ01', 18, 'jinfeihu', 'd', 1521454080),
	(2470, NULL, '::1', 'XX 内网IP', 'jinfeihu删除项目标段：', 18, 'jinfeihu', 'd', 1521454086),
	(2471, NULL, '::1', 'XX 内网IP', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521454101),
	(2472, NULL, '::1', 'XX 内网IP', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521454106),
	(2473, NULL, '::1', 'XX 内网IP', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521454108),
	(2474, NULL, '::1', 'XX 内网IP', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521454110),
	(2475, NULL, '::1', 'XX 内网IP', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521454113),
	(2476, NULL, '::1', 'XX 内网IP', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521454114),
	(2477, NULL, '::1', 'XX 内网IP', 'jinfeihu删除用户：test666', 18, 'jinfeihu', 'd', 1521454447),
	(2478, NULL, '::1', 'XX 内网IP', 'jinfeihu修改模块：压浆', 18, 'jinfeihu', 'm', 1521456091),
	(2479, NULL, '::1', 'XX 内网IP', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521459419),
	(2480, NULL, '::1', 'XX 内网IP', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521459463),
	(2481, NULL, '113.139.91.221', '陕西 西安', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521463623),
	(2482, NULL, '113.139.91.221', '陕西 西安', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521466332),
	(2483, NULL, '113.139.91.221', '陕西 西安', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521466369),
	(2484, NULL, '113.139.91.221', '陕西 西安', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521466371),
	(2485, NULL, '36.47.18.23', '陕西 西安', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521473692),
	(2486, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521509704),
	(2487, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521509979),
	(2488, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521510000),
	(2489, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521510004),
	(2490, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除设备：', 18, 'jinfeihu', 'd', 1521510007),
	(2491, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除权限：智能喷淋养生设备', 18, 'jinfeihu', 'd', 1521510094),
	(2492, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除权限：运梁车', 18, 'jinfeihu', 'd', 1521510101),
	(2493, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除权限：架桥机', 18, 'jinfeihu', 'd', 1521510106),
	(2494, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除权限：运架一体机', 18, 'jinfeihu', 'd', 1521510111),
	(2495, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除权限：水准仪', 18, 'jinfeihu', 'd', 1521510116),
	(2496, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除权限：钻机设备', 18, 'jinfeihu', 'd', 1521510140),
	(2497, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu修改模块：压浆', 18, 'jinfeihu', 'm', 1521510422),
	(2498, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu删除项目：西阎城际铁路', 18, 'jinfeihu', 'd', 1521511386),
	(2499, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu修改项目：西安外环高速', 18, 'jinfeihu', 'm', 1521512500),
	(2500, NULL, '113.134.78.200', '陕西 西安', 'admin登录成功', 20, 'admin', 'l', 1521519706),
	(2501, NULL, '113.134.78.200', '陕西 西安', 'admin添加新监理：西安外环高速公路南段试验检测中心', 20, 'admin', 'a', 1521520138),
	(2502, NULL, '113.134.78.200', '陕西 西安', 'admin修改项目：西安外环高速公路南段', 20, 'admin', 'm', 1521520281),
	(2503, NULL, '222.90.232.86', '陕西 西安', '注册新用户：王超', 27, '王超', 'r', 1521520487),
	(2504, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加新监理：XAWHGS-0001', 18, 'jinfeihu', 'a', 1521522500),
	(2505, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加新标段：YJ-TJ01', 18, 'jinfeihu', 'a', 1521522614),
	(2506, NULL, '113.139.91.127', '陕西 西安', '修改监理：XAWHGS-0001管理的标段', 18, 'jinfeihu', 'm', 1521522656),
	(2507, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加新权限：添加用户', 18, 'jinfeihu', 'a', 1521527371),
	(2508, NULL, '222.90.232.86', '陕西 西安', '注册新用户：李', 28, '李', 'r', 1521531149),
	(2509, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加用户：test111的信息', 18, 'jinfeihu', 'm', 1521532271),
	(2510, NULL, '113.139.91.127', '陕西 西安', '李登录成功', 29, '李', 'l', 1521532567),
	(2511, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加新项目标段：LJ-13', 18, 'jinfeihu', 'a', 1521533209),
	(2512, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加新项目标段：LJ-14', 18, 'jinfeihu', 'a', 1521533250),
	(2513, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu添加用户：zxx123的信息', 18, 'jinfeihu', 'm', 1521533922),
	(2514, NULL, '113.139.91.127', '陕西 西安', '注册新用户：梁霖浩', 31, '梁霖浩', 'r', 1521534275),
	(2515, NULL, '113.139.91.127', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521534364),
	(2516, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu登录成功', 18, 'jinfeihu', 'l', 1521534904),
	(2517, NULL, '113.139.91.127', '陕西 西安', 'jinfeihu审核用户：梁霖浩的状态为正常', 18, 'jinfeihu', 'c', 1521534927),
	(2518, NULL, '1.85.11.243', '陕西 西安', 'zxx123删除用户：王超', 30, '张晓晓', 'd', 1521535028),
	(2519, NULL, '1.85.11.243', '陕西 西安', '注册新用户：皇甫磊磊', 32, '皇甫磊磊', 'r', 1521535354),
	(2520, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521535434),
	(2521, NULL, '1.85.11.243', '陕西 西安', 'zxx123审核用户：皇甫磊磊的状态为正常', 30, '张晓晓', 'c', 1521535509),
	(2522, NULL, '1.85.11.243', '陕西 西安', 'zxx123修改用户：张晓晓的信息', 30, '张晓晓', 'm', 1521535545),
	(2523, NULL, '1.85.11.243', '陕西 西安', '注册新用户：王海英', 33, '王海英', 'r', 1521535763),
	(2524, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521535782),
	(2525, NULL, '1.85.11.243', '陕西 西安', 'zxx123审核用户：皇甫磊磊的状态为正常', 30, '张晓晓', 'c', 1521535847),
	(2526, NULL, '1.85.35.182', '陕西 西安', '梁霖浩登录成功', 31, '梁霖浩', 'l', 1521536337),
	(2527, NULL, '1.85.35.182', '陕西 西安', 'SuperD12审核用户：王海英的状态为正常', 31, '梁霖浩', 'c', 1521536370),
	(2528, NULL, '1.85.11.243', '陕西 西安', '王海英登录成功', 33, '王海英', 'l', 1521536525),
	(2529, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521539250),
	(2530, NULL, '1.85.11.243', '陕西 西安', 'zxx123审核用户：张晓晓的状态为正常', 30, '张晓晓', 'c', 1521539264),
	(2531, NULL, '1.85.11.243', '陕西 西安', 'zxx123修改用户：张晓晓的信息', 30, '张晓晓', 'm', 1521539361),
	(2532, NULL, '1.85.11.243', '陕西 西安', 'zxx123审核用户：张晓晓的状态为正常', 30, '张晓晓', 'c', 1521539373),
	(2533, NULL, '1.85.35.182', '陕西 西安', 'SuperD12修改用户：梁霖浩的信息', 31, '梁霖浩', 'm', 1521539832),
	(2534, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521540061),
	(2535, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521540064),
	(2536, NULL, '1.85.11.243', '陕西 西安', 'zxx123修改用户：张晓晓的信息', 30, '张晓晓', 'm', 1521540108),
	(2537, NULL, '1.85.11.243', '陕西 西安', 'zxx123修改用户：张晓晓的信息', 30, '张晓晓', 'm', 1521540175),
	(2538, NULL, '1.85.11.243', '陕西 西安', 'zxx123审核用户：张晓晓的状态为正常', 30, '张晓晓', 'c', 1521540188),
	(2539, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521540203),
	(2540, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521547241),
	(2541, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521549753),
	(2542, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521549839),
	(2543, NULL, '1.85.11.243', '陕西 西安', '张晓晓登录成功', 30, '张晓晓', 'l', 1521549845),
	(2544, NULL, '113.139.195.222', '陕西 西安', '李登录成功', 29, '李', 'l', 1521550259),
	(2545, NULL, '113.139.195.222', '陕西 西安', '李登录成功', 29, '李', 'l', 1521550571),
	(2546, NULL, '113.139.195.222', '陕西 西安', '李登录成功', 29, '李', 'l', 1521550665),
	(2547, NULL, '113.139.195.222', '陕西 西安', '李登录成功', 29, '李', 'l', 1521550671),
	(2548, NULL, '113.139.195.222', '陕西 西安', '李登录成功', 29, '李', 'l', 1521552057),
	(2549, NULL, '1.85.11.246', '陕西 西安', '皇甫磊磊登录成功', 32, '皇甫磊磊', 'l', 1521552865),
	(2550, NULL, '122.226.183.70', '浙江 台州', 'yf修改权限：添加用户', 25, '1', 'm', 1521552992),
	(2551, NULL, '117.136.25.220', '陕西 西安', 'test111删除单位：陕西西法（南线）城际铁路有限公司', 29, '李', 'd', 1521553242),
	(2552, NULL, '117.136.25.220', '陕西 西安', 'test111删除单位：陕西西法（北线）城际铁路有限公司', 29, '李', 'd', 1521553244),
	(2553, NULL, '117.136.25.220', '陕西 西安', 'test111删除单位：陕西西阎城际铁路有限公司 ', 29, '李', 'd', 1521553247),
	(2554, NULL, '117.136.25.220', '陕西 西安', 'test111修改单位：中心实验室', 29, '李', 'm', 1521553335),
	(2555, NULL, '117.136.25.220', '陕西 西安', 'test111修改单位：总工办', 29, '李', 'm', 1521553350),
	(2556, NULL, '117.136.25.220', '陕西 西安', 'test111修改单位：中心实验室', 29, '李', 'm', 1521553393),
	(2557, NULL, '117.136.25.220', '陕西 西安', 'test111修改单位：总工办', 29, '李', 'm', 1521553400),
	(2558, NULL, '117.136.25.220', '陕西 西安', 'test111添加新项目标段：中心实验室', 29, '李', 'a', 1521553471),
	(2559, NULL, '122.226.183.70', '浙江 台州', 'yf删除项目：西法（北线）城际铁路', 25, '1', 'd', 1521553895),
	(2560, NULL, '122.226.183.70', '浙江 台州', 'yf删除项目：西法（南线）城际铁路', 25, '1', 'd', 1521553897),
	(2561, NULL, '122.226.183.70', '浙江 台州', 'yf删除项目：西安外环高速', 25, '1', 'd', 1521553938),
	(2562, NULL, '113.139.195.222', '陕西 西安', 'test111添加新标段：中心实验室', 29, '李', 'a', 1521554613),
	(2563, NULL, '113.139.195.222', '陕西 西安', '修改监理：西安外环高速公路南段试验检测中心管理的标段', 29, '李', 'm', 1521554660),
	(2564, NULL, '122.226.183.71', '浙江 台州', 'yf添加新设备：1', 25, '1', 'a', 1521554794),
	(2565, NULL, '113.139.195.222', '陕西 西安', 'test111删除单位：总工办', 29, '李', 'd', 1521555049),
	(2566, NULL, '113.139.195.222', '陕西 西安', 'test111删除单位：北京铁研建设监理有限责任公司', 29, '李', 'd', 1521555052),
	(2567, NULL, '113.139.195.222', '陕西 西安', 'test111删除单位：中铁十一局集团有限公司', 29, '李', 'd', 1521555054),
	(2568, NULL, '113.139.195.222', '陕西 西安', 'test111删除单位：中铁华铁工程设计集团有限公司', 29, '李', 'd', 1521555056),
	(2569, NULL, '113.139.195.222', '陕西 西安', 'test111删除单位：中铁二十局集团有限公司', 29, '李', 'd', 1521555058),
	(2570, NULL, '113.139.195.222', '陕西 西安', 'test111修改单位：项目管理处', 29, '李', 'm', 1521555093),
	(2571, NULL, '113.139.195.222', '陕西 西安', 'test111修改单位：中心实验室', 29, '李', 'm', 1521555094),
	(2572, NULL, '113.139.195.222', '陕西 西安', 'test111修改单位：项目管理处', 29, '李', 'm', 1521555096),
	(2573, NULL, '113.143.164.185', '陕西 西安', 'admin添加新设备：TYA-2000A', 20, 'admin', 'a', 1521555310),
	(2574, NULL, '122.226.183.71', '浙江 台州', 'yf修改用户：1的模块权限', 25, '1', 'm', 1521555365),
	(2575, NULL, '113.143.164.185', '陕西 西安', 'admin修改设备：TYA-300B型', 20, 'admin', 'm', 1521555524),
	(2576, NULL, '113.143.164.185', '陕西 西安', 'admin删除设备：', 20, 'admin', 'd', 1521555541),
	(2577, NULL, '113.143.164.185', '陕西 西安', 'admin添加新设备：TYA-2000A型', 20, 'admin', 'a', 1521555672),
	(2578, NULL, '223.72.98.78', '北京 北京', 'yf修改用户：1的信息', 25, '1', 'm', 1521555710),
	(2579, NULL, '122.226.183.70', '浙江 台州', 'yf添加新模块：农民工工资', 25, '1', 'a', 1521556033),
	(2580, NULL, '112.49.30.88', '福建 福州', 'yf修改用户：1的模块权限', 25, '1', 'm', 1521556058),
	(2581, NULL, '113.143.164.185', '陕西 西安', 'admin修改设备：TYA-300B型', 20, 'admin', 'm', 1521556064),
	(2582, NULL, '113.143.164.185', '陕西 西安', 'admin修改设备：TYA-2000A型', 20, 'admin', 'm', 1521556074),
	(2583, NULL, '122.226.183.70', '浙江 台州', 'yf修改模块：农民工工资', 25, '1', 'm', 1521556096),
	(2584, NULL, '122.226.183.70', '浙江 台州', 'yf修改模块：农民工工资', 25, '1', 'm', 1521556105),
	(2585, NULL, '122.226.183.70', '浙江 台州', 'yf修改模块：农民工工资', 25, '1', 'm', 1521556128),
	(2586, NULL, '113.139.195.222', '陕西 西安', 'test111修改用户：李的模块权限', 29, '李', 'm', 1521556170),
	(2587, NULL, '113.143.164.185', '陕西 西安', 'admin添加新设备：WA-100C', 20, 'admin', 'a', 1521556211),
	(2588, NULL, '122.226.183.70', '浙江 台州', 'yf修改模块：农民工工资', 25, '1', 'm', 1521556541),
	(2589, NULL, '113.143.164.185', '陕西 西安', 'admin添加新设备：WA-300C', 20, 'admin', 'a', 1521556545),
	(2590, NULL, '113.143.164.185', '陕西 西安', 'admin添加新设备：WA-1000C', 20, 'admin', 'a', 1521556654),
	(2591, NULL, '113.139.195.222', '陕西 西安', '李登录成功', 29, '李', 'l', 1521557539),
	(2592, NULL, '113.139.195.222', '陕西 西安', 'test111添加用户：lidroid的信息', 29, '李', 'm', 1521557746),
	(2593, NULL, '113.139.195.222', '陕西 西安', 'test111删除用户：李代斌', 29, '李', 'd', 1521557763),
	(2594, NULL, '113.139.195.222', '陕西 西安', 'test111添加用户：lidroid的信息', 29, '李', 'm', 1521557816),
	(2595, NULL, '1.85.11.243', '陕西 西安', 'zxx123修改用户：张晓晓的模块权限', 30, '张晓晓', 'm', 1521558428),
	(2596, NULL, '113.139.209.222', '陕西 西安', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1521558448),
	(2597, NULL, '113.139.209.222', '陕西 西安', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1521558459),
	(2598, NULL, '1.85.11.246', '陕西 西安', 'hfll1234修改用户：皇甫磊磊的信息', 32, '皇甫磊磊', 'm', 1521558838),
	(2599, NULL, '113.143.164.185', '陕西 西安', 'admin登录成功', 20, 'admin', 'l', 1521559143),
	(2600, NULL, '113.143.164.185', '陕西 西安', 'admin登录成功', 20, 'admin', 'l', 1521559144),
	(2601, NULL, '113.143.164.185', '陕西 西安', 'admin登录成功', 20, 'admin', 'l', 1521559145),
	(2602, NULL, '1.83.28.8', '陕西 西安', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1521559728),
	(2603, NULL, '113.143.164.185', '陕西 西安', 'admin删除设备：', 20, 'admin', 'd', 1521560700),
	(2604, NULL, '113.143.164.185', '陕西 西安', 'admin添加新设备：TYA-2000A型', 20, 'admin', 'a', 1521560811),
	(2605, NULL, '1.83.28.8', '陕西 西安', 'test111添加新权限：查看农民工资', 29, '李', 'a', 1521560847),
	(2606, NULL, '1.83.28.8', '陕西 西安', 'test111修改用户：李代斌的模块权限', 29, '李', 'm', 1521560942),
	(2607, NULL, '1.83.28.8', '陕西 西安', 'test111修改用户：李的模块权限', 29, '李', 'm', 1521560953),
	(2608, NULL, '113.139.195.222', '陕西 西安', 'test111修改项目：西安外环高速公路南段', 29, '李', 'm', 1521561262),
	(2609, NULL, '113.139.195.222', '陕西 西安', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1521562000),
	(2610, NULL, '1.83.28.8', '陕西 西安', 'test111修改模块：农民工工资', 29, '李', 'm', 1521562122),
	(2611, NULL, '1.83.28.8', '陕西 西安', 'test111修改模块：农民工工资', 29, '李', 'm', 1521562310),
	(2612, NULL, '1.83.28.8', '陕西 西安', 'test111修改模块：农民工工资', 29, '李', 'm', 1521562889),
	(2613, NULL, '1.83.28.8', '陕西 西安', 'test111修改模块：农民工工资', 29, '李', 'm', 1521563081),
	(2614, NULL, '1.83.28.8', '陕西 西安', 'test111修改模块：农民工工资', 29, '李', 'm', 1521563133),
	(2615, NULL, '222.90.232.86', '陕西 西安', '注册新用户：王馨萍', 36, '王馨萍', 'r', 1521596484),
	(2616, NULL, '222.90.232.86', '陕西 西安', 'zxx123审核用户：王馨萍的状态为正常', 30, '张晓晓', 'c', 1521596507),
	(2617, NULL, '113.139.91.127', '陕西 西安', 'test111修改项目标段：试验检测中心', 29, '李', 'm', 1521600820),
	(2618, NULL, '113.139.91.127', '陕西 西安', 'test111修改模块：计量支付', 29, '李', 'm', 1521610752),
	(2619, NULL, '222.90.232.86', '陕西 西安', '注册新用户：王超', 37, '王超', 'r', 1521611793),
	(2620, NULL, '222.90.232.86', '陕西 西安', 'zxx123审核用户：王超的状态为正常', 30, '张晓晓', 'c', 1521611838),
	(2621, NULL, '113.139.104.166', '陕西 西安', 'test111修改模块：农民工工资', 29, '李', 'm', 1521619663),
	(2622, NULL, '113.139.104.166', '陕西 西安', 'test111修改权限：查看农民工资', 29, '李', 'm', 1521619731),
	(2623, NULL, '113.139.104.166', '陕西 西安', 'test111添加新权限：获取实验室视频', 29, '李', 'a', 1521635268),
	(2624, NULL, '113.139.104.166', '陕西 西安', 'test111删除模块：运架梁', 29, '李', 'd', 1521685135),
	(2627, NULL, '113.139.104.166', '陕西 西安', 'test111删除模块：检测数据', 29, '李', 'd', 1521685492),
	(2628, NULL, '113.139.104.166', '陕西 西安', 'test111添加新模块：隧道安全', 29, '李', 'a', 1521686000),
	(2629, NULL, '113.139.104.166', '陕西 西安', 'test111添加新模块：环境检测', 29, '李', 'a', 1521686075),
	(2630, NULL, '113.139.104.166', '陕西 西安', 'test111修改模块：隧道安全', 29, '李', 'm', 1521686186),
	(2631, NULL, '113.139.104.166', '陕西 西安', 'test111修改模块：环境检测', 29, '李', 'm', 1521686189),
	(2632, NULL, '113.139.104.166', '陕西 西安', 'test111添加新权限：环境检测', 29, '李', 'a', 1521687325),
	(2633, NULL, '113.139.104.166', '陕西 西安', 'test111添加新权限：隧道安全', 29, '李', 'a', 1521687406),
	(2634, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521787903),
	(2635, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521789334),
	(2636, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1521796827),
	(2637, NULL, '::1', 'XX 内网IP', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1521798566),
	(2638, NULL, '::1', 'XX 内网IP', 'test111修改权限：获取实验室视频', 29, '李', 'm', 1521801708),
	(2639, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521809327),
	(2640, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1521809368),
	(2641, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521828783),
	(2642, NULL, '::1', 'XX 内网IP', 'test111修改模块：隧道安全', 29, '李', 'm', 1521829093),
	(2643, NULL, '::1', 'XX 内网IP', 'test111修改模块：计量支付', 29, '李', 'm', 1521829156),
	(2644, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521856687),
	(2645, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1521857703),
	(2646, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521890967),
	(2647, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1521890977),
	(2648, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521891009),
	(2649, NULL, '::1', 'XX 内网IP', 'test111修改模块：压浆', 29, '李', 'm', 1521894336),
	(2650, NULL, '::1', 'XX 内网IP', 'test111添加新设备：abc', 29, '李', 'a', 1521900473),
	(2651, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1521979296),
	(2652, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522075562),
	(2653, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522076483),
	(2654, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522081617),
	(2655, NULL, '::1', 'XX 内网IP', 'test111添加新项目：aaa', 29, '李', 'a', 1522082561),
	(2656, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522135398),
	(2657, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522136436),
	(2658, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522136646),
	(2659, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522144282),
	(2660, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522146863),
	(2661, NULL, '::1', 'XX 内网IP', 'test111修改权限：地图首页', 29, '李', 'm', 1522146898),
	(2662, NULL, '::1', 'XX 内网IP', 'test111修改权限：地图首页', 29, '李', 'm', 1522146917),
	(2663, NULL, '::1', 'XX 内网IP', 'test111修改模块：地图展示', 29, '李', 'm', 1522146955),
	(2664, NULL, '::1', 'XX 内网IP', 'test111修改模块：地图展示', 29, '李', 'm', 1522146978),
	(2665, NULL, '::1', 'XX 内网IP', 'test111修改模块：地图展示', 29, '李', 'm', 1522147028),
	(2666, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522148575),
	(2667, NULL, '::1', 'XX 内网IP', 'test111修改模块：地图展示', 29, '李', 'm', 1522149430),
	(2668, NULL, '::1', 'XX 内网IP', 'test111修改模块：试验室', 29, '李', 'm', 1522158274),
	(2669, NULL, '::1', 'XX 内网IP', 'test111修改模块：拌和站', 29, '李', 'm', 1522158282),
	(2670, NULL, '::1', 'XX 内网IP', 'test111修改模块：拌和站', 29, '李', 'm', 1522158299),
	(2671, NULL, '::1', 'XX 内网IP', 'test111修改模块：试验室', 29, '李', 'm', 1522158305),
	(2672, NULL, '::1', 'XX 内网IP', 'test111修改模块：视频监控', 29, '李', 'm', 1522158805),
	(2673, NULL, '::1', 'XX 内网IP', 'test111修改模块：视频监控', 29, '李', 'm', 1522158819),
	(2674, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522159326),
	(2675, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522159362),
	(2676, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522198830),
	(2677, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522209058),
	(2678, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522219952),
	(2679, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522221202),
	(2680, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522238262),
	(2681, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522238383),
	(2682, NULL, '::1', 'XX 内网IP', 'test111删除用户：adfsafdsa', 29, '李', 'd', 1522238795),
	(2683, NULL, '::1', 'XX 内网IP', 'test111删除用户：fdafdsa', 29, '李', 'd', 1522238916),
	(2684, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522250684),
	(2685, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522260530),
	(2686, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522295689),
	(2687, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522297848),
	(2688, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522311171),
	(2689, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522311522),
	(2690, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522393659),
	(2691, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522395259),
	(2692, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522396278),
	(2693, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522397396),
	(2694, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522415493),
	(2695, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522415521),
	(2696, NULL, '::1', 'XX 内网IP', 'test111添加新项目标段：AAA', 29, '李', 'a', 1522415610),
	(2697, NULL, '::1', 'XX 内网IP', 'test111删除项目标段：', 29, '李', 'd', 1522415616),
	(2698, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522416338),
	(2699, NULL, '::1', 'XX 内网IP', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1522416352),
	(2700, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522416359),
	(2701, NULL, '::1', 'XX 内网IP', '李登录成功', 29, '李', 'l', 1522424545),
	(2702, NULL, '::1', 'XX 内网IP', 'test111修改用户：李代斌的信息', 29, '李', 'm', 1522424557),
	(2703, NULL, '::1', 'XX 内网IP', '李代斌登录成功', 35, '李代斌', 'l', 1522424566),
	(2704, NULL, '::1', 'XX 内网IP', 'lidroid添加新项目标段：AAA', 35, '李代斌', 'a', 1522429645),
	(2705, NULL, '::1', 'XX 内网IP', 'lidroid删除项目标段：', 35, '李代斌', 'd', 1522430052);
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- 导出  表 road.map 结构
CREATE TABLE IF NOT EXISTS `map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `content` varchar(3000) DEFAULT NULL,
  `jwd` varchar(60) DEFAULT NULL,
  `sort` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.map 的数据：~0 rows (大约)
DELETE FROM `map`;
/*!40000 ALTER TABLE `map` DISABLE KEYS */;
/*!40000 ALTER TABLE `map` ENABLE KEYS */;

-- 导出  表 road.material 结构
CREATE TABLE IF NOT EXISTS `material` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '材料名称',
  `type` varchar(30) NOT NULL COMMENT '材料分类',
  `dasign_rate` varchar(8) NOT NULL COMMENT '设计配合比',
  `warn_rate` varchar(8) NOT NULL COMMENT '报警比例',
  `note` varchar(450) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- 正在导出表  road.material 的数据：~3 rows (大约)
DELETE FROM `material`;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` (`id`, `name`, `type`, `dasign_rate`, `warn_rate`, `note`) VALUES
	(1, '骨料1', '12', '12', '12', '12'),
	(2, '骨料2', '1', '1', '1', '1'),
	(3, '骨料3', '1', '1', '1', '1');
/*!40000 ALTER TABLE `material` ENABLE KEYS */;

-- 导出  表 road.mixplant 结构
CREATE TABLE IF NOT EXISTS `mixplant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `name` varchar(60) NOT NULL COMMENT '拌合站名称',
  `database_type` varchar(20) NOT NULL COMMENT '数据库类型',
  `status` varchar(20) NOT NULL COMMENT '采集状态',
  `product_rate` varchar(60) NOT NULL COMMENT '生产能力',
  `factory` varchar(90) NOT NULL COMMENT '生产厂家',
  `capacity` varchar(20) NOT NULL COMMENT '公称容量',
  `fzr` varchar(60) NOT NULL COMMENT '负责人',
  `phone` varchar(15) NOT NULL COMMENT '手机号',
  `created_at` int(11) unsigned NOT NULL COMMENT '登记时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_section_name` (`project_id`,`section_id`,`name`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在导出表  road.mixplant 的数据：~5 rows (大约)
DELETE FROM `mixplant`;
/*!40000 ALTER TABLE `mixplant` DISABLE KEYS */;
INSERT INTO `mixplant` (`id`, `project_id`, `section_id`, `name`, `database_type`, `status`, `product_rate`, `factory`, `capacity`, `fzr`, `phone`, `created_at`) VALUES
	(1, 4, 2, '拌合站1', '12', '2', '12', '1', '1', '1', '1', 1500550839),
	(2, 4, 2, '拌合站2', '2', '2', '2', '2', '2', '2', '2', 1501513925),
	(3, 4, 2, '拌合站32', '3', '3', '3', '33', '3', '3', '3', 1501514006),
	(6, 4, 2, '拌合站45', '5', '5', '5', '5', '5', '5', '5', 1501514240),
	(7, 4, 2, '拌合站3', '1', '1', '1', '1', '1', '1', '1', 1504438761);
/*!40000 ALTER TABLE `mixplant` ENABLE KEYS */;

-- 导出  表 road.module 结构
CREATE TABLE IF NOT EXISTS `module` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `pid` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(60) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `sort` tinyint(2) DEFAULT NULL,
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新窗口打开',
  `shown` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- 正在导出表  road.module 的数据：~18 rows (大约)
DELETE FROM `module`;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` (`id`, `pid`, `name`, `url`, `icon`, `sort`, `is_new`, `shown`) VALUES
	(1, 12, '地图展示', 'map/index', 'map', 2, 0, 0),
	(2, 12, '系统设置', 'manage/project', 'manage', 1, 0, 1),
	(3, 13, '试验室', 'lab/index', 'lab', 2, 0, 1),
	(4, 13, '拌和站', 'snbhz/index', 'snbhz', 3, 0, 1),
	(7, 13, '压浆', 'pressure/index', 'grouting', 6, 0, 1),
	(8, 13, '沉降监测', 'cjjc/index', 'cjjc', 9, 0, 1),
	(9, 13, '张拉', 'zlyj/index', 'zlyj', 4, 0, 1),
	(12, 0, '综合管理', '1', '1', 0, 0, 1),
	(13, 0, '现场管理', '1', '1', 0, 0, 1),
	(14, 0, '外部接入', '1', '1', 0, 0, 1),
	(15, 14, '前期手续', 'qqsx/index', 'qqsx', 13, 1, 1),
	(16, 14, 'BIM', 'bim/index', 'bim', 14, 0, 1),
	(17, 14, '计量支付', 'http://117.34.104.21:10010/xanwh/login;JSESSIONID=cf95ecc1-0baf-4550-8d52-a3ce5ca1b524', 'jlzf', 15, 1, 1),
	(18, 14, '电子档案', 'sgrz/index', 'sgrz', 16, 0, 1),
	(21, 13, '视频监控', 'spjk/index', 'spjk', 1, 1, 1),
	(23, 13, '农民工工资', 'nmggz/index', 'nmggz', 17, 1, 1),
	(25, 13, '隧道安全', 'tunnel/index', 'tunnel', 18, 0, 1),
	(26, 13, '环境检测', 'natural/index', 'natural', 19, 0, 1);
/*!40000 ALTER TABLE `module` ENABLE KEYS */;

-- 导出  表 road.pbbh 结构
CREATE TABLE IF NOT EXISTS `pbbh` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `ds` double(6,1) unsigned DEFAULT NULL COMMENT '大石',
  `zs` double(6,1) unsigned DEFAULT NULL COMMENT '中石',
  `xs` double(6,1) unsigned DEFAULT NULL COMMENT '小石',
  `sz` double(6,1) unsigned DEFAULT NULL COMMENT '砂子',
  `sn` double(6,1) unsigned DEFAULT NULL COMMENT '水泥',
  `fmh` double(6,1) unsigned DEFAULT NULL COMMENT '粉煤灰',
  `s` double(6,1) unsigned DEFAULT NULL COMMENT '水',
  `wjj` double(6,1) unsigned DEFAULT NULL COMMENT '外加剂',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在导出表  road.pbbh 的数据：~0 rows (大约)
DELETE FROM `pbbh`;
/*!40000 ALTER TABLE `pbbh` DISABLE KEYS */;
INSERT INTO `pbbh` (`id`, `name`, `ds`, `zs`, `xs`, `sz`, `sn`, `fmh`, `s`, `wjj`) VALUES
	(1, 'PF170310001', 194.0, 618.0, 400.0, 667.0, 423.0, 1.0, 155.0, 4.2);
/*!40000 ALTER TABLE `pbbh` ENABLE KEYS */;

-- 导出  表 road.pda 结构
CREATE TABLE IF NOT EXISTS `pda` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL COMMENT '项目id',
  `company` varchar(60) DEFAULT NULL COMMENT '单位名称',
  `username` varchar(20) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `region` tinyint(3) unsigned DEFAULT NULL,
  `city` smallint(3) unsigned DEFAULT NULL,
  `phone` varchar(15) NOT NULL COMMENT '手机号码',
  `phone_model` varchar(30) NOT NULL COMMENT '手机型号',
  `phone_system` varchar(20) NOT NULL COMMENT '手机系统',
  `phone_px` varchar(20) NOT NULL COMMENT '手机像素',
  `status` tinyint(1) unsigned NOT NULL COMMENT '1',
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `project` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在导出表  road.pda 的数据：~2 rows (大约)
DELETE FROM `pda`;
/*!40000 ALTER TABLE `pda` DISABLE KEYS */;
INSERT INTO `pda` (`id`, `project_id`, `company`, `username`, `name`, `password`, `remember_token`, `ip`, `region`, `city`, `phone`, `phone_model`, `phone_system`, `phone_px`, `status`, `created_at`, `updated_at`) VALUES
	(1, 0, NULL, 'yf', NULL, '$2y$10$oJAwP2gM9Y4wZrnBfBY1CuwjNJlUS88GjCs0XFkzhZSskYE.uYmDi', NULL, '127.0.0.1', 0, 0, '', '', '', '', 1, 2017, 1494484152),
	(2, 4, '1234', 'pda111', 'pda111', '', NULL, NULL, NULL, NULL, '11', '1', '1', '1', 1, 1501577930, 0);
/*!40000 ALTER TABLE `pda` ENABLE KEYS */;

-- 导出  表 road.permission 结构
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` tinyint(2) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限id',
  `url` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(16) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否左侧菜单显示',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8;

-- 正在导出表  road.permission 的数据：~145 rows (大约)
DELETE FROM `permission`;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` (`id`, `mid`, `pid`, `url`, `name`, `icon`, `description`, `status`, `sort`, `created_at`, `updated_at`) VALUES
	(1, 2, 0, 'manage/index', '系统设置首页', NULL, NULL, 0, 100, 1504447811, 1504447882),
	(2, 2, 0, 'manage/uu', '用户管理', '&#xe62c;', '权限列表，角色列表', 1, 3, 1500465290, 1513692482),
	(3, 2, 2, 'manage/permission', '权限列表', '', '权限列表', 1, 31, 1500465290, 1513692622),
	(4, 2, 2, 'manage/role', '角色列表', '', '角色列表', 1, 32, 1500465499, 1513692622),
	(5, 2, 2, 'manage/admin', '用户信息', '', '用户列表', 1, 33, 1500466420, 1513692623),
	(6, 2, 2, 'manage/pda', 'PDA用户信息', '', 'PDA列表', 0, 34, 1500466551, 1513692625),
	(7, 2, 10, 'manage/log', '日志信息', '', '日志列表', 1, 45, 1500468551, 1513692633),
	(8, 2, 0, 'manage/pro', '项目管理', '&#xe667;', '', 1, 1, 1500468608, 1513692465),
	(9, 2, 0, 'manage/dev', '设备管理', '&#xe63c;', '', 1, 2, 1500468628, 1513692470),
	(10, 2, 0, 'manage/ll', '日志管理', '&#xe616;', '日志列表', 1, 4, 1504440712, 1513692483),
	(11, 2, 8, 'manage/project', '项目信息', '', '', 1, 20, 1500468902, 1512907102),
	(12, 2, 8, 'manage/section', '标段信息', '', '', 1, 22, 1500469010, 1512907260),
	(13, 2, 8, 'manage/supervision', '监理信息', '', '', 1, 21, 1500469498, 1512907261),
	(14, 2, 8, 'manage/factory', '厂家设置', '', '', 0, 23, 1500469555, 1500870014),
	(15, 2, 8, 'manage/factory_detail', '厂家明细', '', '', 0, 24, 1500469584, 1500870017),
	(16, 2, 8, 'manage/material', '材料设置', '', '', 0, 25, 1500469622, 1500870020),
	(18, 2, 9, 'manage/device', '设备管理', '', '', 0, 26, 1500469730, 1504601657),
	(19, 2, 9, 'manage/truck', '车辆管理', '', '', 0, 27, 1500470006, 1500870044),
	(20, 2, 9, 'manage/detection_device', '检测设备管理', '', '', 0, 28, 1500470053, 1504536423),
	(21, 2, 8, 'manage/mixplant', '标段拌合站列表', '', '', 0, 100, 1500546983, 1500546983),
	(22, 2, 8, 'manage/sup_section', '设置标段', '', '', 0, 100, 1500561994, 1500561994),
	(23, 2, 9, 'manage/dev_category', '设备分类', '', '', 0, 29, 1500723639, 1501761706),
	(24, 2, 9, 'manage/dev_type', '设备类型', '', '', 0, 30, 1500723681, 1501761714),
	(25, 2, 9, 'manage/collection', '采集点', '', '', 0, 31, 1500736125, 1501516286),
	(26, 2, 9, 'manage/truck_category', '车辆分类', '', '', 0, 32, 1500802765, 1504536414),
	(27, 2, 2, 'manage/module', '模块管理', '', '', 1, 38, 1501586031, 1501588581),
	(28, 4, 0, 'snbhz/dev', '设备信息', '&#xe62e;', '', 1, 4, 1501586235, 1501992763),
	(29, 4, 28, 'snbhz/device', '设备列表', '', '', 1, 100, 1501586276, 1501681640),
	(30, 4, 0, 'snbhz/sj', '拌和数据', '&#xe616;', '', 1, 5, 1501684511, 1513324033),
	(31, 4, 30, 'snbhz/product_data', '拌和生产数据', '', '', 1, 100, 1501684588, 1513324041),
	(32, 4, 30, 'snbhz/data_report', '拌和数据报表', '', '', 1, 100, 1501684685, 1513324049),
	(33, 4, 30, 'snbhz/data_curve', '拌和数据曲线', '', '', 1, 100, 1501684728, 1513324060),
	(34, 4, 30, 'snbhz/deviation_curve', '偏差率曲线', '', '', 1, 100, 1501684862, 1501684862),
	(35, 4, 0, 'snbhz/warn', '报警信息', '&#xe62f;', '', 1, 6, 1501684984, 1501992770),
	(36, 4, 35, 'snbhz/warn_info', '报警信息', '', '', 1, 100, 1501685029, 1501685029),
	(37, 4, 35, 'snbhz/warn_report', '报警信息统计', '', '', 1, 100, 1501685057, 1501685057),
	(38, 4, 0, 'snbhz/db', '生产统计', '&#xe635;', '', 1, 7, 1501685098, 1505140801),
	(39, 4, 38, 'snbhz/product_report', '日生产统计', '', '', 1, 100, 1501685262, 1501685262),
	(40, 4, 38, 'snbhz/product_compare', '生产对比图', '', '', 0, 100, 1501685304, 1516719265),
	(41, 4, 35, 'snbhz/warn_compare', '报警对比图', '', '', 1, 100, 1501685344, 1505140768),
	(42, 4, 0, 'mixplant/index', '拌和站首页', '', '', 0, 8, 1501742189, 1513324074),
	(43, 4, 28, 'snbhz/index', '设备状态', '', '', 1, 5, 1501842683, 1501992768),
	(44, 4, 30, 'snbhz/deal', '处理拌合信息', '', '', 0, 100, 1501992720, 1501992720),
	(45, 4, 30, 'snbhz/detail_info', '获取物料和处理信息', '', '', 0, 100, 1502026756, 1502026756),
	(48, 2, 8, 'manage/beamfield', '标段梁场列表', '', '', 0, 100, 1504447532, 1504447532),
	(51, 2, 8, 'manage/tunnel', '标段隧道列表', '', '', 0, 100, 1504447651, 1504447651),
	(52, 2, 2, 'manage/user_mod', '设置操作员管理的模块', '', '', 0, 100, 1504447811, 1504447882),
	(57, 2, 8, 'manage/get_sec', '通过监理获取标段', '', '', 0, 100, 1504527292, 1504527292),
	(58, 2, 2, 'manage/dev_user?type=1', '试验室负责人', '', '', 0, 100, 1504597087, 1504618378),
	(59, 2, 2, 'manage/dev_user?type=2', '张拉压浆设备负责人', '', '', 0, 100, 1504597144, 1504618387),
	(60, 2, 2, 'manage/dev_user?type=3', '拌合设备负责人', '', '', 0, 100, 1504597186, 1504618394),
	(61, 2, 9, 'manage/kzy_dev', '抗折压一体机', '', '', 1, 31, 1504597249, 1508233426),
	(62, 2, 9, 'manage/yl_dev', '压力试验机', '', '', 1, 32, 1504597270, 1508233427),
	(63, 2, 9, 'manage/wn_dev', '万能试验机', '', '', 1, 33, 1504597342, 1508233428),
	(64, 2, 9, 'manage/bh_dev', '拌和设备', '', '', 1, 34, 1504597363, 1513323139),
	(65, 2, 9, 'manage/get_sup', '通过项目获取监理信息', '', '', 0, 100, 1504765772, 1508233445),
	(66, 3, 0, 'lab/dev', '设备信息', '', '', 1, 100, 1505642965, 1505642965),
	(67, 3, 66, 'lab/index', '设备状态', '', '', 1, 100, 1505642987, 1505642987),
	(68, 3, 66, 'lab/device', '设备列表', '', '', 1, 100, 1505642999, 1505642999),
	(69, 3, 0, 'lab/sj', '试验数据', '', '', 1, 100, 1505643019, 1505643019),
	(70, 3, 69, 'lab/lab_data', '试验数据', '', '', 1, 100, 1505643057, 1505643057),
	(71, 3, 0, 'lab/bj', '报警信息', '', '', 1, 100, 1505643106, 1505643106),
	(72, 3, 71, 'lab/warn_data', '报警数据', '', '', 1, 100, 1505643124, 1505643124),
	(73, 3, 71, 'lab/warn_report', '报警信息统计', '', '', 0, 100, 1505643189, 1518010167),
	(74, 3, 0, 'lab/tj', '统计信息', '', '', 0, 100, 1505643218, 1505643354),
	(75, 3, 69, 'lab/section_report', '试验数据统计', '', '', 1, 100, 1505643412, 1518010125),
	(76, 3, 69, 'lab/type_report', '各类型试验数据统计', '', '', 1, 100, 1505643540, 1510486447),
	(77, 3, 71, 'lab/section_warn_report', '报警统计', '', '', 1, 100, 1505643619, 1518010140),
	(78, 3, 71, 'lab/type_warn_report', '各类型报警统计', '', '', 1, 100, 1505643701, 1518010196),
	(80, 2, 9, 'manage/ylj_dev', '压路机设备', '', '', 1, 36, 1508044123, 1508233452),
	(81, 2, 9, 'manage/qzy_dev', '全站仪', '', '', 1, 37, 1508044136, 1508233455),
	(82, 2, 9, 'manage/zl_dev', '张拉设备', '', '', 1, 38, 1508044162, 1508233456),
	(83, 2, 9, 'manage/yj_dev', '压浆设备', '', '', 1, 39, 1508044191, 1508233458),
	(85, 2, 9, 'manage/znys_dev', '智能蒸汽养生设备', '', '', 1, 41, 1508044229, 1508233463),
	(90, 1, 0, 'map/index', '地图首页', '', '', 1, 100, 1508230089, 1522146917),
	(91, 3, 69, 'lab/deal', '处理报警数据', '', '', 0, 100, 1510478668, 1510478668),
	(92, 2, 2, 'manage/user_info', '审核用户', '', '', 0, 100, 1511429567, 1511429567),
	(93, 25, 0, 'tunnel/', '隧道安全', '&#xe6c9;', '', 1, 100, 1511770510, 1511770510),
	(94, 25, 0, 'tunnel/tj', '统计分析', '&#xe635;', '', 1, 100, 1511770598, 1511770598),
	(95, 25, 93, 'tunnel/index', '定位信息', '', '', 1, 100, 1511770743, 1511770743),
	(96, 25, 93, 'tunnel/people', '当前隧道人员', '', '', 1, 100, 1511770764, 1511770764),
	(97, 25, 93, 'tunnel/car', '当前隧道车辆', '', '', 1, 100, 1511770785, 1511770785),
	(98, 25, 94, 'tunnel/history_people', '历史隧道人员', '', '', 1, 100, 1511770850, 1511770850),
	(99, 25, 94, 'tunnel/history_car', '历史隧道车辆', '', '', 1, 100, 1511770878, 1511770878),
	(100, 20, 0, 'ycjc/index', '实时监测', '&#xe690;', '', 1, 100, 1511788045, 1511788672),
	(101, 20, 0, 'ycjc/history_data', '历史报警数据', '&#xe667;', '', 1, 100, 1511788395, 1511788738),
	(102, 20, 0, 'ycjc/warn', '报警统计', '&#xe635;', '', 1, 100, 1511788447, 1511788708),
	(103, 4, 30, 'snbhz/file/upload', '上传文件', '', '', 0, 100, 1512999976, 1512999976),
	(104, 4, 30, 'snbhz/del_file', '删除上传数据', '', '', 0, 100, 1513000016, 1513000016),
	(105, 2, 8, 'manage/psection', '项目标段', '', '', 1, 100, 1513605236, 1513605236),
	(106, 2, 8, 'manage/get_psec', '根据项目获取标段', '', '', 0, 100, 1513605270, 1513605270),
	(107, 2, 2, 'manage/position', '职务管理', '', '', 1, 100, 1513605291, 1513605291),
	(108, 2, 8, 'manage/map', '地图管理', '', '', 1, 100, 1513691920, 1513691920),
	(109, 4, 35, 'snbhz/warn_set', '报警设置', '', '', 1, 100, 1514278535, 1514278535),
	(110, 3, 66, 'lab/olab', '进入试验室系统', '', '', 0, 100, 1514985619, 1514985619),
	(111, 3, 71, 'lab/warn_set', '报警设置', '', '', 1, 100, 1515986323, 1515986323),
	(112, 4, 38, 'snbhz/product_report_week', '周生产统计', '', '', 0, 100, 1515986362, 1516719257),
	(113, 21, 0, 'spjk/index', '视频监控首页', '', '', 1, 100, 1516193401, 1516193401),
	(114, 4, 0, 'snbhz/sxh', '信息化报表', '&#xe667;', '', 1, 100, 1516368130, 1516503267),
	(115, 4, 114, 'snbhz/stat_week', '周报表', '', '', 1, 100, 1516368152, 1516368152),
	(116, 4, 114, 'snbhz/stat_month', '月报表', '', '', 1, 100, 1516368173, 1516368173),
	(117, 4, 114, 'snbhz/tzlb', '处理台账', '', '', 1, 100, 1516368196, 1516503411),
	(118, 4, 114, 'snbhz/stat_login', '登录统计', '', '', 1, 100, 1516368219, 1516503290),
	(119, 4, 35, 'snbhz/clbg', '处理报告', '', '', 0, 100, 1516368238, 1516368997),
	(120, 3, 71, 'lab/clbg', '处理报告', '', '', 0, 100, 1516512673, 1516512673),
	(121, 3, 0, 'lab/xxh', '信息化报表', '', '', 1, 100, 1516512710, 1516512942),
	(122, 3, 121, 'lab/tzlb', '处理台账', '', '', 1, 100, 1516512732, 1516512732),
	(123, 3, 121, 'lab/stat_login', '登录统计', '', '', 1, 100, 1516512758, 1516512758),
	(124, 3, 121, 'lab/stat_week', '周统计', '', '', 1, 100, 1516512783, 1516512783),
	(125, 3, 121, 'lab/stat_month', '月统计', '', '', 1, 100, 1516512811, 1516512811),
	(126, 3, 69, 'lab/file/upload', '上传文件', '', '', 0, 100, 1516801293, 1516801293),
	(127, 3, 69, 'lab/del_file', '删除上传数据', '', '', 0, 100, 1516801320, 1516801320),
	(128, 3, 66, 'lab/video', '实时视频', '', '', 0, 100, 1517566872, 1517566872),
	(129, 2, 10, 'manage/stat_login', '登录统计', '', '', 1, 100, 1519736856, 1519736856),
	(130, 3, 71, 'lab/get_select_val', '获取报警信息选项', '', '', 0, 100, 1519736884, 1519736884),
	(131, 4, 35, 'snbhz/get_select_val', '获取报警信息选项', '', '', 0, 100, 1519736921, 1519736921),
	(132, 15, 0, 'qqsx/index', '前期手续首页', '', '', 0, 100, 1519736974, 1519736974),
	(133, 9, 0, 'zlyj/d', '设备信息', '', '', 1, 100, 1519737060, 1519737060),
	(134, 9, 133, 'zlyj/index', '设备状态', '', '', 1, 100, 1519737078, 1519737078),
	(135, 9, 133, 'zlyj/device', '设备列表', '', '', 1, 100, 1519737107, 1519737107),
	(136, 9, 0, 'zlyj/dd', '张拉压浆数据', '', '', 1, 100, 1519737556, 1519737556),
	(137, 9, 136, 'zlyj/zlyj_data', '张拉压浆数据', '', '', 1, 100, 1519737580, 1519737580),
	(138, 9, 136, 'zlyj/zl_data_report', '张拉数据报表', '', '', 0, 100, 1519737601, 1519737601),
	(139, 9, 136, 'zlyj/yj_data_report', '压浆数据报表', '', '', 0, 100, 1519737615, 1519737615),
	(140, 9, 0, 'zlyj/warn', '报警信息', '', '', 1, 100, 1519737635, 1519737635),
	(141, 9, 140, 'zlyj/warn_info', '报警信息', '', '', 1, 100, 1519737651, 1519737651),
	(142, 9, 140, 'zlyj/warn_report', '报警信息统计', '', '', 1, 100, 1519737672, 1519737672),
	(143, 9, 140, 'zlyj/warn_compare', '报警对比图', '', '', 0, 100, 1519737707, 1519737707),
	(144, 9, 140, 'zlyj/warn_set', '报警设置', '', '', 1, 100, 1519737726, 1519737726),
	(145, 9, 140, 'zlyj/get_select_val', '获取报警信息选项', '', '', 0, 100, 1519737751, 1519737751),
	(146, 9, 0, 'zlyj/stat', '信息化报表', '', '', 1, 100, 1519737774, 1519737774),
	(147, 9, 146, 'zlyj/tzlb', '处理台账', '', '', 1, 100, 1519737797, 1519737797),
	(148, 9, 146, 'zlyj/stat_login', '登录统计', '', '', 1, 100, 1519737812, 1519737812),
	(149, 9, 146, 'zlyj/stat_week', '周报表', '', '', 1, 100, 1519737826, 1519737826),
	(150, 9, 146, 'zlyj/stat_month', '月报表', '', '', 1, 100, 1519737840, 1519737840),
	(151, 9, 136, 'zlyj/deal', '处理报警信息', '', '', 0, 100, 1519737855, 1519737855),
	(152, 9, 136, 'zlyj/file/upload', '文件上传', '', '', 0, 100, 1519737916, 1519737916),
	(153, 9, 136, 'zlyj/file/del_file', '文件删除', '', '', 0, 100, 1519737935, 1519737935),
	(154, 2, 2, 'manage/company', '单位管理', '', '', 1, 100, 1519820824, 1519820824),
	(155, 20, 0, 'zjzl/index', '桩基质量', '', '', 1, 100, 1521081985, 1521081985),
	(156, 2, 2, 'manage/addUser', '添加用户', '', '', 0, 100, 1521527371, 1521552992),
	(157, 23, 0, 'nmggz/index', '查看农民工资', '', '', 1, 100, 1521560847, 1521619730),
	(158, 3, 66, 'lab/getVideo', '获取实验室视频', '', '', 0, 100, 1521635268, 1521801708),
	(160, 26, 0, 'natural/index', '环境检测', 'natural', '', 1, 100, 1521687325, 1521687325);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;

-- 导出  表 road.permission_role 结构
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.permission_role 的数据：~614 rows (大约)
DELETE FROM `permission_role`;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(18, 1),
	(21, 1),
	(22, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(38, 1),
	(39, 1),
	(40, 1),
	(41, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(48, 1),
	(51, 1),
	(52, 1),
	(57, 1),
	(58, 1),
	(59, 1),
	(60, 1),
	(61, 1),
	(62, 1),
	(63, 1),
	(64, 1),
	(65, 1),
	(66, 1),
	(67, 1),
	(68, 1),
	(69, 1),
	(70, 1),
	(71, 1),
	(72, 1),
	(73, 1),
	(74, 1),
	(75, 1),
	(76, 1),
	(77, 1),
	(78, 1),
	(80, 1),
	(81, 1),
	(82, 1),
	(83, 1),
	(85, 1),
	(90, 1),
	(91, 1),
	(92, 1),
	(93, 1),
	(94, 1),
	(95, 1),
	(96, 1),
	(97, 1),
	(98, 1),
	(99, 1),
	(100, 1),
	(103, 1),
	(104, 1),
	(105, 1),
	(106, 1),
	(107, 1),
	(108, 1),
	(109, 1),
	(110, 1),
	(111, 1),
	(112, 1),
	(113, 1),
	(114, 1),
	(115, 1),
	(116, 1),
	(117, 1),
	(118, 1),
	(119, 1),
	(120, 1),
	(121, 1),
	(122, 1),
	(123, 1),
	(124, 1),
	(125, 1),
	(126, 1),
	(127, 1),
	(128, 1),
	(129, 1),
	(130, 1),
	(131, 1),
	(132, 1),
	(133, 1),
	(134, 1),
	(135, 1),
	(136, 1),
	(137, 1),
	(138, 1),
	(139, 1),
	(140, 1),
	(141, 1),
	(142, 1),
	(143, 1),
	(144, 1),
	(145, 1),
	(146, 1),
	(147, 1),
	(148, 1),
	(149, 1),
	(150, 1),
	(151, 1),
	(152, 1),
	(153, 1),
	(154, 1),
	(155, 1),
	(156, 1),
	(157, 1),
	(158, 1),
	(160, 1),
	(1, 2),
	(2, 2),
	(5, 2),
	(7, 2),
	(8, 2),
	(9, 2),
	(10, 2),
	(11, 2),
	(12, 2),
	(13, 2),
	(21, 2),
	(22, 2),
	(28, 2),
	(29, 2),
	(30, 2),
	(31, 2),
	(32, 2),
	(33, 2),
	(34, 2),
	(35, 2),
	(36, 2),
	(37, 2),
	(38, 2),
	(39, 2),
	(40, 2),
	(41, 2),
	(43, 2),
	(45, 2),
	(48, 2),
	(51, 2),
	(52, 2),
	(57, 2),
	(61, 2),
	(62, 2),
	(63, 2),
	(64, 2),
	(65, 2),
	(66, 2),
	(67, 2),
	(68, 2),
	(69, 2),
	(70, 2),
	(71, 2),
	(72, 2),
	(73, 2),
	(75, 2),
	(76, 2),
	(77, 2),
	(78, 2),
	(80, 2),
	(81, 2),
	(82, 2),
	(83, 2),
	(85, 2),
	(90, 2),
	(91, 2),
	(92, 2),
	(93, 2),
	(94, 2),
	(95, 2),
	(96, 2),
	(97, 2),
	(98, 2),
	(99, 2),
	(100, 2),
	(101, 2),
	(102, 2),
	(103, 2),
	(104, 2),
	(105, 2),
	(106, 2),
	(107, 2),
	(108, 2),
	(109, 2),
	(110, 2),
	(111, 2),
	(112, 2),
	(113, 2),
	(114, 2),
	(115, 2),
	(116, 2),
	(117, 2),
	(118, 2),
	(119, 2),
	(120, 2),
	(121, 2),
	(122, 2),
	(123, 2),
	(124, 2),
	(125, 2),
	(126, 2),
	(127, 2),
	(128, 2),
	(129, 2),
	(130, 2),
	(131, 2),
	(132, 2),
	(133, 2),
	(134, 2),
	(135, 2),
	(136, 2),
	(137, 2),
	(138, 2),
	(139, 2),
	(140, 2),
	(141, 2),
	(142, 2),
	(143, 2),
	(144, 2),
	(145, 2),
	(146, 2),
	(147, 2),
	(148, 2),
	(149, 2),
	(150, 2),
	(151, 2),
	(152, 2),
	(153, 2),
	(154, 2),
	(1, 3),
	(2, 3),
	(5, 3),
	(7, 3),
	(8, 3),
	(9, 3),
	(10, 3),
	(12, 3),
	(13, 3),
	(21, 3),
	(22, 3),
	(28, 3),
	(29, 3),
	(30, 3),
	(31, 3),
	(32, 3),
	(33, 3),
	(34, 3),
	(35, 3),
	(36, 3),
	(37, 3),
	(38, 3),
	(39, 3),
	(40, 3),
	(41, 3),
	(42, 3),
	(43, 3),
	(44, 3),
	(45, 3),
	(48, 3),
	(51, 3),
	(52, 3),
	(57, 3),
	(61, 3),
	(62, 3),
	(63, 3),
	(64, 3),
	(65, 3),
	(66, 3),
	(67, 3),
	(68, 3),
	(69, 3),
	(70, 3),
	(71, 3),
	(72, 3),
	(73, 3),
	(75, 3),
	(76, 3),
	(77, 3),
	(78, 3),
	(80, 3),
	(81, 3),
	(82, 3),
	(83, 3),
	(85, 3),
	(90, 3),
	(91, 3),
	(92, 3),
	(93, 3),
	(94, 3),
	(95, 3),
	(96, 3),
	(97, 3),
	(98, 3),
	(99, 3),
	(100, 3),
	(101, 3),
	(102, 3),
	(103, 3),
	(104, 3),
	(105, 3),
	(106, 3),
	(109, 3),
	(110, 3),
	(111, 3),
	(112, 3),
	(113, 3),
	(114, 3),
	(115, 3),
	(116, 3),
	(117, 3),
	(118, 3),
	(119, 3),
	(120, 3),
	(121, 3),
	(122, 3),
	(123, 3),
	(124, 3),
	(125, 3),
	(126, 3),
	(127, 3),
	(128, 3),
	(129, 3),
	(130, 3),
	(131, 3),
	(133, 3),
	(134, 3),
	(135, 3),
	(136, 3),
	(137, 3),
	(138, 3),
	(139, 3),
	(140, 3),
	(141, 3),
	(142, 3),
	(143, 3),
	(144, 3),
	(145, 3),
	(146, 3),
	(147, 3),
	(148, 3),
	(149, 3),
	(150, 3),
	(151, 3),
	(152, 3),
	(153, 3),
	(157, 3),
	(158, 3),
	(160, 3),
	(1, 4),
	(2, 4),
	(5, 4),
	(8, 4),
	(9, 4),
	(10, 4),
	(11, 4),
	(12, 4),
	(13, 4),
	(21, 4),
	(22, 4),
	(28, 4),
	(29, 4),
	(30, 4),
	(31, 4),
	(32, 4),
	(33, 4),
	(34, 4),
	(35, 4),
	(36, 4),
	(37, 4),
	(38, 4),
	(39, 4),
	(40, 4),
	(41, 4),
	(42, 4),
	(43, 4),
	(44, 4),
	(45, 4),
	(48, 4),
	(51, 4),
	(52, 4),
	(57, 4),
	(61, 4),
	(62, 4),
	(63, 4),
	(64, 4),
	(65, 4),
	(66, 4),
	(67, 4),
	(68, 4),
	(69, 4),
	(70, 4),
	(71, 4),
	(72, 4),
	(73, 4),
	(74, 4),
	(75, 4),
	(76, 4),
	(77, 4),
	(78, 4),
	(80, 4),
	(81, 4),
	(82, 4),
	(83, 4),
	(85, 4),
	(90, 4),
	(91, 4),
	(92, 4),
	(93, 4),
	(94, 4),
	(95, 4),
	(96, 4),
	(97, 4),
	(98, 4),
	(99, 4),
	(100, 4),
	(101, 4),
	(102, 4),
	(103, 4),
	(104, 4),
	(106, 4),
	(108, 4),
	(109, 4),
	(110, 4),
	(111, 4),
	(112, 4),
	(113, 4),
	(114, 4),
	(115, 4),
	(116, 4),
	(117, 4),
	(118, 4),
	(119, 4),
	(120, 4),
	(121, 4),
	(122, 4),
	(123, 4),
	(124, 4),
	(125, 4),
	(126, 4),
	(127, 4),
	(128, 4),
	(129, 4),
	(130, 4),
	(131, 4),
	(132, 4),
	(133, 4),
	(134, 4),
	(135, 4),
	(136, 4),
	(137, 4),
	(138, 4),
	(139, 4),
	(140, 4),
	(141, 4),
	(142, 4),
	(143, 4),
	(144, 4),
	(145, 4),
	(146, 4),
	(147, 4),
	(148, 4),
	(149, 4),
	(150, 4),
	(151, 4),
	(152, 4),
	(153, 4),
	(157, 4),
	(158, 4),
	(160, 4),
	(1, 5),
	(2, 5),
	(5, 5),
	(8, 5),
	(9, 5),
	(10, 5),
	(11, 5),
	(12, 5),
	(13, 5),
	(21, 5),
	(28, 5),
	(29, 5),
	(30, 5),
	(31, 5),
	(32, 5),
	(33, 5),
	(34, 5),
	(35, 5),
	(36, 5),
	(37, 5),
	(38, 5),
	(39, 5),
	(40, 5),
	(41, 5),
	(43, 5),
	(44, 5),
	(45, 5),
	(48, 5),
	(51, 5),
	(52, 5),
	(57, 5),
	(61, 5),
	(62, 5),
	(63, 5),
	(64, 5),
	(65, 5),
	(66, 5),
	(67, 5),
	(68, 5),
	(69, 5),
	(70, 5),
	(71, 5),
	(72, 5),
	(73, 5),
	(74, 5),
	(75, 5),
	(76, 5),
	(77, 5),
	(78, 5),
	(80, 5),
	(81, 5),
	(82, 5),
	(83, 5),
	(85, 5),
	(90, 5),
	(91, 5),
	(92, 5),
	(93, 5),
	(94, 5),
	(95, 5),
	(96, 5),
	(97, 5),
	(98, 5),
	(99, 5),
	(100, 5),
	(101, 5),
	(102, 5),
	(103, 5),
	(104, 5),
	(106, 5),
	(108, 5),
	(109, 5),
	(110, 5),
	(111, 5),
	(112, 5),
	(113, 5),
	(114, 5),
	(115, 5),
	(116, 5),
	(117, 5),
	(118, 5),
	(119, 5),
	(120, 5),
	(121, 5),
	(122, 5),
	(123, 5),
	(124, 5),
	(125, 5),
	(126, 5),
	(127, 5),
	(128, 5),
	(129, 5),
	(130, 5),
	(131, 5),
	(132, 5),
	(133, 5),
	(134, 5),
	(135, 5),
	(136, 5),
	(137, 5),
	(138, 5),
	(139, 5),
	(140, 5),
	(141, 5),
	(142, 5),
	(143, 5),
	(144, 5),
	(145, 5),
	(146, 5),
	(147, 5),
	(148, 5),
	(149, 5),
	(150, 5),
	(151, 5),
	(152, 5),
	(153, 5);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;

-- 导出  表 road.position 结构
CREATE TABLE IF NOT EXISTS `position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- 正在导出表  road.position 的数据：~56 rows (大约)
DELETE FROM `position`;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` (`id`, `role_id`, `name`) VALUES
	(1, 5, '项目经理'),
	(2, 5, '项目常务副经理'),
	(3, 5, '总工'),
	(4, 5, '质量总监'),
	(5, 5, '安全总监'),
	(6, 5, '安质部长'),
	(7, 5, '工程部长'),
	(8, 5, '项目部信息化管理员'),
	(9, 5, '试验室信息化管理员'),
	(10, 5, '试验室主任'),
	(11, 5, '拌合站站长'),
	(12, 5, '拌和站信息化管理员'),
	(13, 5, '操作手'),
	(14, 4, '总监'),
	(15, 4, '驻站监理'),
	(16, 4, '现场监理'),
	(17, 3, '信息员'),
	(18, 3, '董事长'),
	(19, 2, '集团职位'),
	(20, 3, '总经理'),
	(21, 3, '副总经理'),
	(22, 3, '纪检书记'),
	(23, 3, '质量安全部部长'),
	(24, 3, '质量安全部副部长'),
	(26, 3, '工程管理部'),
	(27, 3, '质量安全部'),
	(28, 3, '工程管理部部长'),
	(29, 3, '工程管理部副部长'),
	(30, 2, '专业工程师'),
	(31, 3, '专业工程师'),
	(32, 4, '专业工程师'),
	(33, 5, '专业工程师'),
	(34, 3, '桥梁专业工程师'),
	(35, 3, '路基专业工程师'),
	(36, 3, '隧道专业工程师'),
	(37, 3, '轨道专业工程师'),
	(38, 3, '试验专业工程师'),
	(39, 3, '安全专业工程师'),
	(40, 3, '质量专业工程师'),
	(41, 3, '环水保专业工程师'),
	(42, 4, '桥梁专业工程师'),
	(43, 4, '路基专业工程师'),
	(44, 4, '隧道专业工程师'),
	(45, 4, '轨道专业工程师'),
	(46, 4, '试验专业工程师'),
	(47, 4, '安全专业工程师'),
	(48, 4, '质量专业工程师'),
	(49, 4, '环水保专业工程师'),
	(50, 5, '桥梁专业工程师'),
	(51, 5, '路基专业工程师'),
	(52, 5, '隧道专业工程师'),
	(53, 5, '轨道专业工程师'),
	(54, 5, '试验专业工程师'),
	(55, 5, '安全专业工程师'),
	(56, 5, '质量专业工程师'),
	(57, 5, '环水保专业工程师');
/*!40000 ALTER TABLE `position` ENABLE KEYS */;

-- 导出  表 road.project 结构
CREATE TABLE IF NOT EXISTS `project` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT '项目名称',
  `mileage` varchar(20) NOT NULL,
  `section_num` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标段数量',
  `supervision_num` smallint(3) NOT NULL,
  `summary` varchar(9000) DEFAULT NULL COMMENT '概况',
  `created_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- 正在导出表  road.project 的数据：~1 rows (大约)
DELETE FROM `project`;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` (`id`, `name`, `mileage`, `section_num`, `supervision_num`, `summary`, `created_at`) VALUES
	(4, '西安外环高速公路南段', '176公里', 5, 2, '', 1500476291);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;

-- 导出  表 road.project_section 结构
CREATE TABLE IF NOT EXISTS `project_section` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- 正在导出表  road.project_section 的数据：~1 rows (大约)
DELETE FROM `project_section`;
/*!40000 ALTER TABLE `project_section` DISABLE KEYS */;
INSERT INTO `project_section` (`id`, `project_id`, `name`) VALUES
	(9, 4, '试验检测中心');
/*!40000 ALTER TABLE `project_section` ENABLE KEYS */;

-- 导出  表 road.role 结构
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在导出表  road.role 的数据：~4 rows (大约)
DELETE FROM `role`;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', '超级管理员', '管理所有权限', 1500466551, 1500466551),
	(3, 'xmyh', '项目公司用户', '', 1500470580, 1515232515),
	(4, 'jl', '监理', '', 1500470606, 1500470606),
	(5, 'htd', '合同段', '', 1500470620, 1500470620);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- 导出  表 road.role_user 结构
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.role_user 的数据：~14 rows (大约)
DELETE FROM `role_user`;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
	(18, 1),
	(25, 1),
	(29, 1),
	(20, 3),
	(28, 3),
	(30, 3),
	(31, 3),
	(33, 3),
	(35, 3),
	(37, 3),
	(32, 4),
	(23, 5),
	(24, 5),
	(36, 5);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;

-- 导出  表 road.sdry 结构
CREATE TABLE IF NOT EXISTS `sdry` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) NOT NULL,
  `supervision_id` int(10) NOT NULL,
  `section_id` int(10) NOT NULL,
  `tunnel` varchar(45) NOT NULL COMMENT '隧道名称',
  `dm` varchar(21) DEFAULT NULL COMMENT '左洞右洞',
  `name` varchar(30) DEFAULT NULL COMMENT '人员/车辆名称',
  `bm` varchar(30) DEFAULT NULL COMMENT '部门',
  `zw` varchar(30) DEFAULT NULL COMMENT '职位',
  `gz` varchar(30) DEFAULT NULL COMMENT '工种',
  `wz` varchar(30) DEFAULT NULL COMMENT '位置',
  `jdsj` int(11) unsigned DEFAULT NULL COMMENT '进洞时间',
  `cdsj` int(11) unsigned DEFAULT NULL COMMENT '出洞时间',
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '类型1人员2车辆',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- 正在导出表  road.sdry 的数据：~4 rows (大约)
DELETE FROM `sdry`;
/*!40000 ALTER TABLE `sdry` DISABLE KEYS */;
INSERT INTO `sdry` (`id`, `project_id`, `supervision_id`, `section_id`, `tunnel`, `dm`, `name`, `bm`, `zw`, `gz`, `wz`, `jdsj`, `cdsj`, `type`) VALUES
	(1, 4, 2, 1, '富县隧道', '1', '1', '1', '1', '1', '1', 1511768820, 1511768830, 1),
	(2, 4, 2, 1, '韩城隧道', '1', '1', '1', '1', '1', '1', 1511768820, 1511768830, 1),
	(3, 4, 2, 1, '富县隧道', '1', '1', '1', '1', '1', '1', 1511768820, 1, 2),
	(4, 4, 2, 1, '富县隧道', '1', '1', '1', '1', '1', '1', 1511768820, 1, 1);
/*!40000 ALTER TABLE `sdry` ENABLE KEYS */;

-- 导出  表 road.sdry_total 结构
CREATE TABLE IF NOT EXISTS `sdry_total` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL,
  `supervision_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `tunnel` varchar(45) NOT NULL COMMENT '隧道名称',
  `num` int(10) unsigned NOT NULL COMMENT '数量',
  `date` varchar(21) DEFAULT NULL COMMENT '日期',
  `dm` varchar(30) DEFAULT NULL COMMENT '左洞右洞',
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '类型1人员2车辆',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在导出表  road.sdry_total 的数据：~0 rows (大约)
DELETE FROM `sdry_total`;
/*!40000 ALTER TABLE `sdry_total` DISABLE KEYS */;
INSERT INTO `sdry_total` (`id`, `project_id`, `supervision_id`, `section_id`, `tunnel`, `num`, `date`, `dm`, `type`) VALUES
	(1, 4, 2, 1, '12', 12, '2017-11-27', '1', 1);
/*!40000 ALTER TABLE `sdry_total` ENABLE KEYS */;

-- 导出  表 road.section 结构
CREATE TABLE IF NOT EXISTS `section` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL COMMENT '所属项目',
  `psection_id` smallint(10) unsigned DEFAULT NULL,
  `name` varchar(60) NOT NULL COMMENT '标段名称',
  `begin_position` varchar(30) NOT NULL COMMENT '起始桩号',
  `end_position` varchar(30) NOT NULL COMMENT '终止桩号',
  `cbs_name` varchar(60) NOT NULL COMMENT '承包商名称',
  `bhz_num` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '拌合站数量',
  `lc_num` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '梁场数量',
  `sd_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '隧道数量',
  `fzr` varchar(60) NOT NULL COMMENT '负责人',
  `phone` varchar(15) NOT NULL COMMENT '手机号',
  `created_at` int(11) unsigned NOT NULL COMMENT '登记时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_name` (`project_id`,`name`),
  UNIQUE KEY `psection_id` (`psection_id`) USING BTREE,
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_sec_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- 正在导出表  road.section 的数据：~0 rows (大约)
DELETE FROM `section`;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` (`id`, `project_id`, `psection_id`, `name`, `begin_position`, `end_position`, `cbs_name`, `bhz_num`, `lc_num`, `sd_num`, `fzr`, `phone`, `created_at`) VALUES
	(18, 4, 9, '试验检测中心', '1', '100', '123', 0, 0, 0, '123', '123', 1521554613);
/*!40000 ALTER TABLE `section` ENABLE KEYS */;

-- 导出  表 road.snbhz_info 结构
CREATE TABLE IF NOT EXISTS `snbhz_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL COMMENT '项目id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `device_cat` tinyint(1) unsigned NOT NULL COMMENT '设备分类',
  `device_type` tinyint(1) unsigned DEFAULT NULL COMMENT '设备类型',
  `device_id` int(10) unsigned NOT NULL COMMENT '设备id',
  `scdw` varchar(90) DEFAULT NULL,
  `jldw` varchar(90) DEFAULT NULL,
  `time` int(11) unsigned NOT NULL COMMENT '生产时间',
  `sgdw` varchar(60) DEFAULT NULL COMMENT '施工单位',
  `sgdd` varchar(45) NOT NULL COMMENT '施工地点',
  `jzbw` varchar(100) NOT NULL COMMENT '浇筑部位',
  `pfl` varchar(20) NOT NULL COMMENT '盘方量',
  `pbbh` varchar(20) NOT NULL COMMENT '配比编号',
  `cph` varchar(15) DEFAULT NULL COMMENT '车牌号',
  `driver` varchar(30) NOT NULL COMMENT '司机',
  `operator` varchar(30) NOT NULL COMMENT '操作员',
  `kssj` int(11) DEFAULT NULL,
  `jssj` int(11) DEFAULT NULL,
  `is_warn` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否报警0否1是',
  `warn_info` varchar(90) DEFAULT NULL COMMENT '报警信息',
  `warn_level` tinyint(1) unsigned DEFAULT NULL COMMENT '1初级2中级3高级',
  `is_sec_deal` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '标段是否处理0未处理1已处理',
  `is_sup_deal` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '监理是否处理0未处理1已处理',
  `is_pro_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '是否项目部处理 高级报警需要其处理',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '上传时间',
  `is_24_notice` tinyint(1) unsigned DEFAULT '0' COMMENT '是否24小时通知',
  `is_48_notice` tinyint(1) unsigned DEFAULT '0' COMMENT '是否48小时通知',
  `warn_sx_level` tinyint(1) DEFAULT NULL COMMENT '1初级2中级3高级 因时限导致的报警升级',
  `warn_sx_info` varchar(45) DEFAULT NULL COMMENT '时限升级原因',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `section_id` (`section_id`),
  KEY `device_id` (`device_id`),
  KEY `device_cat` (`device_cat`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_info 的数据：~0 rows (大约)
DELETE FROM `snbhz_info`;
/*!40000 ALTER TABLE `snbhz_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_info` ENABLE KEYS */;

-- 导出  表 road.snbhz_info_deal 结构
CREATE TABLE IF NOT EXISTS `snbhz_info_deal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `snbhz_info_id` int(10) unsigned NOT NULL COMMENT '水泥拌合站信息id',
  `sec_info` varchar(300) DEFAULT '' COMMENT '标段处理信息',
  `sec_img` varchar(100) DEFAULT NULL,
  `sec_file` varchar(100) DEFAULT NULL,
  `sec_name` varchar(60) DEFAULT '' COMMENT '标段处理人',
  `sec_time` int(11) unsigned DEFAULT NULL COMMENT '标段处理时间',
  `sup_info` varchar(300) DEFAULT '' COMMENT '监理处理信息',
  `sup_img` varchar(100) DEFAULT NULL,
  `sup_file` varchar(100) DEFAULT NULL,
  `sup_name` varchar(60) DEFAULT '' COMMENT '监理处理人',
  `sup_time` int(11) unsigned DEFAULT NULL COMMENT '监理处理时间',
  `pro_info` varchar(300) DEFAULT NULL,
  `pro_img` varchar(100) DEFAULT NULL,
  `pro_file` varchar(100) DEFAULT NULL,
  `pro_time` int(11) DEFAULT NULL,
  `pro_name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `snbhz_info_id` (`snbhz_info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_info_deal 的数据：~0 rows (大约)
DELETE FROM `snbhz_info_deal`;
/*!40000 ALTER TABLE `snbhz_info_deal` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_info_deal` ENABLE KEYS */;

-- 导出  表 road.snbhz_info_detail 结构
CREATE TABLE IF NOT EXISTS `snbhz_info_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `snbhz_info_id` int(10) unsigned NOT NULL COMMENT '水泥拌合站信息id',
  `ds` double(6,1) DEFAULT '0.0' COMMENT '大石实际值',
  `ds_pcl` double(6,1) DEFAULT '0.0' COMMENT '大石偏差率',
  `zs` double(6,1) DEFAULT '0.0' COMMENT '中石实际值',
  `zs_pcl` double(6,1) DEFAULT '0.0' COMMENT '中石偏差率',
  `xs` double(6,1) DEFAULT '0.0' COMMENT '小石实际值',
  `xs_pcl` double(6,1) DEFAULT '0.0' COMMENT '小石偏差率',
  `sz` double(6,1) DEFAULT '0.0' COMMENT '砂子实际值',
  `sz_pcl` double(6,1) DEFAULT '0.0' COMMENT '砂子偏差率',
  `sn` double(6,1) DEFAULT '0.0' COMMENT '水泥实际值',
  `sn_pcl` double(6,1) DEFAULT '0.0' COMMENT '水泥偏差率',
  `fmh` double(6,1) DEFAULT '0.0' COMMENT '粉煤灰实际值',
  `fmh_pcl` double(6,1) DEFAULT '0.0' COMMENT '粉煤灰偏差率',
  `s` double(6,1) DEFAULT '0.0' COMMENT '水实际值',
  `s_pcl` double(6,1) DEFAULT '0.0' COMMENT '水偏差率',
  `wjj` double(6,1) DEFAULT '0.0' COMMENT '外加剂实际值',
  `wjj_pcl` double(6,1) DEFAULT '0.0' COMMENT '外加剂偏差率',
  PRIMARY KEY (`id`),
  UNIQUE KEY `snbhz_info_id` (`snbhz_info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_info_detail 的数据：~0 rows (大约)
DELETE FROM `snbhz_info_detail`;
/*!40000 ALTER TABLE `snbhz_info_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_info_detail` ENABLE KEYS */;

-- 导出  表 road.snbhz_info_detail_new 结构
CREATE TABLE IF NOT EXISTS `snbhz_info_detail_new` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `snbhz_info_id` int(10) unsigned NOT NULL,
  `type` tinyint(2) unsigned NOT NULL COMMENT '物料类型',
  `design` double(8,3) NOT NULL COMMENT '设定值',
  `fact` double(8,3) NOT NULL COMMENT '实际值',
  `pcl` double(8,3) NOT NULL COMMENT '偏差率',
  PRIMARY KEY (`id`),
  KEY `snbhz_info_id` (`snbhz_info_id`),
  CONSTRAINT `detail_snbhz_info` FOREIGN KEY (`snbhz_info_id`) REFERENCES `snbhz_info` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_info_detail_new 的数据：~0 rows (大约)
DELETE FROM `snbhz_info_detail_new`;
/*!40000 ALTER TABLE `snbhz_info_detail_new` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_info_detail_new` ENABLE KEYS */;

-- 导出  表 road.snbhz_product_total 结构
CREATE TABLE IF NOT EXISTS `snbhz_product_total` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `num` double unsigned NOT NULL COMMENT '生产重量',
  `date` date NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`),
  KEY `sec_id` (`section_id`),
  KEY `supervision_id` (`supervision_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_product_total 的数据：~0 rows (大约)
DELETE FROM `snbhz_product_total`;
/*!40000 ALTER TABLE `snbhz_product_total` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_product_total` ENABLE KEYS */;

-- 导出  表 road.snbhz_stat_day 结构
CREATE TABLE IF NOT EXISTS `snbhz_stat_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sc_num` int(10) unsigned DEFAULT NULL COMMENT '生产次数',
  `scl` double DEFAULT NULL COMMENT '生产量',
  `bj_num` int(10) unsigned DEFAULT NULL COMMENT '报警次数',
  `cl_num` int(10) DEFAULT NULL,
  `bhgl` double DEFAULT NULL COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `date` varchar(15) DEFAULT NULL COMMENT '统计日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_stat_day 的数据：~0 rows (大约)
DELETE FROM `snbhz_stat_day`;
/*!40000 ALTER TABLE `snbhz_stat_day` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_stat_day` ENABLE KEYS */;

-- 导出  表 road.snbhz_stat_month 结构
CREATE TABLE IF NOT EXISTS `snbhz_stat_month` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sc_num` int(10) unsigned DEFAULT '0' COMMENT '生产次数',
  `scl` double DEFAULT '0' COMMENT '生产量',
  `bj_num` int(10) unsigned DEFAULT '0' COMMENT '报警次数',
  `cl_num` int(10) unsigned DEFAULT '0' COMMENT '处理数',
  `bhgl` double DEFAULT '0' COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `month` varchar(2) DEFAULT NULL COMMENT '月份',
  `created_at` int(11) DEFAULT NULL COMMENT '月份 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_stat_month 的数据：~0 rows (大约)
DELETE FROM `snbhz_stat_month`;
/*!40000 ALTER TABLE `snbhz_stat_month` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_stat_month` ENABLE KEYS */;

-- 导出  表 road.snbhz_stat_week 结构
CREATE TABLE IF NOT EXISTS `snbhz_stat_week` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sc_num` int(10) unsigned DEFAULT '0' COMMENT '生产次数',
  `scl` double DEFAULT '0' COMMENT '生产量',
  `bj_num` int(10) unsigned DEFAULT '0' COMMENT '报警次数',
  `cl_num` int(10) unsigned DEFAULT '0' COMMENT '处理数',
  `bhgl` double DEFAULT '0' COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `week` varchar(2) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_stat_week 的数据：~0 rows (大约)
DELETE FROM `snbhz_stat_week`;
/*!40000 ALTER TABLE `snbhz_stat_week` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_stat_week` ENABLE KEYS */;

-- 导出  表 road.snbhz_user_warn 结构
CREATE TABLE IF NOT EXISTS `snbhz_user_warn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `cj_0` tinyint(1) unsigned DEFAULT NULL,
  `cj_24` tinyint(1) unsigned DEFAULT NULL,
  `cj_48` tinyint(1) unsigned DEFAULT NULL,
  `zj_0` tinyint(1) unsigned DEFAULT NULL,
  `zj_24` tinyint(1) unsigned DEFAULT NULL,
  `zj_48` tinyint(1) unsigned DEFAULT NULL,
  `gj_0` tinyint(1) unsigned DEFAULT NULL,
  `gj_24` tinyint(1) unsigned DEFAULT NULL,
  `gj_48` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_user_warn 的数据：~0 rows (大约)
DELETE FROM `snbhz_user_warn`;
/*!40000 ALTER TABLE `snbhz_user_warn` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_user_warn` ENABLE KEYS */;

-- 导出  表 road.snbhz_warn_info 结构
CREATE TABLE IF NOT EXISTS `snbhz_warn_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned NOT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `device_id` int(10) unsigned NOT NULL COMMENT '设备id',
  `snbhz_info_id` int(10) unsigned NOT NULL,
  `warn_type` varchar(30) NOT NULL,
  `design_value` double(8,2) NOT NULL,
  `fact_value` double(8,2) NOT NULL,
  `design_pcl` double(8,2) NOT NULL,
  `fact_pcl` double(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `snbhz_info_id` (`snbhz_info_id`),
  KEY `sec_id` (`section_id`),
  KEY `dev_id` (`device_id`),
  KEY `supervision_id` (`supervision_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_warn_info 的数据：~0 rows (大约)
DELETE FROM `snbhz_warn_info`;
/*!40000 ALTER TABLE `snbhz_warn_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_warn_info` ENABLE KEYS */;

-- 导出  表 road.snbhz_warn_total 结构
CREATE TABLE IF NOT EXISTS `snbhz_warn_total` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) NOT NULL,
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理id',
  `section_id` int(10) NOT NULL,
  `device_id` int(10) unsigned NOT NULL,
  `num` int(6) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `section_id` (`section_id`),
  KEY `supervision_id` (`supervision_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.snbhz_warn_total 的数据：~0 rows (大约)
DELETE FROM `snbhz_warn_total`;
/*!40000 ALTER TABLE `snbhz_warn_total` DISABLE KEYS */;
/*!40000 ALTER TABLE `snbhz_warn_total` ENABLE KEYS */;

-- 导出  表 road.stat_day 结构
CREATE TABLE IF NOT EXISTS `stat_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `module_id` tinyint(2) DEFAULT NULL,
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sc_num` int(10) unsigned DEFAULT NULL COMMENT '生产次数',
  `cl_num` int(10) unsigned DEFAULT NULL COMMENT '处理数',
  `scl` double DEFAULT NULL COMMENT '生产量',
  `bj_num` int(10) unsigned DEFAULT NULL COMMENT '报警次数',
  `bhgl` double DEFAULT NULL COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `date` varchar(15) DEFAULT NULL COMMENT '统计日期',
  PRIMARY KEY (`id`),
  KEY `pro` (`project_id`),
  KEY `sup` (`supervision_id`),
  KEY `sec` (`section_id`),
  KEY `mod` (`module_id`),
  KEY `dev` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- 正在导出表  road.stat_day 的数据：~7 rows (大约)
DELETE FROM `stat_day`;
/*!40000 ALTER TABLE `stat_day` DISABLE KEYS */;
INSERT INTO `stat_day` (`id`, `project_id`, `supervision_id`, `section_id`, `module_id`, `device_id`, `sc_num`, `cl_num`, `scl`, `bj_num`, `bhgl`, `cll`, `date`) VALUES
	(32, 4, 1, 2, 3, 50, 2, NULL, 0, 0, 0, NULL, '2018-01-19'),
	(33, 4, 1, 2, 3, 49, 2, 0, 0, 0, 0, NULL, '2018-01-23'),
	(35, 4, 1, 2, 3, 47, 1, 0, 0, 0, 0, NULL, '2018-01-24'),
	(36, 4, 1, 2, 3, 48, 1, 0, 0, 0, 0, NULL, '2018-01-24'),
	(40, 4, 1, 2, 3, 50, 1, 0, 0, 1, 100, NULL, '2018-01-26'),
	(49, 4, 1, 2, 3, 49, 2, 0, NULL, 0, 0, NULL, '2018-01-23'),
	(55, 4, 1, 2, 3, 50, 1, 0, NULL, 1, 100, NULL, '2018-02-03');
/*!40000 ALTER TABLE `stat_day` ENABLE KEYS */;

-- 导出  表 road.stat_month 结构
CREATE TABLE IF NOT EXISTS `stat_month` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `module_id` tinyint(2) DEFAULT NULL,
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sc_num` int(10) unsigned DEFAULT '0' COMMENT '生产次数',
  `scl` double DEFAULT '0' COMMENT '生产量',
  `bj_num` int(10) unsigned DEFAULT '0' COMMENT '报警次数',
  `cl_num` int(10) unsigned DEFAULT '0' COMMENT '处理数',
  `bhgl` double DEFAULT '0' COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `month` varchar(2) DEFAULT NULL COMMENT '月份',
  `created_at` int(11) DEFAULT NULL COMMENT '月份 ',
  PRIMARY KEY (`id`),
  KEY `pro` (`project_id`),
  KEY `sup` (`supervision_id`),
  KEY `sec` (`section_id`),
  KEY `dev` (`device_id`),
  KEY `mod` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

-- 正在导出表  road.stat_month 的数据：~12 rows (大约)
DELETE FROM `stat_month`;
/*!40000 ALTER TABLE `stat_month` DISABLE KEYS */;
INSERT INTO `stat_month` (`id`, `project_id`, `supervision_id`, `section_id`, `module_id`, `device_id`, `sc_num`, `scl`, `bj_num`, `cl_num`, `bhgl`, `cll`, `month`, `created_at`) VALUES
	(25, 4, 1, 2, 3, 47, 1, 0, 0, 0, 0, NULL, '01', 1517661608),
	(26, 4, 1, 2, 3, 48, 1, 0, 0, 0, 0, NULL, '01', 1517661608),
	(27, 4, 1, 2, 3, 49, 6, 0, 0, 0, 0, NULL, '01', 1517661608),
	(28, 4, 1, 2, 3, 50, 3, 0, 1, 0, 33.3, 0, '01', 1517661608),
	(37, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '02', 1519835402),
	(38, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '02', 1519835402),
	(39, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '02', 1519835402),
	(40, 4, 1, 2, 3, 50, 1, 0, 1, 0, 100, 0, '02', 1519835402),
	(72, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '03', 1520820890),
	(73, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '03', 1520820890),
	(74, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '03', 1520820890),
	(75, 4, 1, 2, 3, 50, 0, 0, 0, 0, 0, NULL, '03', 1520820890);
/*!40000 ALTER TABLE `stat_month` ENABLE KEYS */;

-- 导出  表 road.stat_week 结构
CREATE TABLE IF NOT EXISTS `stat_week` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(10) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(10) unsigned DEFAULT NULL COMMENT '标段id',
  `module_id` tinyint(2) DEFAULT NULL,
  `device_id` int(10) unsigned DEFAULT NULL COMMENT '设备id',
  `sc_num` int(10) unsigned DEFAULT '0' COMMENT '生产次数',
  `scl` double DEFAULT '0' COMMENT '生产量',
  `bj_num` int(10) unsigned DEFAULT '0' COMMENT '报警次数',
  `cl_num` int(10) unsigned DEFAULT '0' COMMENT '处理数',
  `bhgl` double DEFAULT '0' COMMENT '不合格率%',
  `cll` double unsigned DEFAULT NULL COMMENT '处理率',
  `week` varchar(2) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pro` (`project_id`),
  KEY `sup` (`supervision_id`),
  KEY `sec` (`section_id`),
  KEY `mod` (`module_id`),
  KEY `dev` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;

-- 正在导出表  road.stat_week 的数据：~28 rows (大约)
DELETE FROM `stat_week`;
/*!40000 ALTER TABLE `stat_week` DISABLE KEYS */;
INSERT INTO `stat_week` (`id`, `project_id`, `supervision_id`, `section_id`, `module_id`, `device_id`, `sc_num`, `scl`, `bj_num`, `cl_num`, `bhgl`, `cll`, `week`, `created_at`) VALUES
	(1, 4, 1, 2, 3, 47, 1, 0, 0, 0, 0, NULL, '04', 1517661608),
	(2, 4, 1, 2, 3, 48, 1, 0, 0, 0, 0, NULL, '04', 1517661608),
	(3, 4, 1, 2, 3, 49, 6, 0, 0, 0, 0, NULL, '04', 1517661608),
	(4, 4, 1, 2, 3, 50, 3, 0, 1, 0, 33.3, 0, '04', 1517661608),
	(13, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '05', 1517761800),
	(14, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '05', 1517761800),
	(15, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '05', 1517761800),
	(16, 4, 1, 2, 3, 50, 1, 0, 1, 0, 100, 0, '05', 1517761800),
	(55, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '06', 1518366600),
	(56, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '06', 1518366600),
	(57, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '06', 1518366600),
	(58, 4, 1, 2, 3, 50, 0, 0, 0, 0, 0, NULL, '06', 1518366600),
	(77, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '07', 1518971400),
	(78, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '07', 1518971400),
	(79, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '07', 1518971400),
	(80, 4, 1, 2, 3, 50, 0, 0, 0, 0, 0, NULL, '07', 1518971400),
	(97, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '08', 1519576200),
	(98, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '08', 1519576200),
	(99, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '08', 1519576200),
	(100, 4, 1, 2, 3, 50, 0, 0, 0, 0, 0, NULL, '08', 1519576200),
	(121, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '09', 1520181002),
	(122, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '09', 1520181002),
	(123, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '09', 1520181002),
	(124, 4, 1, 2, 3, 50, 0, 0, 0, 0, 0, NULL, '09', 1520181002),
	(145, 4, 1, 2, 3, 47, 0, 0, 0, 0, 0, NULL, '10', 1520820890),
	(146, 4, 1, 2, 3, 48, 0, 0, 0, 0, 0, NULL, '10', 1520820890),
	(147, 4, 1, 2, 3, 49, 0, 0, 0, 0, 0, NULL, '10', 1520820890),
	(148, 4, 1, 2, 3, 50, 0, 0, 0, 0, 0, NULL, '10', 1520820890);
/*!40000 ALTER TABLE `stat_week` ENABLE KEYS */;

-- 导出  表 road.supervision 结构
CREATE TABLE IF NOT EXISTS `supervision` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `company` varchar(120) NOT NULL,
  `position` varchar(45) NOT NULL,
  `fzr` varchar(60) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_name` (`project_id`,`name`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- 正在导出表  road.supervision 的数据：~0 rows (大约)
DELETE FROM `supervision`;
/*!40000 ALTER TABLE `supervision` DISABLE KEYS */;
INSERT INTO `supervision` (`id`, `project_id`, `name`, `type`, `company`, `position`, `fzr`, `phone`, `created_at`) VALUES
	(6, 4, '西安外环高速公路南段试验检测中心', 1, '陕西交建公路工程试验检测有限公司', '', '徐赟', '123', '1521520138');
/*!40000 ALTER TABLE `supervision` ENABLE KEYS */;

-- 导出  表 road.supervision_section 结构
CREATE TABLE IF NOT EXISTS `supervision_section` (
  `supervision_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `section` (`section_id`) USING BTREE,
  KEY `supervision_id` (`supervision_id`),
  CONSTRAINT `section` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `sup` FOREIGN KEY (`supervision_id`) REFERENCES `supervision` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.supervision_section 的数据：~0 rows (大约)
DELETE FROM `supervision_section`;
/*!40000 ALTER TABLE `supervision_section` DISABLE KEYS */;
INSERT INTO `supervision_section` (`supervision_id`, `section_id`) VALUES
	(6, 18);
/*!40000 ALTER TABLE `supervision_section` ENABLE KEYS */;

-- 导出  表 road.truck 结构
CREATE TABLE IF NOT EXISTS `truck` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) unsigned DEFAULT NULL,
  `section_id` int(10) unsigned NOT NULL COMMENT '所属标段id',
  `group_code` varchar(20) NOT NULL COMMENT '分组编号',
  `truck_num` varchar(20) NOT NULL COMMENT '车牌号',
  `truck_type` varchar(60) NOT NULL COMMENT '车辆类型',
  `unit_name` varchar(60) NOT NULL COMMENT '所属单位',
  `driver_name` varchar(30) NOT NULL COMMENT '司机名称',
  `phone` varchar(15) NOT NULL COMMENT '联系方式',
  `by1_select` varchar(30) DEFAULT NULL COMMENT '模拟1',
  `by2_select` varchar(30) DEFAULT NULL COMMENT '模拟2',
  `by3_select` varchar(30) DEFAULT NULL COMMENT '模拟3',
  `by4_select` varchar(30) DEFAULT NULL COMMENT '模拟4',
  `by5_select` varchar(30) DEFAULT NULL COMMENT '模拟5',
  `factory_code` varchar(30) NOT NULL COMMENT '出厂编号',
  `created_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project` (`project_id`),
  KEY `section` (`section_id`),
  KEY `truck_num` (`truck_num`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在导出表  road.truck 的数据：~2 rows (大约)
DELETE FROM `truck`;
/*!40000 ALTER TABLE `truck` DISABLE KEYS */;
INSERT INTO `truck` (`id`, `project_id`, `section_id`, `group_code`, `truck_num`, `truck_type`, `unit_name`, `driver_name`, `phone`, `by1_select`, `by2_select`, `by3_select`, `by4_select`, `by5_select`, `factory_code`, `created_at`) VALUES
	(1, 4, 3, '1', '1', '1', '1', '1', '1', '', '2', '1', '2', NULL, '1', 1500809060),
	(2, 4, 2, '2', '2', '3', '2', '2', '2', '', '', '', '', NULL, '2', 1500809060);
/*!40000 ALTER TABLE `truck` ENABLE KEYS */;

-- 导出  表 road.truck_category 结构
CREATE TABLE IF NOT EXISTS `truck_category` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- 正在导出表  road.truck_category 的数据：~7 rows (大约)
DELETE FROM `truck_category`;
/*!40000 ALTER TABLE `truck_category` DISABLE KEYS */;
INSERT INTO `truck_category` (`id`, `name`) VALUES
	(2, '压路机'),
	(8, '安全检查车'),
	(3, '摊铺机'),
	(1, '普通车辆'),
	(5, '水泥运输车'),
	(4, '沥青运输车'),
	(7, '监理专用车');
/*!40000 ALTER TABLE `truck_category` ENABLE KEYS */;

-- 导出  表 road.tunnel 结构
CREATE TABLE IF NOT EXISTS `tunnel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `name` varchar(60) NOT NULL COMMENT '隧道名称',
  `left_begin_position` varchar(10) NOT NULL COMMENT '左洞起始桩号',
  `left_end_position` varchar(10) NOT NULL COMMENT '左洞终止桩号',
  `right_begin_position` varchar(10) NOT NULL COMMENT '右洞起始桩号',
  `right_end_position` varchar(10) NOT NULL COMMENT '右洞终止桩号',
  `length` varchar(20) NOT NULL COMMENT '隧道长度',
  `station_num` varchar(10) NOT NULL COMMENT '基站数量',
  `status` varchar(20) NOT NULL COMMENT '状态',
  `created_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projetc_section_name` (`project_id`,`section_id`,`name`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- 正在导出表  road.tunnel 的数据：~3 rows (大约)
DELETE FROM `tunnel`;
/*!40000 ALTER TABLE `tunnel` DISABLE KEYS */;
INSERT INTO `tunnel` (`id`, `project_id`, `section_id`, `name`, `left_begin_position`, `left_end_position`, `right_begin_position`, `right_end_position`, `length`, `station_num`, `status`, `created_at`) VALUES
	(1, 4, 2, '隧道1', '1', '1', '1', '1', '2', '1', '1', 1500556728),
	(2, 4, 2, '隧道2', '1', '1', '1', '1', '1', '1', '1', 1501515770),
	(4, 4, 2, '隧道3', '1', '1', '1', '1', '1', '1', '1', 1501516055);
/*!40000 ALTER TABLE `tunnel` ENABLE KEYS */;

-- 导出  表 road.user 结构
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(5) unsigned NOT NULL COMMENT '项目id',
  `section_id` int(10) unsigned NOT NULL COMMENT '标段id',
  `supervision_id` int(10) unsigned NOT NULL COMMENT '监理信息id',
  `company_id` varchar(60) DEFAULT NULL COMMENT '单位名称',
  `username` varchar(20) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `type` tinyint(1) unsigned DEFAULT NULL,
  `role` tinyint(2) unsigned NOT NULL COMMENT '角色',
  `position` varchar(30) DEFAULT NULL COMMENT '部门',
  `position_id` int(10) unsigned DEFAULT NULL COMMENT '职位id',
  `phone` varchar(15) DEFAULT NULL COMMENT '联系方式',
  `session_id` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `region` tinyint(3) unsigned DEFAULT NULL,
  `city` smallint(3) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `openid` varchar(30) DEFAULT NULL,
  `wx_last_time` int(11) unsigned DEFAULT NULL,
  `has_sh` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否具有审核权限0没有1有',
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `md5_pass` varchar(32) DEFAULT NULL,
  `new_pass` varchar(20) DEFAULT NULL,
  `qqsx_name` varchar(60) DEFAULT NULL COMMENT '前期手续账号',
  `qqsx_pass` varchar(32) DEFAULT NULL COMMENT '前期手续密码',
  `hk_username` varchar(30) DEFAULT NULL,
  `hk_password` varchar(32) DEFAULT NULL,
  `nmgz` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `project` (`project_id`),
  KEY `section` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- 正在导出表  road.user 的数据：~16 rows (大约)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `project_id`, `section_id`, `supervision_id`, `company_id`, `username`, `name`, `password`, `remember_token`, `type`, `role`, `position`, `position_id`, `phone`, `session_id`, `ip`, `region`, `city`, `status`, `openid`, `wx_last_time`, `has_sh`, `created_at`, `updated_at`, `md5_pass`, `new_pass`, `qqsx_name`, `qqsx_pass`, `hk_username`, `hk_password`, `nmgz`) VALUES
	(18, 0, 0, 0, '0', 'jinfeihu', 'jinfeihu', '$2y$10$z.7jbS.IK9YAGnHpGLE8ou41U0tVuA0r43DbubkgIifPZhk.atU.m', 'Wi73E9l1T2ozRejH0kcDx1tx46Qslrr6ZoZL4YrurM0q2opBetn2VlHbei5s', NULL, 1, '集团', 19, '15029209956', NULL, '221.11.61.67', NULL, NULL, 1, NULL, NULL, 1, 1520754121, 0, NULL, NULL, '陕西省发展和改革委员会', '', 'admin', '0192023A7BBD73250516F069DF18B500', NULL),
	(20, 0, 0, 0, '9', 'admin', 'admin', '$2y$10$nyqjqd.umyadUk3M8Gl6jO/2h1YuOyfz6tEXGA4Zp94UPoxRCz2eK', 'mVWkie5VtcQpJ6iEayStlqGv7pEVSSOBxluh6fkVA0SXqdoIDZPlxcaYR6t7', NULL, 3, '项目公司', 18, '17609047785', NULL, '113.143.164.185', NULL, NULL, 1, NULL, NULL, 1, 1520754529, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(23, 0, 2, 1, '9', 'test1234', 'test1234', '$2y$10$3N1v/YDP834htKUNuF0nJOThd9yQ8AUmHk5Mrjmk5IndDul1Zinq.', 'kntBVIyvXc66QXrEtLEbNcP39QOR5334BP6epudwlUp3QuwQG49xao6haMoP', NULL, 5, '标段', 9, '18710884925', NULL, '122.226.183.71', NULL, NULL, 1, NULL, NULL, 1, 1520755120, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(24, 0, 2, 1, '9', 'test2234', 'test2234', '$2y$10$yeTLaSiKopRlpVyUsVlwSOjaViAFboUu5HQTkQE9XQc14oojfIk8a', NULL, NULL, 5, '标段', 10, '3', NULL, '127.0.0.1', NULL, NULL, 0, NULL, NULL, 0, 1520755247, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(25, 0, 0, 0, '1', 'yf', '1', '$2y$10$vZj50lPq3yROeozIw7vqReUC5A2HBOvJyzZJSor3RuyVWkDp8ARJG', 'lvUCXg0BnHatyAo3Nos6jHK9fBMLQfLhad2X3DIw6i1hRl1Yq88DLYSznUns', NULL, 1, '集团', 1, '18710884925', NULL, '112.49.30.88', NULL, NULL, 1, NULL, NULL, 1, 1520755442, 0, NULL, NULL, NULL, NULL, 'admin', '0192023A7BBD73250516F069DF18B500', NULL),
	(26, 0, 0, 0, '0', 'testuser', 'testuser', '$2y$10$ceb2xrMES2vTi1ZC2QAfC..k8hTcDLxnAmnQn2xSmGfZ6hOsb52oa', NULL, NULL, 2, '集团', 19, '6', NULL, '127.0.0.1', NULL, NULL, 1, NULL, NULL, 0, 1520852015, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(28, 0, 0, 0, '10', 'yongli', '李', '$2y$10$iPJ69Y/nOG8gtELyGmzMXeyM7zmGkNFT8n8jKrVq945TRQ.ewyP22', NULL, NULL, 3, '机电', 31, '18691631655', NULL, '222.90.232.86', NULL, NULL, 0, NULL, NULL, 0, 1521531149, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(29, 0, 0, 0, '2', 'test111', '李', '$2y$10$5aBqirVV3MnVVd1XdexhcOJ39ME.vrsGMg4LWsQKgi2aKpiZiXaR2', 'EA0WtA3LYUh6RGvZnsEz0AvMiBx5sUvJ1DXGkLzSbcPFubHcuOlSMQPO8tIy', NULL, 1, 'aaa', 17, '13991957210', NULL, '::1', NULL, NULL, 1, NULL, NULL, 1, 1521532271, 0, NULL, NULL, NULL, NULL, 'zxx123', '2BA18AC6B95D522A8B41250884AAC1D4', 302),
	(30, 0, 0, 0, '10', 'zxx123', '张晓晓', '$2y$10$JrYlAzsaXHBm8VSg36bKN.RdlkliCCCDFyA5gDtXy/eOs1KB1Gc1C', 'iXnG3OomPPitNDlEDNYcnbtaVitbHa4VvzxqkWE5g7964MWneqoO0ZUZGrsZ', NULL, 3, '长安大学', 17, '15529332235', NULL, '221.11.61.205', NULL, NULL, 1, NULL, NULL, 1, 1521533922, 0, NULL, NULL, NULL, NULL, 'zxx123', '2BA18AC6B95D522A8B41250884AAC1D4', NULL),
	(31, 0, 0, 0, '10', 'SuperD12', '梁霖浩', '$2y$10$UaADgwPeC3MujB1q2wMQLOEmkzCd/tZbJFjaxVL9kLTz8YcQoAkvq', 'em1Trmlk4ZyRsvUpHumqbzDZhCM39clnuUBr9KAd0AF8WaFeuLJBMyKuCArv', NULL, 3, '长安大学', 17, '13186186860', NULL, '1.85.35.182', NULL, NULL, 1, NULL, NULL, 1, 1521534266, 0, NULL, NULL, NULL, NULL, 'admin', '518BEDF671377055E944F0BCF5EFC005', NULL),
	(32, 0, 0, 6, '10', 'hfll1234', '皇甫磊磊', '$2y$10$x3JywnW1Fp5x2ag0/VL16e3OvgSUnXdxKKKSylRKYtVUNyzB2ymoS', 'DYDxOmioF14zeb5WXZQS1Gt6r7ACO4sYCw3AYgiuNGINbmpcfWUJeJxRB2YE', NULL, 4, '建设部部长', 15, '13720621170', NULL, '1.85.11.246', NULL, NULL, 1, NULL, NULL, 0, 1521535354, 0, NULL, NULL, NULL, NULL, 'admin', '518BEDF671377055E944F0BCF5EFC005', NULL),
	(33, 0, 0, 0, '10', 'why123', '王海英', '$2y$10$dyXouH8JNEx9ZmKQlC8wm.WVjbOX48mKHdB3D7b16yDxdsIOmtJPy', 'pRcu8Za21seK1oTTd6a9qfnJmwpqTciE2jt0ypHPIdAZlHNN3sD63EggZBbz', NULL, 3, '质量安全部', 23, '13891958502', NULL, '1.85.11.243', NULL, NULL, 1, NULL, NULL, 0, 1521535763, 0, NULL, NULL, NULL, NULL, 'zxx123', '2BA18AC6B95D522A8B41250884AAC1D4', NULL),
	(35, 0, 0, 0, '10', 'lidroid', '李代斌', '$2y$10$eEr5p7GfZ8Emuxr5uJq32eEi54hphAU9qpH1c2Q15w3gvWQsIMfXy', 'V85s77Bsbp8V2FqlO0NyGLwrS1ev43qGMp7hNE6FhamciAIMxVPoEKO3AQCj', NULL, 3, '软件开发部', 20, '13991957210', NULL, '113.139.195.222', NULL, NULL, 1, 'o4nJv0UC2nULE3Ms6UsZcmH2wvvk', NULL, 1, 1521557816, 0, NULL, NULL, NULL, NULL, 'zxx123', '2BA18AC6B95D522A8B41250884AAC1D4', NULL),
	(36, 0, 18, 6, '10', 'wxp552', '王馨萍', '$2y$10$oDdfhxsUswyu1goS4bFCp.jkwoPLPlc1dkPoURZEKHX2Mkly/MNIq', NULL, NULL, 5, '机械学院', 12, '15809228258', NULL, '222.90.232.86', NULL, NULL, 1, NULL, NULL, 0, 1521596484, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(37, 0, 0, 0, '10', 'wc@417', '王超', '$2y$10$7ARKMQ.FwJoecp/eUHXco.pWUhezIfYRCwqfRbzyR3Nof.fhAIx6i', NULL, NULL, 3, '工程科', 26, '18037800906', NULL, '222.90.232.86', NULL, NULL, 1, NULL, NULL, 0, 1521611793, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- 导出  表 road.user_module 结构
CREATE TABLE IF NOT EXISTS `user_module` (
  `user_id` int(10) unsigned NOT NULL,
  `module_id` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`module_id`),
  KEY `module` (`module_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.user_module 的数据：~153 rows (大约)
DELETE FROM `user_module`;
/*!40000 ALTER TABLE `user_module` DISABLE KEYS */;
INSERT INTO `user_module` (`user_id`, `module_id`) VALUES
	(18, 1),
	(20, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(18, 2),
	(20, 2),
	(23, 2),
	(24, 2),
	(25, 2),
	(26, 2),
	(28, 2),
	(29, 2),
	(30, 2),
	(31, 2),
	(32, 2),
	(33, 2),
	(35, 2),
	(36, 2),
	(37, 2),
	(18, 3),
	(20, 3),
	(23, 3),
	(24, 3),
	(25, 3),
	(26, 3),
	(28, 3),
	(29, 3),
	(30, 3),
	(31, 3),
	(32, 3),
	(33, 3),
	(35, 3),
	(36, 3),
	(37, 3),
	(29, 4),
	(35, 4),
	(18, 7),
	(20, 7),
	(25, 7),
	(28, 7),
	(29, 7),
	(30, 7),
	(31, 7),
	(32, 7),
	(33, 7),
	(35, 7),
	(36, 7),
	(37, 7),
	(18, 8),
	(20, 8),
	(25, 8),
	(28, 8),
	(29, 8),
	(30, 8),
	(31, 8),
	(32, 8),
	(33, 8),
	(35, 8),
	(36, 8),
	(37, 8),
	(18, 9),
	(20, 9),
	(23, 9),
	(24, 9),
	(25, 9),
	(28, 9),
	(29, 9),
	(30, 9),
	(31, 9),
	(32, 9),
	(33, 9),
	(35, 9),
	(36, 9),
	(37, 9),
	(18, 15),
	(20, 15),
	(25, 15),
	(28, 15),
	(29, 15),
	(30, 15),
	(31, 15),
	(32, 15),
	(33, 15),
	(35, 15),
	(36, 15),
	(37, 15),
	(18, 16),
	(25, 16),
	(28, 16),
	(29, 16),
	(30, 16),
	(31, 16),
	(32, 16),
	(33, 16),
	(35, 16),
	(36, 16),
	(37, 16),
	(18, 17),
	(25, 17),
	(28, 17),
	(29, 17),
	(30, 17),
	(31, 17),
	(32, 17),
	(33, 17),
	(35, 17),
	(36, 17),
	(37, 17),
	(18, 18),
	(25, 18),
	(28, 18),
	(29, 18),
	(30, 18),
	(31, 18),
	(32, 18),
	(33, 18),
	(35, 18),
	(36, 18),
	(37, 18),
	(18, 21),
	(20, 21),
	(23, 21),
	(24, 21),
	(25, 21),
	(28, 21),
	(29, 21),
	(30, 21),
	(31, 21),
	(32, 21),
	(33, 21),
	(35, 21),
	(36, 21),
	(37, 21),
	(25, 23),
	(29, 23),
	(30, 23),
	(35, 23),
	(36, 23),
	(37, 23),
	(29, 25),
	(35, 25),
	(29, 26);
/*!40000 ALTER TABLE `user_module` ENABLE KEYS */;

-- 导出  表 road.user_project 结构
CREATE TABLE IF NOT EXISTS `user_project` (
  `user_id` int(10) unsigned NOT NULL,
  `project_id` mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`project_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `project_i` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `user_i` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.user_project 的数据：~39 rows (大约)
DELETE FROM `user_project`;
/*!40000 ALTER TABLE `user_project` DISABLE KEYS */;
INSERT INTO `user_project` (`user_id`, `project_id`) VALUES
	(1, 4),
	(5, 4),
	(6, 4),
	(15, 4),
	(20, 4),
	(23, 4),
	(24, 4),
	(25, 4),
	(28, 4),
	(29, 4),
	(30, 4),
	(31, 4),
	(32, 4),
	(33, 4),
	(35, 4),
	(36, 4),
	(37, 4),
	(40, 4),
	(41, 4),
	(42, 4),
	(44, 4),
	(47, 4),
	(49, 4),
	(50, 4),
	(51, 4),
	(56, 4),
	(57, 4),
	(66, 4),
	(67, 4),
	(68, 4),
	(70, 4),
	(71, 4),
	(72, 4),
	(73, 4),
	(74, 4),
	(113, 4),
	(116, 4);
/*!40000 ALTER TABLE `user_project` ENABLE KEYS */;

-- 导出  表 road.warn_deal_info 结构
CREATE TABLE IF NOT EXISTS `warn_deal_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` tinyint(2) unsigned DEFAULT NULL COMMENT '模块id',
  `device_id` smallint(5) unsigned DEFAULT NULL COMMENT '设备id 因为有的模块信息表可能几个',
  `info_id` int(10) unsigned NOT NULL COMMENT '对应模块信息id',
  `sec_info` varchar(300) DEFAULT '' COMMENT '标段处理信息',
  `sec_img` varchar(100) DEFAULT NULL,
  `sec_file` varchar(100) DEFAULT NULL,
  `sec_name` varchar(60) DEFAULT '' COMMENT '标段处理人',
  `sec_time` int(11) unsigned DEFAULT NULL COMMENT '标段处理时间',
  `sup_info` varchar(300) DEFAULT '' COMMENT '监理处理信息',
  `sup_img` varchar(100) DEFAULT NULL,
  `sup_file` varchar(100) DEFAULT NULL,
  `sup_name` varchar(60) DEFAULT '' COMMENT '监理处理人',
  `sup_time` int(11) unsigned DEFAULT NULL COMMENT '监理处理时间',
  `pro_info` varchar(300) DEFAULT NULL COMMENT '项目处理信息',
  `pro_name` varchar(60) DEFAULT NULL COMMENT '项目处理人',
  `pro_img` varchar(100) DEFAULT NULL COMMENT '项目处理图片',
  `pro_file` varchar(100) DEFAULT NULL COMMENT '项目处理文件',
  `pro_time` tinyint(11) unsigned DEFAULT NULL COMMENT '项目处理时间',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `device_id` (`device_id`),
  KEY `info_id` (`info_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- 正在导出表  road.warn_deal_info 的数据：~6 rows (大约)
DELETE FROM `warn_deal_info`;
/*!40000 ALTER TABLE `warn_deal_info` DISABLE KEYS */;
INSERT INTO `warn_deal_info` (`id`, `module_id`, `device_id`, `info_id`, `sec_info`, `sec_img`, `sec_file`, `sec_name`, `sec_time`, `sup_info`, `sup_img`, `sup_file`, `sup_name`, `sup_time`, `pro_info`, `pro_name`, `pro_img`, `pro_file`, `pro_time`) VALUES
	(1, NULL, NULL, 27, 'ui', NULL, NULL, 'yf2234', 1516554747, '', NULL, NULL, '', 1516554747, NULL, NULL, NULL, NULL, NULL),
	(2, NULL, NULL, 28, '', NULL, NULL, '', 1516554747, '', NULL, NULL, '', 1516554747, NULL, NULL, NULL, NULL, NULL),
	(3, NULL, 1, 28, '', NULL, NULL, '', NULL, '1234', 'images/2018-02/5a7319a932c22.png', 'file/2018-02/5a7319ad22317.docx', 'yf2234', 1517492731, NULL, NULL, NULL, NULL, NULL),
	(4, 4, 1, 28, '', NULL, NULL, '', NULL, '12345678', 'images/2018-02/5a7319a932c22.png', 'file/2018-02/5a7319ad22317.docx', 'yf2234', 1517493515, NULL, NULL, NULL, NULL, NULL),
	(5, 4, 1, 35, '1234', NULL, NULL, '1', 1519877494, '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
	(25, 4, 59, 4538, '缓冲量过大，调节缓冲装置', NULL, NULL, '李少峰', 1519613899, '降低缓冲率，调节出料口高度', NULL, NULL, '邢相青', 1519814362, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `warn_deal_info` ENABLE KEYS */;

-- 导出  表 road.warn_user_set 结构
CREATE TABLE IF NOT EXISTS `warn_user_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` smallint(5) DEFAULT NULL,
  `supervision_id` int(10) DEFAULT NULL,
  `section_id` int(10) DEFAULT NULL,
  `module_id` smallint(2) unsigned DEFAULT NULL COMMENT '模块id',
  `user_id` int(10) unsigned DEFAULT NULL,
  `cj_0` tinyint(1) unsigned DEFAULT NULL,
  `cj_24` tinyint(1) unsigned DEFAULT NULL,
  `cj_48` tinyint(1) unsigned DEFAULT NULL,
  `zj_0` tinyint(1) unsigned DEFAULT NULL,
  `zj_24` tinyint(1) unsigned DEFAULT NULL,
  `zj_48` tinyint(1) unsigned DEFAULT NULL,
  `gj_0` tinyint(1) unsigned DEFAULT NULL,
  `gj_24` tinyint(1) unsigned DEFAULT NULL,
  `gj_48` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `pro_id` (`project_id`),
  KEY `sec_id` (`section_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `warn_user_set_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在导出表  road.warn_user_set 的数据：~0 rows (大约)
DELETE FROM `warn_user_set`;
/*!40000 ALTER TABLE `warn_user_set` DISABLE KEYS */;
INSERT INTO `warn_user_set` (`id`, `project_id`, `supervision_id`, `section_id`, `module_id`, `user_id`, `cj_0`, `cj_24`, `cj_48`, `zj_0`, `zj_24`, `zj_48`, `gj_0`, `gj_24`, `gj_48`) VALUES
	(1, 4, 6, 18, 4, 35, 1, 1, 1, 1, 1, 1, 1, 1, 1);
/*!40000 ALTER TABLE `warn_user_set` ENABLE KEYS */;

-- 导出  表 road.yajiang_info 结构
CREATE TABLE IF NOT EXISTS `yajiang_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(11) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '标段id',
  `device_cat` smallint(2) unsigned DEFAULT NULL COMMENT '设备分类id',
  `device_id` int(11) unsigned DEFAULT NULL COMMENT '设备id',
  `yzlc` varchar(60) DEFAULT NULL COMMENT '预制梁场',
  `sgdw` varchar(100) DEFAULT NULL COMMENT '施工单位',
  `jldw` varchar(100) DEFAULT NULL COMMENT '监理单位',
  `yjlh` varchar(100) DEFAULT NULL COMMENT '压浆梁号',
  `lblx` varchar(60) DEFAULT NULL COMMENT '梁板类型',
  `kwh` varchar(30) DEFAULT NULL COMMENT '孔位号',
  `kds` varchar(30) DEFAULT NULL COMMENT '孔道数',
  `time` int(11) unsigned DEFAULT NULL COMMENT '压浆日期',
  `kssj` int(11) unsigned DEFAULT NULL COMMENT '开始时间',
  `jssj` int(11) unsigned DEFAULT NULL COMMENT '结束时间',
  `bysj` int(11) unsigned DEFAULT NULL COMMENT '保压时间',
  `jbsj` int(11) unsigned DEFAULT NULL COMMENT '搅拌时间',
  `yjms` varchar(60) DEFAULT NULL COMMENT '压浆模式',
  `yjfx` varchar(30) DEFAULT NULL COMMENT '压浆放向',
  `czry` varchar(30) DEFAULT NULL COMMENT '操作人员',
  `is_warn` tinyint(1) unsigned DEFAULT NULL COMMENT '是否报警0否1是',
  `warn_info` varchar(60) DEFAULT NULL COMMENT '报警信息',
  `warn_level` tinyint(1) unsigned DEFAULT NULL COMMENT '1初级2中级3高级',
  `is_sec_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '标段是否处理0未处理1已处理',
  `is_sup_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '监理是否处理0未处理1已处理',
  `is_pro_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '建设单位是否处理0未处理1已处理',
  `is_notice_24` tinyint(1) unsigned DEFAULT NULL COMMENT '是否24小时通知',
  `is_notice_48` tinyint(1) unsigned DEFAULT NULL COMMENT '是否48小时通知',
  `warn_sx_level` tinyint(1) DEFAULT NULL COMMENT '1初级2中级3高级 因时限导致的报警升级',
  `warn_sx_info` varchar(45) DEFAULT NULL COMMENT '时限升级原因',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pro_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `sec_id` (`section_id`),
  KEY `dev_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.yajiang_info 的数据：~0 rows (大约)
DELETE FROM `yajiang_info`;
/*!40000 ALTER TABLE `yajiang_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `yajiang_info` ENABLE KEYS */;

-- 导出  表 road.yajiang_info_detail 结构
CREATE TABLE IF NOT EXISTS `yajiang_info_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `yajiang_info_id` int(11) unsigned DEFAULT NULL,
  `jjyl_design` varchar(30) DEFAULT NULL COMMENT '金浆压力',
  `jjyl_fact` varchar(30) DEFAULT NULL,
  `fjyl_design` varchar(30) DEFAULT NULL COMMENT '返浆压力',
  `fjyl_fact` varchar(30) DEFAULT NULL,
  `zzl_design` varchar(30) DEFAULT NULL COMMENT '总重量',
  `zzl_fact` varchar(30) DEFAULT NULL,
  `snzl_design` varchar(30) DEFAULT NULL COMMENT '水泥重量',
  `snzl_fact` varchar(30) DEFAULT NULL,
  `yjj_design` varchar(30) DEFAULT NULL COMMENT '压浆剂',
  `yjj_fact` varchar(30) DEFAULT NULL,
  `shui_design` varchar(30) DEFAULT NULL COMMENT '水',
  `shui_fact` varchar(30) DEFAULT NULL,
  `sjb_design` varchar(30) DEFAULT NULL COMMENT '水胶比',
  `sjb_fact` varchar(30) DEFAULT NULL,
  `phb_design` varchar(30) DEFAULT NULL COMMENT '配合比',
  `phb_fact` varchar(30) DEFAULT NULL,
  `ldd_design` varchar(30) DEFAULT NULL COMMENT '流动度',
  `ldd_fact` varchar(30) DEFAULT NULL,
  `hjwd_design` varchar(30) DEFAULT NULL COMMENT '环境温度',
  `hjwd_fact` varchar(30) DEFAULT NULL,
  `jywd_design` varchar(30) DEFAULT NULL COMMENT '浆液温度',
  `jywd_fact` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `yajing_info_id` (`yajiang_info_id`),
  CONSTRAINT `yajiang` FOREIGN KEY (`yajiang_info_id`) REFERENCES `yajiang_info` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.yajiang_info_detail 的数据：~0 rows (大约)
DELETE FROM `yajiang_info_detail`;
/*!40000 ALTER TABLE `yajiang_info_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `yajiang_info_detail` ENABLE KEYS */;

-- 导出  表 road.ycjc 结构
CREATE TABLE IF NOT EXISTS `ycjc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `supervision_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL COMMENT 'pm2.5',
  `pm25` varchar(255) DEFAULT NULL COMMENT 'pm2.5',
  `pm10` varchar(255) DEFAULT NULL COMMENT 'pm10',
  `wd` varchar(255) DEFAULT NULL COMMENT '温度',
  `sd` varchar(255) DEFAULT NULL COMMENT '湿度',
  `fs` varchar(255) DEFAULT NULL COMMENT '风速',
  `fx` varchar(255) DEFAULT NULL COMMENT '风向',
  `zs` varchar(255) DEFAULT NULL COMMENT '噪声',
  `warn` varchar(60) DEFAULT NULL COMMENT '报警信息',
  `time` int(11) unsigned DEFAULT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `sec_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.ycjc 的数据：~0 rows (大约)
DELETE FROM `ycjc`;
/*!40000 ALTER TABLE `ycjc` DISABLE KEYS */;
/*!40000 ALTER TABLE `ycjc` ENABLE KEYS */;

-- 导出  表 road.ycjc_total 结构
CREATE TABLE IF NOT EXISTS `ycjc_total` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `supervision_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL COMMENT 'pm2.5',
  `num` varchar(255) DEFAULT NULL COMMENT 'pm2.5',
  `date` varchar(255) DEFAULT NULL COMMENT 'pm10',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `sec_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.ycjc_total 的数据：~0 rows (大约)
DELETE FROM `ycjc_total`;
/*!40000 ALTER TABLE `ycjc_total` DISABLE KEYS */;
/*!40000 ALTER TABLE `ycjc_total` ENABLE KEYS */;

-- 导出  表 road.zhangla_info 结构
CREATE TABLE IF NOT EXISTS `zhangla_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(11) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '标段id',
  `device_cat` smallint(2) unsigned DEFAULT NULL COMMENT '设备分类id',
  `device_id` int(11) unsigned DEFAULT NULL COMMENT '设备id',
  `yzlc` varchar(60) DEFAULT NULL COMMENT '预制梁场',
  `zldw` varchar(100) DEFAULT NULL COMMENT '张拉单位',
  `jldw` varchar(100) DEFAULT NULL COMMENT '监理单位',
  `gjlx` varchar(100) DEFAULT NULL COMMENT '构件类型',
  `jksbbh` varchar(60) DEFAULT NULL COMMENT '监控设备编号',
  `time` int(11) unsigned DEFAULT NULL COMMENT '张拉时间',
  `tsjz` varchar(30) DEFAULT NULL COMMENT '砼设计强度（MPa）',
  `tqd` varchar(30) DEFAULT NULL COMMENT '砼强度',
  `zllh` varchar(60) DEFAULT NULL COMMENT '张拉梁号',
  `zlgy` varchar(60) DEFAULT NULL COMMENT '张拉工艺',
  `zlsx` varchar(60) DEFAULT NULL COMMENT '张拉顺序',
  `gsbh` varchar(60) DEFAULT NULL COMMENT '钢束编号',
  `kwh` varchar(60) DEFAULT NULL COMMENT '孔位号',
  `czry` varchar(30) DEFAULT NULL COMMENT '操作人员',
  `status` tinyint(1) unsigned DEFAULT NULL,
  `is_warn` tinyint(1) unsigned DEFAULT NULL COMMENT '是否报警0否1是',
  `warn_info` varchar(60) DEFAULT NULL COMMENT '报警信息',
  `warn_level` tinyint(1) unsigned DEFAULT NULL COMMENT '1初级2中级3高级',
  `is_sec_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '标段是否处理0未处理1已处理',
  `is_sup_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '监理是否处理0未处理1已处理',
  `is_pro_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '建设单位是否处理0未处理1已处理',
  `is_notice_24` tinyint(1) unsigned DEFAULT NULL COMMENT '是否24小时通知',
  `is_notice_48` tinyint(1) unsigned DEFAULT NULL COMMENT '是否48小时通知',
  `warn_sx_level` tinyint(1) DEFAULT NULL COMMENT '1初级2中级3高级 因时限导致的报警升级',
  `warn_sx_info` varchar(45) DEFAULT NULL COMMENT '时限升级原因',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pro_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `sec_id` (`section_id`),
  KEY `dev_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.zhangla_info 的数据：~0 rows (大约)
DELETE FROM `zhangla_info`;
/*!40000 ALTER TABLE `zhangla_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `zhangla_info` ENABLE KEYS */;

-- 导出  表 road.zhangla_info_detail 结构
CREATE TABLE IF NOT EXISTS `zhangla_info_detail` (
  `id` int(11) NOT NULL,
  `zhangla_info_id` int(11) unsigned DEFAULT NULL COMMENT '项目id',
  `csxcl1_design` varchar(255) DEFAULT NULL COMMENT '初始行程力1',
  `csxcl1_fact` varchar(30) DEFAULT NULL COMMENT '初始行程力1',
  `csxcsc1_design` varchar(30) DEFAULT NULL COMMENT '初始行程伸长量1',
  `csxcsc1_fact` varchar(255) DEFAULT NULL,
  `csxcl2_design` varchar(30) DEFAULT NULL COMMENT '初始行程力2',
  `csxcl2_fact` varchar(255) DEFAULT NULL,
  `csxcsc2_design` varchar(30) DEFAULT NULL COMMENT '初始行程伸长量2',
  `csxcsc2_fact` varchar(255) DEFAULT NULL,
  `dyxcl1_design` varchar(30) DEFAULT NULL COMMENT '第一行程力1',
  `dyxcl1_fact` varchar(255) DEFAULT NULL,
  `dyxcsc1_design` varchar(30) DEFAULT NULL COMMENT '第一行程伸长量1',
  `dyxcsc1_fact` varchar(255) DEFAULT NULL,
  `dyxcl2_design` varchar(30) DEFAULT NULL COMMENT '第一行程力2',
  `dyxcl2_fact` varchar(255) DEFAULT NULL,
  `dyxcsc2_design` varchar(30) DEFAULT NULL COMMENT '第一行程伸长量2',
  `dyxcsc2_fact` varchar(255) DEFAULT NULL,
  `dexcl1_design` varchar(30) DEFAULT NULL COMMENT '第二行程力1',
  `dexcl1_fact` varchar(255) DEFAULT NULL,
  `dexcsc1_design` varchar(30) DEFAULT NULL COMMENT '第二行程伸长量1',
  `dexcsc1_fact` varchar(255) DEFAULT NULL,
  `dexcl2_design` varchar(30) DEFAULT NULL COMMENT '第二行程力2',
  `dexcl2_fact` varchar(255) DEFAULT NULL,
  `dexcsc2_design` varchar(30) DEFAULT NULL COMMENT '第二行程伸长量2',
  `dexcsc2_fact` varchar(255) DEFAULT NULL,
  `dsxcl1_design` varchar(30) DEFAULT NULL COMMENT '第三行程力1',
  `dsxcl1_fact` varchar(255) DEFAULT NULL,
  `dsxcsc1_design` varchar(30) DEFAULT NULL COMMENT '第三行程伸长量1',
  `dsxcsc1_fact` varchar(255) DEFAULT NULL,
  `dsxcl2_design` varchar(30) DEFAULT NULL COMMENT '第三行程力2',
  `dsxcl2_fact` varchar(255) DEFAULT NULL,
  `dsxcsc2_design` varchar(30) DEFAULT NULL COMMENT '第三行程伸长量2',
  `dsxcsc2_fact` varchar(255) DEFAULT NULL,
  `dsixcl1_design` varchar(30) DEFAULT NULL COMMENT '第四行程力1',
  `dsixcl1_fact` varchar(255) DEFAULT NULL,
  `dsixcsc1_design` varchar(30) DEFAULT NULL COMMENT '第四行程伸长量1',
  `dsixcsc1_fact` varchar(255) DEFAULT NULL,
  `dsixcl2_design` varchar(30) DEFAULT NULL COMMENT '第四行程力2',
  `dsixcl2_fact` varchar(255) DEFAULT NULL,
  `dsixcsc2_design` varchar(30) DEFAULT NULL COMMENT '第四行程伸长量2',
  `dsixcsc2_fact` varchar(255) DEFAULT NULL,
  `hsz1_design` varchar(30) DEFAULT NULL COMMENT '回缩值1',
  `hsz1_fact` varchar(255) DEFAULT NULL,
  `hsz2_design` varchar(30) DEFAULT NULL COMMENT '回缩值2',
  `hsz2_fact` varchar(255) DEFAULT NULL,
  `sjzll` varchar(30) DEFAULT NULL COMMENT '设计张拉力',
  `zlbl` varchar(30) DEFAULT NULL COMMENT '张拉比例',
  `shejscl` varchar(30) DEFAULT NULL COMMENT '设计伸长量',
  `shijscl` varchar(30) DEFAULT NULL COMMENT '实际伸长量',
  `yslwc` varchar(30) DEFAULT NULL COMMENT '延伸率误差',
  PRIMARY KEY (`id`),
  KEY `zhangla_info_id` (`zhangla_info_id`),
  CONSTRAINT `zhangla` FOREIGN KEY (`zhangla_info_id`) REFERENCES `zhangla_info` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.zhangla_info_detail 的数据：~0 rows (大约)
DELETE FROM `zhangla_info_detail`;
/*!40000 ALTER TABLE `zhangla_info_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `zhangla_info_detail` ENABLE KEYS */;

-- 导出  表 road.zjzl_info 结构
CREATE TABLE IF NOT EXISTS `zjzl_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(11) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '标段id',
  `device_cat` smallint(2) unsigned DEFAULT NULL COMMENT '设备分类id',
  `device_id` int(11) unsigned DEFAULT NULL COMMENT '设备id',
  `sgdw` varchar(100) DEFAULT NULL COMMENT '施工单位',
  `jldw` varchar(100) DEFAULT NULL COMMENT '监理单位',
  `bdbm` varchar(100) DEFAULT NULL COMMENT '标段编码',
  `clxzjclbh` varchar(60) DEFAULT NULL COMMENT '长螺旋钻机车辆编号',
  `tshntbcbh` varchar(60) DEFAULT NULL COMMENT '拖式混凝土泵车车辆编号',
  `clxzjsjxm` varchar(30) DEFAULT NULL COMMENT '长螺旋钻机司机姓名',
  `tshntbcczy` varchar(30) DEFAULT NULL COMMENT '拖式混凝土泵车操作员姓名',
  `zxh` varchar(60) DEFAULT NULL COMMENT '桩序号',
  `azb1_fact` varchar(30) DEFAULT NULL COMMENT 'A坐标(钻机经度坐标) （m）',
  `bzb1_fact` varchar(30) DEFAULT NULL COMMENT 'B坐标（钻机纬度坐标）(m)',
  `czsd_design` varchar(30) DEFAULT NULL COMMENT '沉桩深度(m)',
  `czsd_fact` varchar(30) DEFAULT NULL,
  `wgqj_design` varchar(20) DEFAULT NULL COMMENT '桅杆倾角',
  `wgqj_fact` varchar(20) DEFAULT NULL,
  `zjptspcd_design` varchar(30) DEFAULT NULL COMMENT '桩机平台水平程度',
  `zjptspcd_fact` varchar(30) DEFAULT NULL,
  `clcdl_design` varchar(30) DEFAULT NULL COMMENT '持力层电流(A)',
  `clcdl_fact` varchar(30) DEFAULT NULL,
  `bspl_fact` varchar(30) DEFAULT NULL COMMENT '泵送排量',
  `dgzbscs_design` varchar(30) DEFAULT NULL COMMENT '单根桩泵送次数',
  `dgzbscs_fact` varchar(30) DEFAULT NULL,
  `dghntgrl_design` varchar(30) DEFAULT NULL COMMENT '单根混凝土灌入量',
  `dghntgrl_fact` varchar(30) DEFAULT NULL,
  `ljhntbrl_fact` varchar(30) DEFAULT NULL COMMENT '累计混凝土泵入量',
  `bcljyxsj_fact` varchar(30) DEFAULT NULL COMMENT '泵车累积运行时间',
  `azb2_fact` varchar(30) DEFAULT NULL COMMENT 'A坐标(泵车经度坐标)',
  `bzb2_fact` varchar(30) DEFAULT NULL COMMENT 'B坐标(泵车纬度坐标)',
  `time` int(11) DEFAULT NULL COMMENT '开始时间',
  `cksj` int(11) DEFAULT NULL COMMENT '成孔时间',
  `czsj` int(11) DEFAULT NULL COMMENT '成桩时间',
  `zsc_design` varchar(30) DEFAULT NULL COMMENT '总时长(min)',
  `zsc_fact` varchar(30) DEFAULT NULL,
  `zksd_design` varchar(30) DEFAULT NULL COMMENT '钻孔速度(m/min)',
  `zksd_fact` varchar(30) DEFAULT NULL,
  `bgsd_design` varchar(30) DEFAULT NULL COMMENT '拔管速度(m/min)',
  `bgsd_fact` varchar(30) DEFAULT NULL,
  `sgxcfs_fact` varchar(30) DEFAULT NULL COMMENT '施工现场风速(m/s)',
  `is_warn` tinyint(1) unsigned DEFAULT NULL COMMENT '是否报警0否1是',
  `warn_info` varchar(60) DEFAULT NULL COMMENT '报警信息',
  `warn_level` tinyint(1) unsigned DEFAULT NULL COMMENT '1初级2中级3高级',
  `is_sec_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '标段是否处理0未处理1已处理',
  `is_sup_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '监理是否处理0未处理1已处理',
  `is_pro_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '建设单位是否处理0未处理1已处理',
  `is_notice_24` tinyint(1) unsigned DEFAULT NULL COMMENT '是否24小时通知',
  `is_notice_48` tinyint(1) unsigned DEFAULT NULL COMMENT '是否48小时通知',
  `warn_sx_level` tinyint(1) DEFAULT NULL COMMENT '1初级2中级3高级 因时限导致的报警升级',
  `warn_sx_info` varchar(45) DEFAULT NULL COMMENT '时限升级原因',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pro_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `sec_id` (`section_id`),
  KEY `dev_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在导出表  road.zjzl_info 的数据：~2 rows (大约)
DELETE FROM `zjzl_info`;
/*!40000 ALTER TABLE `zjzl_info` DISABLE KEYS */;
INSERT INTO `zjzl_info` (`id`, `project_id`, `supervision_id`, `section_id`, `device_cat`, `device_id`, `sgdw`, `jldw`, `bdbm`, `clxzjclbh`, `tshntbcbh`, `clxzjsjxm`, `tshntbcczy`, `zxh`, `azb1_fact`, `bzb1_fact`, `czsd_design`, `czsd_fact`, `wgqj_design`, `wgqj_fact`, `zjptspcd_design`, `zjptspcd_fact`, `clcdl_design`, `clcdl_fact`, `bspl_fact`, `dgzbscs_design`, `dgzbscs_fact`, `dghntgrl_design`, `dghntgrl_fact`, `ljhntbrl_fact`, `bcljyxsj_fact`, `azb2_fact`, `bzb2_fact`, `time`, `cksj`, `czsj`, `zsc_design`, `zsc_fact`, `zksd_design`, `zksd_fact`, `bgsd_design`, `bgsd_fact`, `sgxcfs_fact`, `is_warn`, `warn_info`, `warn_level`, `is_sec_deal`, `is_sup_deal`, `is_pro_deal`, `is_notice_24`, `is_notice_48`, `warn_sx_level`, `warn_sx_info`, `created_at`) VALUES
	(1, 4, 2, 1, 5, 67, '施工单位', '监理单位', 'XH-TJ01', '1', '1', '1', '1', '01', '108.72', '34.36', '15', '16', '1', '1', '', NULL, '20', '20', '5', '5', '5', '5', '5', '6', '6', '66', '66', 1520924720, 1, 1, '1', '1', '1', '1', '1', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 4, 2, 1, 5, 67, '施工单位', '监理单位', 'XH-TJ01', '1', '1', '1', '1', '02', '108.72', '34.32', '16', '10', '1', '1', NULL, NULL, '20', '19', '5', '5', '5', '5', '5', '6', '6', '66', '66', 1520924720, 1, 1, '1', '1', '1', '1', '1', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `zjzl_info` ENABLE KEYS */;

-- 导出  表 road.zlyj_info 结构
CREATE TABLE IF NOT EXISTS `zlyj_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned DEFAULT NULL COMMENT '项目id',
  `supervision_id` int(11) unsigned DEFAULT NULL COMMENT '监理id',
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '标段id',
  `device_cat` smallint(2) unsigned DEFAULT NULL COMMENT '设备分类id',
  `device_id` int(11) unsigned DEFAULT NULL COMMENT '设备id',
  `yzlc` varchar(60) DEFAULT NULL COMMENT '预制梁场',
  `zldw` varchar(100) DEFAULT NULL COMMENT '张拉单位',
  `jldw` varchar(100) DEFAULT NULL COMMENT '监理单位',
  `gjlx` varchar(100) DEFAULT NULL COMMENT '构件类型',
  `jksbbh` varchar(60) DEFAULT NULL COMMENT '监控设备编号',
  `time` int(11) unsigned DEFAULT NULL COMMENT '张拉时间',
  `tsjz` varchar(30) DEFAULT NULL COMMENT '砼设计强度（MPa）',
  `tqd` varchar(30) DEFAULT NULL COMMENT '砼强度',
  `zllh` varchar(60) DEFAULT NULL COMMENT '张拉梁号',
  `zlgy` varchar(60) DEFAULT NULL COMMENT '张拉工艺',
  `zlsx` varchar(60) DEFAULT NULL COMMENT '张拉顺序',
  `gsbh` varchar(60) DEFAULT NULL COMMENT '钢束编号',
  `kwh` varchar(60) DEFAULT NULL COMMENT '孔位号',
  `czry` varchar(30) DEFAULT NULL COMMENT '操作人员',
  `status` tinyint(1) unsigned DEFAULT NULL,
  `is_warn` tinyint(1) unsigned DEFAULT NULL COMMENT '是否报警0否1是',
  `warn_info` varchar(60) DEFAULT NULL COMMENT '报警信息',
  `warn_level` tinyint(1) unsigned DEFAULT NULL COMMENT '1初级2中级3高级',
  `is_sec_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '标段是否处理0未处理1已处理',
  `is_sup_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '监理是否处理0未处理1已处理',
  `is_pro_deal` tinyint(1) unsigned DEFAULT NULL COMMENT '建设单位是否处理0未处理1已处理',
  `is_notice_24` tinyint(1) unsigned DEFAULT NULL COMMENT '是否24小时通知',
  `is_notice_48` tinyint(1) unsigned DEFAULT NULL COMMENT '是否48小时通知',
  `warn_sx_level` tinyint(1) DEFAULT NULL COMMENT '1初级2中级3高级 因时限导致的报警升级',
  `warn_sx_info` varchar(45) DEFAULT NULL COMMENT '时限升级原因',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pro_id` (`project_id`),
  KEY `sup_id` (`supervision_id`),
  KEY `sec_id` (`section_id`),
  KEY `dev_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在导出表  road.zlyj_info 的数据：~0 rows (大约)
DELETE FROM `zlyj_info`;
/*!40000 ALTER TABLE `zlyj_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `zlyj_info` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
