<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/1
 * Time: 下午5:13
 */

namespace app\challenge\service;


use app\common\service\BaseService;
use app\users\model\User;
use greatsir\RedisClient;

class RankService extends BaseService
{
    public static function getYesterDay($date)
    {
        try{
            //从redis里面取出集合
            $redis = RedisClient::getHandle();
            $res   = $redis->getKey('iqRanklist:'.$date);
            if(empty($res)){
                //查询出来
                $uids = $redis->declineZset('iqRank:'.$date,0,5);
                $where['user_id'] = array('in',$uids);
                $user = new User();
                $users = $user->where($where)->select();
                $res = json_encode($users);
                $redis->setKey('iqRanklist:'.$date,$res);
            }
            return json_decode($res);
        }catch (\Exception $e){
            throw new \think\Exception($e->getMessage(),$e->getCode());
        }

    }
}