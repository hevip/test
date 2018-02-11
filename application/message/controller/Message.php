<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 上午9:59
 */

namespace app\message\controller;

use app\common\controller\Api;
use app\message\service\MessageService;
use think\Request;
use think\Db;

class Message extends Api
{
    public function message()
    {
        $data = Request::instance()->post();
        $res = MessageService::sentMessage($data);
        if($res){
            return $this->responseSuccess($res);
        }else{
            return $this->responseError(MessageService::getError());
        }
    }
}