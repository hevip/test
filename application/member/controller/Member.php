<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-8-30
 * Time: 下午5:40
 */

namespace app\member\controller;

use app\common\controller\Api;
use app\member\service\MemberService;
use think\Request;

class Member extends Api
{
    /**
     * 用户列表
     */
    public function  index($page=1)
    {
        $list = MemberService::_list($page);
        if($list){
            return $this->responseSuccess($list);
        }else{
            return $this->responseError(MemberService::getError());
        }

    }

    /**
     *创建用户
     */
    public function create()
    {
       $data   = Request::instance()->post();
       $result = MemberService::create($data);
       if($result){
           return $this->responseSuccess($result);
       }else{
           return $this->responseError(MemberService::getError());
       }
    }
}