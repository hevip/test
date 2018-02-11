<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 下午3:21
 */

namespace app\blank\validate;

use think\Validate;
class Blank extends Validate
{
    protected $rule = [
        'url'   =>'require'
    ];

}