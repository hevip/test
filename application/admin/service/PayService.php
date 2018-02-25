<?php
/**
 * 管理员service
 * User: greatsir
 * Date: 17-6-30
 * Time: 上午11:47
 */
namespace app\admin\service;
use app\common\service\BaseService;

use Firebase\JWT\JWT;
use think\Validate;
use think\Db;
use think\Debug;

class PayService extends BaseService
{
    /**
     * 支付订单列表
     */
    public static function payOderList($data)
    {


        $total = Db::name('pay_orders')->count();

        //页数
        $page = $data['page'];
        if(empty($page)|| $page <= 1){
            $start_page = 1;
        }else{
            $start_page = ($page-1)*10;
        }

        $condition = '';
        //判断未提现、已提现
        switch ($data['is_success']) {
            case 1:
                $condition = [
                    'is_pay' => 1
                ];
                break;
            case 0:
                $condition = [
                    'is_pay' => 0
                ];
                break;
            default:
                self::setError([
                    'status_code'=>500,
                    'message'    =>"Unlawful submission"
                ]);
                return false;
        }

        //是否搜索姓名
        if (isset($data['user_name'])) {
            $user_name = Db::name('users')->where('user_name','like',$data['user_name'])->field('user_id')->find();
            if (!empty($user_name)) {
                $condition['user_id'] = $user_name['user_id'];
            }
        }

        //是否搜索时间
        if (isset($data['start_time']) and isset($data['end_time'])) {

            $showData = Db::name('pay_orders')->alias('p')->join('wj_users u','p.user_id = u.user_id')
                ->where('create_time','between',$data['start_time'].','.$data['end_time'])
                ->where($condition)->order('id desc')->limit($start_page,10)->
                field('u.user_id,u.user_name,p.order_sn,p.pay_money,p.trade_no,p.goods_id,p.id,p.is_pay,p.createtime')->select();
        }else{


            $showData = Db::name('pay_orders')->alias('p')->join('wj_users u','p.user_id = u.user_id')->order('id desc')->where($condition)->limit($start_page,10)
                ->field('p.id,u.user_id,u.user_name,p.order_sn,p.pay_money,p.trade_no,p.goods_id,p.id,p.is_pay,p.createtime')->select();

        }


        if(!$showData){
            self::setError([
                'status_code'=>509,
                'message'=>'没有排行数据',
            ]);
            return false;
        }

        $showData['total'] = $total;

        return $showData;
    }
}