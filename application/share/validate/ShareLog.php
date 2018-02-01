<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/29
 * Time: 下午4:43
 */
namespace app\share\validate;
use think\Validate;

class ShareLog extends Validate
{
    protected $rule = [
        'openGId'=>'require'
    ];
}