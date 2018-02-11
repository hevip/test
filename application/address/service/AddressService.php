<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 上午10:05
 */

namespace app\address\service;

//use app\address\model\address;
use app\common\service\BaseService;
//use app\common\model\RoleModule;
use greatsir\Snowflake;
use think\Db;

class AddressService extends BaseService
{
    //奖品领取
    public static function receiveGoods($data)
    {
        //验证信息
        $validate = validate('app\address\validate\Address');
        if(!$validate->check($data)){
            self::setError(['status_code'=>4004, 'message'=>$validate->getError()]);
            return false;
        }else{
            $res = Db::name('users')->where('user_id',$data['user_id'])->value('user_prizes');
            if(!empty($res)){
                if($res['user_prizes']<5){
                    self::setError(['status_code'=>4005,'message' =>'奖品数量不足']);
                    return false;
                }else{
                    $msg['message'] = '请填写收货信息';
                    $msg['status'] = 1;
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
    //收货地址显示
    public static function index()
    {
        $res = Db::name('address')->select();
        if(empty($res)){
            $msg = '暂无数据';
            return $msg;
        }elseif($res){
            return $res;
        }else{
            self::setError([
                'status_code'=>500,
                'message'    =>'服务器忙，请稍候再试'
            ]);
            return false;
        }
    }
    //收货地址填写
    public static function address($data)
    {
        $validate = validate('address');
        if(!$validate->check($data)){
            self::setError(['status_code'=>4004,'message'=>$validate->getError()]);
            return false;
        }
            /*$info['contacts'] = $data['contacts'];
            $info['mobile'] = $data['mobile'];
            $info['province'] = $data['province'];
            $info['city'] = $data['city'];
            $info['country'] = $data['country'];
            $info['address'] = $data['address'];
            $info['user_id'] = $data['user_id'];
            $info['prizes'] = $data['prizes'];
            $info['c_time'] = time();*/
        $e = Db::name('users')->where('user_id',$data['user_id'])->field('user_prizes')->find();
        if($data['prizes'] > $e['user_prizes']||$data['prizes']<1)
        {
            self::setError([
                'status_code'=>4004,
                'message'    =>'请输入正确的奖品数量'
            ]);
            return false;
        }
           /* else{
                $res = Db::name('address')->insert($info);
                if($res){
                    $a = $e['user_prizes'] - $data['prizes'];
                    $result = Db::name('users')
                        ->where('user_id',$data['user_id'])
                        ->setField('user_prizes',$a);
                    if($result){
                        $msg['message'] = '我们将尽快安排发货';
                        $msg['status'] = 1;
                        return $msg;
                    }else{
                        $msg['message'] = '我们将尽快安排发货';
                        $msg['status'] = 0;
                        return $msg;
                    }
                }else{
                    self::setError([
                        'status_code'=>500,
                        'message'    =>'服务器忙，请稍候再试'
                    ]);
                    return false;
                }
            }*/
        $info['c_time'] = time();
        $info['contacts'] = $data['contacts'];
        $info['mobile'] = $data['mobile'];
        $info['province'] = $data['province'];
        $info['city'] = $data['city'];
        $info['country'] = $data['country'];
        $info['address'] = $data['address'];
        $info['user_id'] = $data['user_id'];
        $info['prize_number'] = $data['prizes'];
        $info['sorder_no'] = Snowflake::generateParticle(2);
       //插入配送订单表
        Db::startTrans();
        try{
            Db::name('send_orders')->insert($info);
            Db::name('users')->where(['user_id'=>$data['user_id']])->setDec('user_prizes',$data['prizes']);
            Db::commit();
            return ['sorder_no'=>$info['sorder_no']];
        }catch (\Exception $e){
            Db::rollback();
            throw new \think\Exception($e->getMessage(),$e->getCode());
            return false;
        }
    }

}