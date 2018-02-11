<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/8
 * Time: 下午5:20
 */

namespace app\common\controller;


use think\Controller;
use think\Response;

class Version extends Controller
{
    public function getVersion()
    {
        return Response::create([
            'status'=>'success',
            'data'  => [
                'version'=>0
            ]
        ],'json');
    }
}