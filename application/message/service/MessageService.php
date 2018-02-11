<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-7-11
 * Time: 上午10:05
 */

namespace app\message\service;

use app\common\service\BaseService;

use think\Db;

class MessageService extends BaseService
{
    //奖品寄出后的模板消息推送
    public static function sentMessage($data)
    {
        $validate = validate('message');
        if (!$validate->check($data)) {
            self::setError(['status_code' => 4004, 'message' => $validate->getError()]);
            return false;
        } else {
            $info['express_name'] = $data['express_name'];
            $info['express_no'] = $data['express_no'];
            $res = Db::name('send_orders')->where('user_id', $data['user_id'])->update($info);
            if ($res) {
                $info['message'] = '你的奖品已发货请注意查收';
                $info['res'] = $res;
                return $info;
            } else {
                self::setError(['status_code' => 500, 'message' => '服务器忙，请稍候再试']);
                return false;
            }
        }
    }
}