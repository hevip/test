<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 上午10:05
 */

namespace app\address\service;

use app\address\model\address;
use app\common\service\BaseService;
use app\common\model\RoleModule;
use think\Db;

class AddressService extends BaseService
{
    //奖品领取
    public static function receiveGoods($user_id)
    {
        //验证信息
        $validate = validate('address');
        if(!$validate->check($user_id)){
            self::setError(['status_code'=>4004, 'message'=>$validate->getError()]);
            return false;
        }else{
            $res = Db::name('users')->where('user_id',$user_id)->value('user_prizes');
            if(!empty($res)){
                if($res['user_prizes']<5){
                    $msg['message'] = '奖品未达到5个不能领取';
                    return $msg;
                }else{
                    $msg['message'] = '请填写收货信息';
                    return $msg;
                }
            }else{
                self::setError([
                    'status_code'=>4004,
                    'message'    =>'用户不存在'
                ]);
                return false;
            }

        }
    }

    //收货地址填写
    public static function address($data)
    {
        $validate = validate('address');
        if(!$validate->check($data)){
            self::setError(['status_code'=>4004,'message'=>$validate->getError()]);
            return false;
        }else{
            $info['contacts'] = $data['name'];
            $info['mobile'] = $data['tel'];
            $res = Db::name('send_orders')->insert($info);
            if($res){
                $result = Db::name('users')->where('user_id',$data['user_id'])->setField('user_prizes',0);
                if($result){
                    $msg['message'] = '我们将尽快安排发货';
                    return $msg;
                }else{
                    self::setError([
                        'status_code'=>500,
                        'message'    =>'信息设置失败'
                    ]);
                    return false;
                }
            }else{
                self::setError([
                    'status_code'=>500,
                    'message'    =>'信息保存失败'
                ]);
                return false;
            }
        }
    }


}