<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/5
 * Time: 上午9:05
 */
namespace app\goods\controller;


use app\common\controller\Api;
use app\goods\service\GoodsService;
use think\Request;

class Goods extends Api
{
    /*
     * 商品列表
     */
    public function index($page=1)
    {
        $goods_name = Request::instance()->post();
        $result = GoodsService::Goodslist($page,$goods_name);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }
    /*
     * 添加商品
     */
    public function create()
    {
        $data = Request::instance()->only(['goods_title','goods_desc','sale_price','original_price','chances'],'post');
        $res = GoodsService::create($data);
        if($res){
            return $this->responseSuccess($res);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }
    /*
     * 更新商品
     */
    public function update($id)
    {
        $data = Request::instance()->post();
        $res = GoodsService::update($data,$id);
        if($res){
            return $this->responseSuccess($res);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }
    /*
     * 删除商品
     */
    public function delete($id)
    {
        $data = GoodsService::delete($id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }
    /*
     * 获取所有商品
     */
    public function getAll()
    {
        $data = GoodsService::getAll();
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }
}