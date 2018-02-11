<?php
/**
 * Created by PhpStorm.
 * Pay: greatsir
 * Date: 2018/1/26
 * Time: 下午4:22
 */
namespace app\rule\service;


use app\common\service\BaseService;
use think\Db;

class RuleService extends BaseService
{
    /**
     * 规则说明
     */
    public static function ruleDescription()
    {
        $ruleInfo = Db::name('rule')->where('status',1)->select();

        return $ruleInfo;
    }
}