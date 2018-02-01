<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-31
 * Time: 17:11
 */

namespace app\userlist\controller;

use app\common\controller\Api;
use app\userlist\service\UserlistService;

class Userlist extends Api
{
    public function index($page=1)
    {
        $res = UserlistService::user($page);
        if($res){
            return $this->responseSuccess($res);
        }else{
            return $this->responseError(UserlistService::getError());
        }
    }
}