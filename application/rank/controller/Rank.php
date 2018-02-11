<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-29
 * Time: 9:04
 */
namespace app\rank\controller;
use app\common\controller\Api;
use app\rank\service\RankService;
class Rank extends Api
{

    public function user_msg($open_id){
        $data = RankService::user_msg($open_id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(RankService::getError());
        }
    }
    public function prizes_rank(){
        $data = RankService::prizes_rank();
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(RankService::getError());
        }
    }

    public function challenges_rank(){
        $page = input('page');
       $data = RankService::challenges_rank($page);
       if(!empty($data)){
           return $this->responseSuccess($data);
       }else{
           return $this->responseError(RankService::getError());
       }
    }

    public function group_rank($group_openid = 0){
        $data = RankService::group_rank($group_openid);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(RankService::getError());
        }
    }
}