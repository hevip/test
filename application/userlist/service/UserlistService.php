<?php
namespace app\userlist\service;


use app\common\service\BaseService;
use think\Db;

class UserlistService extends BaseService
{
    public static function user($page)
    {
        $res = Db::name('users')->page($page, 10)->where('is_del', 0)->select();
        if ($res) {
            $data['total'] = Db::name('users')->where('is_del', 0)->count();
            $data['res'] = $res;
            return $data;
        } else {
            self::setError(['status_code' => 500, 'message' => '服务器忙，请稍候在试']);
            return false;
        }
    }
}