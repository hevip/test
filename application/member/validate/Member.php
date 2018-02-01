<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-9-6
 * Time: 下午1:21
 */
namespace app\member\validate;

use think\Validate;
use greatsir\RedisClient;
class Member extends Validate
{
    protected $rule = [

        'mobile' =>'checkMobile:',
        'code'   =>'checkCode:',
        'password'=>'checkPassword',

    ];
    //自定义验证规则
    protected function checkMobile($value)
    {
        //step1:验证手机号是否存在
        if(!isset($value)){
            return '手机号不能为空';
        }
        if(!$this->isMobile($value))
        {
            return '手机号格式不正确';
        }
        if($this->checkUnique($value))
        {
            return '手机号已经存在';
        }
        return true;
        //step2:验证手机号是否符合规则
        //step3:验证手机号是否已经存在

    }
    protected function checkCode($value,$rule,$data)
    {
        if(!isset($value)){
            return '短信验证码不能为空';
        }
        $redis = RedisClient::getHandle();
        $code = $redis->getKey('register_code'.$data['mobile']);
        if($code!==$value){
            return '输入的短信验证码无效';
        }
        return true;
    }

    protected function checkPassword($value,$rule,$data)
    {
        if(!isset($value)){
            return '密码不能为空';
        }
        $cpassword = isset($data['cpassword']) ? $data['cpassword'] : '';
        if($value!==$cpassword){
            return '两次输入的密码不一致';
        }
        return true;
    }
    /*
     * 校验手机号格式
     */
    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    /**
     * 校验手机号是否已经注册
     * @param $mobile
     * @return bool
     */
    function checkUnique($mobile)
    {
        $redis = RedisClient::getHandle();
        $res   = $redis->in_set('register_mobiles',$mobile);
        return $res;
    }
}