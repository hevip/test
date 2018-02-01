<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 下午3:21
 */

namespace app\address\validate;

use think\Validate;
class Address extends Validate
{
    protected $rule = [
        'user_id' =>'require',
        'tel'   =>'require',

    ];
    protected $message= [
        'user_id.require' => '名称必须',
        'tel.require'   => '电话必须',
    ];
}