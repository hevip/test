<?php
namespace app\common\service;
use Payment\Notify\PayNotifyInterface;
use Payment\Config;
use app\common\model\PayOrders;
use think\Db;
use think\Log;

/**
 * @author: helei
 * @createTime: 2016-07-20 18:31
 * @description:
 */

/**
 * 客户端需要继承该接口，并实现这个方法，在其中实现对应的业务逻辑
 * Class TestNotify
 * anthor helei
 */

class TestNotify implements PayNotifyInterface
{
    public function notifyProcess(array $data)
    {
        $channel = $data['channel'];
        if ($channel === Config::ALI_CHARGE) {// 支付宝支付
        } elseif ($channel === Config::WX_CHARGE) {// 微信支付
        } elseif ($channel === Config::CMB_CHARGE) {// 招商支付
        } elseif ($channel === Config::CMB_BIND) {// 招商签约
        } else {
            // 其它类型的通知
        }

        //获取用户信息
        $user_data = Db::name('users')->where('user_openid',$data['buyer_id'])->field('user_id')->find();

        //查询订单
        $order_info = Db::name('pay_orders')->where(['user_id'=>$user_data['user_id'],'order_sn'=> $data['order_no'],'is_pay' => 0])->field('id,order_sn,goods_id,pay_money')->find();
        //不存在订单

        if (empty($order_info)) {
            return false;
        }

        //判断金额是否相同
        if ($order_info['pay_money'] != $data['amount']) {
            return false;
        }
        $goods_info = Db::name('goods')->where('goods_id',$order_info['goods_id'])->find();

        Db::startTrans();
        try{
            //更改状态
            Db::name('pay_orders')->where(['order_sn'=>$data['order_no']])->setField('is_pay',1);
            Db::name('users')->where(['user_id'=>$user_data['user_id']])->setInc('reset_challenges', $goods_info['chances']);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new \think\Exception($e->getMessage(),$e->getCode());
            return false;
        }
    }
}