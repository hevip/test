<?php
/**
 * Created by PhpStorm.
 * Pay: greatsir
 * Date: 2018/1/26
 * Time: 下午4:20
 */
namespace app\rule\controller;

use app\common\controller\Api;
use app\rule\service\RuleService;

class Rule extends Api
{
    /**
     * 规则说明
    **/
    public function ruleDescription()
    {
        $list = RuleService::ruleDescription();

        if($list){
            return $this->responseSuccess($list);
        }else{
            return $this->responseError(RuleService::getError());
        }
    }
}