<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/29
 * Time: 下午1:21
 */
namespace app\challenge\validate;
use think\Validate;

class Settlement extends Validate
{
    protected $rule = [
        '_sign'=>'require'
    ];
}