<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-30
 * Time: 17:57
 */

namespace app\prizes\service;
use app\common\service\BaseService;
use think\Db;

class PrizesService extends BaseService
{
    public static function add($prize_title,$prize_img){
        if(empty($prize_title) || empty($prize_img)){
            self::setError([
                'status_code'=>4055,
                'message'    =>'请输入奖品名称及奖品照片',
            ]);
            return false;
        }
        if(strlen($prize_img) > 200 || strlen($prize_title) > 45 ){
            self::setError([
                'status_code'=>4055,
                'message'    =>'商品或图片图片名称过长',
            ]);
            return false;
        }

        $datas = Db::name('prizes')->where(array('prize_title'=>$prize_title,'is_del'=>0))->find();
        if(!empty($datas)){
            self::setError([
                'status_code'=>4057,
                'message'    =>'存在相同的名称',
            ]);
            return false;
        }
        $data['prize_img'] = $prize_img;
        $data['prize_title'] = $prize_title;
        $insert =  Db::name('prizes')->insert($data);
        if($insert){
            return true;
        }else{
            self::setError([
                'status_code'=>509,
                'message'    =>'数据库写入数据失败',
            ]);
            return false;
        }
    }

    public static function del($prize_id){
        $data = Db::name('prizes')->where(array('id'=>$prize_id,'is_del'=>0))->find();
        if(empty($data)){
            self::setError([
                'status_code'=>4055,
                'message'    =>'请输入正确的奖品ID',
            ]);
            return false;
        }
        $update = Db::name('prizes')->where(array('id'=>$prize_id,'is_del'=>0))->update(array('is_del'=>1));
        if($update){
            return true;
        }else{
            self::setError([
                'status_code'=>509,
                'message'    =>'数据库修改数据失败',
            ]);
            return false;
        }
    }
}