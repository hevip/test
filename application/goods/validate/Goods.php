<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/5
 * Time: 上午9:42
 */
namespace app\goods\validate;


use think\Validate;

class Goods extends Validate
{
    protected $rule=[
        'goods_title'=>'require',
        'goods_desc' =>'require',
        'sale_price' =>'require',
        'original_price'=>'require',
        'chances'=>'require'
    ];
}