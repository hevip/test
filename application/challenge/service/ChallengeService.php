<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/26
 * Time: 下午4:51
 */
namespace app\challenge\service;

use app\common\service\BaseService;
use app\users\service\UserService;
use greatsir\RedisClient;
use greatsir\Snowflake;
use think\Db;

class ChallengeService extends BaseService
{
    /*
     * 处理结果
     */
    public static function Settlement($data,$uid)
    {
        $validate = validate('Settlement');
        if(!$validate->check($data)){
            self::setError([
                'status_code'=>'4101',
                'message'    =>$validate->getError()
            ]);
            return false;
        }
        //校验成功
        if($userInfo = UserService::getUserInfo($uid)){
            //用户信息获取
            if($userInfo['reset_challenges']==0){
                self::setError([
                    'status_code'=>4112,
                    'message'    =>'您没有了挑战机会'
                ]);
                return false;
            }
            //获取明文
            $clearData = rsaDecryptData($data['_sign']);
            if(!$clearData){
                self::setError([
                    'status_code'=>4111,
                    'message'    =>'请求无效，系统识别为非法请求'
                ]);
                return false;
            }
            if($checkRes = self::checkRequest($clearData)){
                //请求合法,根据status的不同在执行不同的逻辑
                if($checkRes['status']){
                    return self::ChallengeSucess($uid,$checkRes);
                }else{
                    return self::ChallengeFailed($uid,$checkRes);
                }
            }else{
                self::setError([
                    'status_code'=>4111,
                    'message'    =>'请求无效，系统识别为非法请求'
                ]);
                return false;
            }
            //根据明文信息、执行相关逻辑
        }

    }
    /*
     * 挑战成功
     */
    public static function ChallengeSucess($uid,$data)
    {
        Db::startTrans();
        try{
            /*
             * 挑战次数加一，娃娃数量加一,剩余机会减一
             */
            $user_prizeData = [];
            $user_prizeData['user_id'] = $uid;
            $user_prizeData['prize_no']= Snowflake::generateParticle(2);//业务字段
            $user_prizeData['add_time']= date('Y-m-d',time());
            $logData = [];
            $logData['user_id'] = $uid;
            $logData['end_number'] = 500;
            $logData['challenge_status'] = 1;
            $logData['challenge_time']   = time();
            $where['user_id'] = $uid;
            $where['is_del']  = 0;
            Db::name('users')->where($where)->setInc('user_challenges',1);
            Db::name('users')->where($where)->setInc('user_prizes',1);
            Db::name('users')->where($where)->setDec('reset_challenges',1);
            Db::name('users')->where($where)->setField('max_number',500);
            Db::name('users_prizes')->insert($user_prizeData);
            Db::name('challenge_log')->insert($logData);//添加挑战记录
            if($data['gid']){
                $groupData['user_id'] =$uid;
                $groupData['opengid'] = $data['gid'];
                Db::name('group')->insert($groupData);
            }

            Db::commit();
            $redis = RedisClient::getHandle();
            //当天的iqRank zset集合某user中增1
            $redis->zsetIncrby('iqRank:'.date('Y-m-d',time()),1,$uid);
            //用户挑战榜加一次
            $redis->zsetIncrby('userChallenge',1,$uid);
            //群内集合增加
            if($data['gid']){
                $redis->zsetAdd('group:'.$data['gid'],500,$uid);
            }
            return [
                'challenge_time'=>time(),
                'challenge_res' =>1,
                'reset_challenges'=>Db::name('users')->where($where)->value('reset_challenges')
            ];
        }catch (\Exception $e){
            Db::rollback();
            throw new \think\Exception($e->getMessage(),$e->getCode());
        }
    }
    /*
     * 挑战失败
     */
    public static function ChallengeFailed($uid,$data)
    {
        $where['user_id'] = $uid;
        $where['is_del']  = 0;
        $history = Db::name('users')->where($where)->value('max_number');
        $max_number = $history>$data['end']?$history:$data['end'];
        Db::startTrans();
        try{
            /*
             * 挑战次数加一、剩余挑战次数减一
             */
            $logData = [];
            $logData['user_id'] = $uid;
            $logData['end_number'] = $data['end'];
            $logData['challenge_status'] = 0;
            $logData['challenge_time']   = time();
            Db::name('users')->where($where)->setInc('user_challenges',1);
            Db::name('users')->where($where)->setDec('reset_challenges',1);
            Db::name('users')->where($where)->setField('max_number',$max_number);
            Db::name('challenge_log')->insert($logData);//添加挑战记录
            if($data['gid']){
                //
                $groupData['user_id'] =$uid;
                $groupData['opengid'] = $data['gid'];
                Db::name('group')->insert($groupData);
            }
            Db::commit();
            $redis = RedisClient::getHandle();
            //用户挑战榜加一次
            $redis->zsetIncrby('userChallenge',1,$uid);
            //群内集合增加
            if($data['gid']){
                $redis->zsetAdd('group:'.$data['gid'],$max_number,$uid);
            }
            return [
                'challenge_time'=>time(),
                'challenge_res' =>0,
                'reset_challenges'=>Db::name('users')->where($where)->value('reset_challenges')
                ];
        }catch (\Exception $e){
            Db::rollback();
            throw new \think\Exception($e->getMessage(),$e->getCode());
        }
    }
    /*
     * 检测请求合法性
     * $data = 'end=500&gid=0&openid=123123&status=1&timestamp=1564782&sign=';
     * @params str $data;解密后的明文包含业务字段m(status timestamp openid) sign
     * step_one:用客户端public_key 解密sign，拿到消息摘要h(m),然后计算明文中的m hash之后的str是否和解密后的
     * 消息摘要一致，
     * 若一致则视为请求合法，否则视为非法请求
     * return boolean $check_res;
     */
    public static function checkRequest($data)
    {
        /*
         * 验证逻辑
         * 参数 status,gid ,timestamp openid end  sign
         * 时间戳
         * rsa签名
         */
        //根据参数校验签名是否正确，如果正确则返回true,否则返回false
        $a1 = explode('&',$data);
        $key = [];
        $value= [];
        foreach ($a1 as $v){
            $p = strpos($v,'=');
            $k = substr($v,0,$p);
            $v1 = substr($v,$p+1);
            array_push($key,$k);
            array_push($value,$v1);

        }
        $params = array_combine($key,$value);
        //获取签名
        $sign = $params['sign'];
        unset($params['sign']);
        $str  = http_build_query($params);
        $public_key = config('client_rsa_public_key');//获取客户端公钥
        /*foreach (str_split(base64_decode($sign), 128) as $chunk) {

            openssl_public_decrypt($chunk, $decryptData, $public_key);

            $h_str2 .= $decryptData;
        }*/
        $check_res = openssl_verify($str, base64_decode($sign), $public_key);
        //opcd enssl_public_decrypt(base64_decode($sign),$h_str2,$public_key);
        if($check_res){
           $check_res = $params;
        }else{
            $check_res = false;
        }
        return $check_res;
    }
}