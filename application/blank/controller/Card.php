<?php
namespace app\blank\controller;

use think\Controller;
use think\Request;
use think\Db;

class Card extends Controller
{
    public function index()
    {
      $res = Db::name('card')->select();
        if($res){
            return json_encode($res);
        }elseif(empty($res)){
            $msg = '暂无数据';
            return $msg;
        }else{
            $msg = '服务器忙，请稍候再试';
            return $msg;
        }
    }

    public function add()
    {
        $data = Request::instance()->post();
        $info['url'] = $data['url'];
        $info['c_time'] = time();
        $res = Db::name('card')->insert($info);
        if($res){
            $msg[] = '添加成功';
            return $msg;
        }else{
            $msg = '添加失败';
            return $msg;
        }
    }
}