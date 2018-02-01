<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 下午3:21
 */

namespace app\message\validate;

use think\Validate;
class Message extends Validate
{
    protected $rule = [

        'express_name' =>'require',
        'express_no'   =>'require'

    ];
    protected $message= [
        'rexpress_name.require' => '物流公司必须',
        'express_no.require'  =>'运单号必须',
    ];
}