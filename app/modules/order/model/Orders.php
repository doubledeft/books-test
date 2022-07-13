<?php

namespace app\modules\order\model;

use app\common\base\ApiModel;
use app\modules\book\model\BookInfo;

class Orders extends ApiModel
{
    public static function tableName(){
        return '{{%orders}}';
    }
    public function rules(){
        return [
            [['id','user_id', 'payment_type', 'status','order_mount','buyer_rate'], 'integer'],
            [['payment','post_fee','create_time','update_time','payment_time','end_time','close_time',],'string']
        ];
    }


}