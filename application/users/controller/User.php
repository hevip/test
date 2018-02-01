<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/26
 * Time: 下午4:20
 */
namespace app\users\controller;


use app\common\controller\Api;
use app\user\service\UserService;

class User extends Api
{
    /*
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $uid = $this->auth['user_id'] ?? '';
        $userInfo = UserService::getUserInfo($uid);
        if($userInfo){
            return $this->responseSuccess($userInfo);
        }else{
            return $this->responseError(UserService::getError());
        }
    }


}