<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/1
 * Time: 下午4:51
 */

namespace app\challenge\controller;


use app\challenge\service\RankService;
use app\common\controller\Api;

class Rank extends Api
{
    //
    public function getYesterDay()
    {
        $date = date('Y-m-d',strtotime('-1 days'));
        $res  = RankService::getYesterDay($date);
        return $this->responseSuccess($res);
    }
}