<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-29
 * Time: 16:14
 */

namespace app\prizes\controller;
use app\common\controller\Api;
use app\prizes\service\IndexService;

class Index extends Api
{
    public function index(){
        $list = IndexService::prizes_list();
        if($list){
            return $this->responseSuccess($list);
        }else{
            return $this->responseError(IndexService::getError());
        }
    }
}