<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2017/12/25
 * Time: 下午9:16
 */

return [
    'use_sandbox'       => false,// 是否使用 微信支付仿真测试系统

    'app_id'            => 'wxccf058a8c4b07245',  // 公众账号IDwxc5b0906ec933e00b
    'mch_id'            => '1493899022',// 商户id1494450052
    'md5_key'           => 'mcUiD517LZWpnn4xuGwNYvWJnD1hnmCd',// md5 秘钥
    'app_cert_pem'      => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR .  'weixin' . DIRECTORY_SEPARATOR . 'appclient_cert.pem',
    'app_key_pem'       => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR .  'weixin' . DIRECTORY_SEPARATOR . 'appclient_key.pem',
    'sign_type'         => 'MD5',// MD5  HMAC-SHA256
    'limit_pay'         => [
        //'no_credit',
    ],// 指定不能使用信用卡支付   不传入，则均可使用
    'fee_type'          => 'CNY',// 货币类型  当前仅支持该字段

    'notify_url'        => 'https://api.cfoeu.cn/pay/success',

    'redirect_url'      => 'http://www.greatsir.com/pay/success',// 如果是h5支付，可以设置该值，返回到指定页面

    'return_raw'        => false,// 在处理回调时，是否直接返回原始数据，默认为true
];