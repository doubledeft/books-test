<?php

namespace app\modules\order\service;

use app\common\base\ApiService;
use app\modules\order\model\Cart;
use app\modules\order\model\Orders;

class CartService extends ApiService
{
    public function __construct()
    {
        parent::init();
        $this->model=new Cart();
    }

    public function getShopCartInfo($userName){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        if (empty($userInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '用户不存在');
        }
        $list=$this->lists([
            'condition'=>[
                'user_id'=>$userInfo['id']
            ],
            'with'=>['bookInfo']
        ]);
        return $list;
    }

    public function addShopCartGoods($userName,$bookId){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        if (empty($userInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '用户不存在');
        }
        $cartInfo=$this->info([
            'condition'=>[
                'user_id'=>$userInfo['id'],
                'book_id'=>$bookId,
                'status'=>1
            ]
        ]);
        $this->save([
                'user_id'=>$userInfo['id'],
                'book_id'=>$bookId,
                'status'=>1
            ],[
                'user_id',
                'book_id',
                'status'
        ]);
        $cartInfo=$this->info([
            'condition'=>[
                'user_id'=>$userInfo['id'],
                'book_id'=>$bookId,
                'status'=>1
            ]
        ]);
        return $cartInfo;
    }

    public function reduceShopCartGoods($userName,$bookId){
        //查询用户id
        $userInfo=self::callModuleService('user','UserService','info',[
            'condition'=>[
                'username'=>$userName
            ]
        ]);
        if (empty($userInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '用户不存在');
        }
        $cartInfo=$this->info([
            'condition'=>[
                'user_id'=>$userInfo['id'],
                'book_id'=>$bookId
            ]
        ]);
        if (empty($cartInfo)){
            return self::error('ERROR_INVALID_PASSWORD', '删除货物不存在');
        }
        $this->update([
            'status'=>0
        ],[
            'user_id'=>$userInfo['id'],
            'book_id'=>$bookId
        ]);
    }
}