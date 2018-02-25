<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-02-23
 * Time: 10:44
 */
namespace app\loan\service;

use app\common\service\BaseService;
use think\Db;
class LoanService extends BaseService
{


    //贷款列表
    public static function loan_list($page,$search,$key){
        $search = json_decode($search,true);
        if(empty($page) || $page < 1){
            $page = 0;
        }else{
            $page = ($page-1) * 10;
        }
        $search['is_del'] = 0;

        $field='loan_id,title,limit,desc,rate,success,photo';
        if(!empty($key)){
            $data = Db::name('loan')->where($search)->where('title','like','%'.$key.'%')->field($field)->select();
            $data['count'] = Db::name('loan')->where($search)->where('title','like','%'.$key.'%')->count();
        }else{
            $data = Db::name('loan')->where($search)->limit($page,10)->field($field)->select();
            $data['count'] = Db::name('loan')->where($search)->count();
        }
        if($data){
            foreach ($data as $k){
                $datas[]= $k;
            }
            return ($datas);
        }else{
            self::setError(['status_code' => 500, 'message' => '没有数据']);
            return false;
        }
    }



    //贷款详情页
    public static function detail($loan_id){
        $map=[
            'loan_id'=>$loan_id,
            'is_del' =>0
        ];
        $data = self::loan($loan_id);
        $data['detail'] = Db::name('loan_detail')->where($map)->value('content');
        if(!$data['detail']){
            self::setError(['status_code' => 4055, 'message' => '请输入正确ID']);
            return false;
        }else{
            return $data;
        }
    }

    //同类贷款
    public static function similar($loan_id){
        $map = [
            'loan_id'=>$loan_id,
            'is_del' =>0,
        ];
        $field='loan_id,title,limit,desc,rate,success,photo';
        $loan = Db::name('loan')->where($map)->find();
        $map['class'] = $loan['class'];
        $map['limit_class'] = $loan['limit_class'];
        $data = Db::name('loan')->where($map)->field($field)->limit(3)->select();
        if($data){
            return $data;
        }else{
            return '没有相同';
        }
    }

    //后台列表
    public static function admin_list($page,$search){
        if(empty($page) || $page < 1 ){
            $page = 0;
        }else{
            $page = ($page-1) * 10;
        }
        $where = [
            'is_del'=>0,
        ];
        if(!empty($search)){
            $data = Db::name('loan')->where($where)->where('title','like','%'.$search.'%')->select();
            $data['count'] = Db::name('loan')->where($where)->where('title','like','%'.$search.'%')->count();
        }else{
            $data = Db::name('loan')->where($where)->limit($page,10)->select();
            $data['count'] = Db::name('loan')->where($where)->count();
        }
        if($data){
            foreach ($data as $k){
                $datas[]= $k;
            }
            return $datas;
        }else{
            self::setError(['status_code' => 500, 'message' => '没有数据']);
            return false;
        }
    }


    //添加
    public static function admin_add($data,$detail){
        $msg = '';
        $data = json_decode($data,true);
        if(empty($data['photo'])){$msg =  '请上传照片';}
        if(empty($data['title'])){$msg =  '请输入名称';}
        if(empty($data['desc'])) {$msg = '请输入描述';}
        if(empty($data['rate'])){ $msg = '请输入费率';}
        if(empty($data['success'])){$msg = '请输入成功率';}
        if(!is_numeric($data['class']) || !is_numeric($data['limit_class'])){
            $msg = '请输入正确的分类';
        }
        if(empty($data['class'])){$msg = '请选择商户类型';}
        if(empty($data['limit_class'])){$msg = '请选择额度分类';}
        if(!empty($msg)){
            self::setError(['status_code' => 4055, 'message' => $msg]);
            return false;

        }
        $loan_id = Db::name('loan')->insertGetId($data);
        if($loan_id){
            if(!empty($detail)){
                $details['content'] = $detail;
                $details['loan_id'] = $loan_id;
                $add = Db::name('loan_detail')->insert($details);
            }
            if($loan_id && $add){
                return true;
            }else{
                self::setError(['status_code' => 500, 'message' => '服务器忙']);
                return false;
            }
        }else{
            self::setError(['status_code' => 500, 'message' => '服务器忙']);
            return false;
        }
    }

    //后台删除
    public static function admin_del($loan_id){
        $loan = self::loan($loan_id);
        $map = ['loan_id'=>$loan_id,'is_del'=>0];
        $del = Db::name('loan')->where($map)->update(['is_del'=>1]);
        if($del){
            Db::name('loan_detail')->where($map)->update(['is_del'=>1]);
            return true;
        }else{
            self::setError(['status_code' => 500, 'message' => '服务器忙']);
            return false;
        }
    }


    public static function admin_update($loan_id,$data,$detail){
        $loan = self::loan($loan_id);
        $map = ['loan_id'=>$loan_id,'is_del'=>0];
        $msg = '';
        if(empty($data['photo'])){$msg =  '请上传照片';}
        if(empty($data['title'])){$msg ='请输入名称';}
        if(empty($data['desc'])){$msg ='请输入描述';}
        if(empty($data['rate'])){$msg= '请输入费率';}
        if(empty($data['success'])){$msg = '请输入成功率';}
        if(!is_numeric($data['class'])  || !is_numeric($data['limit_class'])){
            $msg = '请输入正确的分类';
        }
        if(empty($data['class'])){$msg = '请选择商户类型';}
        if(empty($data['limit_class'])){$msg = '请选择额度分类';}
        if(!empty($msg)){
            self::setError(['status_code' => 4055, 'message' => $msg]);
            return false;
        }
        $update = Db::name('loan')->where($map)->update($data);
        if(!empty($detail) || $detail != 0 ){
            $re  = Db::name('loan_detail')->where($map)->find();
            if(!$re){
                self::setError(['status_code' => 500, 'message' => '数据错误']);
                return false;
            }
            $detas['content'] = $detail;
            $detas['loan_id'] =$loan_id;
            $details = Db::name('loan_detail')->where($map)->update($detas);
        }

        if($update || $details){
            return true;
        }else{
            self::setError(['status_code' => 500, 'message' => '服务器忙']);
            return false;
        }
    }



    //该商户是否存在
    public static function loan($loan_id){
        $map = ['loan_id'=>$loan_id,'is_del'=>0];
        $loan = Db::name('loan')->where($map)->find();
        if(!$loan){
            self::setError(['status_code' => 4055, 'message' => '请输入正确的商户ID']);
            return false;
        }else{
            return $loan;
        }
    }
}