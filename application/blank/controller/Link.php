<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/7
 * Time: 下午1:29
 */

namespace app\blank\controller;


use app\common\controller\Api;
use think\Db;
use think\Request;

class Link extends Api
{
    /*
     * 添加链接1
     */
    public function addlink1()
    {
        $data = Request::instance()->post();
        if(isset($data['id'])){
            $res = Db::name('link1')->where(['id'=>$data['id']])->update($data);
        }else{
            $res = Db::name('link1')->insert($data);
        }
        /*if(Db::name('link1')->where(['id'=>$data['id']])->find()){
            $res = Db::name('link1')->where(['id'=>$data['id']])->update($data);
        }else{
            $res = Db::name('link1')->insert($data);
        }*/
        if($res||$res==0){
            return $this->responseSuccess(['link'=>$data['link']]);
        }else{
            return $this->responseError([
                'status_code'=>500,
                'message' =>'网络错误，请稍后重试'
            ]);
        }
    }
    /*
     * 添加链接2
     */
    public function addlink2()
    {
        $data = Request::instance()->post();
        if(isset($data['id'])){
            $res = Db::name('link2')->where(['id'=>$data['id']])->update($data);
        }else{
            $res = Db::name('link2')->insert($data);
        }
        /*if(Db::name('link2')->where(['id'=>$data['id']])->find()){
            $res = Db::name('link2')->where(['id'=>$data['id']])->update($data);
        }else{
            $res = Db::name('link2')->insert($data);
        }*/
        if($res||$res==0){
            return $this->responseSuccess(['link'=>$data['link']]);
        }else{
            return $this->responseError([
                'status_code'=>500,
                'message' =>'网络错误，请稍后重试'
            ]);
        }
    }
}