<?php

namespace app\modules\order\model;

use app\common\base\ApiModel;
use app\modules\book\model\BookInfo;

class OrderDetail extends ApiModel
{
    public static function tableName(){
        return '{{%book_info}}';
    }
    public function rules(){
        return [
            [['id','book_id', 'book_category_id', 'store_id','pages','deal_mount','look_mount','store_mount','is_shelf'], 'integer'],
            [['name', 'outline','detail','press','publish_date','size','version','author','translator','isbn','catalog',
                'image_url','pack_style','cname','description','cata','content','store_time'], 'string'],
            [['market_price','member_price','discount'],'double'],
        ];
    }

    public function getBookInfo()
    {
        return $this->hasOne(BookInfo::className(), ['book_id' => 'bookid'])
            ->select(['id','name', 'author','price']);
    }
}