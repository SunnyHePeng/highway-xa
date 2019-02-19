<?php 
namespace App\Send;

use App\Http\Controllers\Wechat\IndexController as Wechat;

/**
 * 微信模板消息
 */
class SendWechat {

    /*报警通知
        {{first.DATA}}
        设备：{{keyword1.DATA}}
        报警时间：{{keyword2.DATA}}
        报警内容：{{keyword3.DATA}}
        {{remark.DATA}}*/
    public function sendBj($openid, $info)
    {
        //$openid = 'o4nJv0UC2nULE3Ms6UsZcmH2wvvk';
        //发送的模板信息(微信要求json格式，这里为数组（方便添加变量）格式，然后转为json)
        $post_data = [
                'touser'=>$openid,
                'template_id'=>'rl-BRMxkU8DnMwJiamyQMvmjKsfccz80FO6EMp9dBCU',
                'url'=>'',
                'data'=>[
                        'first' =>[
                                'value'=>$info['first'],
                                'color'=>'#173177'
                        ],
                        'keyword1'=>[
                                'value'=>$info['dcode'],
                                'color'=>'#173177'
                        ],
                        'keyword2'=>[
                                'value'=>date('Y年m月d日 H:i:s',$info['time']),
                                'color'=>'#173177'
                        ],
                        'keyword3'=>[
                                'value'=>$info['info'],
                                'color'=>'#173177'
                        ],
                        'remark'=>[
                                'value'=>$info['level'].'报警，请及时处理',
                                'color'=>'#173177'
                        ],
                ]
        ];
        //将上面的数组数据转为json格式
        $post_data = json_encode($post_data);
        //发送数据
        $res = $this->send($post_data);
        
        return $res;
    }


    /*注册审核通知
        {{first.DATA}}
        机构名称：{{keyword1.DATA}}
        所属部门：{{keyword2.DATA}}
        用户姓名：{{keyword3.DATA}}
        用户角色：{{keyword4.DATA}}
        申请时间：{{keyword5.DATA}}
        {{remark.DATA}}*/
    public function sendSh($openid, $info)
    {
        //$openid = 'o4nJv0UC2nULE3Ms6UsZcmH2wvvk';
        //发送的模板信息(微信要求json格式，这里为数组（方便添加变量）格式，然后转为json)
        $post_data = [
                'touser'=>$openid,
                'template_id'=>'MR2xxLzI6Tgpcqn2xvcDh5DKfC0_IgrUc2yWIHZvJ8I',
                'url'=>'',
                'data'=> [
                        'first' => [
                                'value'=>'新用户注册成功',
                                'color'=>'#173177'
                        ],
                        'keyword1'=>[
                                'value'=>$info['company'],
                                'color'=>'#173177'
                        ],
                        'keyword2'=>[
                                'value'=>$info['position'],
                                'color'=>'#173177'
                        ],
                        'keyword3'=> [
                                'value'=>$info['name'],
                                'color'=>'#173177'
                        ],
                        'keyword4'=>[
                                'value'=>$info['role'],
                                'color'=>'#173177'
                        ],
                        'keyword5'=> [
                                'value'=>date('Y年m月d日 H:i:s',$info['time']),
                                'color'=>'#173177'
                        ],
                        'remark'=> [
                                'value'=>'请及时审核处理',
                                'color'=>'#173177'
                        ],
                ]
        ];
        //将上面的数组数据转为json格式
        $post_data = json_encode($post_data);
        //发送数据
        $res = $this->send($post_data);
        
        return $res;
    }

    /*隧道进度统计更新推送消息*/
    public function sendSdtj($openid,$info)
    {
        $post_data = [
            'touser'=>$openid,
            'template_id'=>'8mUcnQa4PFTrHnVeGG2AnOQX_RMzUmmcWvqfoA6ZDTA',
            'url'=>$info['url'],
            'data'=> [
                'first' => [
                    'value'=>$info['first'],
                    'color'=>'#173177'
                ],
                'keyword1'=>[
                    'value'=>$info['word'],
                    'color'=>'#173177'
                ],
                'keyword2'=>[
                    'value'=>'施工进行中',
                    'color'=>'#173177'
                ],
                'keyword3'=> [
                    'value'=>$info['con'],
                    'color'=>'#173177'
                ],
                'keyword4'=> [
                    'value'=>date('Y年m月d日 H:i:s',$info['time']),
                    'color'=>'#173177'
                ],
                'remark'=> [
                    'value'=>'请您注意跟进，谢谢',
                    'color'=>'#173177'
                ],
            ]
        ];
        //将上面的数组数据转为json格式
        $post_data = json_encode($post_data);
        //发送数据
        $res = $this->send($post_data);

        return $res;
    }

    /*通知公告审核提醒推送*/
    public function sendApprovalNotice($openid,$info)
    {
        $post_data = [
            'touser'=>$openid,
            'template_id'=>'UDMyPoFMYLynvVw205hcYQplmgOl1dnSZdZBe17OatU',
            'url'=>$info['url'],
            'data'=> [
                'first' => [
                    'value'=>$info['first'],
                    'color'=>'#173177'
                ],
                'keyword1'=>[
                    'value'=>$info['word1'],
                    'color'=>'#173177'
                ],
                'keyword2'=>[
                    'value'=>$info['word2'],
                    'color'=>'#173177'
                ],
                'remark'=> [
                    'value'=>$info['remark'],
                    'color'=>'#173177'
                ],
            ]
        ];
        //将上面的数组数据转为json格式
        $post_data = json_encode($post_data);
        //发送数据
        $res = $this->send($post_data);

        return $res;
    }
    /**
     * 发送拌和设备与采集端连接异常通知
     * @param $openid
     * @param $info
     * @return mixed
     */
    public function sendDeviceFailure($openid, $info){
        //$openid = 'opV3P1BXo1vBfhmTaWdrszvjiuCs';
        //发送的模板信息(微信要求json格式，这里为数组（方便添加变量）格式，然后转为json)
        $post_data = [
            'touser'=>$openid,
            'template_id'=>'JHU1FiV-LPp22uNyHQijEtYvddYMVFILJulahGhdX0A',
            'data'=>[
                'first' =>[
                    'value'=>$info['first'],
                    'color'=>'#173177'
                ],
                'keyword1'=>[
                    'value'=>$info['device_code'],
                    'color'=>'#173177'
                ],
                'keyword2'=>[
                    'value'=>$info['device_name'],
                    'color'=>'#173177'
                ],
                'keyword3'=>[
                    'value'=>$info['project_name'].'--'.$info['section_name'],
                    'color'=>'#173177'
                ],
                'keyword4'=>[
                    'value'=>$info['info'],
                    'color'=>'#173177'
                ],
                'keyword5'=>[
                    'value'=>$info['time'],
                    'color'=>'#173177'
                ],
                'remark'=>[
                    'value'=>$info['remark'],
                    'color'=>'#173177'
                ],
            ]
        ];
        //将上面的数组数据转为json格式
        $post_data = json_encode($post_data);
        //发送数据
        $res = $this->send($post_data);

        return $res;
    }

    /**
     * 调用模板消息接口
     */
    protected function send($data)
    {
        $access_token = (new Wechat)->getAccessToken();

        //模板信息请求地址
        $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
    
        //发送数据，post方式<br>　　　　　　　　　//配置curl请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //接收执行返回的数据
        $res = curl_exec($ch);
        //关闭句柄
        curl_close($ch);
        
        return $res;
    }

    /**
     * 发送模板消息
     * 
     * @param string $touser
     * @param string $template_id
     * @param array $data
     * @param string $url
     * @return string
     */
    public function sendTemplateMessage($touser, $template_id, $data, $url = '')
    {
        return $this->send( json_encode( compact('touser', 'template_id', 'url', 'data') ) );
    }    
}