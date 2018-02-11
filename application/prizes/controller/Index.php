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

    //奖品列表
    public function index(){
        $search = input ('search');
        $list = IndexService::prizes_list($search);
        if($list){
            return $this->responseSuccess($list);
        }else{
            return $this->responseError(IndexService::getError());
        }
    }
}