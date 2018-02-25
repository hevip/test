<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-29
 * Time: 10:20
 */
namespace app\rank\service;
use think\Db;
use think\Cache;
use app\common\service\BaseService;
class RankService extends BaseService
{

    //获得娃娃数量排行榜
    public static function prizes_rank(){
        $prizes_rank = Cache::get('prizes_rank');
        if(empty($prizes_rank)){
            $list = Db::name('users')->where('is_del',0)->field('user_name,user_icon,user_prizes')->order('user_prizes desc')->limit(5)->select();
            if(!$list){
                self::setError([
                    'status_code'=>509,
                    'message'=>'没有排行数据',
                ]);
                return false;
            }
            Cache::set('prizes_rank',$list,3600*24);
            return Cache::get('prizes_rank');
        }else{
            return $prizes_rank;
        }
    }


    //挑战次数排行榜
    public static function challenges_rank($page){
        if(empty($page)|| $page < 1){
            $pages = 50;
        }else{
            if($page >9 ){
                $pages = 50;
            }else{
                $pages = 10+$page*5;
            }
        }
        $list =  Db::name('users')->where('is_del',0)->field(
            'user_name,user_icon,user_challenges')->order('user_challenges desc')->limit
        ($pages)->select();
        if(!$list){
            self::setError([
                'status_code'=>509,
                'message'=>'没有排行数据',
            ]);
            return false;
        }
        return $list;
    }


    //群内排行榜
    public static function group_rank($group_openid){
        $map = ['opengid' =>$group_openid];
        $user_data = Db::name('group')->alias('g')->Distinct(true)->join('users u','g.user_id =u.user_id')->order('max_number desc')
            ->field('u.user_name,user_icon,max_number')->where($map)->select();
        if(!$user_data){
            self::setError([
                'status_code' =>4055,
                'message' =>'请输入正确的群openid',
            ]);
            return false;
        }else{
            return $user_data;
        }

    }


    //我的信息
    public static function user_msg($open_id){
        $user_msg = Db::name('users')->field('user_name,user_icon,user_prizes,user_challenges,reset_challenges,max_number')
            ->where(array('user_openid'=>$open_id,'is_del'=>0))->find();
        if(empty($user_msg)){
            self::setError([
                'status_code' =>4055,
                'message' =>'请输入正确的用户openid',
            ]);
            return false;
        }else{
            return $user_msg;
        }


    }

    public static function update($type,$user_openid,$update_num){
        $map = [
            'user_openid'=>$user_openid,
            'is_del' =>0,
        ];
        $user = Db::name('users')->where($map)->find();
        if(!$user){
            self::setError([
                'status_code' =>4055,
                'message' =>'请输入正确用户openid',
            ]);
            return false;

        }
        if($type == 1){
            $data['max_number'] = $update_num;
        }elseif ($type == 2){
            $data['user_prizes'] = $update_num;
        }elseif($type == 3) {
            $data['user_challenges'] = $update_num;
        }else{
            self::setError([
                'status_code' =>4055,
                'message' =>'请填写您要修改的记录',
            ]);
            return false;
        }

        $update = Db::name('users')->where($map)->update($data);
        if($update){
            return true;
        }else{
            self::setError([
                'status_code' =>500,
                'message' =>'服务器忙，请稍后再试',
            ]);
            return false;
        }
    }

}