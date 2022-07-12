<?php

namespace app\modules\order\model;

use app\common\base\ApiModel;

class OrderDetail extends ApiModel
{
    public static function tableName(){
        return '{{%book_info}}';
    }
    public function rules(){
        return [
            [['book_id', 'book_category_id', 'store_id','pages','deal_mount','look_mount','store_mount','is_shelf'], 'integer'],
            [['name', 'outline','detail','press','publish_date','size','version','author','translator','isbn','catalog',
                'image_url','pack_style','cname','description','cata','content','store_time'], 'string'],
            [['market_price','member_price','discount'],'double'],
        ];
    }
}