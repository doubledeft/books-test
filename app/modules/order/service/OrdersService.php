<?php

namespace app\modules\order\service;

use app\common\base\ApiService;
use app\common\Consts;
use app\modules\order\model\Orders;
use app\modules\user\models\Account;

class OrdersService extends ApiService
{
    public function __construct()
    {
        parent::init();
        $this->model=new Orders();
    }

    //获取用户购物车信息
    public function getShopCartInfo($userId){
        $orderInfo=$this->info([
            'condition'=>[
                'user_id'=>$userId,
                'status'=>Consts::ORDER_IN_CART
            ]
        ]);
        if (empty($orderInfo)){
            return self::error('ERROR_INVALID_USERID', '用户购物车为空');
        }
        //查询用户购物车内容
        $orderDetailList=$this->lists([
            'condition'=>[
                'order_id'=>$orderInfo['order_id'],
                'user_id'=>$userId
            ]
        ]);
        return $orderDetailList;
    }

    //添加购物车内的商品
    public function addShopCartGoods($userId,$bookId){
        $bookInfo=self::callModuleService('book','BookInfoService','info',[
            'condition'=>[
                'book_id'=>$bookId
            ]
        ]);
        if (empty($bookInfo)){
            return self::error('ERROR_INVALID_USERID', '传入不存在的图书id');
        }
        $orderInfo=$this->info([
            'condition'=>[
                'user_id'=>$userId,
                'status'=>Consts::ORDER_IN_CART
            ]
        ]);
        if (empty($orderInfo)){
           $this->add([
               'user_id'=>$userId,
               'payment'=>"0",
               'status'=>Consts::ORDER_IN_CART
           ]);
           $orderInfo=$this->info([
                'condition'=>[
                    'user_id'=>$userId,
                    'status'=>Consts::ORDER_IN_CART
                ]
            ]);
        }
        //查询是否已添加此商品
        $orderDetailInfo=self::callModuleService('order','OrderDetailService','info',[
            'condition'=>[
                'order_id'=>$orderInfo['order_id'],
                'book_id'=>$bookId
            ]
        ]);
        if (empty($orderDetailInfo)){
            $this->add([
                'order_id'=>$orderInfo['order_id'],
                'book_id'=>$bookId,
                'unit_price'=>$bookInfo['market_price'],
                'total_price'=>$bookInfo['market_price'],
                'post_status'=>0,
            ]);
            $orderDetailInfo=self::callModuleService('order','OrderDetailService','info',[
                'condition'=>[
                    'order_id'=>$orderInfo['order_id'],
                    'book_id'=>$bookId
                ]
            ]);
        }
        $this->update([
            'mount'=>$orderDetailInfo['mount']+1,
            'total_price'=>$orderDetailInfo['mount']*$orderDetailInfo['unit_price']
        ],[
            'order_detail_id'=>$orderDetailInfo['order_detail_id']
        ]);
        //返回更新后的购物车商品信息
        $orderDetailInfo=self::callModuleService('order','OrderDetailService','info',[
            'condition'=>[
                'order_detail_id'=>$orderDetailInfo['order_detail_id']
            ]
        ]);
        return  $orderDetailInfo;
    }

    //减少购物车中的商品
    public function reduceShopCartGoods($userId,$bookId){
        //查询购物车信息
        $orderInfo=$this->info([
            'condition'=>[
                'user_id'=>$userId,
                'status'=>Consts::ORDER_IN_CART
            ]
        ]);
        if (empty($orderInfo)){
            return self::error('ERROR_INVALID_USERID', '用户未往购物车内添加任何商品');
        }
        //查询货物信息
        $orderDetailInfo=self::callModuleService('order','OrderDetailService','info',[
            'condition'=>[
                'and',
                [ 'order_id'=>$orderInfo['order_id']],
                [ 'book_id'=>$bookId],
                ['>','mount','1']
            ]
        ]);
        if (empty( $orderDetailInfo)){
            return self::error('ERROR_INVALID_USERID', '用户购物车中没有此商品');
        }
        $this->update([
            'mount'=>$orderDetailInfo['mount']-1,
            'total_price'=>$orderDetailInfo['unit_price']*($orderDetailInfo['mount']-1)
        ],[
            'order_detail_id'=>$orderDetailInfo['order_detail_id']
        ]);
        $orderDetailInfo=self::callModuleService('order','OrderDetailService','info',[
            'condition'=>[
                'order_detail_id'=>$orderDetailInfo['order_detail_id']
            ]
        ]);
        return $orderDetailInfo;
    }

    //提交订单
    public function addOrder($userId){
        $orderInfo=$this->info([
            'condition'=>[
                'user_id'=>$userId,
                'status'=>Consts::ORDER_IN_CART
            ]
        ]);
        if (empty($orderInfo)){
            return self::error('ERROR_INVALID_USERID', '用户购物车为空');
        }
        //获取订单中的商品
        $orderDetailList=self::callModuleService('order','OrderDetailService','list',[
           'condition'=>[
               'order_id'=>$orderInfo['order_id'],
               'user_id'=>$userId
           ]
        ]);
        $totalPayment=array_sum(array_column($orderDetailList,'total_price'));
        $this->update([
            'status'=>0,
            'create_time'=>date("Y-m-d H:i:s"),
            'update_time'=>date("Y-m-d H:i:s"),
            'payment'=>$totalPayment
        ],[
            'order_id'=>$orderInfo['order_id'],
        ]);
    }

    //获取用户所有订单信息
    public function listOrderInfo($userId){
        $orderList=$this->lists([
            'condition'=>[
                'user_id'=>$userId
            ],
            'orderby'=>[
                'status'=>SORT_DESC
            ]
        ]);
        return $orderList;
    }

    //取消订单
    public function cancelOrder($userId,$orderId){
        $orderInfo=$this->info([
            'condition'=>[
                'user_id'=>$userId,
                'order_id'=>$orderId
            ]
        ]);
        if (empty($orderInfo)){
            return self::error('ERROR_INVALID_USERID', '该订单不存在');
        }
        $this->update([
            'status'=>5
        ],[
            'user_id'=>$userId,
            'order_id'=>$orderId
        ]);
        return [
            'status'=>true
        ];
    }

}