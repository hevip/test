<?php
namespace app\member\service;

use app\member\model\Member;
use Firebase\JWT\JWT;
use app\common\service\BaseService;
use greatsir\RedisClient;
use Monolog\Processor\MercurialProcessor;

/**
 * Created by PhpStorm.
 * User: greatsir
 * Date: 17-8-11
 * Time: 下午1:58
 */
class MemberService extends BaseService
{
    /**
     * 会员列表
     */
    public static function _list($page)
    {
        if(Validate::is($page,'number')){
            $memberModel = new Member();
            $list =$memberModel->page($page,50)->select()->toArray();
            if($list){
                $data['total'] = $memberModel->count();
                $data['list']  = $list;
                return $data;
            }else{
                self::setError([
                    'status_code'=>500,
                    'message'    =>$memberModel->getError()
                ]);
                return false;
            }
        }else{
            self::setError([
                'status_code'=> 4010,
                'message'    => 'the type of page is wrong'
            ]);
            return false;
        }
    }
    /**
     * @param $map
     * @return bool
     */
    public function checklogin($map)
    {
        $where['mobile'] = $map['account'];
        $where['passwd']= $map['password'];
        $member = new Member();
        $map['is_del'] = 0;
        $res = $member->field('uid,mobile,nickname,face,money,jifen')->where($where)->find();
        if($res){
            //更新数据
            $result = $res->getData();
            $payload['requesterID'] = $result['uid'];
            $payload['identity']    = 'yezhu';
            $payload['exp']         = time()+604800;
            $result['token']        = JWT::encode($payload,'yjlm');
            return $result;
        }else{
            return false;
        }
    }
    public static function create($data)
    {
        //
        $validate = validate('Member');
        if(!$validate->check($data)){
            self::setError([
                'status_code'=>4042,
                'message'    =>$validate->getError()
            ]);
            return false;
        }
        //验证通过添加注册
        $member = new Member();
        $member_data['mobile'] = $data['mobile'];
        $member_data['passwd'] = md5($data['password']);
        //创建时间
        //$member_data['create_time'] = time();
        $res    = $member->save($member_data);
        if($res){
            //把mobile放到set集合里面
            $redis = RedisClient::getHandle();
            $redis->add_set('register_mobiles',$data['mobile']);
            $serv = new self();
            $map['account'] = $member_data['mobile'];
            $map['password']= $member_data['passwd'];
            $memberInfo = $serv->checklogin($map);
            return $memberInfo;
        }else{
            self::setError([
                'status_code'=> 500,
                'message'    => $member->getError()
            ]);
            return false;
        }
    }
}