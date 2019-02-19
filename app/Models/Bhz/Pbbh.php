<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 配比编号模型（弃）
 */
class Pbbh extends Common
{
    protected $table = 'pbbh';
    protected $fillable = [
        					'id',
        					'name',
      						'ds',
      						'zs',
      						'xs',
      						'sz',
      						'sn',
      						'fmh',
      						's',
      						'wjj',
  						];
   	protected $rule = [
         			'name'=>'required',
                ];
    public $timestamps = false;
}