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
        'contacts' =>'require',
        'mobile' =>'require',
        'province' =>'require',
        'city' =>'require',
        'country' =>'require',
        'address' =>'require',
        'prizes' =>'require|number'
    ];
}