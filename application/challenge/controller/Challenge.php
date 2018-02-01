<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/26
 * Time: 下午4:48
 */
namespace app\challenge\controller;


use app\challenge\service\ChallengeService;
use app\common\controller\Api;
use think\Request;

class Challenge extends Api
{
    /*
     * 挑战结算
     */
    public function deal()
    {
        $data = Request::instance()->post();
        $uid  = $this->auth['user_id']??'';
        $result = ChallengeService::Settlement($data,$uid);//结算
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(ChallengeService::getError());
        }
    }
}