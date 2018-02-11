<?php
/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 2018/2/5
 * Time: 上午9:09
 */
namespace app\goods\service;

use app\common\service\BaseService;
use think\Db;

class GoodsService extends BaseService
{
    /*
     * 商品列表
     */
    public static function Goodslist($page,$goods_name)
    {
        if(empty($goods_name)){
            $res = Db::name('goods')
                ->where('is_del', 0)->page($page, 10)->order('goods_id desc')->select();
            if($res){
                $data['total'] = Db::name('goods')->where('is_del',0)->count();
                $data['list'] = $res;
                return $data;
            }else {
                self::setError(['status_code' => 500, 'message' => '服务器忙，请稍候在试']);
                return false;
            }
        }else{
            $validate = validate('app\goods\validate\GoodsName');
            if(!$validate->check($goods_name)){
                self::setError(['status_code'=>4004,'message'=>$validate->getError()]);
                return false;
            }else{
                $res = Db::name('goods')
                    ->where('goods_title','like','%'.$goods_name['goods_title'].'%')
                    ->where('is_del', 0)
                    ->page($page, 10)
                    ->order('goods_id desc')
                    ->select();
                if ($res){
                    $data['total'] = Db::name('goods')->where('is_del',0)->where('goods_title','like','%'.$goods_name['goods_title'].'%')->count();
                    $data['list'] = $res;
                    return $data;
                }elseif(empty($res)){
                    self::setError(['status_code' => 500, 'message' => '没有找到相关数据']);
                    return false;
                }else{
                    self::setError(['status_code' => 500, 'message' => '服务器忙，请稍候在试']);
                    return false;
                }
            }
        }
    }
    public static function create($data)
    {
        $validate = validate('app\goods\validate\Goods');
        if(!$validate->check($data)){
            self::setError([
                'status_code'=>4117,
                'message'    =>$validate->getError()
            ]);
            return false;
        }
        try{
            $res = Db::name('goods')->insertGetId($data);
            if($res){
                return ['goods_id'=>$res];
            }else{
                self::setError([
                    'status_code'=>500,
                    'message'    =>'网络请求错误，请稍后重试'
                ]);
                return false;
            }
        }catch (\Exception $e){
            throw new \think\Exception($e->getMessage(),$e->getCode());
        }
    }
    public static function read($id)
    {
        $info = Db::name('goods')->where(['goods_id'=>$id])->find();
        if(!empty($info)){
            return $info;
        }else{
            self::setError([
                'status_code'=>404,
                'message'    =>'请求资源不存在'
            ]);
            return false;
        }
    }
    /*
     * 获取
     */
    public static function update($data,$id)
    {
        $validate = validate('app\goods\validate\Goods');
        if(!$validate->check($data)){
            self::setError([
                'status_code'=>4117,
                'message'    =>$validate->getError()
            ]);
            return false;
        }
        if(!self::read($id)){
            return false;
        }
        $res = Db::name('goods')->where(['goods_id'=>$id])->update($data);
        if($res||$res==0){
            return ['up_time'=>time()];
        }else{
            self::setError([
                'status_code'=>500,
                'message'    =>'网络请求失败，请稍后重试'
            ]);
            return false;
        }
    }

    public static function delete($id)
    {
        $map=[
            'goods_id'=>$id,
            'is_del'  =>0,
        ];
        $data = Db::name('goods')->where($map)->find();
        if(empty($data)){
            self::setError([
                'status_code'=>4055,
                'message'=>'请输入正确的商品ID',
            ]);
            return false;
        }else{
            $del = Db::name('goods')->where($map)->update(array('is_del'=>1));
            if($del){
                return true;
            }else{
                self::setError([
                    'status_code'=>500,
                    'message'=>'操作失败，请稍后再试',
                ]);
                return false;
            }
        }
    }

    public static function getAll(){
        $list = Db::name('goods')->where('is_del',0)->select();
        if(empty($list)){
            self::setError([
                'status_code' =>500,
                'message' =>'暂无商品数据',
            ]);
            return false;
        }else{
            return $list;
        }
    }



}