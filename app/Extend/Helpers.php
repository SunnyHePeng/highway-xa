<?php

namespace App\Extend;

/**
 * 自定义函数
 */
class Helpers
{
    /**
     * 包装soap数据成HighChart格式
     * 
     * @param array $raw
     * @return array
     */
    static public function highChartColumnWrapper($raw)
    {
        $data = [];
        $cate = [];
        
        if(self::array_depth($raw) == 1)
            $raw = [$raw];

        foreach($raw as $key => $value){
            
            $data[$key]['name'] = array_shift($value);
            $data[$key]['data'] = array_values(array_map('intval',$value));
            $cate = array_keys($value);
        }
 
        return [$data, $cate];
    }

    /**
     * 获取数组深度
     * 
     * @return int
     */
    static public function array_depth($array) 
    {
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::array_depth($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }        
        return $max_depth;
    }
    
    /**
     * 拼接SQL IN语法所需的字符串
     */
    static public function implodeCollectToSQLsWhereInString($collect)
    {
        return $collect->map(function($item, $index){
                    return "'{$item}'";
                })
                ->implode(', ');
    }

    /**
     * 递归遍历ztree数据，返回选择的名字
     * 
     * @param array $data
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    static public function recursiveSearchZtreeData($data, $key, $value)
    {
        static $tem = null;

        foreach($data as $k => $v){

            if($value == $v['id'] && $key == $v['key']){
                $tem = $v['name'];
            } 

            if(array_key_exists('children', $v)) self::recursiveSearchZtreeData($v['children'], $key, $value);
        }

        return $tem;
    }

    /**
     * 分解ztree链接
     * 
     * @return mixed
     */
    static public function resolveZtreeSearchInRequest($data)
    {
        $keys = ['pro_id', 'sec_id', 'sup_id'];
        
        foreach($keys as $key){
            if($id = request($key))
                return self::recursiveSearchZtreeData($data, $key, $id);
        }

        return '全部';
    }    

}
