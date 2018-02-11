<?php
namespace app\blank\controller;

//use app\common\controller\Api;
//use app\address\service\AddressService;
use think\Controller;
use think\Request;
use think\Db;

class Blank extends Controller
{
    public function index()
    {
      $res = Db::name('blank')->select();
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
        $res = Db::name('blank')->insert($info);
        if($res){
            $msg = '添加成功';
            return $msg;
        }else{
            $msg = '添加失败';
            return $msg;
        }
    }
    /*
     *
     */
     public function getLink1()
     {

         $res = Db::name('link1')->where(['is_show'=>1])->order('sort asc')->select();
         //$callback    = $_GET['callback'];
         //echo $callback."(".json_encode($res).")";
         return json($res);
     }
     public function getLink2()
     {
         $res = Db::name('link2')->where(['is_show'=>1])->order('sort asc')->select();
         //$callback    = $_GET['callback'];
         //echo $callback."(".json_encode($res).")";
         return json($res);
     }
}