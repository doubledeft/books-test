<?php

namespace app\controllers\order;

use app\common\base\ApiController;

class OrderController extends ApiController
{

    /**
     * 返回用户的购物车信息
     */
    public function actionGetShopCartInfo(){
        $this->rules = [
            [['user_id'], 'required'],
            [['user_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('order', 'OrdersService', 'getShopCartInfo',$inputs['user_id']);
        return [
            'info'=>$info
        ];
    }

    /**
     * 向购物车内容添加商品
     */
    public function actionAddShopCartGoods(){
        $this->rules = [
            [['user_id','book_id'], 'required'],
            [['user_id','book_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('order', 'OrdersService', 'addShopCartGoods',$inputs['user_id'],$inputs['book_id']);
        return [
            'info'=>$info
        ];
    }
    /**
     * 向购物车减少商品数量
     */
    public function actionReduceShopCartGoods(){
        $this->rules = [
            [['user_id','book_id'], 'required'],
            [['user_id','book_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('order', 'OrdersService', 'reduceShopCartGoods',$inputs['user_id'],$inputs['book_id']);
        return [
            'info'=>$info
        ];
    }

    /**
     * 提交订单
     */
    public function actionAddOrder(){
        $this->rules = [
            [['user_id'], 'required'],
            [['user_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('order', 'OrdersService', 'addOrder',$inputs['user_id']);
        return [
            'info'=>$info
        ];
    }

    /**
     * 获取所有订单信息
     */
    public function actionListOrderInfo(){
        $this->rules = [
            [['user_id'], 'required'],
            [['user_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('order', 'OrdersService', 'listOrderInfo',$inputs['user_id']);
        return [
            'info'=>$info
        ];
    }

    /**
     * 取消订单
     */
    public function actionCancelOrder(){
        $this->rules = [
            [['user_id','order_id'], 'required'],
            [['user_id','order_id'],'integer']
        ];
        $inputs = $this->validate();

        $info = self::callModuleService('order', 'OrdersService', 'cancelOrder',$inputs['user_id'],$inputs['order_id']);
        return [
            'info'=>$info
        ];
    }
}