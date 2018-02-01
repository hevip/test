<?php
namespace app\address\controller;

use app\common\controller\Api;
use app\address\service\AddressService;
use think\Request;
use think\Db;

class Address extends Api
{
    //奖品领取
    public function goods(){
        $user_id  = $this->auth['user_id']??'';
        $result = AddressService::receiveGoods($user_id);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(AddressService::getError());
        }
    }

    //添加收货地址
    public function address()
    {
        $data = Request::instance()->post();
        $result = AddressService::address($data);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(AddressService::getError());
        }
    }



}