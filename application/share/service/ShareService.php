<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/1/26
 * Time: 下午4:36
 */
namespace app\share\service;


use app\common\service\BaseService;
use app\share\model\ShareLog;
use app\users\service\UserService;
use greatsir\RedisClient;
use greatsir\wechat\WXBizDataCrypt;
use think\Db;

class ShareService extends BaseService
{
    /*
     * 分享到微信群
     * @params str $gid群id
     * @params str $uid 用户id
     */
    public static function ShareTowgroup($data,$uid)
    {
        //
        $validate = validate('ShareLog');
        if(!$validate->check($data)){
            self::setError([
                'status_code'=>'4102',
                'message'    =>$validate->getError()
            ]);
            return false;
        }
        $shareLog = new ShareLog();
        $res = $shareLog->save([
            'opengid' => $data['openGId'],
            'user_id' => $uid,
            'share_time' => time()
        ]);
        $groupData['user_id'] =$uid;
        $groupData['opengid'] = $data['openGId'];
        $res2 = Db::name('group')->insert($groupData);
        if($res&&$res2){
            try{
                //每天分享至不同的群则加一次挑战机会
                //判断今天是否分享到该群
                $redis = RedisClient::getHandle();
                //判断今天是否分享key为某用户分享的群id,ShareGroup
                $key = 'ShareGroup:'.date('Y-m-d',time()).':'.$uid;
                if($redis->keyExists($key)){
                    //如果存在,直接判定，无需设置过期时间
                    if(!$redis->in_set($key,$data['openGId'])&&$redis->getSetCount($key)<5){
                        //群不在这里并且不超过5次，则挑战次数加1
                        Db::name('users')->where([
                            'user_id'=>$uid,
                            'is_del' =>0
                        ])->setInc('reset_challenges',1);
                        $redis->add_set($key,$data['openGId']);
                    }
                }else{
                    //不存在
                    $redis->add_set($key,$data['openGId']);
                    //设置过期时间
                    $expire = strtotime(date('Y-m-d',strtotime('+1 day')))-time();
                    $redis->keyexpire($key,$expire);
                    Db::name('users')->where([
                        'user_id'=>$uid,
                        'is_del' =>0
                    ])->setInc('reset_challenges',1);
                }
                $max_number = Db::name('users')->where([
                    'user_id'=>$uid
                ])->value('max_number');
                $redis->zsetAdd('group:'.$data['openGId'],$max_number,$uid);
                return ['share_time'=>time(),
                    'reset_challenges'=>Db::name('users')->where([
                        'user_id'=>$uid,
                        'is_del' =>0
                    ])->value('reset_challenges')
                    ];
            }catch (\Exception $e){
                throw new \think\Exception($e->getMessage(),$e->getCode());
            }

        }else{
            self::setError([
                'status_code'=>500,
                'message'    =>'网络请求失败，请稍后重试'
            ]);
            return false;
        }

    }

    public static function getOpenGid($data,$uid)
    {
        try{
            $appid = config('wechatapp_id');
            $userInfo = UserService::read($uid);
            if(!$userInfo){
                self::setError(UserService::getError());
                return false;
            }
            $openid = $userInfo['user_openid'];
            $redis = RedisClient::getHandle();
            $session_key = $redis->getKey('openid:'.$openid);
            //
            $pc = new WXBizDataCrypt($appid,$session_key);
            $errcode = $pc->decryptData($data['encryptedData'],$data['iv'],$newData);
            if($errcode==0){
                //解密成功
                $newData = json_decode($newData);
                return ['opengid'=>$newData->openGId];
            }else{
                self::setError([
                    'status_code'=>$errcode,
                    'message'    =>'数据校验失败'
                ]);
                return false;
            }
        }catch (\Exception $e){
            throw new \think\Exception($e->getMessage(),$e->getCode());
        }
    }

}