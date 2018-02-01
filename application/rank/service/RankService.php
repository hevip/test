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
            $list = Db::name('users')->where('is_del',0)->field('user_name,user_icon,user_prizes')->order('user_prizes desc')->limit(100)->select();
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
    public static function challenges_rank(){
        $challenges_rank = Cache::get('challenges_rank');
        if(empty($challenges_rank)){
            $list =  Db::name('users')->where('is_del',0)->field('user_name,user_icon,max_challenges')->order('user_challenges desc')->limit(100)->select();
            if(!$list){
                self::setError([
                    'status_code'=>509,
                    'message'=>'没有排行数据',
                ]);
                return false;
            }
            Cache::set('challenges_rank',$list,3600*24);
            return Cache::get('challenges_rank');
        }else{
            return $challenges_rank;
        }
    }


    //群内排行榜
    public static function group_rank($group_openid){
        $map = ['group_openid' =>$group_openid];
        $user_id = Db::name('group')->where($map)->select();
        if(!$user_id){
            self::setError([
                'status_code' =>4055,
                'message' =>'请输入正确的群openid',
            ]);
            return false;
        }
        foreach($user_id as $k=>$v){
             $list[] =  Db::name('users')->field('user_name,user_icon,max_challenges')
                 ->order('max_challenges desc')->where(array('user_id'=>$v['user_id'],'is_del'=>0))->find();
        }
        if(empty($list)){
            self::setError([
                'status_code' =>509,
                'message' =>'排名数据错误',
            ]);
            return false;
        }else{
            return $list;
        }

    }
}