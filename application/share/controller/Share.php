<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/26
 * Time: 下午4:35
 */
namespace app\share\controller;


use app\common\controller\Api;
use app\share\service\ShareService;
use think\Request;

class Share extends Api
{
    /*
     * 转发回调
     */
    public function shareCallback()
    {
        $data = Request::instance()->only(['openGId'],'post');
        $uid = $this->auth['user_id']??'';
        $result = ShareService::ShareTowgroup($data,$uid);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(ShareService::getError());
        }
    }

    public function getOpenGid()
    {
        $data = Request::instance()->post();
        $uid = $this->auth['user_id']??'';
        $result = ShareService::getOpenGid($data['shareInfo'],$uid);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(ShareService::getError());
        }
    }

}
