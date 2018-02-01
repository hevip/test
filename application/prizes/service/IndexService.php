<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-29
 * Time: 16:17
 */

namespace app\prizes\service;
use app\common\service\BaseService;
use think\Db;
class IndexService extends BaseService
{
    public static function prizes_list(){
        if(Db::name('prizes')->count() < 1){
            self::setError([
                'status_code'=>509,
                'message'    =>'数据库暂无数据',
            ]);
            return false;
        }
        $list = Db::name('prizes')->where('is_del',0)->select();
        if(!$list){
            self::setError([
                'status_code'=>509,
                'message'    =>'奖品数据错误',
            ]);
            return false;
        }else{
            return $list;
        }

    }
}