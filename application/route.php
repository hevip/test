<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    //资源路由
    '__rest__'=>[
        
        'admins'=>'admin/Admin',
        'modules'=>'admin/Module',
        'articles'=>'article/Article',
        'roles'  =>'admin/Role',
        'articleCate'=>'article/ArticleCate',
    	'appbtns'=>'system/SystemAppbtn',
        'villages'=>'village/Village',
		'property'=>'property/Property',
		'operate'=>'operate/AdvPosition',
        'topics' => 'forum/Topic',
        'modeler' => 'vgirl/Modeler',
        'album' => 'vgirl/Album',
        'album-cate' => 'vgirl/AlbumCate',
        'member' => 'vgirl/Member',
        'member-focus' => 'vgirl/MembFocus',
        'member-album' => 'vgirl/MembAlbum',
        'pic' => 'vgirl/Pic',
        'goods'=>'goods/Goods'

    ],
    
    /**
     * 普通路由
     */
    'address/goods'=>'address/address/goods',
    'address/address'=>'address/address/address',
    'address/index'=>'address/address/index',
    'message/message'=>'message/message/message',
    'userlist/index'=>'userlist/userlist/index',
    'goods/list'=>'goods/goods/index',

    'blank/index'=>'blank/blank/index',
    'blank/add'=>'blank/blank/add',
    'card/add'=>'blank/card/add',
    'card/index'=>'blank/card/index',


    'user/info'=>'rank/Rank/user_msg',
    'advertisement/add'=>'advertisement/Advertisement/add',
    'advertisement/del'=>'advertisement/Advertisement/del',
    'advertisement/update'=>'advertisement/Advertisement/update',
    'prizes/del' =>'prizes/Prizes/del',
    'prizes/add' =>'prizes/Prizes/add',
    'push/advertisement' =>'advertisement/Advertisement/index',
    'shop/prizes' =>'prizes/Index/index',
    'rank/prizes_rank' =>'rank/Rank/prizes_rank',
    'rank/challenges_rank' =>'rank/Rank/challenges_rank',
    'rank/group_rank'=>'rank/Rank/group_rank',
    'notices/getTopNotice'=>'system/Notice/getTopNotice',
    //功能模块路由
    'modules/delete'=>'admin/Module/delete',
    'modules/create'=>'admin/Module/create',
    //角色模块路由
    'roles/create' =>'admin/Role/create',
    'roles/page/:page'   =>['admin/Role/index',['method'=>'get'],['page'=>'\d+']],
    'roles/search'=>'admin/Role/search',
    //授权登录路由
    'auth'   =>'common/Auth/createToken',
    'admins/create'=>'admin/Admin/create',
    //article的分页路由，整合在了article restful路由的index方法上
    'articles/giveUp/:id'=>['article/Article/giveUp',['method'=>'get'],['id'=>'\d+']],
    'articles/commentArticle/:id'=>['article/Article/commentArticle',['method'=>'post'],['id'=>'\d+']],
    'article/page/:page' => ['article/Article/index',['method' => 'get'],['page' => '\d+']],
    'articlecomments/replayComment/:cid' =>['article/ArticleComment/replayComment',['method'=>'post'],['cid'=>'\d+']],
    'articles/getComments/:id'=>['article/Article/getComments',['method'=>'get'],['id'=>'\d+']],
    //组织机构
    'org/tree'=>'system/Org/getTree',
    'orgs/pid/[:pid]' =>['system/Org/index',['method'=>'get'],['pid'=>'\d+']],
    'org/getAllList/[:pid]' => ['system/org/getAllList',['method' => 'get'],['pid' => '\d+']],
    'org/getChildList/[:pid]' => ['system/org/getChildList',['method' => 'get'],['pid' => '\d+']],
    'mytree'=>'admin/Module/testJson',
    //获取文章分类列表
    'articlecates'=>'article/ArticleCate/index',
    'get-article-cate/all/[:id]' => ['article/ArticleCate/getAllList',['method' => 'get'],['id' => '\d+']],
    'get-article-cate/child/[:id]' => ['article/ArticleCate/getChildList',['method' => 'get'],['id' => '\d+']],

    'articleCates/all/[:id]' => ['article/ArticleCate/getAllList',['method' => 'get'],['id' => '\d+']],
    'articleCates/child/[:id]' => ['article/ArticleCate/getChildList',['method' => 'get'],['id' => '\d+']],
    'articleCates/getArticleCateByVillage/:vid'=>['article/ArticleCate/getArticleCateByVillage',['method'=>'get'],['vid'=>'\d+']],
    //小区模块路由
    'villages/create'=>'village/Village/create',
    'villages/getNearVillage'=>'village/Village/getNearVillage',
    //APP按钮模块
    'appbtns/getAppBtnByVillage/:id'=>['system/SystemAppbtn/getAppBtnByVillage',['method'=>'get'],['id'=>'\d+']],
    'appbtns/getFourmBtnByVillage/:id'=>['system/SystemAppbtn/getFourmBtnByVillage',['method'=>'get'],['id'=>'\d+']],
    //文章列表
    //'articles/getArticleListByCate'=>'article/Article/getArticleListByCate',
    'articles/getArticleListByCate/:id/:page/[:vid]'=>['article/Article/getArticleListByCate',['method'=>'get'],['id'=>'\d+','page'=>'\d+','vid'=>'\d+']],
    //开放平台
    //'open/clients/checkClient/:cid'=>['open/Client/checkClient',['menthod'=>'post'],['cid'=>'\d+']],
    //'open/authorize'=>'open/Authorize/index'
    //业主
    'yezhus/test'=>'village/Yezhu/test',
    'yezhus/create'=>'village/Yezhu/create',
    'yezhus/getYezhuByMember/:uid'=>['village/Yezhu/getYezhuByMember',['method'=>'get'],['uid'=>'\d+']],
    //广告模块
    'advs/getV1cAdvByVillage/:id'=>['operate/AdvItems/getV1cAdvByVillage',['method'=>'get'],['id'=>'\d+']],
    'advs/getV1sAdvByVillage/:id'=>['operate/AdvItems/getV1sAdvByVillage',['method'=>'get'],['id'=>'\d+']],
    //开发者
    'developer/register'=>'open/Developer/register',
    //短信接口
    'sms/registerCode'=>'commpont/Sms/registerCode',
    //会员
    'members/create'  =>'member/Member/create',
    //互动
    'topics'=>'',
    'posts/getPosts/:page/[:topic_id]'=>['forum/Posts/getPosts',['method'=>'get'],['page'=>'\d+','topic_id'=>'\d+']],
    'posts/:page'=>['forum/Posts/index',['method'=>'get'],['page'=>'\d+']],
    'posts/create'=>'forum/Posts/create',
    'posts/read/:id'=>['forum/Posts/read',['method'=>'get'],['id'=>'\d+']],
    //vgirlup
    'qiniu/token/:index'=>['commpont/Qiniu/getToken',['method'=>'get'],['index'=>'\d+']],
    'modeler/:page/page' => ['vgirl/Modeler/index',['method' => 'get'],['page' => '\d+']],
    
    'qiniu/downloadtoken'=>'commpont/Qiniu/getDownloadUrl',
    'modeler/search'=>'vgirl/Modeler/search',
    
    'albums/create'=>'vgirl/Album/created',
    'album/page/:page' => ['vgirl/Album/indexed',['method' => 'get'],['page' => '\d+']],
    'album/:page/page' => ['vgirl/Album/index',['method' => 'get'],['page' => '\d+']],
    'album/cate/:id/page/:page'=>['vgirl/Album/getAlbumByCate',['method'=>'get'],['id'=>'\d+','page'=>'\d+']],
    'album/model/:id/page/:page'=>['vgirl/Album/getAlbumByModel',['method'=>'get'],['id'=>'\d+','page'=>'\d+']],
    'album/hot'=>'vgirl/Album/getHot',//获取热门推荐
    'album/new'=>'vgirl/Album/getNew',//获取最新
    'modeler/new-model'=>'vgirl/Modeler/getNewModel',//获取最新
    'album/getAlbumPic/:aid'=>['vgirl/Album/getAlbumPic',['method'=>'get'],['aid'=>'\d+']],
    'album/getrand'=>'vgirl/Album/getRand',
    'album/setHot'=>'vgirl/Album/setHot',
    'albumCates/getCatelist'=>'vgirl/AlbumCate/indexed',
    'albumCates/create'=>'vgirl/AlbumCate/created',
    'carouse/page/:page'=>['vgirl/System/carouselList',['method'=>'get'],['page'=>'\d+']],
    'carouse/create'=>'vgirl/System/createCarouse',
    'carouse/getCarouseMain'=>'vgirl/System/getCarouseMain',
    //微信支付宝回调地址路由，待修改器
    'zhifubao-weixin-huidiao-dizhi'=> 'vgirl/Member/addVIP',

    /**排行榜**/
    'getMemRank' => 'vgirl/RankService/getMemRank',
    'getModelerRank' => 'vgirl/RankService/getModelerRank',
    'getAlbumRank' => 'vgirl/RankService/getAlbumRank',

    /**用户关注模特，收藏专辑接口口**/
    'memb-modeler/:id' => ['vgirl/MembFocus/focus',['method'=>'get'],['id'=>'\d+']],
    'memb-album/:id' => ['vgirl/MembAlbum/focus',['method'=>'get'],['id'=>'\d+']],
    'auth/callback'=>'common/Auth/callback',
    'member/getUserInfo'=>'vgirl/Member/getMyInfo',
    'vips/getVipType'=>'vgirl/Buy/getBuyList',
    'order/pay'=>'vgirl/Buy/pay',
    'pay/callback/alipay'=>'vgirl/PayNotice/ali_callback',
    'pay/callback/wxpay'=>'vgirl/PayNotice/wx_callback',
    'members/getFocus/:page'=>['vgirl/MembFocus/getFocus',['method'=>'get'],['page'=>'\d+']],
    'members/album/getFocus/:page'=>['vgirl/MembAlbum/getFocus',['method'=>'get'],['page'=>'\d+']],
    /**游戏结算**/
    'challenge/deal'=>'challenge/Challenge/deal',//游戏结算接口
    'share/callback'=>'share/Share/shareCallback',//分享到群接口
    'challenge/total' => 'challenge/Challenge/challengeTotal',
    'pay/create' => 'pay/Pay/pay_create',
    'pay/success' => 'pay/PayBack/pay_success',
    'rule/description' => 'rule/Rule/ruleDescription',
    'admin/add_rule' => 'admin/Rule/addRule',
    'admin/del_rule'=>'admin/Rule/delRule',
    'user/info'=>'rank/Rank/user_msg',
    'goods/create'=>'goods/Goods/create',
    'goods/:id'=>['goods/Goods/update',['method'=>'post'],['id'=>'\d+']],//商品更新
    'open/wechat/getopenid'=>'open/Wechat/getOpenid',
    'users/upinfo'=>'users/User/getUserInfo',
    'share/getopengid'=>'share/Share/getOpenGid',
    'goods/del'=>'goods/Goods/delete',
    'goods/getAll'=>'goods/Goods/getAll',
    'admin/payList' => 'admin/Pay/payOderList',
    'getVersion'=>'common/Version/getVersion',
    /*微信杜总*/
    'addlink1'=>'blank/Link/addlink1',
    'addlink2'=>'blank/Link/addlink2',
    'getlink1'=>'blank/Blank/getLink1',
    'getlink2'=>'blank/Blank/getLink2'
];
