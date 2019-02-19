<?php

namespace App\Soap;

use SoapClient;

/**
 * 试验手动数据
 */
class LabStatistic
{
    /**
     * soap 地址
     * 
     * var string
     */
    const URL = 'http://sys.xawhgs.com/DataService/Service1.asmx?WSDL';

    /**
     * soap 方法参数密码
     * 
     * var string
     */
    const PWD = '##461218$$';

    /**
     * soap 客户端
     * 
     * var \SoapClient
     */
    public $client;

    /**
     * 构造函数
     * 
     * return void
     */
    public function __construct()
    {
        $this->client = new SoapClient(self::URL);
    }

    /**
     * 动态调用soap方法
     * 
     * @return array
     */
    public function __call($func, $params)
    {
        $param = array_pop($params);

        $param['p'] = self::PWD;

        $res =  $this->client->$func($param);

        return self::handle($res, $func);
    }

    /**
     * 结果处理
     * 
     * @param string $res
     * @param string $func
     * @return array
     */
    protected function handle($res, $func)
    {
        $obj = simplexml_load_string($res->{"{$func}Result"}->any);
        
        return json_decode(json_encode($obj->NewDataSet), true);
    }
}
