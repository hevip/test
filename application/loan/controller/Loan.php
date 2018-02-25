<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-02-23
 * Time: 10:44
 */

namespace app\loan\controller;

use app\goods\service\GoodsService;
use app\loan\service\LoanService;
class Loan extends Api
{


    //首页列表
    public function loan_list($page=null,$search=null,$key=null){
        $data = LoanService::loan_list($page,$search,$key);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }




    //点击详情页
    public function detail($loan_id){
        $data = LoanService::detail($loan_id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }



    //同类业务
    public function similar($loan_id){
        $data = LoanService::similar($loan_id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }


    //后台列表
    public function admin_list($page=null,$search = null){
        $data = LoanService::admin_list($page,$search);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }

    //添加
    public function admin_add($data,$detail = null){
        $result = LoanService::admin_add($data,$detail);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }


    //删除
    public function admin_del($loan_id){
        $data = LoanService::admin_del($loan_id);
        if($data){
            return $this->responseSuccess($data);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }

    //修改
    public function admin_update($loan_id,$data,$detail=0){
        $result = LoanService::admin_update($loan_id,$data,$detail);
        if($result){
            return $this->responseSuccess($result);
        }else{
            return $this->responseError(GoodsService::getError());
        }
    }


}