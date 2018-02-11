<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-29
 * Time: 17:06
 */

namespace app\Advertisement\service;
use app\common\controller\Api;
use think\Db;
use app\common\service\BaseService;
class AdvertisementService extends BaseService
{
    //广告列表
    public static function lists($search){
        if(empty($search)){
            $list = Db::name('advertisement')->field('title,link_url,photo,id,create_time,end_time')->
            where('is_del',0)->where('end_time','>=',date('Y-m-d',time()))->order('id desc')->select();

        }else{
            $list = Db::name('advertisement')->field('title,link_url,photo,id,create_time,end_time')->
            where('is_del',0)->where('end_time','>=',date('Y-m-d',time()))
                ->where('title','like','%'.$search.'%')->order('id desc')->select();

        }
        if(!$list){
            self::setError([
                'status_code'=>509,
                'message'=>'广告数据错误',
            ]);
            return false;
        }else{
            return $list;
        }
    }

    //添加广告
    public static function add($title,$link_url,$photo,$endtime){
        if(empty($title) || empty($link_url) || empty($photo) || empty($endtime)){
            self::setError([
                'status_code'=>4055,
                'message'=>'请输入完整参数',
            ]);
            return false;
        }
        $list = Db::name('advertisement')->where(array('title'=>$title,'is_del'=>0))->find();
        if(!empty($list)){
            self::setError([
                'status_code'=>4057,
                'message'    =>'存在相同的名称',
            ]);
            return false;
        }
        $patten = '/^\d{4}(\-)\d{1,2}\1\d{1,2}$/';
        if (preg_match($patten, $endtime)) {
            $time_z = strtotime($endtime);
            if($time_z < time()){
                self::setError([
                    'status_code'=>4055,
                    'message'    =>'请输入正确的有效日期',
                ]);
                return false;
            }else{
                $time = date('Y-m-d',$time_z);
            }
        } else {
            self::setError([
                'status_code'=>4055,
                'message'    =>'时间格式错误 例：2018-01-01 ，2018-1-1',
            ]);
            return false;
        }
        $data = ([
            'title'=>$title,
            'link_url'=>$link_url,
            'photo'=>$photo,
            'create_time'=>date('Y-m-d',time()),
            'end_time'=>$time,
        ]);
        if(Db::name('advertisement')->insert($data)){
            return true;
        }else{
            self::setError([
                'status_code'=>509,
                'message'    =>'数据库修改数据失败',
            ]);
            return false;
        }
    }

    //删除广告（软删）
    public static function del($advertisement_id){

        $data = Db::name('advertisement')->where(array('id'=>$advertisement_id,'is_del'=>0))->find();
        if(empty($data)){
            self::setError([
                'status_code'=>4055,
                'message'    =>'请输入正确ID',
            ]);
            return false;
        }
        $update = Db::name('advertisement')->where(array('id'=>$advertisement_id,'is_del'=>0))->update(array('is_del'=>1));
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

    //修改广告有效时间
    public static function update($advertisement_id,$week){
        $data = Db::name('advertisement')->where(array('id'=>$advertisement_id,'is_del'=>0))->find();
        if(empty($data)){
            self::setError([
                'status_code'=>4055,
                'message'    =>'请输入正确的广告ID',
            ]);
            return false;
        }
        if($data['end_time'] > '2022-01-01'){
            self::setError([
                'status_code'=>4055,
                'message'    =>'该广告时间已经够长了',
            ]);
            return false;
        }
        if(!is_numeric($week)){
            self::setError([
                'status_code'=>4055,
                'message'    =>'请输入有效数字，例：1 = 1周',
            ]);
            return false;
        }
        if( $week <=0 || $week > 108){
            self::setError([
                'status_code'=>4055,
                'message'    =>'输入数字不得小于1,不得大于 108',
            ]);
            return false;
        }
        $add_time = $week *604800;
        if(strtotime($data['end_time']) > time()){
            $new_time = strtotime($data['end_time']) + $add_time;
            $new_time = date('Y-m-d',$new_time);
        }elseif (strtotime($data['end_time']) <= time()){
            $new_time = date('Y-m-d',time() + $add_time);
        };
        if(Db::name('advertisement')->where(array('id'=>$advertisement_id,'is_del'=>0))->update(array('end_time'=>$new_time))){
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
