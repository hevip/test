<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-29
 * Time: 16:59
 */

namespace app\advertisement\controller;
use app\common\controller\Api;
use app\advertisement\service\AdvertisementService;
class Advertisement extends Api
{

    //广告列表
    public function index(){
        $search = input('search');
        $list = AdvertisementService::lists($search);
        if($list){
            return $this->responseSuccess($list);
        }else{
            return $this->responseError(AdvertisementService::getError());
        }
    }


    //添加广告
    public function add($title,$link_url,$photo,$endtime){
        $data = AdvertisementService::add($title,$link_url,$photo,$endtime);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(AdvertisementService::getError());
        }
    }


    //删除广告（软删）
    public function del($advertisement_id){
        $data = AdvertisementService::del($advertisement_id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(AdvertisementService::getError());
        }
    }

    //修改广告有效时间
    public function update($advertisement_id,$week){
        $data = AdvertisementService::update($advertisement_id,$week);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(AdvertisementService::getError());
        }
    }
}