<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-30
 * Time: 17:56
 */

namespace app\prizes\controller;
use app\common\controller\Api;
use app\prizes\service\PrizesService;

class Prizes extends Api
{
    //添加奖品
    public function add($prize_title,$prize_img){
        $data = PrizesService::add($prize_title,$prize_img);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(PrizesService::getError());
        }
    }

    //删除奖品（软删除）
    public function del($prize_id){
        $data = PrizesService::del($prize_id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(PrizesService::getError());
        }
    }
}