<?php

namespace App\Models\WeatherMessage;

use App\Models\Common;
use Input, DB;

/**
 * 天气信息
 */
class WeatherMessage extends Common
{
    protected $table = 'weather_message';
    protected $fillable = ['id',
        'time',
        'code',
        'weather_text',
        'temperature_low',
        'temperature_high',
        'weather_cate',
        'construction_situation',
    ];

    public $timestamps = false;



}