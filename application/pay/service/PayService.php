<?php
/**
 * Created by PhpStorm.
 * Pay: greatsir
 * Date: 2018/1/26
 * Time: 下午4:22
 */
namespace app\pay\service;

use app\common\model\PayOrders;
use app\common\service\BaseService;
use think\Db;
use think\Loader;
use Payment\Common\PayException;
use Payment\Client\Charge;
use Payment\Config;
use Payment\Client\Notify;
use Payment\Notify\PayNotifyInterface;
use app\common\service\TestNotify;
class PayService extends BaseService
{
    /**
     * 微信支付创建
     */
    public static function pay_creat($uid,$data)
    {
        $payModel = new PayOrders();

        //验证post值
        if(!is_numeric($data['goods_id'])){
            self::setError([
                'status_code'=> '500',
                'message'    => 'The commodity number is not a number',
            ]);
            return false;
        }
        //查询商品价格
        $goods_money = Db::name('goods')->where('goods_id',$data['goods_id'])->value('sale_price');


        // 判断是否存在超时订单
        $overtime_data = $payModel->where(['user_id'=>$uid,'is_pay'=>0])->where('endtime','<',time())->field('id,order_sn')->select();

        if (!empty($overtime_data)) {
            foreach ($overtime_data as $k=>$v) {
                $payModel->where(['id'=> $v['id'],'order_sn' => $v['order_sn']])->update(['is_pay' => -1]);
            }
        }

        date_default_timezone_set('Asia/Shanghai');

        $order_sn ='WJ'.date('YmdHis').rand(1000,9999);
        //数据准备
        $save_data = [
            'user_id'   => $uid,
            'order_sn'  => $order_sn,
            'pay_money' => $goods_money,
            'goods_id'  => $data['goods_id'],
            'is_pay'    => 0,
            'createtime'=> time(),
            'endtime'   => time()+600,
        ];

        //储存数据
        $list = $payModel::savePayOrder($save_data);


        if (!$list['status_code']) {
            self::setError([
                'status_code'=> '500',
                'message'    => 'Add data failure'
            ]);
            return false;
        }

        //获取openid
        $user_data = Db::name('users')->where('user_id',$uid)->field('user_openid')->find();
        $openid = $user_data['user_openid'];

        //统一下单
        $wxConfig = config('wxpay');
        $payData = [
            'body'    => '拜年智力',
            'subject'    => '微聚',
            'order_no'    => $order_sn,
            'timeout_express' => time() + 600,// 表示必须 600s 内付款
            'amount'    =>$goods_money,// 微信沙箱模式，需要金额固定为3.01
            'return_param' => '123',
            'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
            'openid' => $openid,
            'product_id' => '123',
        ];

        try {
            $ret = Charge::run(Config::WX_CHANNEL_LITE, $wxConfig, $payData);
            return $ret;
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }



    }
    /**
     * 支付成功回调
     */
    public static function pay_success()
    {
        //回调验证
        $wxConfig = config('wxpay');
        $callback = new TestNotify();
        $config = $wxConfig;
        $type = 'wx_charge';
        try {
            //$retData = Notify::getNotifyData($type, $config);// 获取第三方的原始数据，未进行签名检查
            $ret = Notify::run($type, $config, $callback);// 处理回调，内部进行了签名检查
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }


}