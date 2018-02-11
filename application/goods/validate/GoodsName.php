<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/5
 * Time: 上午9:42
 */
namespace app\goods\validate;


use think\Validate;

class GoodsName extends Validate
{
    protected $rule=[
        'goods_title'=>'chsAlphaNum'
    ];
}