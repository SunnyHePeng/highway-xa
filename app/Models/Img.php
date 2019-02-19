<?php
namespace App\Models;

use Image, Session, Response, Cache, Input;
class Img{

    protected $code_width;
    protected $code_height;
    protected $code_num;

    /**
     * 初始化验证码格式
     */
    public function __construct($width, $height, $num)
    {
        $this->code_width = $width;
        $this->code_height = $height;
        $this->code_num = $num;
    }
    /**
     * @return mixed
     */
    public function code()
    {
        return $this->makeCode();
    }

    /**
     * @return mixed
     */
    protected function makeCode()
    {
        $charset = "2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY";
        $cWidth = $this->code_width;  //画布宽度
        $cHeight = $this->code_height;  //画布高度
        $code = "";
        $color =  ['#99c525','#fc9721','#8c659d','#00afd8'];
        $img = Image::canvas($cWidth, $cHeight, '#ccc');
        for ($i=0;$i<$this->code_num;$i++) {
            //画出干扰线
            $img->line(mt_rand(0,$cWidth),mt_rand(0,$cHeight),mt_rand(0,$cWidth),mt_rand(0,$cHeight), function ($draw) use ($color){
                $draw->color($color[array_rand($color,1)]);
            });
            //随机取出验证码
            $code .= $charset[mt_rand(0,strlen($charset)-1)];
            //画出验证码
            $img->text($code[$i],($this->code_width/$this->code_num)*$i+5,28,function($font) use($color){
                $font->file(public_path('api/arial.ttf'));
                $font->size(25);
                $font->color($color[array_rand($color,1)]);
                $font->angle(mt_rand(-30,30));
            });
        }
        //在session中放置code
        if(Input::get('sid')){
            Cache::put(Input::get('sid'), strtolower($code), Config()->get('common.cache_expire'));
        }else{
            Session::put('verifycode', strtolower($code));
        }

        $response = Response::make($img->encode('png'));
        $response->header('Content-Type', 'image/png');
        return $response;
    }
}