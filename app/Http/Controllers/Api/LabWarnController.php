<?php
namespace App\Http\Controllers\Api;


/**
*$is_warn_para1  有效强度是否合格   砂浆抗压   在钢筋实验表示 下屈服强度
*$is_warn_para2  加荷速率是否合格                             抗拉强度
*$is_warn_para3
*$is_warn  整体是否合格
*
**/
class LabWarnController {
    public function getWarnInfo($data, $lab_detail){
    	$result = [];
    	switch ($data['T6']) {
    		case '砂浆抗压':
    			$result = $this->sjky($data['T10'], $data['D2'], $data['D4'], $lab_detail, $data['D3']);
    			break;
            case '混凝土抗压':
                $result = $this->hntky($data['T10'], $data['D2'], $data['D4'], $lab_detail, $data['T13'], $data['D3']);
                break;
            case '混凝土抗折':
                $result = $this->hntkz($data['T10'], $data['D2'], $data['D15'], $lab_detail);
                break;
            case '水泥胶砂抗压':
                $result = $this->snjsKyOrKz(2, $data['T8'], $data['T10'], $data['T9'], $data['D2'], $data['D4'], $data['T13'], $lab_detail);
                break;
            case '水泥胶砂抗折':
                $result = $this->snjsKyOrKz(1, $data['T8'], $data['T10'], $data['T9'],$data['D2'], $data['D4'], $data['T13'], $lab_detail);
    		    break;
            case '预应力筋':
                $result = $this->gjsy(2, $data['T8'], $data['T12'], $data['T13'], $lab_detail);
                break;
            default:
                //钢筋焊接接头试验、热轧光圆钢筋拉伸
                $result = $this->gjsy(1, $data['T8'], $data['T12'], $data['T13'], $lab_detail);
    			break;
    	}

    	return $result;
    }

    /*砂浆抗压*/
    protected function sjky($qddj, $jzsl, $yxqd, $detail, $yxlz){
    	$data['is_warn_para1'] = $data['is_warn_para2'] = 1;
    	//判断加载速率是否合格
    	if($jzsl > 0.5 && $jzsl < 1.5 ){
    		$data['is_warn_para2'] = 0;
    	}
    	//判断有效强度是否合格  M7.0, M10
        //字母后面的数字即为最小强度
    	$qddj = substr($qddj, 1);
    	if(!$yxqd){
    		//计算有效强度 三组数据
    		$yxqd = $this->getYxqdByThree($detail);
    		$data['yxqd'] = $yxqd;
    	}
    	if($yxqd >= $qddj){
    		$data['is_warn_para1'] = 0;
    	}
    	if($data['is_warn_para1'] || $data['is_warn_para2']){
    		$data['is_warn'] = 1;
    	}

        if($yxlz){
            $data['yxlz'] = round($yxlz/1000, 1);
        }
    	return $data;
    }

    /*混凝土抗压*/
    protected function hntky($qddj, $jzsl, $yxqd, $detail, $sjgg, $yxlz){
        $data['is_warn_para1'] = $data['is_warn_para2'] = 1;
        //判断有效强度是否合格  C15,C20,C25,C30,C40,C50,C55
        //强度标号中的数字为抗压强度标准值
        $qddj = substr($qddj, 1);
        if(!$yxqd){
            //计算有效强度 三组数据
            $yxqd = $this->getYxqdByThree($detail);
            $data['yxqd'] = $yxqd;
        }
        if($yxqd >= $qddj){
            $data['is_warn_para1'] = 0;
        }

        //判断加载速率是否合格
        $is_hg = $this->judgeHntJzsl($jzsl, $sjgg, $qddj);
        if($is_hg){
            $data['is_warn_para2'] = 0;
        }
        
        if($data['is_warn_para1'] || $data['is_warn_para2']){
            $data['is_warn'] = 1;
        }

        if($yxlz){
            $data['yxlz'] = round($yxlz/1000, 1);
        }
        return $data;
    }

    /**
    *混凝土抗折
    *C15=3.0MPa、C20= 3.5MPa、 C25=4.0MPa、C30=4.5MPa、C35=5.0MPa、
    *C40=5.5MPa、C45=6.0MPa、C50=6.5MPa、C55=7.0MPa
    **/
    protected function hntkz($qddj, $jzsl, $jxqd, $detail){
        $data['is_warn_para1'] = $data['is_warn_para2'] = 1;

        $qddj_v = substr($qddj, 1);
        //判断有效强度是否合格
        //强度标号中的数字为抗压强度标准值
        $qddj = [
            '15'=>'3.0',
            '20'=>'3.5',
            '25'=>'4.0',
            '30'=>'4.5',
            '35'=>'5.0',
            '40'=>'5.5',
            '45'=>'6.0',
            '50'=>'6.5',
            '55'=>'7.0'
            ];
        if(!$jxqd){
            //计算有效强度 三组数据
            $jxqd = $this->getYxqdByThree($detail);
            $data['jxqd'] = $jxqd;
        }
        if($jxqd >= $qddj[$qddj_v]){
            $data['is_warn_para1'] = 0;
        }
        //不计算加载速率 
        $data['is_warn_para2'] = 0;

        if($data['is_warn_para1'] || $data['is_warn_para2']){
            $data['is_warn'] = 1;
        }
        return $data;
    }

    /**
    *水泥胶砂抗压/抗折
    *（一）规范要求：
    *品种         强度等级    抗压强度       抗折强度
    *                          3d    28d     3d      28d
    *硅酸盐水泥      42.5    ≥17.0   42.5    ≥3.5    ≥6.5
    *                42.5R   ≥22.0           ≥4.0    
    *                52.5    ≥23.0   52.5    ≥4.0    ≥7.0
    *                52.5R   ≥27.0           ≥5.0    
    *                62.5    ≥28.0   62.5    ≥5.0    ≥8.0
    *                62.5R   ≥32.0           ≥5.5    
    *
    *普通硅酸盐水泥  42.5    ≥17.0   42.5    ≥3.5    ≥6.5
    *                42.5R   ≥22.0           ≥4.0    
    *                52.5    ≥23.0   52.5    ≥4.0    ≥7.0
    *                52.5R   ≥27.0           ≥5.0    
　  *
    *                32.5    ≥10.0   32.5    ≥2.5    ≥5.5
    *矿渣硅酸盐水泥  32.5R   ≥15.0           ≥3.5    
    *火山灰质硅酸盐水泥42.5  ≥15.0   42.5    ≥3.5    ≥6.5
    *粉煤灰硅酸盐水泥42.5R   ≥19.0           ≥4.0    
    *复合硅酸盐水泥  52.5    ≥21.0   52.5    ≥4.0    ≥7.0
    *　              52.5R   ≥23.0           ≥4.5    
    *
    *1）  硅酸盐水泥，分P.I和P.II
    *2）  普通硅酸盐水泥（简称普通水泥）,代号：P.O。
    *3）  矿渣硅酸盐水泥，代号：P.S，分为P.S.A和P.S.B。
    *4）  火山灰质硅酸盐水泥，代号：P.P。
    *5）  粉煤灰硅酸盐水泥，代号：P.F。
    *6）  复合硅酸盐水泥（简称复合水泥），代号P.C。
    *
    *抗折试验以一组三个试件抗折结果的平均值作为试验结果。
    *当三个强度值中有超出平均值士10%时，应剔除后再取平均值作为抗折强度试验结果。
    *
    *抗压试验如六个测定值中有一个超出六个平均值的±10%，就应剔除这个结果，而以剩下五个的平均数为结果。
    *如果五个测定值中再有超过它们平均数±10%的，则此组结果作废。
    *
    *2、加荷速率的报警分抗压抗折两种类型，
    *抗压的报警条件为：加荷速度值不在2.4KN/s±0.2KN/s速率范围内报警，
    *抗折的报警条件为：加荷速度值不在50N/s±10N/s范围内为抗折加荷速度报警。
    *
    *$type 1 抗折  2 抗压
    **/
    protected function snjsKyOrKz($type, $sjpz, $qddj, $sylq, $jzsl, $yxqd, $sjgg, $detail){
        $data['is_warn_para1'] = $data['is_warn_para2'] = 1;
        $qddj_info[2] = [
            0=>[
                '42.5'=>['3'=>'17.0','28'=>'42.5'],
                '42.5R'=>['3'=>'22.0','28'=>'42.5'],
                '52.5'=>['3'=>'23.0','28'=>'52.5'],
                '52.5R'=>['3'=>'27.0','28'=>'52.5'],
                '62.5'=>['3'=>'28.0','28'=>'62.5'],
                '62.5R'=>['3'=>'32.0','28'=>'62.5']
                ],
            1=>[
                '42.5'=>['3'=>'17.0','28'=>'42.5'],
                '42.5R'=>['3'=>'22.0','28'=>'42.5'],
                '52.5'=>['3'=>'23.0','28'=>'52.5'],
                '52.5R'=>['3'=>'27.0','28'=>'52.5']
                ],
            2=>[
                '32.5'=>['3'=>'10.0','28'=>'32.5'],
                '32.5R'=>['3'=>'15.0','28'=>'32.5'],
                '42.5'=>['3'=>'15.0','28'=>'42.5'],
                '42.5R'=>['3'=>'19.0','28'=>'42.5'],
                '52.5'=>['3'=>'21.0','28'=>'52.5'],
                '52.5R'=>['3'=>'23.0','28'=>'52.5'],
                ],
            ];
    
        $qddj_info[1] = [
            0=>[
                '42.5'=>['3'=>'3.0','28'=>'6.5'],
                '42.5R'=>['3'=>'4.0','28'=>'6.5'],
                '52.5'=>['3'=>'4.0','28'=>'7.0'],
                '52.5R'=>['3'=>'5.0','28'=>'7.0'],
                '62.5'=>['3'=>'5.0','28'=>'8.0'],
                '62.5R'=>['3'=>'5.5','28'=>'8.0']
                ],
            1=>[
                '42.5'=>['3'=>'3.0','28'=>'6.5'],
                '42.5R'=>['3'=>'4.0','28'=>'6.5'],
                '52.5'=>['3'=>'4.0','28'=>'7.0'],
                '52.5R'=>['3'=>'5.0','28'=>'7.0'],
                ],
            2=>[
                '32.5'=>['3'=>'2.5','28'=>'5.5'],
                '32.5R'=>['3'=>'3.5','28'=>'5.5'],
                '42.5'=>['3'=>'3.5','28'=>'6.5'],
                '42.5R'=>['3'=>'4.0','28'=>'6.5'],
                '52.5'=>['3'=>'4.0','28'=>'7.0'],
                '52.5R'=>['3'=>'4.5','28'=>'7.0'],
                ],
            ];
    
        //根据试件品种获取具体信息
        if($sjpz == 'P.I' || $sjpz == 'P.II'){
            $key = 0;
        }elseif ($sjpz == 'P.O') {
            $key = 1;
        }else{
            $key = 2;
        }
        $qddj_info = $qddj_info[$type][$key][$qddj][$sylq];
        //判断强度是否合格
        if(!$yxqd){
            $data['yxqd'] = $yxqd = $this->snjsQd($type, $detail);
        }
        if($yxqd >= $qddj_info){
            $data['is_warn_para1'] = 0;
        }
        //判断加载速率是否合格
        if($type == 1){  //50N/s±10N/s
            if($jzsl >= 40 && $jzsl <= 60){
                $data['is_warn_para2'] = 0;
            }
        }
        if($type == 1){  //2.4KN/s±0.2KN/s
            if($jzsl<100){
                if($jzsl >= 2.2 && $jzsl <= 2.6){
                    $data['is_warn_para2'] = 0;
                }
            }else{
                if($jzsl >= 2200 && $jzsl <= 2600){
                    $data['is_warn_para2'] = 0;
                }
            }
        }
        //计算有效力值  （有效强度*试件规格）/1000
        $data['yxlz'] = round($yxqd*$sjgg/1000, 1);

        if($data['is_warn_para1'] || $data['is_warn_para2']){
            $data['is_warn'] = 1;
        }
        
        return $data;
    }


    /**
    *钢筋拉伸、焊接接头试验、钢绞线拉伸试验
    *  1、热轧光圆钢筋、热轧带筋钢筋
    *牌号  公称直径d（mm）   屈服强度标准值（N/mm2）  极限强度标准值（N/mm2）
    *HPB235  6~22               235                     370
    *HPB300                     300                     420
    *
    *HRB335  6~50               335                     455
    *HRBF335         
    *
    *HRB400  6~50               400                     540
    *HRBF400         
    *RRB400          
    *
    *HRB500  6~50               500                     630
    *HRBF500         
    *
    *2、预应力筋
    *　                  种类         屈服强度标准值（Mpa）    极限强度标准值（Mpa）
    *中强度预应力钢筋    620/800        620                     800
    *                    780/970        780                     970
    *                    980/1270       980                     1270
    *                    1080/1370      1080                    1370
    *预应力螺纹钢筋      PSB785         785                     980
    *                    PSB930         930                     1080
    *                    PSB1080        1080                    1230
    *报警原则：一组中的每一个试件的试验强度不小于标准值则。
    *$type 2 预应力筋 1其他
    **/
    protected function gjsy($type, $sypz, $lbph, $sjgg, $detail){
        if($type==1){
            $qddj = [
                'HPB235'=>['235','370'],
                'HPB300'=>['300','420'],
                'HRB335'=>['335','455'],
                'HRBF335'=>['335','455'],
                'HRB400'=>['400','540'],
                'HRBF400'=>['400','540'],
                'RRB400'=>['400','540'],
                'HRB500'=>['500','630'],
                'HRBF500'=>['500','630']
            ];
            $qddj = $qddj[$lbph];
            //判断各个试件是否合格
            $data = $this->judgeGjHg($qddj, $detail);
        }
        if($type==2){
            $qddj = [
                '620/800'=>['620','800'],
                '780/970'=>['780','970'],
                '980/1270'=>['980','1270'],
                '1080/1370'=>['1080','1370'],
                'PSB785'=>['785','980'],
                'PSB930'=>['930','1080'],
                'PSB1080'=>['1080','1230'],
            ];
            $qddj = $qddj[$sypz];
            $data = $this->judgeGjHg($qddj, $detail);
        }
        if($data['warn']['is_warn_para1'] || $data['warn']['is_warn_para2']){
            $data['warn']['is_warn'] = 1;
        }
        return $data;
    }

    /**通过三组数据获取有效强度
	*
    *最大值和最小值都没有超过中间值的15%，则以三个试件抗压强度的算术平均值作为该组试件的抗压强度值
    *三个测定值中的最大或最小值中有一个与中间值的差异超过中间值的15％，则把最大及最小值舍去，取中间值作为该组试件的抗压强度值
    *最大、最小值均与中间值相差15％以上，则此组试验作废
    **/
    protected function getYxqdByThree($detail){
    	//var_dump($detail);
    	$qd[0]= $detail[0]['qd'];
    	$qd[1] = $detail[1]['qd'];
    	$qd[2] = $detail[2]['qd'];
    	sort($qd);
    	$cz1 = ($qd[1] - $qd[0])/$qd[1];
    	$cz2 = ($qd[2] - $qd[1])/$qd[1];
    	if($cz1 < 0.15 && $cz2 < 0.15){
    		$yxqd = ($qd[0] + $qd[1] + $qd[2])/3;
    	}
    	if($cz1 > 0.15 && $cz2 > 0.15){
    		$yxqd = '';
    	}
    	if(($cz1 > 0.15 && $cz2 < 0.15) || ($cz1 < 0.15 && $cz2 > 0.15)){
    		$yxqd = $qd[1];
    	}
    	return $yxqd ? round($yxqd, 1) : '';
    }

    /**判断混凝土加载速率
    *加载速率报警单位为KN/s，其合格范围为：
    *试件规格    小于C30      大于等于C30小于C60       大于等于C60
    *100*100 3KN/s-5KN/s         5KN/s-8KN/s           8KN/s-10KN/s
    *150*150 6.75KN/s-11.25KN/s  11.25KN/s-18KN/s      18KN/s-22.5KN/s
    *200*200 12KN/s-20KN/s       20KN/s-32KN/s         32KN/s-40KN/s
    *
    *加载速率报警单位为MPa/s，经过换算报警范围为：
    *强度                  速率
    *小于C30               0.3Mpa/s-0.5Mpa/s
    *大于等于C30小于C60    0.5Mpa/s-0.8Mpa/s
    *大于等于C60           0.8Mpa/s-1.0Mpa/s
    **/
    protected function judgeHntJzsl($jzsl, $sjgg, $qddj){
        $detail = [
            '1'=>[0=>'0.3',1=>'0.5',2=>'0.8',3=>'1.0'],
            '100'=>[0=>'3',1=>'5',2=>'8',3=>'10'],
            '150'=>[0=>'6.75',1=>'11.25',2=>'18',3=>'22.5'],
            '200'=>[0=>'12',1=>'20',2=>'32',3=>'40'],
            ];

        $is_hg = false;
        if($jzsl <= 1){      //单位MPa/s
            $is_hg = $this->hntJzslDetail($qddj, $jzsl, $detail[1]);
        }else{
            $sjgg = substr($sjgg, 0, 3);
            $is_hg = $this->hntJzslDetail($qddj, $jzsl, $detail[$sjgg]);
        }
        return $is_hg;
    }

    /*获取混凝土加载速率详情*/
    protected function hntJzslDetail($qddj, $jzsl, $detail){
        $is_hg = false;
        if($qddj < 30){
            if($jzsl >= $detail[0] && $jzsl <= $detail[1]){
                $is_hg = true;
            }
            return $is_hg;
        }
        if($qddj >= 30 && $qddj < 60){
            if($jzsl >= $detail[1] && $jzsl <= $detail[2]){
                $is_hg = true;
            }
            return $is_hg;
        }
        if($qddj >= 60){
            if($jzsl >= $detail[2] && $jzsl <= $detail[3]){
                $is_hg = true;
            }
            return $is_hg;
        }

        return $is_hg;
    }
    
    /*获取水泥胶砂实验强度
    *抗折试验以一组三个试件抗折结果的平均值作为试验结果。
    *当三个强度值中有超出平均值士10%时，应剔除后再取平均值作为抗折强度试验结果。
    *
    *抗压试验如六个测定值中有一个超出六个平均值的±10%，就应剔除这个结果，而以剩下五个的平均数为结果。
    *如果五个测定值中再有超过它们平均数±10%的，则此组结果作废。
    *
    *$type 1 抗折  2 抗压
    */
    protected function snjsQd($type, $detail){
        if($type == 1){
            //第一次获取平均值
            $data = $this->getAver($detail);
            
            //第二次获取平均值
            $data = $this->getAver($detail, 1, $data['pjz']);
            return round($data['pjz'], 1);
        }

        if($type == 1){
            //第一次获取平均值
            $data = $this->getAver($detail);
            
            //第二次获取平均值
            $data = $this->getAver($detail, 1, $data['pjz']);

            //第三次获取平均值
            $data = $this->getAver($detail, 1, $data['pjz']);
            if($data[i]<5){
                return '';
            }
            return round($data['pjz'], 1);
        }
    }

    /*获取平均强度值*/
    protected function getAver($detail, $is_bj=0, $pjz = 0){
        $qd = 0;
        $i = 0;
        foreach ($detail as $key => $value) {
            if($is_bj){
                $cz = abs($value['qd'] - $pjz);
                if($cz/$pjz < 0.1){
                    $i++;
                    $qd = $qd + $value['qd'];
                }
            }else{
                $i++;
                $qd = $qd + $value['qd'];
            }
        }
        return ['pjz'=>$qd/$i, 'i'=>$i];
    }

    /*判断钢筋实验试件是否合格*/
    protected function judgeGjHg($qddj, $detail){
        $data['warn']['is_warn_para1'] = 0;
        $data['warn']['is_warn_para2'] = 0;
        foreach ($detail as $key => $value) {
            if($value['qd'] >= $qddj[0]){
                $detail[$key]['is_qd_warn'] = 0;
            }else{
                $detail[$key]['is_qd_warn'] = 1;
                $data['warn']['is_warn_para1'] = 1;
            }
            if($value['jxqd'] >= $qddj[1]){
                $detail[$key]['is_jxqd_warn'] = 0;
            }else{
                $detail[$key]['is_jxqd_warn'] = 1;
                $data['warn']['is_warn_para2'] = 1;
            }
        }
        //添加钢筋实验试件信息
        $data['detail'] = $detail;
        return $data;
    }

}

